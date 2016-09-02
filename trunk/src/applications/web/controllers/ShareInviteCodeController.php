<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/3
 * Time: 15:41
 */
class ShareInviteCodeController extends AbstractActivityAction
{
    const BASE_URL = "http://act.wetolink.com/shareInviteCode/index";

    public function __construct()
    {
        parent::__construct(self::BASE_URL);
    }

    public function indexAction()
    {
        //获取服务号的openId
        $openId = $this->getParam("openid");
        $magazineInfo = WeChatOpenService::getInstance()->getMagazineByClient($openId);
        $inviteCode = OneShareService::getInstance()->getInstance()->getInviteCode($magazineInfo["openid"]);

        $this->_smarty->assign("inviteCode", $inviteCode);
        $this->_smarty->display('activity/share-invite-code.tpl');
    }
}