<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/16
 * Time: 20:54
 */
class MallController extends AbstractActivityAction
{
    public function indexAction()
    {
        $goodList = OneShareService::getInstance()->getGoodList(PHP_INT_MAX, true);

        $this->_smarty->assign("userInfo", $this->_userInfo);
        $this->_smarty->assign("goodList", $goodList);
        $this->_smarty->display('mall/home.tpl');
    }

    public function buyAction()
    {
        $this->_isJson = true;

        if (empty($this->_userInfo)) {
            throw new Exception("用户信息不存在", 409);
        }

        $itemId = $this->getParam("item");
        $openId = $this->getParam("openid");
        $score = $this->getParam("num");

        if (!ctype_digit($itemId) || !ctype_digit($score) || $score <= 0 || $itemId <= 0) {
            throw new Exception("商品item id或者购买num输入非法", 402);
        }

        OneShareService::getInstance()->consumerScore($openId, $score, $itemId);
    }

    public function moreAction()
    {
        $this->_isJson = true;

        $lastId = $this->getParam("last_id");
        if (!ctype_digit($lastId)) {
            $lastId = PHP_INT_MAX;
        }

        $goodList = OneShareService::getInstance()->getGoodList($lastId);
        $this->_data = $goodList["goodList"];
    }

    public function detailAction()
    {
        $itemId = $this->getParam("item");
        if (!ctype_digit($itemId) || $itemId <= 0) {
            throw new Exception("商品item id输入非法", 402);
        }
        $shareItem = OneShareService::getInstance()->getShareItem($itemId);
        $shareItem["desc"] = json_decode($shareItem["desc"], true);

        $jsapi = $this->setWechatShare(
            "这里有iPhone7免费送，帮我抢你也能参与哦！",
            "据说iPhone7的预约排到了11月，凤凰科技免费送iPhone7，来的早机会大呦！",
            "http://act.wetolink.com/shareItem/iphone/",
            "http://act.wetolink.com/resource/img/p-1.jpg"
        );
        $this->_smarty->assign("jsapi", $jsapi);

        $this->_smarty->assign("good", $shareItem);
        $this->_smarty->assign("userInfo", $this->_userInfo);
        $this->_smarty->display('mall/good-detail.tpl');
    }

    public function historyAction()
    {
        $ret = array();
        try {
            $openId = $this->getParam("openid");
            $ret = OneShareService::getInstance()->getCurrentBuyHistory($openId, PHP_INT_MAX, 4);
        } catch (Exception $e) {

        }

        $this->_smarty->assign("history", $ret);
        $this->_smarty->display('mall/history.tpl');
    }

    public function moreHistoryAction()
    {
        $this->_isJson = true;
        $lastId = $this->getParam("last_id");
        if (!ctype_digit($lastId) || $lastId <= 0) {
            throw new Exception("parameter error", 406);
        }

        $ret = array();
        try {
            $openId = $this->getParam("openid");
            $ret = OneShareService::getInstance()->getCurrentBuyHistory($openId, $lastId);
        } catch (Exception $e) {

        }

        $this->_data = $ret;
    }
}