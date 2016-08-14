<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/8/14
 * Time: 9:22
 */
class AbstractActivityAction extends Action
{
    private $_salt;
    private $_baseUrl;
    
    public function __construct($baseUrl)
    {
        parent::__construct();
        $this->_salt = ConfigLoader::getConfig("SALT");
        $this->_baseUrl = $baseUrl;
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
                $redirectUrl = WeChatClientService::getInstance()->getUserOpenidUrl($this->_baseUrl);
                header('Location: ' . $redirectUrl);
                exit;
            }
        }

        $this->_setCookie($openId);
        Request::getInstance()->setParam("openid", $openId);
    }

    //TODO 不知道为啥不能设置过期时间
    private function _setCookie($openId)
    {
        setcookie('wx', md5($openId . $this->_salt));
        setcookie("openid", $openId);
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
}