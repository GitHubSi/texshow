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

    public function __construct()
    {
        parent::__construct();
        $this->_salt = ConfigLoader::getConfig("SALT");
    }

    public function preDispatch()
    {
        //获取访问用户的openid
        $openId = "";
        if (preg_match("/micromessenger/i", $_SERVER['HTTP_USER_AGENT'])) {
            $openId = $this->_simpleCheckCookieValid();
            if (!$openId) {
                $code = $this->getParam("code");
                try {
                    $userInfo = WeChatClientService::getInstance()->getOAuthAccessToken($code);
                    $openId = $userInfo['openid'];
                } catch (Exception $e) {
                    $localUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    header('Location: ' . WeChatClientService::getInstance()->getUserOpenidUrl($localUrl));
                    exit;
                }
            }
            $this->_setCookie($openId);
        }
        Request::getInstance()->setParam("openid", $openId);
    }

    public function setWechatShare($title, $desc, $link, $imgUrl)
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $jsApiInfo = WeChatMagazineService::getInstance()->getJsApiInfo($url);
        $jsApiInfo['sharetext'] = $title;
        $jsApiInfo['shareurl'] = $link;
        $jsApiInfo["shareimg"] = $imgUrl;
        $jsApiInfo['sharedesc'] = $desc;

        return json_encode($jsApiInfo);
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

        if (md5($openId . $this->_salt) == $encodeUserId) {
            return $openId;
        }
        return false;
    }
}