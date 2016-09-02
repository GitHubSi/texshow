<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 23:37
 */
class OneShareService
{
    const EXTRA_ADD_NUM = 100000;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new OneShareService();
        }
        return $instance;
    }

    /**
     * @param $openId   订阅号的openid
     * @return mixed
     */
    public function getInviteCode($openId)
    {
        $userInfo = WeChatMagazineService::getInstance()->getUserInfo($openId);
        return $userInfo["id"] + self::EXTRA_ADD_NUM;
    }


    /**
     * @param $openId
     * @param $score
     * @throws Exception
     */
    public function consumerScore($openId, $score)
    {
        $userInfo = WeChatOpenService::getInstance()->getMagazineByClient($openId);
        if (empty($userInfo) || $userInfo["score"] < $score) {
            throw new Exception("Pay error, openid=" . $openId, 10001);
        }

    }
}