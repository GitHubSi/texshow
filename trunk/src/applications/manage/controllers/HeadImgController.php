<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/24
 * Time: 17:54
 */
class HeadImgController extends AbstractSecurityAction
{
    public function indexAction()
    {
        $this->_smarty->assign('tpl', "admin/head_img/add.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }
}