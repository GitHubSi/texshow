<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/24
 * Time: 17:54
 */
class HeadImgController extends AbstractSecurityAction
{
    private $_headImgMapper;

    public function __construct()
    {
        parent::__construct();
        $this->_headImgMapper = new HeadImgMapper();
    }

    public function indexAction()
    {
        $this->_smarty->assign('tpl', "admin/head_img/add.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function addAction()
    {
        $name = $this->getParam("name");
        $imgUrl = $this->getParam("img_url");
        $redirectUrl = $this->getParam("redirect_url");

        if (empty($name) || empty($imgUrl) || empty($redirectUrl)) {
            header("Location: /headImg/index?errorMsg=缺少必填项");
            return;
        }

        $this->_headImgMapper->addHeadImg($name, $imgUrl, $redirectUrl);

        header("Location: /headImg/index?successMsg=添加成功");
        return;
    }

    public function detailAction()
    {
        $headImages = $this->_headImgMapper->getImgByState(HeadImgMapper::NO_DELETE);

        $this->_smarty->assign('headImgs', $headImages);
        $this->_smarty->assign('tpl', "admin/head_img/detail.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function delAction()
    {
        $id = $this->getParam("id");
        if (ctype_digit($id) && $id > 0) {
            $this->_headImgMapper->updateState($id, HeadImgMapper::IS_DELETE);
        }

        header("Location: /headImg/detail?successMsg=添加成功");
    }
}