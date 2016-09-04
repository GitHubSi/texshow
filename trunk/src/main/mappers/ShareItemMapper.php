<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/12
 * Time: 22:56
 */
class ShareItemMapper
{
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

    public function getScoreNum($item)
    {
        return $this->_db->getRow(
            "SELECT id, name, current_score, total_score, create_time, update_time, start_time, end_time FROM t_share_item WHERE id = ?",
            $item
        );
    }
}