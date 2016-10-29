<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/23
 * Time: 20:06
 */
class GoodsManageController extends AbstractSecurityAction
{
    private $_shareItemMapper;

    public function __construct()
    {
        parent::__construct();
        $this->_shareItemMapper = new ShareItemMapper();
    }

    public function indexAction()
    {
        $allGoods = $this->_shareItemMapper->getAllGoods(PHP_INT_MAX);
        foreach ($allGoods as $good) {
            if ($good["state"] == ShareItemMapper::IS_OFFLINE) {
                $result["offline"][] = $good;
                continue;
            }
            $result["online"][] = $good;
        }

        $this->_smarty->assign('goods', $result);
        $this->_smarty->assign('tpl', "admin/good_manage/index.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function addGoodAction()
    {

    }
}