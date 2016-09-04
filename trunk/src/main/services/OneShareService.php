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
    const NEW_USER_START_TIME = "2016-09-04 00:00:00";

    private $_weChatMagazineUserMapper;
    private $_oneShareMapper;
    private $_shareItemMapper;

    protected function __construct()
    {
        $this->_weChatMagazineUserMapper = new WeChatMagazineUserMapper();
        $this->_oneShareMapper = new OneShareMapper();
        $this->_shareItemMapper = new ShareItemMapper();
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
     * @param $openId       服务号的openid
     * @param $score
     * @param int $item 为1表示奖品为iphone
     * @return bool
     * @throws Exception
     */
    public function consumerScore($openId, $score, $item = 1)
    {
        $itemInfo = $this->_shareItemMapper->getScoreNum($item);
        if (empty($itemInfo) || $itemInfo["current_score"] + $score > $itemInfo["total_score"]) {
            throw new Exception("商品信息不存在或者购买份数太多", 10001);
        }

        $userInfo = WeChatOpenService::getInstance()->getMagazineByClient($openId);
        if (empty($userInfo) || $userInfo["score"] < $score) {
            throw new Exception("Pay error, 积分不足。openid=" . $openId, 10002);
        }

        $db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
        $db->startTrans();
        try {
            $currentScore = $userInfo["score"] - $score;
            $this->_weChatMagazineUserMapper->updateScore($userInfo["openid"], $currentScore);
            $this->_oneShareMapper->addOneShare($userInfo["openid"], $score, $item);
            $this->_shareItemMapper->updateScoreNum($score, $item);
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw new Exception("数据库错误" . $e->getCode() . $e->getTraceAsString(), 10003);
        }
        return true;
    }

    /**
     * @param $openId   服务号的openid
     * @param int $record
     * @return mixed
     */
    public function getCurrentBuyHistory($openId, $record = 5)
    {
        $magaUserInfo = WeChatOpenService::getInstance()->getMagazineByClient($openId);
        $buyHistory = $this->_oneShareMapper->getCurrentBuyHistory($magaUserInfo["openid"], $record);
        foreach ($buyHistory as $key => $history) {
            $goods = $this->_shareItemMapper->getScoreNum($history["item"]);
            $history["good_name"] = $goods["name"];
            $buyHistory[$key] = $history;
        }
        return $buyHistory;
    }


    public function addShareScore($openId, $input)
    {
        $openId = "owfVItxlcSHCoAJrPN1Tg4mhzkh0";
        if ($input < 100000 || $input > 999999) {
            return false;
        }

        //是否新注册的用户
        $magazineUserInfo = WeChatMagazineService::getInstance()->getUserInfo($openId);
        if ($magazineUserInfo["create_time"] < self::NEW_USER_START_TIME || $magazineUserInfo["invite"] == WeChatMagazineUserMapper::CONFIRM_INVITE) {
            return false;
        }

        $targetUserInfo = $this->_weChatMagazineUserMapper->getInfoById($input - self::EXTRA_ADD_NUM);
        if (empty($targetUserInfo)) {
            return false;
        }

        $db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
        $db->startTrans();
        try {
            $this->_weChatMagazineUserMapper->updateScore($targetUserInfo["openid"], $targetUserInfo["score"] + 1);
            $this->_weChatMagazineUserMapper->updateInviteState($magazineUserInfo["openid"]);
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollback();
        }
        return false;
    }

}