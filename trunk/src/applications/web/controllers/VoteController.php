<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/11/27
 * Time: 17:42
 */
class VoteController extends AbstractActivityAction
{
    const RULE_LINK = "http://share.iclient.ifeng.com/sharenews.f?aid=cmpp_040710044517783";
    const PAGE_SIZE = 20;
    const HUNDRED = 100;
    const LIKED_RANK = "liked_rank";

    private $_voteLogMapper;
    private $_voteUserMapper;

    public function __construct()
    {
        parent::__construct();

        $this->_voteLogMapper = new VoteLogMapper();
        $this->_voteUserMapper = new VoteUserMapper();
    }

    public function indexAction()
    {
        $topFourUser = $this->_voteUserMapper->getAllUser(PHP_INT_MAX, PHP_INT_MAX, 4);
        foreach ($topFourUser as &$user) {
            $user = $this->_metaUserInfo($user);
        }

        //redirect pc
        if (!preg_match("/(iPhone|Android|iPad|BlackBerry|Windows Phone)/i", $_SERVER['HTTP_USER_AGENT'])) {
            header("Location: http://tech.ifeng.com/test/special/nrxwmd/list.shtml");
            return;
        }

        $this->_smarty->assign("ruleLink", self::RULE_LINK);
        $this->_smarty->assign("userList", $topFourUser);
        $this->_smarty->display("vote/index.tpl");
    }

    public function detailAction()
    {
        $id = intval($this->getParam("no"));
        $userInfo = $this->_voteUserMapper->getUserById($id);
        if (!empty($userInfo)) {
            $userInfo = $this->_metaUserInfo($userInfo, true);
        }

        $shareContext = array(
            "title" => "“{$userInfo['msg']['wish']}”正在向我招手，快来帮我投票！",
            "content" => "我正在参加凤凰科技的你任性我买单活动，任性大奖任你拿！",
            "img" => $userInfo["msg"]["poster"],
            "url" => "http://act.wetolink.com/vote/detail?no={$id}"
        );

        $jsapi = $this->setWechatShare($shareContext["title"], $shareContext["content"], $shareContext["url"], $shareContext["img"]);
        $this->_smarty->assign("jsapi", $jsapi);
        $this->_smarty->assign("userInfo", $userInfo);
        $this->_smarty->display('vote/detail.tpl');
    }

    public function listAction()
    {
        $userList = $this->_voteUserMapper->getAllUser(PHP_INT_MAX, PHP_INT_MAX, self::PAGE_SIZE);
        foreach ($userList as &$user) {
            $user = $this->_metaUserInfo($user);
        }

        $this->_smarty->assign("userList", $userList);
        $this->_smarty->display('vote/list.tpl');
    }

    //对外提供的接口
    public function infoAction()
    {
        $this->_isJson = true;
        $id = intval($this->getParam("id"));
        if ($id <= 0) {
            throw new Exception("parameter error", 400);
        }

        $userInfo = $this->_voteUserMapper->getUserById($id);
        if (empty($userInfo)) {
            throw new Exception("user don't existed", 400);
        }

        $detailInfo = $this->_metaUserInfo($userInfo, true);
        $this->_data = $detailInfo;
    }

    public function findAction()
    {
        $this->_isJson = true;
        $id = intval($this->getParam("no")) - self::HUNDRED;
        if ($id <= 0) {
            throw new Exception("parameter error", 400);
        }

        $userInfo = $this->_voteUserMapper->getUserById($id);
        if (empty($userInfo)) {
            throw new Exception("user don't existed", 400);
        }

        $this->_data = $id;
    }

    public function moreAction()
    {
        $this->_isJson = true;

        $lastNo = $this->getParam("last_id");
        $lastLiked = $this->getParam("last_liked");
        $pageSize = $this->getParam("count");
        if (!ctype_digit($lastNo) || !ctype_digit($lastLiked)) {
            throw new Exception("parameter error ", 400);
        }

        if ($lastNo == 0) {
            $lastNo = PHP_INT_MAX;
            $lastLiked = PHP_INT_MAX;
        }

        if (!ctype_digit($pageSize) || empty($pageSize)) {
            $pageSize = self::PAGE_SIZE;
        }

        $userList = $this->_voteUserMapper->getAllUser($lastNo, $lastLiked, $pageSize);
        foreach ($userList as &$user) {
            $user = $this->_metaUserInfo($user);
        }

        $this->_data = $userList;
    }

    private function _metaUserInfo($user, $isDetail = false)
    {
        $user["number"] = $user["id"] + self::HUNDRED;
        $user['msg'] = json_decode($user['msg'], true);
        if ($isDetail) {
            $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
            $userRank = $redis->zRevRangeByScore(self::LIKED_RANK, '+inf', 0, array('withscores' => TRUE, 'limit' => array(0, 3)));
            if (!empty($userRank)) {
                $topThree = array_keys($userRank);
                if (isset($topThree[0]) && $topThree[0] == $user["id"]) {
                    $user["top1"] = 1;
                } elseif (isset($topThree[1]) && $topThree[1] == $user["id"]) {
                    $user["top2"] = 1;
                } elseif (isset($topThree[2]) && $topThree[2] == $user["id"]) {
                    $user["top3"] = 1;
                }
            }
            $topUserLiked = array_shift($userRank);
            $user['fail'] = $topUserLiked - $user['liked'];
        }
        return $user;
    }
}