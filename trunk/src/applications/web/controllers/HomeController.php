<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/3
 * Time: 15:41
 */
class HomeController extends Action
{
    const BASE_URL = "http://act.wetolink.com/home/index";
    private $_salt;

    public function __construct()
    {
        parent::__construct();

        $this->_salt = ConfigLoader::getConfig("SALT");
    }

    public function preDispatch()
    {
        $openId = $this->_simpleCheckCookieValid();

        if (!$openId) {
            $code = $this->getParam("code");
            try {
                $userInfo = WeChatClientService::getInstance()->getOAuthAccessToken($code);
                $openId = $userInfo['openid'];
                $this->_setCookie($openId);
            } catch (Exception $e) {
                $redirectUrl = WeChatClientService::getInstance()->getUserOpenidUrl(self::BASE_URL);
                header('Location: ' . $redirectUrl);
                exit;
            }
        }

        Request::getInstance()->setParam("openid", $openId);
    }

    function indexAction()
    {
        $openId = $this->getParam("openid");
        if (empty($openId)) {
            return;
        }

        $userInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($openId);
        $this->_smarty->assign("userInfo", $userInfo);

        $dbUserInfo = WeChatClientService::getInstance()->getUserInfo($openId);
        $this->_smarty->assign("score", $dbUserInfo['score']);

        $salveList = UserRelationService::getInstance()->listUserScore($dbUserInfo["unionid"], PHP_INT_MAX);
        $this->_smarty->assign("salveList", $salveList);

        $this->_smarty->display('activity/home.tpl');
    }

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

    private function _setCookie($openId)
    {
        setcookie('wx', md5($openId . $this->_salt), 86400, '/');
        setcookie("openid", $openId, 86400, '/');
    }
}