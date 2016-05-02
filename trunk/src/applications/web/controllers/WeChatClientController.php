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
        $response = array();
        $response["MsgType"] = "text";
        $response["Content"] = "欢迎关注服务号！";

        WechatClientService::getInstance()->unsubscribe($this->_openid);
        return $response;
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
        $openId = $this->getValue("FromUserName");
        //红包逻辑
        //验证抽奖的红包密码是否正确
        if (ctype_digit($content) && 6 == strlen($content) && $this->_redis->sIsMember(RedPackService::REDIS_RED_PACK, $content)
            && $this->_redis->sRem(RedPackService::REDIS_RED_PACK, $content)
        ) {
            if ($this->_redpackMapper->getInfoByOpenId($openId)) {
                //用户已经领取过红包
                $response["Content"] = "您之前已经参加过我们的红包活动了哦~，请持续关注我们的公众号！更多活动等待您来参加！";
                return $response;
            }
            $redPackNum = $this->_redis->incr(RedPackService::REDIS_RED_PACK_NUM);
            $isSendRed = $redPackNum % 3 ? false : true;
            if ($isSendRed) {
                //发红包,并存储红包记录
                $redPackData = RedPackService::getInstance()->sendPack(RedPackService::RED_PACK_TYPE, $openId, 100, "360儿童手表任性发红包", 1);
                $redPackresult = RedPackService::getInstance()->xmlToArray($redPackData);
                if ($redPackresult['return_code'] == 'FAIL' || $redPackresult['result_code'] == 'FAIL') {
                    //todo 红包发送失败的用户，并没有存储到数据表中
                    $response["Content"] = "您今天的运气可能不太好哦~没有获得红包，请持续关注我们的公众号！更多活动等待您来参加！";
                } else {
                    RedPackService::getInstance()->saveRedPackResult($redPackresult);
                    $response["Content"] = "恭喜您中奖啦！快让您的朋友也来参加活动抢红包吧！";
                }
            } else {
                //不发红包
                $this->_redpackMapper->insertUser($openId);
                $response["Content"] = "您今天的运气可能不太好哦~没有获得红包，请持续关注我们的公众号！更多活动等待您来参加！";
            }
            return $response;
        }
    }
}