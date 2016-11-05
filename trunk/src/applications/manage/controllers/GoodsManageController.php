<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/23
 * Time: 20:06
 */
class GoodsManageController extends AbstractSecurityAction
{
    private $_shareItemMapper;

    public function __construct()
    {
        parent::__construct();
        $this->_shareItemMapper = new ShareItemMapper();
    }

    public function indexAction()
    {
        $allGoods = $this->_shareItemMapper->getAllGoods(PHP_INT_MAX);
        foreach ($allGoods as $good) {
            if ($good["state"] == ShareItemMapper::IS_OFFLINE) {
                $result["offline"][] = $good;
                continue;
            }
            $result["online"][] = $good;
        }

        $this->_smarty->assign('goods', $result);
        $this->_smarty->assign('tpl', "admin/good_manage/index.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function modifyAction()
    {
        $name = $this->getParam("name");
        $totalScore = $this->getParam("price");
        $image = $this->getParam("image");
        $descImgs = $this->getParam("desc_img");
        $descTitles = $this->getParam("desc_title");
        $type = $this->getParam("type");
        $itemId = $this->getParam("id");

        if (empty($name) || empty($totalScore) || empty($image) || empty($descImgs)) {
            header("Location: /GoodsManage/index?successMsg=参数不能为空");
            return;
        }
        if ($type == "edit" && !ctype_digit($itemId)) {
            header("Location: /GoodsManage/index?errMsg=");
            return;
        }

        $descArray = array();
        foreach ($descImgs as $key => $descImg) {
            $descArray[] = array(
                "title" => $descTitles[$key],
                "image" => $descImg
            );
        }

        $insertDay = date("Y-m-d H:i:s");
        if ($type == "add") {
            $this->_shareItemMapper->insertGood($name, $image, json_encode($descArray), $totalScore, $insertDay, $insertDay);
        }
        if ($type == "edit") {
            $this->_shareItemMapper->updateGood($itemId, $name, $image, json_encode($descArray), $totalScore, $insertDay, $insertDay);
        }

        header("Location: /GoodsManage/index?successMsg=添加成功");
    }

    public function infoAction()
    {
        $type = $this->getParam("type");
        $itemId = $this->getParam("id");

        $allowType = array(
            "detail", "edit", "add"
        );
        if (!in_array($type, $allowType)) {
            header("Location: /GoodsManage/index?successMsg=类型参数错误");
            return;
        }

        if (!empty($itemId)) {
            $goodsInfo = $this->_shareItemMapper->getGoodById($itemId);
            $goodsInfo["desc"] = json_decode($goodsInfo["desc"], true);
        }

        $this->_smarty->assign("good", $goodsInfo);
        $this->_smarty->assign('id', $itemId);
        $this->_smarty->assign('type', $type);
        $this->_smarty->assign('tpl', "admin/good_manage/add.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function recordHistoryAction()
    {
        $itemId = $this->getParam("id");
        if (!ctype_digit($itemId)) {
            header("Location: /GoodsManage/index?successMsg=类型参数错误");
            return;
        }

        $goodsInfo = OneShareService::getInstance()->getShareItem($itemId);

        $redisRankKey = OneShareService::RANK_BUY_GOOD_NUM . $itemId;
        $rankListUser = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"))->zRevRangeByScore($redisRankKey, "+inf", "-inf", array(
            'withscores' => TRUE, 'limit' => array(0, 40)
        ));

        $this->_smarty->assign("good", $goodsInfo);
        $this->_smarty->assign('rankList', $rankListUser);
        $this->_smarty->assign('tpl', "admin/good_manage/user-rank.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function userDetailAction()
    {
        $openId = $this->getParam("openid");
        $userInfo = WeChatMagazineService::getInstance()->getUserInfoByOpenID($openId);
        $this->_smarty->assign("userInfo", $userInfo);
        $this->_smarty->assign('tpl', "admin/good_manage/userinfo.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function prizeAction()
    {
        $openId = $this->getParam("openid");
        $item = $this->getParam("id");

        //check当前商品whether已经offline
        $goodInfo = OneShareService::getInstance()->getShareItem($item);
        if (empty($goodInfo) || $goodInfo["state"] == ShareItemMapper::IS_ONLINE) {
            header("Location: /goodsManage/recordHistory?id=" . $item . "#商品还没有达到购买份额");
            return;
        }

        if (!empty($goodInfo["openid"])) {
            header("Location: /goodsManage/recordHistory?id=" . $item . "#该商品已经设置了中奖用户，请勿重复设置");
            return;
        }

        //set the user to 中奖
        $this->_shareItemMapper->updateOpenId($item, $openId);
        header("Location: /goodsManage/recordHistory?id=" . $item . "#该商品已经成功设置了中奖用户");
    }
}

