<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/12
 * Time: 22:56
 */
class PrizeMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
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