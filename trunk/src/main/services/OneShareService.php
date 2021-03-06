<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 23:37
 */
class OneShareService
{
    const EXTRA_ADD_NUM = 100000;       //for invite code
    const GOODS_PAGE_SIZE = 20;
    const NEW_USER_START_TIME = "2016-09-04 00:00:00";
    const RANK_BUY_GOOD_NUM = "mall:rank:";

    private $_weChatMagazineUserMapper;
    private $_oneShareMapper;
    private $_shareItemMapper;
    private $_headImageMapper;

    protected function __construct()
    {
        $this->_weChatMagazineUserMapper = new WeChatMagazineUserMapper();
        $this->_oneShareMapper = new OneShareMapper();
        $this->_shareItemMapper = new ShareItemMapper();
        $this->_headImageMapper = new HeadImgMapper();
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

    public function getShareItem($itemId)
    {
        $itemInfo = $this->_shareItemMapper->getGoodById($itemId);
        if (empty($itemInfo)) {
            throw new Exception("商品信息不存在", 404);
        }
        return $itemInfo;
    }

    /**
     * @param $userInfo magazine user base info
     * @param $buyNum
     * @param int $itemId goods id
     * @return bool
     * @throws Exception
     */
    public function consumerScore($userInfo, $buyNum, $itemId = 1)
    {
        if ($userInfo["score"] < $buyNum) {
            throw new Exception("Pay error, 积分不足。openid=" . $userInfo["openid"], 10002);
        }

        $itemInfo = $this->_shareItemMapper->getGoodById($itemId);
        if (empty($itemInfo) || $itemInfo["current_score"] + $buyNum > $itemInfo["total_score"]) {
            throw new Exception("商品信息不存在或者购买份数多于库存", 10001);
        }

        $db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
        $db->startTrans();
        try {
            $currentScore = $userInfo["score"] - $buyNum;
            $this->_weChatMagazineUserMapper->updateScore($userInfo["openid"], $currentScore);
            $this->_oneShareMapper->addOneShare($userInfo["openid"], $buyNum, $itemId);
            $this->_shareItemMapper->updateScoreNum($buyNum, $itemId);

            //this operation is to avoid calculate the sum that user buy total num about the item
            $RedisRankKey = self::RANK_BUY_GOOD_NUM . $itemId;
            RedisClient::getInstance(ConfigLoader::getConfig("REDIS"))->zIncrBy($RedisRankKey, $buyNum, $userInfo["openid"]);

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw new Exception("数据库错误" . $e->getCode() . $e->getTraceAsString(), 10003);
        }

        $afterItemInfo = $this->_shareItemMapper->getGoodById($itemId);
        if ($afterItemInfo["current_score"] >= $afterItemInfo["total_score"]) {
            $this->_shareItemMapper->updateGoodsState($itemId, ShareItemMapper::IS_OFFLINE);
        }

        return true;
    }

    public function getCurrentBuyHistory($magazineOpenid, $lastId = PHP_INT_MAX, $record = 20)
    {
        $buyHistory = $this->_oneShareMapper->getCurrentBuyHistory($magazineOpenid, $lastId, $record);
        foreach ($buyHistory as $key => $history) {
            $goods = $this->_shareItemMapper->getGoodById($history["item"]);

            $history["batch"] = str_pad($goods["id"], 10, "0", STR_PAD_LEFT);
            $history["good_name"] = $goods["name"];
            if (!empty($goods["openid"])) {
                $history["winner"] = $goods["winner"];
            } else {
                $history["winner"] = "正在输入...";
            }
            $buyHistory[$key] = $history;
        }
        return $buyHistory;
    }


    public function addShareScore($openId, $input)
    {
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

    //是否包含轮播图
    public function getGoodList($lastId, $includeHomePage = false)
    {
        if ($includeHomePage) {
            $headImgList = $this->_headImageMapper->getImgByState(HeadImgMapper::NO_DELETE);
        }

        $goodList = $this->_shareItemMapper->getAllGoods($lastId);
        foreach ($goodList as &$good) {
            $good["rank"] = ceil($good["current_score"] / $good["total_score"] * 100) . "%";
        }

        return array(
            "headImg" => $headImgList,
            "goodList" => $goodList
        );
    }

}