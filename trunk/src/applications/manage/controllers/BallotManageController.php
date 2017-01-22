<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2017/1/1
 * Time: 18:36
 */
class BallotManageController extends AbstractSecurityAction
{
    const PAGE_SIZE = 20;
    private $_ballotMapper;
    private $_ballotType = array(
        "PRODUCTION" => 1,
        "TECHNOLOGY" => 2,
        "NEW_COMPANY" => 3,
        "STRONG_COMPANY" => 4
    );

    public function __construct()
    {
        parent::__construct();
        $this->_ballotMapper = new BallotMapper();
    }

    public function indexAction()
    {
        $ballotList = $this->_ballotMapper->getAllBallot();

        $result = array();
        foreach ($ballotList as $user) {
            $user["msg"] = json_decode($user['msg'], true);
            $result[$user["type"]][] = $user;
        }

        $this->_smarty->assign("userList", $result);
        $this->_smarty->assign('tpl', 'admin/ballot/index.tpl');
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function modifyAction()
    {
        $name = $this->getParam("name");
        $imageUrl = $this->getParam("img_url");
        $ballotType = $this->getParam("ballot_type");
        $prizeReason = $this->getParam("prize_reason");

        $ballotId = $this->getParam("id");
        $type = $this->getParam("type");

        //todo 将来将管理后台的disply页面抽象到基类中
        $allType = array("add", "edit");
        if (!in_array($type, $allType)) {
            $this->_smarty->assign("error", "编辑类型错误");
            return;
        }

        if ($type == "add" || $type == "edit") {
            if (empty($name) || empty($imageUrl) || empty($ballotType)) {
                $this->_smarty->assign("error", "缺少必要的参数");
                return;
            }
            $msg = json_encode(
                array(
                    'name' => trim($name),
                    'img_url' => trim($imageUrl),
                    'prize_reason' => $prizeReason
                )
            );
        }

        if ($type == "add") {
            $ballotId = $this->_ballotMapper->addBallotItem($name, $msg, $ballotType);
        }

        if ($type == "edit") {
            if (empty($ballotId)) {
                $this->_smarty->assign("缺少必要的参数", 400);
                return;
            }
            $this->_ballotMapper->updateBallotItem($ballotId, $name, $msg, $ballotType);
        }

        header("Location: /BallotManage/detail?type=info&id={$ballotId}");
    }

    public function detailAction()
    {
        $type = $this->getParam("type");
        $itemId = $this->getParam("id");

        $allType = array("add", "edit", "info");
        if (!in_array($type, $allType)) {
            $this->_smarty->assign("编辑类型错误", 400);
            return;
        }

        if ($type != "add" && empty($itemId)) {
            $this->_smarty->assign("缺少必要的参数", 400);
            return;
        }

        $ballotItem = array();
        if ($type != "add") {
            $ballotItem = $this->_ballotMapper->getBallotItemById($itemId);
            if (!empty($ballotItem)) {
                $ballotItem["msg"] = json_decode($ballotItem["msg"], true);
            }
        }

        $this->_smarty->assign("type", $type);
        $this->_smarty->assign("userInfo", $ballotItem);
        $this->_smarty->assign('tpl', 'admin/ballot/edit.tpl');
        $this->_smarty->display('admin/b-index.tpl');
    }

    public function delAction()
    {
        $ballotId = $this->getParam("id");
        if (!ctype_digit($ballotId)) {
            return false;
        }

        $userInfo = $this->_ballotMapper->getBallotItemById($ballotId);
        if (empty($userInfo)) {
            return false;
        }

        $this->_ballotMapper->deleteBallotItem($ballotId);
        header("Location: /ballotManage/index");
    }

    public function addLikeAction()
    {
        $ballotId = $this->getParam("id");
        if (!ctype_digit($ballotId)) {
            return false;
        }

        $userInfo = $this->_ballotMapper->getBallotItemById($ballotId);
        if (empty($userInfo)) {
            return false;
        }

        $this->_ballotMapper->updateLiked($ballotId, 1);
        header("Location: /ballotManage/index");
    }
}