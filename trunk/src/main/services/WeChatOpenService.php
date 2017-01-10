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
    private $_weChatMagaTechUserMapper;

    protected function __construct()
    {
        $this->_weChatMagazineUserMapper = new WeChatMagazineUserMapper();
        $this->_weChatClientUserMapper = new WeChatClientUserMapper();
        $this->_weChatMagaTechUserMapper = new WeChatMagaTechUserMapper();
    }

    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new WeChatOpenService();
        }
        return $instance;
    }

    //@deprecated
    public function getMagazineByClient($clientOpenId)
    {
        //usually, this function don't throw any exception in micro messenger user agent. because try to fetch user base info through client openid
        //will return a data set that subscribe is zero,and openid,union id
        $clientUserInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($clientOpenId);
        return WeChatMagazineService::getInstance()->getUserInfoByUnionId($clientUserInfo["unionid"]);
    }

    public function getMagaByClient($clientOpenId, $magaList)
    {
        $userInfo = array();
        $clientUserInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($clientOpenId);
        switch ($magaList) {
            case "tex" :
                $userInfo = WeChatMagazineService::getInstance()->getUserInfoByUnionId($clientUserInfo["unionid"]);
                break;
            case "tech" :
                $userInfo = $this->_weChatMagaTechUserMapper->getInfoByUnionId($clientUserInfo["unionid"]);
                break;
        }
        return $userInfo;
    }
}