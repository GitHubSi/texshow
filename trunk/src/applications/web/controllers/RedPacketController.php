<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/2
 * Time: 13:02
 */
class RedPacketController extends Action
{
    const START_TIME = '2016-05-12 07:00:00';

    public function __construct()
    {
        parent::__construct();
    }

    //magazine: random generate verify code, update user read packet state
    public static function GetRedPacketCode($openId)
    {
        $userInfo = WeChatMagazineService::getInstance()->getUserInfo($openId, true);

        //this condition don't happen in normal, just avoid unknown condition
        if (empty($userInfo)) {
            WechatMagazineService::getInstance()->subscribe($openId);
            return '';
        }

        //the user take part in this activity must be a new user for subscription
        if ($userInfo['create_time'] > self::START_TIME) {
            if (WeChatMagazineUserMapper::RED_PACKET_INIT == $userInfo['redpacket']) {

                //generate verify code
                $effectRow = WeChatMagazineService::getInstance()->updateRedPacketState($openId, WeChatMagazineUserMapper::RED_PACKET_SUCC);
                if ($effectRow) {
                    $password = substr(time(), -2) . rand(1000, 9999);
                    RedisClient::getInstance(ConfigLoader::getConfig("REDIS"))->sAdd(RedPacketService::REDIS_VERIFY_CODE_SET, $password);

                    //response message to client
                    $response['MsgType'] = 'news';
                    $response['ArticleCount'] = 1;
                    $response['Articles'][] = array(
                        'Title' => '点击获取抽奖口令',
                        'PicUrl' => 'http://p2.ifengimg.com/a/2016/0510/6cc6bd1079b2894size110_w900_h500.jpg',
                        'Url' => "http://act.wetolink.com/RedPacket/index?code={$password}"
                    );
                }
            } else {
                $response["MsgType"] = "text";
                $response["Content"] = '抱歉，您已经领取过抽奖口令了！';
            }
            return $response;
        } else {
            return "";
        }
    }

    //client: verify code ,then judge whether to send red packet
    public static function sendRedPacket($openId, $verifyCode)
    {
        //only response text message
        $response["MsgType"] = "text";
        $redis = RedisClient::getInstance(ConfigLoader::getConfig('REDIS'));
        $userInfo = WeChatClientService::getInstance()->getUserInfo($openId, true);

        //just void unknown condition
        if (empty($userInfo)) {
            WeChatClientService::getInstance()->subscribe($openId);
            return '';
        }

        if (ctype_digit($verifyCode) && 6 == strlen($verifyCode) && $redis->sIsMember(RedPacketService::REDIS_VERIFY_CODE_SET, $verifyCode) && $redis->sRem(RedPacketService::REDIS_VERIFY_CODE_SET, $verifyCode)) {
            if ($userInfo['redpacket'] != WeChatClientUserMapper::RED_PACKET_INIT) {
                $response["Content"] = "您之前已经参加过我们的红包活动了哦~，请持续关注我们的公众号！更多活动等待您来参加！";
                return $response;
            }

            $sendRedPacketNum = $redis->incr(RedPacketService::REDIS_CLIENT_RED_NUM);
            $isSendRed = $sendRedPacketNum % 10 ? false : true;
            if ($isSendRed) {
                //send red packet
                $randomMoney = mt_rand(10, 50);
                $redPacket = RedPacketService::getInstance()->sendPack(RedPacketService::NORMAL_RED_PACKET_TYPE, $openId, 100 + $randomMoney, "TeX微信红包大放送", 1);
                $redPacketResult = SLXml::xmlToArray($redPacket);
                if ($redPacketResult['return_code'] == 'FAIL' || $redPacketResult['result_code'] == 'FAIL') {
                    WeChatClientService::getInstance()->updateRedPacketState($openId, WeChatClientUserMapper::RED_PACKET_FAIL);
                    $response["Content"] = "您今天的运气可能不太好哦~没有获得红包，请持续关注我们的公众号！更多活动等待您来参加！";
                    //todo: need to logger this error

                } else {
                    WeChatClientService::getInstance()->updateRedPacketState($openId, WeChatClientUserMapper::RED_PACKET_SUCC);
                    $response["Content"] = "恭喜您中奖啦！快让您的朋友也来参加活动抢红包吧！";
                }
            } else {
                WeChatClientService::getInstance()->updateRedPacketState($openId, WeChatClientUserMapper::RED_PACKET_FAIL);
                $response["Content"] = "您今天的运气可能不太好哦~没有获得红包，请持续关注我们的公众号！更多活动等待您来参加！";
            }
            return $response;
        }
    }

    public function indexAction()
    {
        $code = $this->getParam("code");
        $this->_smarty->assign("code", $code);
        $this->_smarty->display('activity/redpacket.tpl');
    }

}