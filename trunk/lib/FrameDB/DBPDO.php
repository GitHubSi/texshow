<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 15:51
 */
class DBPDO
{
    private $_config = array();
    private $_conn = null;
    private $_fetch_type = PDO::FETCH_ASSOC;
    private $_transaction = false;
    private $_error_mode = PDO::ERRMODE_EXCEPTION;
    private $_reconnected = false;
    private $_auto_reconnect = true;

    public function __construct($config)
    {
        $this->_config = $config;
    }

    //connect to mysql
    private function _connect()
    {
        if ($this->_conn == null) {
            if ($this->_config["unix_socket"]) {
                $dsn = "mysql:dbname={$this->_config["database"]};unix_socket={$this->_config["unix_socket"]}";
            } else {
                $dsn = "{$this->_config["driver"]}:dbname={$this->_config["database"]};host={$this->_config["host"]};port={$this->_config["port"]}";
            }

            $username = $this->_config["username"];
            $password = $this->_config["password"];
            $options = $this->_config['options'] + array(PDO::ATTR_PERSISTENT => $this->_config['persistent']);

            try {
                $this->_conn = new PDO($dsn, $username, $password, $options);
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode());
            }

            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, $this->_error_mode);
            $this->execute("SET NAMES '{$this->_config["charset"]}'");
            $this->execute("SET character_set_client=binary");
        }
    }

    private function _exec($sql, $params)
    {
        $this->_connect();

        //Prepares a statement for execution and returns a statement object
        $stmt = new DBStatement($this->_conn->prepare($sql));
        if (is_array($params)) {
            if (!empty($params)) {
                $i = 0;
                foreach ($params as $value) {
                    $stmt->bind(++$i, $value);
                }
            }
        } else {
            $stmt->bind(1, $params);
        }
        $execute_return = $stmt->execute();
        return array("stmt" => $stmt, "execute_return" => $execute_return);
    }

    private function _process($sql, $params)
    {

        if (in_array(preg_replace("/\s{2,}/", " ", strtolower($sql)), array("begin", "commit", "rollback", "start transaction", "set autocommit=0", "set autocommit=1"))) {
            throw new Exception("");
        }

        if ($this->_transaction) {
            if ($this->_reconnected) {
                throw new PDOException("transaction condition lost connect stop to reconnect");
            } else {
                try {
                    $arr_exec_result = $this->_exec($sql, $params);
                } catch (PDOException $e) {
                    throw $e;
                }
            }
        } else {
            try {
                $arr_exec_result = $this->_exec($sql, $params);
            } catch (PDOException $e) {
                if ($this->_auto_reconnect && in_array($e->getCode(), array(2013, 2006))) {
                    try {
                        $this->close();
                        $arr_exec_result = $this->_exec($sql, $params);
                    } catch (PDOException $e) {
                        throw $e;
                    }
                } else {
                    throw $e;
                }
            }
        }

        return $arr_exec_result;
    }

    private function _checkSafe($sql, $is_open_safe = true)
    {
        if (!$is_open_safe) {
            return true;
        }

        $string = strtolower($sql);
        $operate = strtolower(substr($sql, 0, 6));
        $is_safe = true;
        switch ($operate) {
            case "select":
                if (strpos($string, "where") && !preg_match("/\(.*\)/", $string) && !strpos($string, "?")) {
                    $is_safe = false;
                }
                break;
            case "insert":
            case "update":
            case "delete":
                if (!strpos($string, "?")) {
                    $is_safe = false;
                }
                break;
        }

        if (!$is_safe) {
            throw new Exception("SQL statement is unsafe.");
        }

        return $is_safe;
    }

    public function getInsertId()
    {
        return $this->_conn->lastInsertId();
    }

    public function execute($sql, $params = array(), $is_open_safe = true)
    {
        $this->_checkSafe($sql, $is_open_safe);

        $arr_process_result = $this->_process($sql, $params);

        if ($arr_process_result["execute_return"]) {
            $operate = strtolower(substr($sql, 0, 6));
            switch ($operate) {
                case "insert":
                    $arr_process_result["execute_return"] = $this->getInsertId();
                    break;
                case "update":
                case "delete":
                    $arr_process_result["execute_return"] = $arr_process_result["stmt"]->getEffectedRows();
                    break;
                default:
                    break;
            }
        }

        return $arr_process_result["execute_return"];
    }

    public function query($sql, $params = array(), $is_open_safe = true)
    {
        $this->_checkSafe($sql, $is_open_safe);
        $result = $this->_process($sql, $params);

        return $result["stmt"];
    }

    public function getOne($sql, $params = array(), $safe = true)
    {
        $stmt = $this->query($sql, $params, $safe);
        $record = $stmt->fetch($this->_fetch_type);
        return is_array($record) && !empty($record) ? array_shift($record) : null;
    }

    public function getRow($sql, $params = array(), $safe = true)
    {
        $stmt = $this->query($sql, $params, $safe);
        $record = $stmt->fetch($this->_fetch_type);
        return is_array($record) && !empty($record) ? $record : array();
    }

    public function getAll($sql, $params = array(), $safe = true)
    {
        $stmt = $this->query($sql, $params, $safe);
        $data = array();
        while ($record = $stmt->fetch($this->_fetch_type)) {
            $data[] = $record;
        }
        return $data;
    }

    public function setWaitTimeOut($seconds)
    {
        $this->execute("set wait_timeout=$seconds");
    }

    public function setFetchMode($fetch_type = PDO:: FETCH_ASSOC)
    {
        $this->_fetch_type = $fetch_type;
    }

    public function startTrans()
    {
        if ($this->_transaction) {
            throw new PDOException("transaction is stopped");
        }

        $this->_connect();
        try {
            $this->_conn->beginTransaction();
        } catch (PDOException $e) {
            throw $e;
        }

        $this->_transaction = true;
        $this->_reconnected = false;
    }

    public function commit()
    {
        if (!$this->_transaction) {
            throw new PDOException("transaction is stopped");
        }

        $this->_transaction = false;
        $this->_reconnected = false;

        try {
            $this->_conn->commit();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function rollback()
    {
        if (!$this->_transaction) {
            throw new PDOException("transaction is stopped");
        }

        $this->_transaction = false;
        $this->_reconnected = false;

        try {
            $this->_conn->rollback();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function close()
    {
        $this->_conn = null;
    }

}