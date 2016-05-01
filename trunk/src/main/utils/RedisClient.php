<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 17:08
 */
class RedisClient
{
    private static $_instancePool;

    //todo: need to limit the config format
    private static $_default_config = array(
        "auth" => "",
        "host" => "",
        "port" => "6379",
        "db" => 0
    );

    public static function getInstance($conf = array())
    {
        $poolKey = md5(serialize($conf));

        if (isset(self::$_instancePool[$poolKey])) {
            $instance = self::$_instancePool[$poolKey];
            // test the instance connection
            if (self::_testConnection($instance)) {
                return $instance;
            }
        }

        $instance = self::_createInstance($conf);
        self::$_instancePool[$poolKey] = $instance;

        return $instance;
    }

    /**
     * Test the connection of an instance
     * @param $instance
     * @return bool
     */
    private static function _testConnection($instance)
    {
        try {
            if ($instance->ping() == '+PONG') {
                return true;
            }
        } catch (RedisException $e) {
        }
        return false;
    }

    private static function _createInstance($conf)
    {
        $instance = new Redis();

        // use db as the persistent id, same db use the same connection
        if (!$instance->pconnect($conf['host'], $conf['port'], 3, $conf['db'])) {
            throw new RuntimeException("Fail to connect to redis, host={$conf['host']}, " .
                "port={$conf['port']}");
        }

        if (!$instance->auth($conf['auth'])) {
            $error = $instance->getLastError();
            throw new RuntimeException("Fail to auth redis, error={$error}");
        }

        $instance->select($conf['db']);
        return $instance;
    }

    public static function close()
    {
        if (empty(self::$_instancePool)) {
            return;
        }
        try {
            foreach (self::$_instancePool as $db => $instance) {
                $instance->close();
            }
        } catch (Exception $e) {
            // ignore the exception
        }
    }
}