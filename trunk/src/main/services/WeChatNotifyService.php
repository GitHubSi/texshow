<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/8/13
 * Time: 15:22
 */
class WeChatNotifyService extends WxPayNotify
{
    private $_weChatNotifyMapper;

    private function __construct()
    {
        $this->_weChatNotifyMapper = new WeChatNotifyMapper();
    }

    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new WeChatNotifyService();
        }
        return $instance;
    }

    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
//        Log::DEBUG("query:" . json_encode($result));

        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS"
        ) {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        $notfiyOutput = array();

        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }

        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }

        if (!$this->_handleNotify($data)) {
            return false;
        }

        return true;
    }

    //save notify message
    private function _handleNotify($data)
    {
        $notify = $this->_weChatNotifyMapper->getNotify($data["out_trade_no"]);
        if ($notify) {
            return false;
        }

        $this->_weChatNotifyMapper->addNotify($data["openid"], $data["out_trade_no"], $data["cash_fee"], $data["time_end"]);

        //TODO add score to users
        return true;
    }
}

