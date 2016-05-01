<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 16:09
 */
class DBStatement
{

    private $_stmt;

    public function __construct($stmt)
    {
        $this->_stmt = $stmt;
    }

    //Fetches a row from a result set associated with a PDOStatement object
    public function fetch($mode = PDO::FETCH_ASSOC)
    {
        return $this->_stmt->fetch($mode);
    }

    public function execute()
    {
        return $this->_stmt->execute();
    }

    //For a prepared statement using question mark placeholders, this will be the 1-indexed position of the parameter.
    public function bind($parameter, $value)
    {
        return $this->_stmt->bindValue($parameter, $value);
    }

    //returns the number of rows affected by the last DELETE, INSERT, or UPDATE statement executed by the corresponding PDOStatement object.
    public function getEffectedRows()
    {
        return $this->_stmt->rowCount();
    }

}