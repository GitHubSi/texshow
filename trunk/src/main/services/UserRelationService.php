<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/23
 * Time: 22:44
 */
class UserRelationService
{
    private $_userRelationMapper;
    private $_weChatClientUserMapper;

    protected function __construct()
    {
        $this->_userRelationMapper = new UserRelationMapper();
        $this->_weChatClientUserMapper = new WeChatClientUserMapper();
    }

    public static function getInstance()
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new UserRelationService();
        }
        return $instance;
    }

    /**
     * user A share poster to B, when B subscribe client, add A invalid score.
     * @param $masterUnionId
     * @param $slaveUnionId
     * @return int
     */
    public function addScoreBySharedPoster($masterUnionId, $slaveUnionId)
    {
        if ($masterUnionId == $slaveUnionId) {
            return false;
        }

        $slaveUnionInfo = $this->_userRelationMapper->getSlave($slaveUnionId);
        if (!empty($slaveUnionInfo)) {
            return false;
        }

        return $this->_userRelationMapper->addRelation($masterUnionId, $slaveUnionId);
    }

    /**
     * we should rebuild user info
     *      because database is short for username and user head image
     * @param $unionId
     * @param $lastId
     * @param int $state
     * @param int $pageSize
     * @return mixed
     */
    public function listUserScore($unionId, $lastId, $state = UserRelationMapper::NO_VALID, $pageSize = 20)
    {
        $userList = $this->_userRelationMapper->getSalveByState($unionId, $lastId, $state, $pageSize);
        foreach ($userList as &$user) {
            $userDetailInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($user["openid"]);
            $user['name'] = $userDetailInfo["nickname"];
            $user['head'] = $userDetailInfo["headimgurl"];
        }
        return $userList;
    }
}