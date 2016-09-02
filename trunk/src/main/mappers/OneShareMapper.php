<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/12
 * Time: 22:56
 */
class OneShareMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    /**
     * @param $magaOpenId   订阅号openid
     * @param $score    购买的分数
     * @param $item     购买的商品
     */
    public function addOneShare($magaOpenId, $score, $item)
    {
        return $this->_db->getAll(
            "INSERT INTO t_one_share (openid, score, item, create_time) VALUES(?, ?, ?, now())",
            array($magaOpenId, $score, $item)
        );
    }

    public function getPrizeList()
    {
        return $this->_db->getAll(
            "SELECT id, name, num, idx, score FROM t_prize ORDER BY idx ASC "
        );
    }

    public function getPrizeById($id)
    {
        return $this->_db->getRow(
            "SELECT id, name, num, idx, score FROM t_prize WHERE id = ? ",
            $id
        );
    }

    public function updatePrizeNum($id)
    {
        return $this->_db->execute(
            "UPDATE t_prize SET num = num - 1 WHERE id = ? ",
            $id
        );
    }
}