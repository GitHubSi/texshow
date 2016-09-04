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
    private $_shareItemMapper;

    public function __construct()
    {
        parent::__construct(self::BASE_URL);
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

    public function iphoneAction()
    {
        $item = 1;      //默认item=1表示iphone手机
        $goodInfo = $this->_shareItemMapper->getScoreNum($item);

        $this->_smarty->assign("good", $goodInfo);
        $this->_smarty->assign("startTime", date("y/m/d", strtotime($goodInfo["start_time"])));
        $this->_smarty->assign("endTime", date("y/m/d", strtotime($goodInfo["end_time"])));
        $this->_smarty->display('activity/share-iphone.tpl');
    }

    public function buyAction()
    {
        $item = 1;
        $openId = $this->getParam("openid");
        $score = $this->getParam("rob_num");

        try{
            if (!ctype_digit($score)) {
                throw new Exception("参数错误", 20001);
            }
            OneShareService::getInstance()->consumerScore($openId, $score, $item);
            header("Location: http://act.wetolink.com/home/magazine");
            return;
        } catch (Exception $e) {
            Logger::getRootLogger()->info($e->getMessage());
            header("Location: http://act.wetolink.com/shareItem/iphone");
        }
    }
}
