<?php
/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/4/30
 * Time: 23:15
 */

$SRC_DIR = dirname(dirname(dirname(__FILE__)));
spl_autoload_register(array("WebAutoLoader", "autoload"));

class WebAutoLoader
{

    private static $classes = array(
        //web

        //api

        //mappers

        //services

        //utils
    );

    /**
     * Loads a class.
     * @param string $className The name of the class to load.
     */
    public static function autoload($className)
    {
        if (isset(self::$classes[$className])) {
            global $SRC_DIR;
            include $SRC_DIR . self::$classes[$className];
        }
    }
}
