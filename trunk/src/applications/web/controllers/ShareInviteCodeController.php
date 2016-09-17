<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/3
 * Time: 15:41
 */
class ShareInviteCodeController extends AbstractActivityAction
{

    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        //获取服务号的openId
        $openId = $this->getParam("openid");
        $magazineInfo = WeChatOpenService::getInstance()->getMagazineByClient($openId);
        $inviteCode = OneShareService::getInstance()->getInstance()->getInviteCode($magazineInfo["openid"]);

        $jsapi = $this->setWechatShare(
            "这里有iPhone7免费送，帮我抢你也能参与哦！",
            "据说iPhone7的预约排到了11月，凤凰科技免费送iPhone7，来的早机会大呦！",
            "http://act.wetolink.com/shareInviteCode/",
            "http://act.wetolink.com/resource/img/p-2.jpg"
        );
        $this->_smarty->assign("jsapi", $jsapi);
        $this->_smarty->assign("inviteCode", $inviteCode);
        $this->_smarty->display('activity/share-invite-code.tpl');
    }
}