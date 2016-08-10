<?php

/**
 * Created by PhpStorm.
 * User: fuhui-iri
 * Date: 2016/8/9
 * Time: 13:13
 */
require_once "WxPay.Data.php";
spl_autoload_register(array('WxPayAutoLoader', 'autoload'));

class WxPayAutoLoader
{
    private static $classes = array(
        "WxPayApi" => "/WxPay.Api.php",
        "WxPayConfig" => "/WxPay.Config.php",
        "WxPayException" => "/WxPay.Exception.php",
        "WxPayNotify" => "/WxPay.Notify.php",

        "JsApiPay" => "/WxPay.JsApiPay.php",
    );

    /**
     * Loads a class.
     * @param string $className The name of the class to load.
     */
    public static function autoload($className)
    {
        if (isset(self::$classes[$className])) {
            include dirname(__FILE__) . self::$classes[$className];
        }
    }
}
