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

    /**
     * @desc    通过服务号的openid获取订阅号的openid的信息
     * @param $clientOpenId 服务号的openId
     * @return mixed    array
     */
    public function getMagazineByClient($clientOpenId)
    {
        $clientUserInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($clientOpenId);
        return WeChatMagazineService::getInstance()->getUserInfoByUnionId($clientUserInfo["unionid"]);
    }
}