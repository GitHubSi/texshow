<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/12/2
 * Time: 16:42
 */
class VoteManageController extends AbstractSecurityAction
{
    private $_voteUserMapper;
    const PAGE_SIZE = 40;

    public function __construct()
    {
        parent::__construct();
        $this->_voteUserMapper = new VoteUserMapper();
    }

    public function indexAction()
    {
        $userList = $this->_voteUserMapper->getAllUser(PHP_INT_MAX, self::PAGE_SIZE);
        foreach ($userList as &$user) {
            $user["msg"] = json_decode($user['msg'], true);
        }

        $nextPage = 0;
        if (count($userList) == self::PAGE_SIZE) {
            $nextPage = 1;
        }

        $this->_smarty->assign("nextPage", $nextPage);
        $this->_smarty->assign("userList", $userList);
        $this->_smarty->assign('tpl', 'admin/vote/index.tpl');
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function modifyAction()
    {
        $poster = $this->getParam("poster");
        $nickName = $this->getParam("name");
        $video = $this->getParam("video");
        $wish = $this->getParam("wish");
        $desc = $this->getParam("desc");

        $number = $this->getParam("id");
        $type = $this->getParam("type");

        $allType = array("add", "edit");
        if (!in_array($type, $allType)) {
            $this->_smarty->assign("error", "编辑类型错误");
            return;
        }

        if ($type == "add" || $type == "edit") {
            if (empty($poster) || empty($nickName) || empty($video) || empty($wish)) {
                $this->_smarty->assign("error", "缺少必要的参数");
                return;
            }
            $msg = json_encode(
                array(
                    'poster' => trim($poster),
                    'video' => trim($video),
                    'name' => trim($nickName),
                    'wish' => trim($wish),
                    'desc' => trim($desc)
                )
            );
        }

        if ($type == "add") {
            $number = $this->_voteUserMapper->addUser($nickName, $msg);
        }

        if ($type == "edit") {
            if (empty($number)) {
                $this->_smarty->assign("缺少必要的参数", 400);
                return;
            }
            $this->_voteUserMapper->updateUser($number, $nickName, $msg);
        }

        header("Location: /voteManage/detail?type=info&id={$number}");
    }

    public function detailAction()
    {
        $type = $this->getParam("type");
        $number = $this->getParam("id");

        $allType = array("add", "edit", "info");
        if (!in_array($type, $allType)) {
            $this->_smarty->assign("编辑类型错误", 400);
            return;
        }

        if ($type != "add" && empty($number)) {
            $this->_smarty->assign("缺少必要的参数", 400);
            return;
        }

        $userInfo = array();
        if ($type != "add") {
            $userInfo = $this->_voteUserMapper->getUserById($number);
            if (!empty($userInfo)) {
                $userInfo["msg"] = json_decode($userInfo["msg"], true);
            }
        }

        $this->_smarty->assign("type", $type);
        $this->_smarty->assign("userInfo", $userInfo);
        $this->_smarty->assign('tpl', 'admin/vote/edit.tpl');
        $this->_smarty->display('admin/b-index.tpl');
    }
}