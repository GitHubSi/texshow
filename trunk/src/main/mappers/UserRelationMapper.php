<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/20
 * Time: 23:30
 */
class UserRelationMapper
{
    const IS_VALID = 1;
    const NO_VALID = 0;

    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addRelation($masterUnionId, $slaveUnionId, $score = 2)
    {
        return $this->_db->execute(
            "INSERT INTO t_user_relation (m_unionid, s_unionid, score, state, create_time) VALUES (?, ?, ?, ?, now())",
            array($masterUnionId, $slaveUnionId, $score, self::NO_VALID)
        );
    }

    public function getSlave($sUnionId)
    {
        return $this->_db->getRow(
            "SELECT m_unionid, s_unionid, score, state, create_time, update_time FROM t_user_relation WHERE s_unionid = ?",
            $sUnionId
        );
    }

    public function getSalveByState($mUnionId, $lastId, $state = self::NO_VALID, $pageSize = 20)
    {
        return $this->_db->getAll(
            "SELECT m_unionid, s_unionid, score, state, create_time, update_time FROM t_user_relation
            WHERE m_unionid = ? AND id < ? AND state = ? ORDER BY id DESC LIMIT {$pageSize}",
            array($mUnionId, $lastId, $state)
        );
    }

    public function updateState($sUnionId)
    {
        return $this->_db->execute(
            "UPDATE t_user_relation SET state = ? WHERE s_unionid = ? ",
            array(self::IS_VALID, $sUnionId)
        );
    }

}