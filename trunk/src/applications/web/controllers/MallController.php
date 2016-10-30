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

}