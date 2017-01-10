<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/2
 * Time: 9:45
 */
class WeChatMagaTechController extends AbstractWeChatAction
{
    private $_weChatMagaTechUserMapper;

    public function __construct()
    {
        parent::__construct();

        $weChatConfig = ConfigLoader::getConfig('WECHAT');
        $this->_token = $weChatConfig['tech_magazine']['token'];

        $this->_weChatMagaTechUserMapper = new WeChatMagaTechUserMapper();
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
        WeChatMagaTechService::getInstance()->subscribe($this->_openId);

        /*$response["MsgType"] = "news";
        $response['ArticleCount'] = 1;
        $response['Articles'] = array(
            array(
                'Title' => '凤凰科技年度盛典投票开启',
                'Description' => '',
                'PicUrl' => 'http://p2.ifengimg.com/a/2017_01/d8f2b35af9de4a8_size214_w600_h400.jpg',
                'Url' => 'http://act.wetolink.com/ballot'
            )
        );*/
        $response["MsgType"] = "text";
        $response["Content"] = "谢谢您关注凤凰科技，让我们一起把科技变得更性感。\n\n欢迎分享、评论、吐槽！ \n\n想看点什么，想问点什么，或者想要爆料，直接留言即可。";
        return $response;
    }

    protected function unsubscribeHandler()
    {
        //update user subscribe state to not subscribe
        $this->_weChatMagaTechUserMapper->updateSubscribe($this->_openId, WeChatMagaTechUserMapper::UNSUBSCRIBE);
    }

    protected function textHandler()
    {
        $response = array();
        $content = $this->getValue("Content");

        if (strcmp($content, 'create_menu') == 0) {
            WeChatMagaTechService::getInstance()->createMenu("WECHAT_MAGAZINE_TECH_BUTTON");
            return "";
        }

        $response["MsgType"] = "text";
        return "";
    }
}