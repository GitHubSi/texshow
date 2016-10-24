<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/16
 * Time: 20:54
 */
class MallController extends Action
{
    public function indexAction()
    {
        $this->_smarty->display('mall/home.tpl');
    }
}