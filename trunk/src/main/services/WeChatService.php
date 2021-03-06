<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 20:59
 */
class WeChatService
{
    //redis
    const REDIS_KEY_JS_TICKET = "wechat_js_ticket_";
    const REDIS_ACCESS_TOKEN = "wechat_access_token_";
    //wechat url
    const URL_ACCESS_TOKEN = "https://api.weixin.qq.com/cgi-bin/token";
    const URL_JS_API_TICKET = "https://api.weixin.qq.com/cgi-bin/ticket/getticket";
    const URL_USER_INFO = "https://api.weixin.qq.com/cgi-bin/user/info";
    const URL_OAUTH_AUTHORIZE = "https://open.weixin.qq.com/connect/oauth2/authorize";
    const URL_OAUTH_ACCESS_TOKEN = "https://api.weixin.qq.com/sns/oauth2/access_token";
    const URL_OAUTH_USER_INFO = "https://api.weixin.qq.com/sns/userinfo";
    const URL_CREATE_MEUN = "https://api.weixin.qq.com/cgi-bin/menu/create";
    //upload media resource
    const URL_UPLOAD_MEDIA = "https://api.weixin.qq.com/cgi-bin/media/upload";
    const URL_FOREVER_UPLOAD_MEDIA = "https://api.weixin.qq.com/cgi-bin/material/add_material";
    //crate qr code
    const URL_CREATE_QR_CODE = "https://api.weixin.qq.com/cgi-bin/qrcode/create";
    const URL_GET_QR_CODE = "https://mp.weixin.qq.com/cgi-bin/showqrcode";
    //custom send msg
    const URL_CUSTOM_MSG = "https://api.weixin.qq.com/cgi-bin/message/custom/send";

    const URL_GET_TEMPLATE_ID = "https://api.weixin.qq.com/cgi-bin/template/api_add_template";
    const URL_SEND_TEMPLATE_MSG = "https://api.weixin.qq.com/cgi-bin/message/template/send";

    public $_appId;
    public $_appSecret;

    protected function __construct($appId, $appSecret)
    {
        $this->_appId = $appId;
        $this->_appSecret = $appSecret;
    }

    public function getAccessToken()
    {
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $redisKey = self::REDIS_ACCESS_TOKEN . $this->_appId;
        $accessToken = $redis->get($redisKey);
        if (empty($accessToken)) {
            $accessToken = $this->_getAccessToken();
            $redis->setex($redisKey, 3600, $accessToken);
        }
        return $accessToken;
    }

    private function _getAccessToken()
    {
        $params = array(
            "grant_type" => "client_credential",
            "appid" => $this->_appId,
            "secret" => $this->_appSecret,
        );
        $result = self::urlGet(self::URL_ACCESS_TOKEN, $params);
        return $result["access_token"];
    }

    //get subscribe user info
    public function getUserInfoByOpenID($openID)
    {
        $accessToken = $this->getAccessToken();
        $params = array(
            'access_token' => $accessToken,
            'openid' => $openID,
            'lang' => "zh_CN",
        );
        $result = self::urlGet(self::URL_USER_INFO, $params);
        return $result;
    }

    public function getOAuthAccessToken($code)
    {
        $params = array(
            'code' => $code,
            'appid' => $this->_appId,
            'secret' => $this->_appSecret,
            'grant_type' => 'authorization_code',
        );
        $result = self::urlGet(self::URL_OAUTH_ACCESS_TOKEN, $params);
        return $result;
    }

    /*客服消息接口*/
    public function customSendImg($openId, $mediaId)
    {
        $accessToken = $this->getAccessToken();
        $params = array(
            'touser' => $openId,
            'msgtype' => 'image',
            'image' => array(
                'media_id' => $mediaId
            )
        );
        return self::urlPost(
            self::URL_CUSTOM_MSG . "?access_token={$accessToken}",
            json_encode($params)
        );
    }

    public function customSendText($openId, $text)
    {
        $accessToken = $this->getAccessToken();
        $params = "{\"touser\": \"{$openId}\",\"msgtype\": \"text\", \"text\": {\"content\": \"{$text}\"}}";

        return self::urlPost(
            self::URL_CUSTOM_MSG . "?access_token={$accessToken}",
            $params
        );
    }

    public function customSendPicText($params)
    {
        $accessToken = $this->getAccessToken();
        return self::urlPost(
            self::URL_CUSTOM_MSG . "?access_token={$accessToken}",
            $params
        );
    }

    //pop up authorization page
    public function getUserInfoUrl($redirectURL)
    {
        $params = http_build_query(array(
            'appid' => $this->_appId,
            'redirect_uri' => $redirectURL,
            'response_type' => 'code',
            'scope' => 'snsapi_userinfo',
            'connect_redirect' => '1'
        ));
        return self::URL_OAUTH_AUTHORIZE . "?" . $params . "#wechat_redirect";
    }

    //silent authorization
    public function getUserOpenidUrl($redirectURL)
    {
        $params = http_build_query(array(
            'appid' => $this->_appId,
            'redirect_uri' => $redirectURL,
            'response_type' => 'code',
            'scope' => 'snsapi_base',
            'connect_redirect' => '1'
        ));
        return self::URL_OAUTH_AUTHORIZE . "?" . $params . "#wechat_redirect";
    }

    public function getUserInfoThroughOAuth($code)
    {
        $result = $this->getOAuthAccessToken($code);
        //oauth get user info
        $params = array(
            'access_token' => $result['access_token'],
            'openid' => $result['openid'],
            'lang' => 'zh_CN ',
        );
        $result = self::urlGet(self::URL_OAUTH_USER_INFO, $params);
        return $result;
    }

    public function getTemplateId($templateShort)
    {
        $accessToken = $this->getAccessToken();

        $result = self::urlPost(self::URL_GET_TEMPLATE_ID . "?access_token={$accessToken}",
            array("template_id_short" => $templateShort)
        );
        return $result["template_id"];
    }

    public function sendTemplateMsg($openId, $templateId, $url, $data)
    {
        $accessToken = $this->getAccessToken();

        return self::urlPost(self::URL_SEND_TEMPLATE_MSG . "?access_token={$accessToken}",
            json_encode(array(
                "touser" => $openId,
                "template_id" => $templateId,
                "url" => $url,
                "data" => $data
            ))
        );
    }

    public function checkOpenId($openId)
    {
        if (!preg_match("/^[0-9a-z_\-]{28,}$/i", $openId)) {
            throw new Exception("Invalid user openid {$openId} for wechat client service");
        }
    }

    static function urlGet($url, $paramArr)
    {
        $body = http_build_query($paramArr);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            throw new RuntimeException("Request weixin api failure, url is {$url}, http code is {$httpCode}. Curl error is " . curl_error($ch));
        }
        $result = json_decode($response, true);
        if (array_key_exists('errcode', $result) && $result["errcode"] != 0) {
            throw new RuntimeException("Request weixin api failure, errcode={$result['errcode']}, errmsg={$result['errmsg']}");
        }
        return $result;
    }

    static function urlPost($url, $paramArr)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            throw new RuntimeException("Request weixin api failure, url is {$url}, http code is {$httpCode}. Curl error is " . curl_error($ch));
        }
        $result = json_decode($response, true);
        if (array_key_exists('errcode', $result) && $result["errcode"] != 0) {
            throw new RuntimeException("Request Weixin api failure, errcode={$result['errcode']}, errmsg={$result['errmsg']}");
        }
        return $result;
    }

    public static function makeNonceStr()
    {
        $codeSet = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codes = array();
        for ($i = 0; $i < 16; $i++) {
            $codes[$i] = $codeSet[mt_rand(0, strlen($codeSet) - 1)];
        }
        $nonceStr = implode($codes);
        return $nonceStr;
    }

    //wx jsapi config
    public function getJsApiInfo($url)
    {
        $timestamp = time();
        $ticket = $this->_getJsApiTicket();
        $nonceStr = $this->makeNonceStr();
        //todo: choose what jsApiList
        $infoArr = array(
            'noncestr' => $nonceStr,
            'timestamp' => $timestamp,
            'jsapi_ticket' => $ticket,
            'url' => $url
        );
        ksort($infoArr, SORT_STRING);
        $queryStr = http_build_query($infoArr);
        $queryStr = urldecode($queryStr);
        $signature = sha1($queryStr);
        $infoArr['signature'] = $signature;
        $infoArr['appId'] = $this->_appId;
        return $infoArr;
    }

    //absolute file path add prefix @
    public function uploadImage($filePath)
    {
        $accessToken = $this->getAccessToken();
        $response = self::urlPost(
            self::URL_UPLOAD_MEDIA . "?access_token={$accessToken}&type=image",
            array(
                "media" => "@{$filePath}"
            )
        );
        return $response["media_id"];
    }

    public function uploadForeverImage($filePath)
    {
        $accessToken = $this->getAccessToken();
        $response = self::urlPost(
            self::URL_FOREVER_UPLOAD_MEDIA . "?access_token={$accessToken}&type=image",
            array(
                "media" => "@{$filePath}"
            )
        );
        return $response["media_id"];
    }


    public function createMenu($key)
    {
        $menu = json_encode(ConfigLoader::getConfig($key));
        $accessToken = $this->getAccessToken();

        self::urlPost(
            self::URL_CREATE_MEUN . "?access_token={$accessToken}",
            urldecode($menu)
        );
    }

    public function generateTemporaryQrCode($sceneId, $expire = 2592000)
    {
        $accessToken = $this->getAccessToken();
        $requestParam = array(
            'expire_seconds' => $expire,
            'action_name' => 'QR_SCENE',
            'action_info' => array(
                "scene" => array(
                    "scene_id" => $sceneId
                )
            )
        );

        return self::urlPost(self::URL_CREATE_QR_CODE . "?access_token={$accessToken}",
            json_encode($requestParam)
        );
    }

    private function _getJsApiTicket()
    {
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $redisKey = self::REDIS_KEY_JS_TICKET . $this->_appId;
        $ticket = $redis->get($redisKey);
        if (empty($ticket)) {
            $accessToken = $this->getAccessToken();
            $result = self::urlGet(self::URL_JS_API_TICKET, array(
                "type" => 'jsapi',
                "access_token" => $accessToken,
            ));
            $redis->setex($redisKey, 3600, $result["ticket"]);
        }
        return $ticket;
    }
}