<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2017/1/8
 * Time: 20:25
 */
class WeChatMagaTechService extends WeChatService
{
    private $_weChatMagaTechUserMapper;

    protected function __construct()
    {
        $weChatConfig = ConfigLoader::getConfig('WECHAT');
        $appId = $weChatConfig["tech_magazine"]["id"];
        $appKey = $weChatConfig["tech_magazine"]["secret"];
        parent::__construct($appId, $appKey);
        $this->_weChatMagaTechUserMapper = new WeChatMagaTechUserMapper();
    }

    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new WeChatMagaTechService();
        }
        return $instance;
    }

    public function subscribe($openId)
    {
        try {
            $dbUserInfo = $this->_weChatMagaTechUserMapper->getInfoByOpenId($openId, true);

            if (empty($dbUserInfo)) {
                $weChatUserInfo = $this->getUserInfoByOpenID($openId);
                $this->_weChatMagaTechUserMapper->addSubscribe($openId, $weChatUserInfo['unionid']);
            } else {
                $this->_weChatMagaTechUserMapper->updateSubscribe($openId, WeChatMagaTechUserMapper::SUBSCRIBE);
            }
        } catch (Exception $e) {
            Logger::getRootLogger()->info("WeChatMagaTechController line subscribe failed, openid= {$openId} 
                errMsg=" . $e->getMessage());
        }
    }

}