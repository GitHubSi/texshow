<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/31
 * Time: 21:53
 */
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
        //TODO 明天完成这一部分的开发和设计
        $latestPublic = $this->_shareItemMapper->getGoodsByState(ShareItemMapper::IS_OFFLINE);
        if (!empty($latestPublic)) {
            foreach ($latestPublic as $key => &$publish) {
                $publish["batch"] = str_pad($publish["id"], 10, "0", STR_PAD_LEFT);
                $publish["user_name"] = "稍后开发";
                $publish["invite_code"] = "087678";
                $publish["open_time"] = $publish["create_time"];
            }
        }

        $this->_smarty->assign("latestPublic", $latestPublic);
        $this->_smarty->display('mall/new-public.tpl');
    }
}