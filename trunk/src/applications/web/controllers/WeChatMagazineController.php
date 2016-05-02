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
        $response['MsgType'] = 'news';
        $response['ArticleCount'] = 1;
        $response['Articles'] = [] = array(
            'Title' => '',
            'PicUrl' => '',
            'Url' => ''
        );;

        //save user info to local mysql
        try {
            WechatMagazineService::getInstance()->subscribe($this->_openId);
        } catch (Exception $e) {
        }

        return $response;
    }

    protected function unsubscribeHandler()
    {
        //update user subscribe state to not subscribe
        WechatMagazineService::getInstance()->unsubscribe($this->_openId);
    }

    protected function textHandler()
    {
        $response = array();
        $userInput = $this->getValue("Content");
        $openId = $this->getValue("FromUserName");

        $response["MsgType"] = "text";
        if (strcmp($userInput, "我要抽红包") === 0) {
            return $this->_redPack($openId, $response);
        }

        return $response;
    }
}