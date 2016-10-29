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
        $goodList = OneShareService::getInstance()->getGoodList(true);

        $this->_smarty->assign("goodList", $goodList);
        $this->_smarty->display('mall/home.tpl');
    }

    public function buyAction()
    {
        $itemId = $this->getParam("item");
        $openId = $this->getParam("openid");
        $score = $this->getParam("num");

        if (!ctype_digit($itemId) || !ctype_digit($score) || $score <= 0 || $itemId <= 0) {
            throw new Exception("商品item id或者购买num输入非法", 402);
        }

        OneShareService::getInstance()->consumerScore($openId, $score, $itemId);
    }

}