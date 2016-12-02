<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/11/27
 * Time: 18:09
 */
class VoteLogMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addLog($userId, $openId)
    {
        return $this->_db->execute(
            "INSERT INTO t_vote_log (`openid`, `user_id`, `create_date`, `update_time`) VALUES (?, ?, NOW(), NOW())",
            array($openId, $userId)
        );
    }

    public function getUserById($id)
    {
        return $this->_db->getRow(
            "SELECT `id`, `openid`, `user_id`, `create_date`, `update_time` FROM t_vote_log WHERE openid = ?",
            $id
        );
    }
}