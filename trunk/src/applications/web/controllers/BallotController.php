<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2017/1/2
 * Time: 22:23
 */
class BallotController extends AbstractActivityAction
{
    private $_ballotMapper;
    private $_ballotLogMapper;
    private $_ballotType = array(
        "PRODUCTION" => 1,
        "TECHNOLOGY" => 2,
        "NEW_COMPANY" => 3,
        "STRONG_COMPANY" => 4
    );

    public function __construct()
    {
        parent::__construct();

        $this->_magaCls = "tech";
        $this->_ballotMapper = new BallotMapper();
        $this->_ballotLogMapper = new BallotLogMapper();
    }

    public function indexAction()
    {
        //redirect pc
        if (!preg_match("/(iPhone|Android|iPad|BlackBerry|Windows Phone)/i", $_SERVER['HTTP_USER_AGENT'])) {
            header("Location: http://tech.ifeng.com/it/special/fhkjnzsd/awards_choose.shtml?winzoom=1");
            return;
        }

        $ballotList = $this->_ballotMapper->getAllBallot();

        $result = array();
        foreach ($ballotList as $user) {
            $user["msg"] = json_decode($user['msg'], true);
            $result[$user["type"]][] = $user;
        }

        foreach ($result as $type => $classic) {
            usort($classic, function ($a, $b) {
                if ($a["liked"] == $b["liked"]) {
                    return 0;
                }
                return $a["liked"] < $b["liked"] ? 1 : -1;
            });
            $result[$type] = $classic;
        }

        $this->_smarty->assign("result", $result);
        $this->_smarty->display('ballot/index.tpl');
    }

    public function likeAction()
    {
        $this->_isJson = true;
        if (empty($this->_userInfo)) {
            throw new Exception("user don't subscribe", 407);
        }

        $userId = $this->getParam("user_id");
        $voteInfo = $this->_ballotLogMapper->getVoteLog($this->_userInfo["openid"], $userId, date("Y-m-d"));
        if (!empty($voteInfo)) {
            throw new Exception("only need to vote a time every day", 408);
        }

        $this->_ballotLogMapper->addLog($userId, $this->_userInfo["openid"]);
        $this->_ballotMapper->updateLiked($userId, 1);
    }

    public function getDataAction()
    {
        $callBack = $this->getParam("callback");
        $type = $this->getParam("type");

        if (empty($callBack)) {
            $this->_isJson = true;
            $result = $this->_buildData();
            $this->_data = $result[$type];
        } else {
            header('Content-Type:application/json');
            $result = $this->_buildData();
            $result = $result[$type];
            echo $callBack . "(" . json_encode($result) . ")";
        }
    }

    private function _buildData()
    {
        $result = array();

        $ballotList = $this->_ballotMapper->getAllBallot();
        foreach ($ballotList as $user) {
            $user["msg"] = json_decode($user['msg'], true);
            unset($user["create_time"], $user["update_time"]);      //highlight
            $result[$user["type"]][] = $user;
        }

        foreach ($result as $type => $classic) {
            usort($classic, function ($a, $b) {
                if ($a["liked"] == $b["liked"]) {
                    return 0;
                }
                return $a["liked"] < $b["liked"] ? 1 : -1;
            });
            $result[$type] = $classic;
        }

        return $result;
    }
}