<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/2
 * Time: 9:45
 */
class WeChatMagazineController extends AbstractWeChatAction
{

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
            $this->_staticNumber(self::PREFIX_TODAY_SUBSCRIBE);
        } catch (Exception $e) {
            Logger::getRootLogger()->info("WeChatMagazineController line 48 exception");
        }

        if (empty($dbUserInfo)) {
            UserRelationService::getInstance()->updateScoreValid($weChatUserInfo['unionid']);
        }

        //subscribe info
        $responseContent = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"))->get(ResponseController::MAGAZINE_RESPONSE);
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
        //update user subscribe state to not subscribe
        WeChatMagazineService::getInstance()->unSubscribe($this->_openId);
        $this->_staticNumber(self::PREFIX_TODAY_UN_SUBSCRIBE);

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

        $response["MsgType"] = "text";
        $response["Content"] = $responseArray['default'];
        return $response;
    }

    private function _staticNumber($prefix)
    {
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $todayKey = $prefix . date('Y_m_d');
        if ($redis->exists($todayKey)) {
            $redis->incr($todayKey);
        } else {
            $redis->incr($todayKey);
            $redis->expire($todayKey, 86400);
        }
    }

}