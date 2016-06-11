<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/11
 * Time: 10:06
 */
class PosterService
{
    const POSTER_MSG_QUEUE = "post_msg_queue";
    const POSTER_MAG_QUEUE_MAX_SIZE = 1000;
    const LIMIT_REQUEST_INTERVAL = 1800;
    const LIMIT_REQUEST_KEY_PREFIX = "poster:";
    const POSTER_BG_PATH = "";

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
        if ($queueSize >= self::POSTER_MAG_QUEUE_MAX_SIZE) {
            return false;
        }
        return true;
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

    //thought by auto incremental id to generate temporary qr code, when generate poster
    public function generatePoster($id)
    {
        $temporaryQrCodeInfo = WeChatClientService::getInstance()->generateTemporaryQrCode($id);
        $response = $this->curlGetContent(WeChatService::URL_GET_QR_CODE,
            array('ticket' => $temporaryQrCodeInfo['ticket'])
        );

        if (!$response) {
            return false;
        }

        //response equal to file_get_contents($url)
        $qrCodeResource = imagecreatefromstring($response);
        $bgResource = imagecreatefromjpeg(self::POSTER_BG_PATH);

    }

}