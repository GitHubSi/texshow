<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/16
 * Time: 22:59
 */
class PrizeRecordMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addRecord($prizeId, $openid, $name, $phone, $city, $province, $region, $detail)
    {
        return $this->_db->execute(
            "INSERT INTO t_prize_record (prize_id, openid, name, phone, city, province, region, detail, create_time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, now())",
            array($prizeId, $openid, $name, $phone, $city, $province, $region, $detail)
        );
    }

    public function getRecode($openid)
    {
        return $this->_db->getAll(
            "SELECT id, prize_id, openid, name, phone, city, province, region, detail, create_time FROM t_prize_record
            WHERE openid = ? ",
            $openid
        );
    }
}