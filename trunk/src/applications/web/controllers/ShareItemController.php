<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/8/10
 * Time: 22:29
 */
class ShareItemController extends AbstractActivityAction
{
    const BASE_URL = "http://act.wetolink.com/shareItem/iphone";

    public function __construct()
    {
        parent::__construct(self::BASE_URL);
    }

    public function preDispatch()
    {
        if (Http::$curAction == "notify") {
            return true;
        }

        parent::preDispatch();
    }

    //TODO item最好设计成一个路由的形式，而且item应该是一个数据库存储的数据
    //TODO 在Action不存在的情况下，会报异常
    public function indexAction()
    {
        $item = $this->getParam("item");
        $openId = $this->getParam("openid");

        $this->_smarty->assign("editAddress", 1);
        $this->_smarty->assign("jsApiParameters", WeChatPayService::getInstance()->createOrder($openId));
        $this->_smarty->display('activity/share-item.tpl');

    }

    //receive trade notify
    public function notifyAction()
    {
        return true;
        Logger::getRootLogger()->info("notify");
        WeChatPayService::getInstance()->getInstance()->handleNotify();
    }

    public function iphoneAction(){
        $this->_smarty->display('activity/share-iphone.tpl');
    }
}
