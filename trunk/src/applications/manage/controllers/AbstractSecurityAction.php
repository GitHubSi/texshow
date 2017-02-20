<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/29
 * Time: 11:16
 */
class AbstractSecurityAction extends Action
{
    protected $_useFrame = true;

    public function preDispatch()
    {
        //except login action, thought cookie to verify
        if (Http::$curController == 'response') {
            if (Http::$curAction == 'index' || Http::$curAction == 'login') {
                return;
            }
        }

        $userConfig = ConfigLoader::getConfig('USER');
        $username = $this->getParam('username');
        $verifyKey = $this->getParam('verify_key');

        if (empty($username) || empty($verifyKey)) {
            header("Location: /response/index");
            exit;
        }

        $calculateValue = md5($username . $userConfig['cookie'] . date('Y-m-d'));
        if ($verifyKey != $calculateValue) {
            header("Location: /response/index");
            exit;
        }
    }

    public function postDispatch()
    {
        if ($this->_useFrame) {
            $this->_smarty->assign("menu", $this->_getMenuListConfig());
            $this->_smarty->display('admin/frame.tpl');
        }
    }

    private function _getMenuListConfig()
    {
        $groupList = array(
            "base_info" => array(
                "title" => "基本功能"
            ),
            "nav_mall" => array(
                "title" => "Mall管理"
            ),
            "nav_vote" => array(
                "title" => "投票活动"
            )
        );

        $groupList["base_info"]["sub_menu"] = array(
            array(
                "href" => "/response",
                "title" => "回复自动"
            ),
            array(
                "href" => "/redPacketSetting",
                "title" => "红包设置"
            )
        );

        $groupList["nav_mall"]["sub_menu"] = array(
            array(
                "href" => "/headImg",
                "title" => "轮播图管理"
            ),
            array(
                "href" => "/goodsManage",
                "title" => "商品管理"
            )
        );

        $groupList["nav_vote"]["sub_menu"] = array(
            array(
                "href" => "/voteManage",
                "title" => "投票一期"
            ),
            array(
                "href" => "/ballotManage",
                "title" => "投票二期"
            )
        );

        foreach ($groupList as $groupId => $groupValue) {
            foreach ($groupValue["sub_menu"] as $subKey => $subItem) {
                if (preg_match("/\\" . $subItem["href"] . "/", Http::$curController)) {
                    $groupList[$groupId]["unfold"] = 1;
                    $groupList[$groupId]["sub_menu"][$subKey]["active"] = 1;
                    break;
                }
            }
        }

        return $groupList;
    }
}

