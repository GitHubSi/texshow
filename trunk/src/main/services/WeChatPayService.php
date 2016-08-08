<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/8/8
 * Time: 23:21
 */
class WeChatPayService
{
    private function __construct()
    {
        $weChatConfig = ConfigLoader::getConfig('WECHAT');
    }

    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new WeChatPayService();
        }
        return $instance;
    }



}