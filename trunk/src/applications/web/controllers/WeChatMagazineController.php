<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/2
 * Time: 9:45
 */
class WeChatMagazineController extends AbstractWeChatAction
{

    const URL = "http://h5.8pig.com/subject/couponQRCode.html?";

    public function __construct()
    {
        parent::__construct();

        $weChatConfig = ConfigLoader::getConfig('WECHAT');
        $this->_token = $weChatConfig['magazine']['token'];
    }

    protected function clickHandler()
    {
        $response = array();
        $EventKey = $this->getValue("EventKey");
        switch ($EventKey) {
            case "track" :
                break;
        }

        return $response;
    }

    protected function viewHandler()
    {
        return null;
    }

    protected function subscribeHandler()
    {
        //      save user info to local mysql. add score logic. first, need to judge a user whether a new user.
        // if a new user ,update valid score. warn: if add score failed, this operation will never success forever
        try {
            $dbUserInfo = WeChatMagazineService::getInstance()->getUserInfo($this->_openId, true);

            if (empty($dbUserInfo)) {
                $weChatUserInfo = WeChatMagazineService::getInstance()->getUserInfoByOpenID($this->_openId);
                WeChatMagazineService::getInstance()->subscribe($this->_openId, $weChatUserInfo['unionid']);
            } else {
                WeChatMagazineService::getInstance()->subscribe($this->_openId);
            }
        } catch (Exception $e) {
            Logger::getRootLogger()->info("WeChatMagazineController line 48 exception");
        }

        if (empty($dbUserInfo)) {
            UserRelationService::getInstance()->updateScoreValid($weChatUserInfo['unionid']);
        }

        //subscribe info
        /*$responseContent = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"))->get(ResponseController::MAGAZINE_RESPONSE);
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
        }*/

        $response["MsgType"] = "news";
        $response['ArticleCount'] = 1;
        $response['Articles'] = array(
            array(
                'Title' => '凤凰科技年度盛典投票开启',
                'Description' => '',
                'PicUrl' => 'http://p2.ifengimg.com/a/2017_01/d8f2b35af9de4a8_size214_w600_h400.jpg',
//                'Url' => $this->_makeRedirectUrl()
                'Url' => 'http://act.wetolink.com/ballot'
            )
        );
        return $response;
    }

    protected function unsubscribeHandler()
    {
        //update user subscribe state to not subscribe
        WeChatMagazineService::getInstance()->unSubscribe($this->_openId);
    }

    protected function textHandler()
    {
        $response = array();
        $content = $this->getValue("Content");
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));

        $response["MsgType"] = "text";
        if (strcmp($content, "我要抽红包") === 0) {
            $state = $redis->get(RedPacketSettingController::RED_PACKET_SWITCH);
            if ($state == 'stop') {
                $response['Content'] = "亲，抽红包活动暂停一段时间哦，开启时间另行通知。";
                return $response;
            } elseif ($state == 'start') {
                return RedPacketController::GetRedPacketCode($this->_openId);
            }
        }

        //邀请码验证
        if (OneShareService::getInstance()->addShareScore($this->_openId, $content)) {
            Logger::getRootLogger()->info($this->_openId . "被邀请了");
            $response["Content"] = "已给该邀请码的用户加积分成功，感谢您的参与！";
            return $response;
        }

        //make auto response, the format is strict
        $responseContent = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"))->get(ResponseController::MAGAZINE_RESPONSE);
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

        if (strcmp($content, 'create_menu') == 0) {
            WeChatMagazineService::getInstance()->createMenu("WECHAT_MAGAZINE_BUTTON");
            return "";
        }

        try {
            $custom = $this->delegateTextMsgTask($content, $this->_openId);
        } catch (Exception $e) {
            Logger::getRootLogger()->error(__FUNCTION__ . ":" . $e->getMessage());
        }

        if ($custom) {
            return "";
        } else {
            $response["MsgType"] = "text";
            $response["Content"] = $responseArray['default'];
            return $response;
        }
    }

    private function _makeRedirectUrl($appId = '8011002', $secret = 'ac93de01ece8c5d48967f73d77cf6846')
    {
        $microTimestamp = intval(microtime(true) * 1000);
        $state = mt_rand(1, 10000);

        $encodeParams = array(
            "appid" => $appId,
            "timestamp" => $microTimestamp,
            "state" => $state
        );
        ksort($encodeParams);
        $stringA = urldecode(http_build_query($encodeParams));
        $stringB = $stringA . "&key={$secret}";
        $sign = md5($stringB);

        return self::URL . $stringA . "&sign={$sign}";
    }

    private function delegateTextMsgTask($content, $openId, $messagePipe = "msg_list", $separator = "||")
    {
        $content = strtolower($content);
        if (mb_substr($content, 0, 2) != "tp") {
            return false;
        }

        $content = mb_substr($content, 2);
        if (!ctype_digit($content)) {
            return false;
        }

        if ($content <= 100) {
            return false;
        }

        $content = $content - 100;
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $redis->rPush($messagePipe, $openId . $separator . $content);
        return true;
    }
}