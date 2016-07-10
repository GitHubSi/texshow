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
     * when user subscribe magazine, judge whether add score for this action
     *      we use trans to guarantee accordance
     * @param $slaveUnionId
     * @return bool
     */
    public function updateScoreValid($slaveUnionId)
    {
        $slaveUnionInfo = $this->_userRelationMapper->getSlave($slaveUnionId);
        if (empty($slaveUnionInfo) || $slaveUnionInfo['state'] == UserRelationMapper::IS_VALID) {
            return false;
        }

        $db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
        $db->startTrans();
        try {
            $this->_userRelationMapper->updateState($slaveUnionId);
            $this->_weChatClientUserMapper->updateScore($slaveUnionInfo['m_unionid'], $slaveUnionInfo['score']);
            $db->commit();

            $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
            $redis->lPush("template:msg:list", $slaveUnionInfo['m_unionid'] . " " . $slaveUnionId);

            return true;
        } catch (Exception $e) {
            $db->rollback();
        }
        return false;
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
            $userOpenIdInfo = WeChatClientService::getInstance()->getUserInfoByUnionId($user["s_unionid"]);
            $userDetailInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($userOpenIdInfo["openid"]);

            $user['nickname'] = $userDetailInfo["nickname"];
            $user['headimgurl'] = $userDetailInfo["headimgurl"];
        }
        return $userList;
    }

}