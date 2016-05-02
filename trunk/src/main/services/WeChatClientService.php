<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 23:37
 */
class WeChatClientService extends WeChatService
{
    private $_weChatClientUserMapper;

    protected function __construct()
    {
        $weChatConfig = ConfigLoader::getConfig('WECHAT');
        $appId = $weChatConfig["client"]["id"];
        $appKey = $weChatConfig["client"]["secret"];
        parent::__construct($appId, $appKey);
        $this->_weChatClientUserMapper = new WeChatClientService();
    }

    public static function getInstance()
    {
        static $instance = Array();
        if (is_null($instance)) {
            $instance = new WeChatClientService();
        }
        return $instance;
    }

    public function subscribe($openId)
    {
        return $this->_weChatClientUserMapper->addSubscribe($openId);
    }

    public function unsubscribe($openId)
    {
        return $this->_weChatClientUserMapper->updateSubscribe($openId, WeChatClientUserMapper::UNSUBSCRIBE);
    }

    //red packet content
    public function getRedPacketState($openId, $includeUnSubscribe = true)
    {
        $userInfo = $this->getUserInfo($openId, $includeUnSubscribe);
        return $userInfo['redpacket'];
    }

    public function updateRedPacketState($openId, $redPacketState = WeChatClientUserMapper::RED_PACKET_SUCC)
    {
        return $this->_weChatClientUserMapper->updateRedPacket($openId, $redPacketState);
    }

    //user info
    public function updateUserPhone($openId, $phone)
    {
        return $this->_weChatClientUserMapper->updatePhone($openId, $phone);
    }
}