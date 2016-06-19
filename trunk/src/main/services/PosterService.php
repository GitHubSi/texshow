<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/11
 * Time: 10:06
 */
class PosterService
{
    const POSTER_MSG_QUEUE = "poster_msg_queue";
    const POSTER_MAG_QUEUE_MAX_SIZE = 1000;
    const LIMIT_REQUEST_INTERVAL = 1800;
    const LIMIT_REQUEST_KEY_PREFIX = "poster:";

    //todo this pic can be changed in manage
    const POSTER_LOGO_PATH = "/tmp/bg.jpg";
    const PIC_DIR_PATH = "/tmp/image/";

    private function __construct()
    {
    }

    public static function getInstance()
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new PosterService();
        }
        return $instance;
    }

    public function curlGetContent($url, $param = array())
    {
        $body = http_build_query($param);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            throw new RuntimeException("Request api failure, url is {$url}, http code is {$httpCode}. Curl error is " . curl_error($ch));
        }
        curl_close($ch);
        return $response;
    }

    public function pushPosterMsg($openId)
    {
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $queueSize = $redis->lLen(self::POSTER_MSG_QUEUE);
        $redis->lPush(self::POSTER_MSG_QUEUE, $openId);

        //limit user frequency request this interface , the time interval must reach 30 minutes
        $redis->set(self::LIMIT_REQUEST_KEY_PREFIX . $openId, 1);
        $redis->expire(self::LIMIT_REQUEST_KEY_PREFIX . $openId, self::LIMIT_REQUEST_INTERVAL);

        //false | true just to notice user :maybe this operation will cost more time
        return $queueSize;
    }

    public function limitRequest($openId)
    {
        //get user got poster end time last time
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $exist = $redis->get(self::LIMIT_REQUEST_KEY_PREFIX . $openId);
        if ($exist) {
            return true;
        }
        return false;
    }

    /**
     * thought by auto incremental id to generate temporary qr code, when generate poster
     * @param int $openId
     * @return string
     */
    public function generatePoster($openId)
    {
        $userInfo = WeChatClientService::getInstance()->getUserInfo($openId);
        $temporaryQrCodeInfo = WeChatClientService::getInstance()->generateTemporaryQrCode($userInfo['id']);

        //todo: maybe this part should be putted in function _generateImage and the parameter should to a url
        $response = $this->curlGetContent(WeChatService::URL_GET_QR_CODE,
            array('ticket' => $temporaryQrCodeInfo['ticket'])
        );
        if (!$response) {
            return false;
        }

        $imagePath = $this->_generateImage($response, $openId, $userInfo['id']);
        if (!$imagePath) {
            return false;
        }

        $mediaId = WeChatClientService::getInstance()->uploadImage($imagePath);
        WeChatClientService::getInstance()->customSendImg($openId, $mediaId);

        return $mediaId;
    }

    /**
     * the parameter equal to file_get_contents($url),
     * @param $imageContents the url
     * @param $openId , we should take user head icon url
     * @param int $id
     * @return bool|string
     */
    private function _generateImage($imageContents, $openId, $id = 0)
    {
        $qrCodeResource = imagecreatefromstring($imageContents);

        $userInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($openId);
        $userHeadImgUrl = $userInfo['headimgurl'];
        $logoResource = imagecreatefromstring($this->curlGetContent($userHeadImgUrl));

        if (!$qrCodeResource || !$logoResource) {
            return false;
        }

        //todo: maybe change this format,now for testing
        $qrWidth = imagesx($qrCodeResource);
        $qrHeight = imagesy($qrCodeResource);
        $logoWidth = imagesx($logoResource);
        $logoHeight = imagesy($logoResource);
        $centerLogoWidth = $qrWidth / 5;
        $scale = $logoWidth / $centerLogoWidth;
        $centerLogoHeight = $logoHeight / $scale;
        $centerX = ($qrWidth - $centerLogoWidth) / 2;

        $result = imagecopyresampled($qrCodeResource, $logoResource, $centerX, $centerX, 0, 0, $centerLogoWidth,
            $centerLogoHeight, $logoWidth, $logoHeight);
        if (!$result) {
            return false;
        }

        $fileName = self::PIC_DIR_PATH . $id . '_' . time() . '.jpg';
        $result = imagejpeg($qrCodeResource, $fileName);
        if (!$result) {
            return false;
        }

        imagedestroy($qrCodeResource);
        imagedestroy($logoResource);
        return $fileName;
    }
}