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
        $response['Articles'] = array(
            'Title' => '',
            'PicUrl' => '',
            'Url' => ''
        );;

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

        $response["MsgType"] = "text";
        if (strcmp($content, "我要抽红包") === 0) {
            return RedPacketController::GetRedPacketCode($this->_openId, $content);
        }

        if (strcmp($content, 'create_menu') == 0) {
            WeChatMagazineService::getInstance()->createMenu("WECHAT_MAGAZINE_BUTTON");
        }

        return "";
    }


}