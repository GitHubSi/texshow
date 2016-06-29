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

        $this->_weChatClientUserMapper = new WeChatClientUserMapper();
    }

    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new WeChatClientService();
        }
        return $instance;
    }

    public function subscribe($openId, $unionId = '')
    {
        return $this->_weChatClientUserMapper->addSubscribe($openId, $unionId);
    }

    public function unsubscribe($openId)
    {
        return $this->_weChatClientUserMapper->updateSubscribe($openId, WeChatClientUserMapper::UNSUBSCRIBE);
    }

    public function getUserInfo($openId, $includeUnSubscribe = false)
    {
        return $this->_weChatClientUserMapper->getInfoByOpenId($openId, $includeUnSubscribe);
    }

    public function getUserInfoById($id)
    {
        return $this->_weChatClientUserMapper->getUserInfoById($id);
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

    /**
     * scan qr code to subscribe client.
     * @param $sceneId  make qr code
     * @param $openId
     * @return array|bool
     */
    public function scanSubscribe($sceneId, $openId)
    {
        $currentUserInfo = $this->getUserInfoByOpenID($openId);
        WeChatClientService::getInstance()->subscribe($openId, $currentUserInfo['unionid']);

        $masterUser = $this->_weChatClientUserMapper->getUserInfoById($sceneId);
        if ($masterUser['is_subscribe'] == WeChatClientUserMapper::UNSUBSCRIBE) {
            return false;
        }

        return array(
            "master" => $masterUser['unionid'],
            "slave" => $currentUserInfo['unionid']
        );
    }


}