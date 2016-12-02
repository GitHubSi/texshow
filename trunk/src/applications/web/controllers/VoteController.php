<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/11/27
 * Time: 17:42
 */
class VoteController extends AbstractActivityAction
{
    const RULE_LINK = "";
    const PAGE_SIZE = 20;
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
        $topSixUser = $this->_voteUserMapper->getAllUser(PHP_INT_MAX, 6);
        foreach ($topSixUser as &$user) {
            $user = $this->_metaUserInfo($user);
        }

    }

    public function detailAction()
    {
        $id = $this->getParam("no");

        $userInfo = $this->_voteUserMapper->getUserById($id);
        if (!empty($userInfo)) {
            $userInfo = $this->_metaUserInfo($userInfo, true);
        }

        $this->_smarty->assign("userInfo", $userInfo);
        $this->_smarty->display('vote/detail.tpl');
    }

    public function listAction()
    {
        $userList = $this->_voteUserMapper->getAllUser(PHP_INT_MAX, self::PAGE_SIZE);
        foreach ($userList as &$user) {
            $user = $this->_metaUserInfo($user);
        }

    }

    public function moreAction()
    {


    }

    private function _metaUserInfo($user, $isDetail = false)
    {
        $user['msg'] = json_decode($user['msg'], true);

        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $userRank = $redis->zRangeByScore(self::LIKED_RANK, '-inf', '+inf', array('withscores' => TRUE, 'limit' => array(0, 1)));
        $topUserLiked = array_shift($userRank);
        $user['fail'] = $topUserLiked - $user['liked'];

        return $user;
    }
}