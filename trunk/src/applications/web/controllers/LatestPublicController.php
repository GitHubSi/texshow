<?php

//最新中奖公告页
class LatestPublicController extends AbstractActivityAction
{

    private $_shareItemMapper;

    public function __construct()
    {
        parent::__construct();
        $this->_shareItemMapper = new ShareItemMapper();
    }

    public function indexAction()
    {
        $latestPublic = $this->_shareItemMapper->getGoodsByState(ShareItemMapper::IS_OFFLINE);
        if (!empty($latestPublic)) {
            foreach ($latestPublic as $key => &$publish) {
                $publish["batch"] = str_pad($publish["id"], 10, "0", STR_PAD_LEFT);
                if (empty($publish["openid"])) {
                    $publish["user_name"] = "等待抽取...";
                    $publish["invite_code"] = "等待确认...";
                    $publish["open_time"] = "等待确认...";
                } else {
                    $publish["user_name"] = $publish["winner"];
                    $publish["invite_code"] = OneShareService::getInstance()->getInviteCode($publish["openid"]);
                    $publish["open_time"] = $publish["update_time"];
                }
            }
        }

        $this->_smarty->assign("latestPublic", $latestPublic);
        $this->_smarty->display('mall/new-public.tpl');
    }
}