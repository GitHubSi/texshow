<?php

//夺宝商品信息，同时存储该商品的中奖用户
class ShareItemMapper
{
    const IS_ONLINE = 0;
    const IS_OFFLINE = 1;

    /**
     * name                 goods name
     * current_score        the number of taking part in buying the goods
     * total_score          the goods price
     * image                the good image in the home page
     * state                good state, for managing in management
     * desc                 goods detail info saved by json format
     */

    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function insertGood($name, $image, $desc, $totalScore, $startTime, $endTime)
    {
        return $this->_db->execute(
            "INSERT INTO t_share_item (`name`, `image`, `desc`, `total_score`, `start_time`, `end_time`) VALUES (?, ?, ?, ?, ?, ?)",
            array($name, $image, $desc, $totalScore, $startTime, $endTime)
        );
    }

    public function updateGood($id, $name, $image, $desc, $totalScore, $startTime, $endTime)
    {
        return $this->_db->execute(
            "UPDATE t_share_item SET `name` = ?, `image` = ?, `desc` = ?, `total_score` = ?, `start_time` = ?, `end_time` = ? 
            WHERE id = ? ",
            array($name, $image, $desc, $totalScore, $startTime, $endTime, $id)
        );
    }

    public function updateScoreNum($currentScore, $item)
    {
        return $this->_db->execute(
            "UPDATE t_share_item SET current_score = current_score + ? WHERE id = ?",
            array($currentScore, $item)
        );
    }

    public function getGoodById($itemId)
    {
        return $this->_db->getRow(
            "SELECT `id`, `name`, `image`, `openid`, `winner`, `current_score`, `desc`, `total_score`, `state`, `create_time`, `update_time`, `start_time`, `end_time` 
            FROM t_share_item WHERE id = ?",
            $itemId
        );
    }

    public function getGoodsByState($state)
    {
        return $this->_db->getAll(
            "SELECT `id`, `name`, `image`, `openid`, `winner`, `current_score`, `desc`, `total_score`, `state`, `create_time`, `update_time`, `start_time`, `end_time`
            FROM t_share_item WHERE state = ? ORDER BY update_time DESC",
            $state
        );
    }

    public function getAllGoods($lastId, $pageSize = PHP_INT_MAX)
    {
        return $this->_db->getAll(
            "SELECT `id`, `name`, `image`, `openid`, `winner`, `current_score`, `desc`, `total_score`, `state`, `create_time`, `update_time`, `start_time`, `end_time`
            FROM t_share_item WHERE id < ? ORDER BY id DESC LIMIT {$pageSize}",
            $lastId
        );
    }

    public function updateGoodsState($id, $state)
    {
        return $this->_db->execute(
            "UPDATE t_share_item SET state = ? WHERE id = ? ",
            array($state, $id)
        );
    }

    public function updateOpenId($id, $openid, $winner)
    {
        return $this->_db->execute(
            "UPDATE t_share_item SET openid = ? , winner = ? WHERE id = ? ",
            array($openid, $winner, $id)
        );
    }
}