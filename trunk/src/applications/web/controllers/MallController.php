<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/16
 * Time: 20:54
 */
class MallController extends AbstractActivityAction
{
    private $_shareContext = array(
        "title" => "Tex商城直接分享文案！",
        "content" => "Tex商城直接分享内容",
        "url" => "http://act.wetolink.com/mall",
        "img" => "http://act.wetolink.com/resource/img/p-1.jpg"
    );
    private $_shareItemMapper;
    private $_weChatMagazineUserMapper;

    public function __construct()
    {
        parent::__construct();

        $this->_shareItemMapper = new ShareItemMapper();
        $this->_weChatMagazineUserMapper = new WeChatMagazineUserMapper();
    }

    public function indexAction()
    {
        $goodList = OneShareService::getInstance()->getGoodList(PHP_INT_MAX, true);

        $this->_smarty->assign("jsapi",
            $this->setWechatShare($this->_shareContext["title"],
                $this->_shareContext["content"],
                $this->_shareContext["url"],
                $this->_shareContext["img"])
        );
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
        $buyNum = $this->getParam("num");

        if (!ctype_digit($itemId) || !ctype_digit($buyNum) || $buyNum <= 0 || $itemId <= 0) {
            throw new Exception("购买商品不存在或者购买份数输入非法", 410);
        }

        OneShareService::getInstance()->consumerScore($this->_userInfo, $buyNum, $itemId);
    }

    public function moreAction()
    {
        $this->_isJson = true;

        $lastId = $this->getParam("last_id");
        if (!ctype_digit($lastId)) {
            throw new Exception("parameter error,last_id is can't be null", 405);
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

    //夺宝纪录
    public function historyAction()
    {
        $ret = array();
        if (!empty($this->_userInfo)) {
            $ret = OneShareService::getInstance()->getCurrentBuyHistory($this->_userInfo["openid"], PHP_INT_MAX);
        }

        $this->_smarty->assign("history", $ret);
        $this->_smarty->display('mall/history.tpl');
    }

    //夺宝纪录-加载更多
    public function moreHistoryAction()
    {
        $this->_isJson = true;

        $lastId = $this->getParam("last_id");
        if (!ctype_digit($lastId) || $lastId <= 0) {
            throw new Exception("parameter error", 406);
        }

        $ret = array();
        if (!empty($this->_userInfo)) {
            $ret = OneShareService::getInstance()->getCurrentBuyHistory($this->_userInfo["openid"], PHP_INT_MAX);
        }

        $this->_data = $ret;
    }

    public function winRecordAction()
    {
        $ret = array();
        if (!empty($this->_userInfo)) {
            $records = $this->_shareItemMapper->getGoodByOpenid($this->_userInfo["openid"]);

            if (empty($records)) {
                foreach ($records as $key => $record) {
                    $history["batch"] = str_pad($record["id"], 10, "0", STR_PAD_LEFT);
                    $ret[] = $history;
                }
            }
        }

        $this->_smarty->assign("history", $ret);
        $this->_smarty->display('mall/winner.tpl');
    }

    public function addressAction()
    {
        if (!empty($this->_userInfo)) {
            if (!empty($this->_userInfo["addr"])) {
                $address = json_decode($this->_userInfo["addr"], true);
                $this->_smarty->assign("address", $address);
            }
        }

        $this->_smarty->display('mall/address.tpl');
    }

    public function modifyAddressAction()
    {
        $this->_isJson = true;

        if (empty($this->_userInfo)) {
            throw new Exception("还没有关注订阅号哦！", 405);
        }

        $name = $this->getParam("name");
        $phone = $this->getParam("phone");
        $address = $this->getParam("addr");

        if (empty($name) && empty($phone) && empty($address)) {
            return;
        }

        $addressJsonFormat = json_encode(array(
            "name" => $name,
            "phone" => $phone,
            "address" => $address
        ));
        $this->_weChatMagazineUserMapper->updateUserAddr($addressJsonFormat, $this->_userInfo["openid"]);
    }
}