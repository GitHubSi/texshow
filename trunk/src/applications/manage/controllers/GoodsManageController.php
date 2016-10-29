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

    public function modifyAction()
    {
        $name = $this->getParam("name");
        $totalScore = $this->getParam("price");
        $image = $this->getParam("image");
        $desc = $this->getParam("desc");
        $type = $this->getParam("type");
        $itemId = $this->getParam("id");

        if (empty($name) || empty($totalScore) || empty($image) || empty($desc)) {
            header("Location: /GoodsManage/index?successMsg=参数不能为空");
            return;
        }
        if ($type == "edit" && !ctype_digit($itemId)) {
            header("Location: /GoodsManage/index?errMsg=");
            return;
        }

        //just for extension in the future
        $descArray = array(
            array(
                "title" => "",
                "image" => $desc
            )
        );

        $insertDay = date("Y-m-d H:i:s");
        if ($type == "add") {
            $this->_shareItemMapper->insertGood($name, $image, json_encode($descArray), $totalScore, $insertDay, $insertDay);
        }
        if ($type == "edit") {
            $this->_shareItemMapper->updateGood($itemId, $name, $image, json_encode($descArray), $totalScore, $insertDay, $insertDay);
        }

        header("Location: /GoodsManage/index?successMsg=添加成功");
    }

    public function infoAction()
    {
        $type = $this->getParam("type");
        $itemId = $this->getParam("id");

        $allowType = array(
            "detail", "edit", "add"
        );
        if (!in_array($type, $allowType)) {
            header("Location: /GoodsManage/index?successMsg=类型参数错误");
            return;
        }

        if (!empty($itemId)) {
            $goodsInfo = $this->_shareItemMapper->getGoodById($itemId);

            $descArr = json_decode($goodsInfo["desc"], true);
            $goodsInfo["desc"] = $descArr[0]["image"];
        }

        $this->_smarty->assign("good", $goodsInfo);
        $this->_smarty->assign('id', $itemId);
        $this->_smarty->assign('type', $type);
        $this->_smarty->assign('tpl', "admin/good_manage/add.tpl");
        $this->_smarty->display('admin/b-index.tpl');
    }

}