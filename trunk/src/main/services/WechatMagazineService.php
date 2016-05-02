<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 23:37
 */
class WeChatMagazineService extends WeChatService
{
    private $_wechatMagazineUserMapper;

    protected function __construct()
    {
        $wechatConfig = ConfigLoader::getConfig('WECHAT');
        $appId = $wechatConfig["magazine"]["id"];
        $appKey = $wechatConfig["magazine"]["secret"];
        parent::__construct($appId, $appKey);

        $this->_wechatMagazineUserMapper = new WeChatMagazineService();
    }

    public function subscribe($openId)
    {
        return $this->_wechatMagazineUserMapper->addSubscribe($openId);
    }

    public function unsubscribe($openId)
    {
        return $this->_wechatMagazineUserMapper->updateSubscribe($openId, WeChatClientUserMapper::UNSUBSCRIBE);
    }

    public function getUserInfo($openId, $includeUnSubscribe = false)
    {
        return $this->_wechatMagazineUserMapper->getInfoByOpenId($openId, $includeUnSubscribe);
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
        return $this->_wechatMagazineUserMapper->updateRedPacket($openId, $redPacketState);
    }

    //user info
    public function updateUserPhone($openId, $phone)
    {
        return $this->_wechatMagazineUserMapper->updatePhone($openId, $phone);
    }
}