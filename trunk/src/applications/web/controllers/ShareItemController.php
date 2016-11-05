<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/8/10
 * Time: 22:29
 */
class ShareItemController extends AbstractActivityAction
{
    private $_shareItemMapper;

    public function __construct()
    {
        parent::__construct();
        $this->_shareItemMapper = new ShareItemMapper();
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

    //商品详情页
    public function iphoneAction()
    {
        $item = 1;      //默认item=1表示iphone手机

        $noRegister = 0;
        $goodInfo = $this->_shareItemMapper->getGoodById($item);
        try {
            $magazineInfo = WeChatOpenService::getInstance()->getMagazineByClient($this->getParam("openid"));
            $userInfo = WeChatMagazineService::getInstance()->getUserInfoByOpenID($magazineInfo["openid"]);
            $userInfo = array_merge($magazineInfo, $userInfo);
        } catch (Exception $e) {
            $noRegister = 1;
        }

        $jsapi = $this->setWechatShare(
            "这里有iPhone7免费送，帮我抢你也能参与哦！",
            "据说iPhone7的预约排到了11月，凤凰科技免费送iPhone7，来的早机会大呦！",
            "http://act.wetolink.com/shareItem/iphone/",
            "http://act.wetolink.com/resource/img/p-1.jpg"
        );
        $this->_smarty->assign("jsapi", $jsapi);

        $this->_smarty->assign("noRegister", $noRegister);
        $this->_smarty->assign("userInfo", $userInfo);
        $this->_smarty->assign("good", $goodInfo);
        $this->_smarty->assign("startTime", date("y/m/d", strtotime($goodInfo["start_time"])));
        $this->_smarty->assign("endTime", date("y/m/d", strtotime($goodInfo["end_time"])));
        $this->_smarty->display('activity/share-iphone.tpl');
    }
}
