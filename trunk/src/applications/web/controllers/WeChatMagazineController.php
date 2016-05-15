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
        $response = array();
        $response['MsgType'] = 'text';
        $response['Content'] = "欢迎关注TeX，在这里你能看到全新的科技视频内容，更鲜活更有趣，拒绝枯燥参数，给你最真实的数码体验。在这里，你还能免费参加最新最酷数码产品试玩，每周还有惊喜大奖等你来拿。";

        //save user info to local mysql
        try {
            WeChatMagazineService::getInstance()->subscribe($this->_openId);
        } catch (Exception $e) {
        }

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
        $tag_redpacket = 'redis_red_packet';

        $response["MsgType"] = "text";
        if (strcmp($content, "我要抽红包") === 0) {
            $state = $redis->get($tag_redpacket);
            if ($state == 'stop') {
                $response['Content'] = "亲，抽红包活动暂停一段时间哦，开启时间另行通知。";
                return $response;
            } elseif ($state == 'start') {
                return RedPacketController::GetRedPacketCode($this->_openId);
            }
        }

        if (strcmp($content, 'stop_redpacket') === 0) {
            $redis->set($tag_redpacket, 'stop');
        }

        if (strcmp($content, 'start_redpacket') === 0) {
            $redis->set($tag_redpacket, 'start');
        }

        if (strcmp($content, 'create_menu') == 0) {
            WeChatMagazineService::getInstance()->createMenu("WECHAT_MAGAZINE_BUTTON");
        }

        return "";
    }


}