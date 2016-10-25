<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/12
 * Time: 22:56
 */
class ShareItemMapper
{
    const IS_RUNNING = 0;
    const MEMBER_FULL = 1;
    const GAME_OVER = 2;

    //TODO 表结构修改
    /**
     * name                 商品名称
     * current_score        当前购买用户
     * total_score          总共积分
     * image                商品介绍图片
     * state                活动状态，1表示已经名额已满，2表示抽奖已经结束
     * desc                 商品描述
     */

    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function updateScoreNum($currentScore, $item)
    {
        return $this->_db->execute(
            "UPDATE t_share_item SET current_score = current_score + ? WHERE id = ?",
            array($currentScore, $item)
        );
    }

    public function getGoodById($item)
    {
        return $this->_db->getRow(
            "SELECT id, `name`, image, current_score, `desc`, total_score, state, create_time, update_time, start_time, end_time 
            FROM t_share_item WHERE id = ?",
            $item
        );
    }

    public function getGoodsByState($state)
    {
        return $this->_db->getAll(
            "SELECT id, `name`, image, current_score, total_score, state, create_time, update_time, start_time, end_time  
            FROM t_share_item WHERE state = ? ORDER BY id DESC",
            $state
        );
    }

    public function getAllGoods()
    {
        return $this->_db->getAll(
            "SELECT id, `name`, image, current_score, total_score, state, create_time, update_time, start_time, end_time  
            FROM t_share_item ORDER BY id DESC"
        );
    }

    public function updateGoodsState($id, $state)
    {
        return $this->_db->execute(
            "UPDATE t_share_item SET state = ? WHERE id = ? ",
            array($state, $id)
        );
    }
}