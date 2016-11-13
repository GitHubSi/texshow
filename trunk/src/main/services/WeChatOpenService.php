<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 23:37
 */
class WeChatOpenService
{
    private $_weChatClientUserMapper;
    private $_weChatMagazineUserMapper;

    protected function __construct()
    {
        $this->_weChatMagazineUserMapper = new WeChatMagazineUserMapper();
        $this->_weChatClientUserMapper = new WeChatClientUserMapper();
    }

    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new WeChatOpenService();
        }
        return $instance;
    }

    public function getMagazineByClient($clientOpenId)
    {
        //usually, this function don't throw any exception in micro messenger user agent. because try to fetch user base info through client openid
        //will return a data set that subscribe is zero,and openid,union id
        $clientUserInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($clientOpenId);
        return WeChatMagazineService::getInstance()->getUserInfoByUnionId($clientUserInfo["unionid"]);
    }
}