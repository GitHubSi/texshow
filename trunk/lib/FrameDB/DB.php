<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 15:42
 */
class DB
{
    private static $_container = array();
    private static $_default_config = array(
        "driver" => "mysql",
        "host" => "127.0.0.1",
        "port" => "3306",
        "username" => "root",
        "password" => "",
        "charset" => "utf8",
        "database" => "test",
        "persistent" => true,
        "unix_socket" => "",
        "options" => array()
    );

    public static function getInstance($config = array())
    {
        $key = md5(serialize($config));

        if (!isset(self::$_container[$key]) || !(self::$_container[$key] instanceof DBPDO)) {
            $final_config = array();
            foreach (self::$_default_config as $index => $value) {
                $final_config[$index] = isset($config[$index]) && !empty($config[$index]) ? $config[$index] : self::$_default_config[$index];
            }
            self::$_container[$key] = new DBPDO($final_config);
        }

        return self::$_container[$key];
    }

    public static function destroyInstance($config = array())
    {
        $key = md5(serialize($config));
        if (isset(self::$_container[$key]) && (self::$_container[$key] instanceof DBPDO)) {
            self::$_container[$key]->close();
            unset(self::$_container[$key]);
        }
        return true;
    }
}