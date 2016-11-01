<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 16:54
 */
class WeChatMagazineUserMapper
{
    const UNSUBSCRIBE = 0;
    const SUBSCRIBE = 1;

    //red packet state
    const RED_PACKET_INIT = 0;
    const RED_PACKET_SUCC = 1;

    //是否已经确认了被邀请
    const CONFIRM_INVITE = 1;

    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addSubscribe($openId, $unionId = '')
    {
        return $this->_db->execute(
            "INSERT INTO wechat_magazine_user (openid, unionid, is_subscribe, score, create_time) VALUES (?, ?, ?, ?, now())
            ON DUPLICATE KEY UPDATE is_subscribe = ? ",
            array($openId, $unionId, self::SUBSCRIBE, 1, self::SUBSCRIBE)
        );
    }

    public function updateSubscribe($openId, $subscribeState = self::UNSUBSCRIBE)
    {
        return $this->_db->execute(
            "UPDATE wechat_magazine_user SET is_subscribe = ? WHERE openid = ?",
            array($subscribeState, $openId)
        );
    }

    public function getInfoByOpenId($openId, $includeUnSubscribe = false)
    {
        if ($includeUnSubscribe) {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, nick_name, head_img, is_subscribe, phone, score, redpacket, create_time, update_time, invite FROM wechat_magazine_user
                WHERE openid = ? ",
                $openId
            );
        } else {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, nick_name, head_img, is_subscribe, phone, score, redpacket, create_time, update_time, invite FROM wechat_magazine_user
                WHERE openid = ? AND is_subscribe = ? ",
                array($openId, self::SUBSCRIBE)
            );
        }
    }

    public function getInfoById($id)
    {
        return $this->_db->getRow(
            "SELECT id, openid, unionid, nick_name, head_img, is_subscribe, phone, score, redpacket, create_time, update_time, invite FROM wechat_magazine_user
                WHERE id = ? AND is_subscribe = ? ",
            array($id, self::SUBSCRIBE)
        );
    }

    public function getInfoByUnionId($unionId, $includeUnSubscribe = false)
    {
        if ($includeUnSubscribe) {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, nick_name, head_img, is_subscribe, phone, score, redpacket, create_time, update_time, invite FROM wechat_magazine_user
                WHERE unionid = ? ",
                $unionId
            );
        } else {
            return $this->_db->getRow(
                "SELECT id, openid, unionid, nick_name, head_img, is_subscribe, phone, score, redpacket, create_time, update_time, invite FROM wechat_magazine_user
                WHERE unionid = ? AND is_subscribe = ? ",
                array($unionId, self::SUBSCRIBE)
            );
        }
    }

    public function updatePhone($openId, $phone)
    {
        return $this->_db->execute(
            "UPDATE wechat_magazine_user SET phone = ? WHERE openid = ?",
            array($phone, $openId)
        );
    }

    public function updateRedPacket($openId, $redPacketState)
    {
        return $this->_db->execute(
            'UPDATE wechat_magazine_user SET redpacket = ? WHERE openid = ?',
            array($redPacketState, $openId)
        );
    }

    public function updateScore($openId, $score)
    {
        return $this->_db->execute(
            "UPDATE wechat_magazine_user SET score = ? WHERE openid = ? ",
            array($score, $openId)
        );
    }

    public function updateInviteState($openId)
    {
        return $this->_db->execute(
            "UPDATE wechat_magazine_user SET invite = ? WHERE openid = ? ",
            array(self::CONFIRM_INVITE, $openId)
        );
    }
}