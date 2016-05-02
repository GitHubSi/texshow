<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 23:37
 */
class WeChatMagazineService extends WeChatService
{
    private $_weChatMagazineUserMapper;

    protected function __construct()
    {
        $weChatConfig = ConfigLoader::getConfig('WECHAT');
        $appId = $weChatConfig["magazine"]["id"];
        $appKey = $weChatConfig["magazine"]["secret"];
        parent::__construct($appId, $appKey);
        $this->_weChatMagazineUserMapper = new WeChatMagazineUserMapper();
    }

    public static function getInstance()
    {
        static $instance = Array();
        if (is_null($instance)) {
            $instance = new WeChatMagazineService();
        }
        return $instance;
    }

    public function subscribe($openId)
    {
        return $this->_weChatMagazineUserMapper->addSubscribe($openId);
    }

    public function unSubscribe($openId)
    {
        return $this->_weChatMagazineUserMapper->updateSubscribe($openId, WeChatClientUserMapper::UNSUBSCRIBE);
    }

    public function getUserInfo($openId, $includeUnSubscribe = false)
    {
        return $this->_weChatMagazineUserMapper->getInfoByOpenId($openId, $includeUnSubscribe);
    }

    //red packet content
    public function getRedPacketState($openId, $includeUnSubscribe = true)
    {
        $userInfo = $this->getUserInfo($openId, $includeUnSubscribe);
        return $userInfo['redpacket'];
    }

    //magazine red packet only have two states:0 or 1.
    public function updateRedPacketState($openId, $redPacketState = WeChatMagazineUserMappper::RED_PACKET_SUCC)
    {
        return $this->_weChatMagazineUserMapper->updateRedPacket($openId, $redPacketState);
    }

    //user info
    public function updateUserPhone($openId, $phone)
    {
        return $this->_weChatMagazineUserMapper->updatePhone($openId, $phone);
    }
}