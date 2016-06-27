<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/2
 * Time: 9:45
 */
class WeChatClientController extends AbstractWeChatAction
{
    private $_weChatConfig;

    public function __construct()
    {
        parent::__construct();

        $weChatConfig = ConfigLoader::getConfig("WECHAT");
        $this->_weChatConfig = $weChatConfig['client'];
        $this->_token = $weChatConfig['client']['token'];
    }

    protected function subscribeHandler()
    {
        try {
            $userInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($this->_openId);
            WeChatClientService::getInstance()->subscribe($this->_openId, $userInfo['unionid']);
        } catch (Exception $e) {
            Logger::getRootLogger()->info($e->getMessage());
        }

        //subscribe info
        $responseContent = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"))->get(ResponseController::CLIENT_RESPONSE);
        $responseArray = json_decode($responseContent, true);
        if (is_array($responseArray)) {
            if (isset($responseArray['subscribe'])) {
                $value = $responseArray['subscribe'];
                if (!is_array($value)) {
                    $response["MsgType"] = "text";
                    $response["Content"] = $value;
                } else {
                    $response['MsgType'] = 'news';
                    $response['ArticleCount'] = count($value);
                    $response['Articles'] = $value;
                }
                return $response;
            }
        }
    }

    protected function unsubscribeHandler()
    {
        WeChatClientService::getInstance()->unsubscribe($this->_openId);
    }

    protected function clickHandler()
    {
        $EventKey = $this->getValue("EventKey");
        switch ($EventKey) {
            case "track" :
                break;
        }

        if (isset($response)) {
            return $response;
        }
    }

    protected function textHandler()
    {
        $response["MsgType"] = "text";
        $content = $this->getValue("Content");

        //create menu
        if (strcmp($content, 'create_menu') == 0) {
            WeChatClientService::getInstance()->createMenu("WECHAT_CLIENT_BUTTON");
        }

        //make auto response, the format is strict
        $responseContent = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"))->get(ResponseController::CLIENT_RESPONSE);
        $responseArray = json_decode($responseContent, true);
        if (is_array($responseArray)) {
            foreach ($responseArray as $key => $value) {
                if (strcmp($key, $content) == 0) {
                    if (!is_array($value)) {
                        $response["Content"] = $value;
                    } else {
                        $response['MsgType'] = 'news';
                        $response['ArticleCount'] = count($value);
                        $response['Articles'] = $value;
                    }
                    return $response;
                }
            }
        }

        //just for test case
        if (strcmp("poster", $content) == 0) {
            $isFrequent = PosterService::getInstance()->limitRequest($this->_openId);
            if ($isFrequent) {
                $response['Content'] = "不能重复生成海报哦，请半个小时后重新生成！";
                return $response;
            }

            $number = PosterService::getInstance()->pushPosterMsg($this->_openId);
            if ($number > 5) {
                $response['Content'] = "海报正在生成，前面排队的人数还有{$number},请稍等！";
            } else {
                $response['Content'] = "海报正在生成,请稍等！";
            }
            return $response;
        }

        //whether send red packet
        return RedPacketController::sendRedPacket($this->_openId, $content);
    }
}