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
    const PAGE_SIZE = 20;
    private $_salt;
    private $_prizeMapper;

    public function __construct()
    {
        parent::__construct();

        $this->_salt = ConfigLoader::getConfig("SALT");
        $this->_prizeMapper = new PrizeMapper();
    }

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

    public function indexAction()
    {
        $openId = $this->getParam("openid");
        if (empty($openId)) {
            return;
        }

        $userInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($openId);
        $this->_smarty->assign("userInfo", $userInfo);

        $dbUserInfo = WeChatClientService::getInstance()->getUserInfo($openId);
        $this->_smarty->assign("score", $dbUserInfo['score']);

        $salveList = UserRelationService::getInstance()->listUserScore($dbUserInfo["unionid"], PHP_INT_MAX, UserRelationMapper::NO_VALID, 10);
        $this->_smarty->assign("salveList", $salveList);

        $this->_smarty->display('activity/home.tpl');
    }

    //because this function to small to don't need to create a new controller
    public function prizeAction()
    {
        $prizeList = $this->_prizeMapper->getPrizeList();
        $this->_smarty->assign("prizeList", $prizeList);
        $this->_smarty->display('activity/prize.tpl');
    }

    public function listUserAction()
    {
        $openId = $this->getParam("openid");
        if (empty($openId)) {
            return;
        }

        $dbUserInfo = WeChatClientService::getInstance()->getUserInfo($openId);
        if (empty($dbUserInfo)) {
            return;
        }

        $lastId = $this->getParam("last_id");
        if (empty($lastId)) {
            $lastId = PHP_INT_MAX;
        }

        $salveList = UserRelationService::getInstance()->listUserScore($dbUserInfo["unionid"], $lastId);
        header('Content-Type:application/json');
        echo json_encode($salveList);
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
        setcookie('wx', md5($openId . $this->_salt));
        setcookie("openid", $openId);
    }
}