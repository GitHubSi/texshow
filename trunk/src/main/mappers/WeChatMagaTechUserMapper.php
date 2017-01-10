<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 16:54
 */
class WeChatMagaTechUserMapper
{
    const UNSUBSCRIBE = 0;
    const SUBSCRIBE = 1;

    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addSubscribe($openId, $unionId = '')
    {
        return $this->_db->execute(
            "INSERT INTO wechat_maga_tech_user (openid, unionid, is_subscribe, score, create_time) VALUES (?, ?, ?, ?, now())
            ON DUPLICATE KEY UPDATE is_subscribe = ? ",
            array($openId, $unionId, self::SUBSCRIBE, 0, self::SUBSCRIBE)
        );
    }

    public function updateSubscribe($openId, $subscribeState = self::UNSUBSCRIBE)
    {
        return $this->_db->execute(
            "UPDATE wechat_maga_tech_user SET is_subscribe = ? WHERE openid = ?",
            array($subscribeState, $openId)
        );
    }

    public function getInfoByOpenId($openId, $includeUnSubscribe = false)
    {
        if ($includeUnSubscribe) {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, is_subscribe, score, create_time, update_time FROM wechat_maga_tech_user
                WHERE openid = ? ",
                $openId
            );
        } else {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, is_subscribe, score, create_time, update_time FROM wechat_maga_tech_user
                WHERE openid = ? AND is_subscribe = ? ",
                array($openId, self::SUBSCRIBE)
            );
        }
    }

    public function getInfoById($id)
    {
        return $this->_db->getRow(
            "SELECT id, openid, unionid, is_subscribe, score, create_time, update_time FROM wechat_maga_tech_user
                WHERE id = ? AND is_subscribe = ? ",
            array($id, self::SUBSCRIBE)
        );
    }

    public function getInfoByUnionId($unionId, $includeUnSubscribe = false)
    {
        if ($includeUnSubscribe) {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, is_subscribe, score, create_time, update_time FROM wechat_maga_tech_user
                WHERE unionid = ? ",
                $unionId
            );
        } else {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, is_subscribe, score, create_time, update_time FROM wechat_maga_tech_user
                WHERE unionid = ? AND is_subscribe = ? ",
                array($unionId, self::SUBSCRIBE)
            );
        }
    }
}