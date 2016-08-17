<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/8/16
 * Time: 23:06
 */
class WeChatNotifyMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addNotify($openId, $outTradeNo, $crashFee, $endTime)
    {
        return $this->_db->execute(
            "INSERT INTO wechat_notify (openid, out_trade_no, cash_fee, time_end, create_time) VALUES (?, ?, ?, ?, now())",
            array($openId, $outTradeNo, $crashFee, $endTime)
        );
    }

    public function getNotify($outTradeNo)
    {
        return $this->_db->getRow(
            "SELECT id, openid, out_trade_no, cash_fee, time_end, create_time FROM wechat_notify WHERE out_trade_no = ?",
            $outTradeNo
        );
    }


}