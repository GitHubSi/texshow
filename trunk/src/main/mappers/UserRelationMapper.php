<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/20
 * Time: 23:30
 */
class UserRelationMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addRelation($masterUnionId, $slaveUnionId)
    {
        return $this->_db->execute(
            "INSERT INTO t_user_relation (m_unionid, s_unionid, create_time) VALUES (?, ?, now())",
            array($masterUnionId, $slaveUnionId)
        );
    }

    public function getSlave($unionId)
    {
        return $this->_db->getRow(
            "SELECT m_unionid, s_unionid, create_time FROM t_user_relation WHERE s_unionid = ?",
            $unionId
        );
    }
}