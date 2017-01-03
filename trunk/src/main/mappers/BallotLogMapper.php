<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/11/27
 * Time: 18:09
 */
class BallotLogMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addLog($userId, $openId)
    {
        return $this->_db->execute(
            "INSERT INTO t_ballot_log (`openid`, `user_id`, `create_date`) VALUES (?, ?, DATE(NOW()))",
            array($openId, $userId)
        );
    }

    public function getVoteLog($openId, $userId, $date)
    {
        return $this->_db->getRow(
            "SELECT `id`, `openid`, `user_id`, `create_date` FROM t_ballot_log 
            WHERE openid = ? AND user_id = ? AND create_date = ? ",
            array($openId, $userId, $date)
        );
    }
}