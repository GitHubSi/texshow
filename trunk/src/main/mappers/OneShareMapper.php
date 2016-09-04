<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/12
 * Time: 22:56
 */
class OneShareMapper
{
    const NO_RETURN = 1;    //是否退款
    const IS_RETURN = 2;
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    /**
     * @param $magaOpenId   订阅号openid
     * @param $score    购买的积分数
     * @param $item     购买的商品
     */
    public function addOneShare($magaOpenId, $score, $item)
    {
        return $this->_db->execute(
            "INSERT INTO t_one_share (openid, score, item, create_time) VALUES(?, ?, ?, now())",
            array($magaOpenId, $score, $item)
        );
    }

    public function getCurrentBuyHistory($openId, $limit = 5)
    {
        return $this->_db->getAll(
            "SELECT id, openid, score, item, is_return, create_time, update_time FROM t_one_share WHERE openid = ?
            ORDER BY id DESC LIMIT {$limit}",
            $openId
        );
    }
}