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

        //whether send red packet
        RedPacketController::sendRedPacket($this->_openId, $content);
    }
}