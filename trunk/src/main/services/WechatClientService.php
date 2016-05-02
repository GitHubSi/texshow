<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 23:37
 */
class WechatClientService extends WechatService
{
    private $_wechatClientUserMapper;

    protected function __construct()
    {
        $wechatConfig = ConfigLoader::getConfig('WECHAT');
        $appId = $wechatConfig["client"]["id"];
        $appKey = $wechatConfig["client"]["secret"];
        parent::__construct($appId, $appKey);

        $this->_wechatClientUserMapper = new WechatClientUserMapper();
    }

    public function subscribe($openId)
    {
        return $this->_wechatClientUserMapper->addSubscribe($openId);
    }

    public function unsubscribe($openId)
    {
        return $this->_wechatClientUserMapper->updateSubscribe($openId, WechatClientUserMapper::UNSUBSCRIBE);
    }

    //red packet content
    public function getRedPacketState($openId, $includeUnSubscribe = true)
    {
        $userInfo = $this->getUserInfo($openId, $includeUnSubscribe);
        return $userInfo['redpacket'];
    }

    public function updateRedPacketState($openId, $redPacketState = WechatClientUserMapper::RED_PACKET_SUCC)
    {
        return $this->_wechatClientUserMapper->updateRedPacket($openId, $redPacketState);
    }

    //user info
    public function updateUserPhone($openId, $phone)
    {
        return $this->_wechatClientUserMapper->updatePhone($openId, $phone);
    }
}