<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/8/10
 * Time: 22:29
 */
class ShareItemController extends Action
{
    const BASE_URL = "http://act.wetolink.com/shareItem/index";
    private $_salt;

    public function __construct()
    {
        parent::__construct();
        $this->_salt = ConfigLoader::getConfig("SALT");
    }

    //TODO 抽象一个父类的方法，实现该方法
    public function preDispatch()
    {
        $openId = $this->_simpleCheckCookieValid();
        if (!$openId) {
            $code = $this->getParam("code");
            try {
                $userInfo = WeChatClientService::getInstance()->getOAuthAccessToken($code);
                $openId = $userInfo['openid'];
            } catch (Exception $e) {
                $redirectUrl = WeChatClientService::getInstance()->getUserOpenidUrl(self::BASE_URL);
                header('Location: ' . $redirectUrl);
                exit;
            }
        }

        $this->_setCookie($openId);
        Request::getInstance()->setParam("openid", $openId);
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

    //TODO 该方法重复
    private function _setCookie($openId)
    {
        setcookie('wx', md5($openId . $this->_salt));
        setcookie("openid", $openId);
    }

    //TODO 方法重复
    private function _simpleCheckCookieValid()
    {
        $encodeUserId = $this->getParam("wx");
        $openId = $this->getParam("openid");

        if (empty($encodeUserId) || empty($openId)) {
            return false;
        }

        $weChatSalt = ConfigLoader::getConfig("SALT");
        if (md5($openId . $weChatSalt) == $encodeUserId) {
            return $openId;
        }
        return false;
    }

}