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

            //the user never subscribe client before
            $dbUserInfo = WeChatClientService::getInstance()->getUserInfo($this->_openId, true);
            if (empty($dbUserInfo)) {
                $userInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($this->_openId);
                WeChatClientService::getInstance()->subscribe($this->_openId, $userInfo['unionid']);
            } else {
                WeChatClientService::getInstance()->subscribe($this->_openId);
            }

            //first subscribe,scan qr code
            $eventKey = $this->getValue("EventKey");
            if (empty($dbUserInfo) && !empty($eventKey) && strpos($eventKey, "qrscene_") === 0) {

                //the user never subscribe magazine before
                $magazineUserInfo = WeChatMagazineService::getInstance()->getUserInfoByUnionId($userInfo['unionid'], true);
                if (empty($magazineUserInfo)) {
                    $userId = substr($eventKey, 8);
                    $masterUserInfo = WeChatClientService::getInstance()->getUserInfoById($userId);
                    UserRelationService::getInstance()->addScoreBySharedPoster($masterUserInfo['unionid'], $userInfo['unionid']);

                    //reply magazine QR Code. the user subscribe thought scanning poster
                    $response["MsgType"] = "image";
                    $response["Image"]["MediaId"] = "eM0CsH00u0VhjpuCsIZeHTrp6gWcoSBzBIfjyRHGvYs";
                    return $response;
                }
            }
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

        if (strcmp("open", $content) === 0) {
            Logger::getRootLogger()->info($this->_openId);
        }

        //whether send red packet
        return RedPacketController::sendRedPacket($this->_openId, $content);
    }
}