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
    private $_errCode = 0;
    private $_errMsg = "";

    protected $_data;
    protected $_weChatLogin = false;
    protected $_anonymityLogin = false;
    protected $_isJson = false;
    protected $_userInfo;

    public function __construct()
    {
        parent::__construct();
        $this->_salt = ConfigLoader::getConfig("SALT");
    }

    public function preDispatch()
    {
        //only get openid as user identity
        $openId = $this->getUserOpenid();
        if (!empty($openId)) {
            $this->_weChatLogin = true;
            $this->_userInfo = WeChatOpenService::getInstance()->getMagazineByClient($openId);
        }

        Request::getInstance()->setParam("openid", $openId);
    }

    public function dispatch($action)
    {
        try {
            $this->preDispatch();
            $this->$action();
        } catch (Exception $e) {
            $this->_errCode = $e->getCode();
            $this->_errMsg = $e->getMessage();
            Logger::getRootLogger()->error($this->_errMsg . ", code=" . $this->_errCode);
        }

        $this->postDispatch();
    }

    public function postDispatch()
    {
        if ($this->_isJson) {
            header('Content-Type:application/json');
            $result = array(
                'code' => $this->_errCode,
                'msg' => $this->_errMsg,
                'data' => $this->_data,
            );
            echo json_encode($result);
        } else {
            if ($this->_errCode != 0) {
                $this->_dealException();
            }
        }
    }

    public function getUserOpenid()
    {
        $openId = "";
        if (preg_match("/micromessenger/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
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
                $this->_setCookie($openId);
            }
        }

        return $openId;
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

    private function _setCookie($openId)
    {
        setcookie('wx', md5($openId . $this->_salt), time() + SystemUtil::DAY, "/");
        setcookie("openid", $openId, time() + SystemUtil::DAY, "/");
    }

    private function _simpleCheckCookieValid()
    {
        $encodeOpenid = $this->getParam("wx");
        $openId = $this->getParam("openid");

        if (empty($encodeOpenid) || empty($openId)) {
            return false;
        }

        if (md5($openId . $this->_salt) == $encodeOpenid) {
            return $openId;
        }
        return false;
    }

    public static function generateAnonymityUser()
    {
        $uid = floor(microtime(true) * 1000) . mt_rand(1, 1000);
        return $uid;
    }

    private function _dealException()
    {
        header("Location: http://act.wetolink.com/mall");
    }
}