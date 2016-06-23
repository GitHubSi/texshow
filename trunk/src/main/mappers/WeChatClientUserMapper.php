<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 16:54
 */
class WeChatClientUserMapper
{
    const UNSUBSCRIBE = 0;
    const SUBSCRIBE = 1;
    const RED_PACKET_INIT = 0;
    const RED_PACKET_SUCC = 1;
    const RED_PACKET_FAIL = 2;

    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addSubscribe($openId, $unionId = '')
    {
        return $this->_db->execute(
            "INSERT INTO wechat_client_user (openid, unionid, is_subscribe, create_time) VALUES (?, ?, ?, now())
            ON DUPLICATE KEY UPDATE is_subscribe = ? ",
            array($openId, $unionId, self::SUBSCRIBE, self::SUBSCRIBE)
        );
    }

    public function updateSubscribe($openId, $subscribeState = self::UNSUBSCRIBE)
    {
        return $this->_db->execute(
            "UPDATE wechat_client_user SET is_subscribe = ? WHERE openid = ?",
            array($subscribeState, $openId)
        );
    }

    public function getInfoByOpenId($openId, $includeUnSubscribe = false)
    {
        if ($includeUnSubscribe) {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, is_subscribe, phone, score, redpacket, create_time, update_time FROM wechat_client_user
                WHERE openid = ? ",
                $openId
            );
        } else {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, is_subscribe, phone, score, redpacket, create_time, update_time FROM wechat_client_user
                WHERE openid = ? AND is_subscribe = ? ",
                array($openId, self::SUBSCRIBE)
            );
        }
    }

    public function getUserInfoById($id)
    {
        return $this->_db->getRow(
            "SELECT id, openid, unionid, is_subscribe, phone, score, redpacket, create_time, update_time FROM wechat_client_user
            WHERE id = ? AND is_subscribe = ? ",
            array($id, self::SUBSCRIBE)
        );
    }

    public function getInfoByUnionId($unionId, $includeUnSubscribe = false)
    {
        if ($includeUnSubscribe) {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, is_subscribe, phone, score, redpacket, create_time, update_time FROM wechat_client_user
                WHERE unionid = ? ",
                $unionId
            );
        } else {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, is_subscribe, phone, score, redpacket, create_time, update_time FROM wechat_client_user
                WHERE unionid = ? AND is_subscribe = ? ",
                array($unionId, self::SUBSCRIBE)
            );
        }
    }

    public function updatePhone($openId, $phone)
    {
        return $this->_db->execute(
            "UPDATE wechat_client_user SET phone = ? WHERE openid = ?",
            array($phone, $openId)
        );
    }

    public function updateRedPacket($openId, $redPacketState)
    {
        return $this->_db->execute(
            'UPDATE wechat_client_user SET redpacket = ? WHERE openid = ?',
            array($redPacketState, $openId)
        );
    }
}