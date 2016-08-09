<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/8/8
 * Time: 23:21
 */
class WeChatPayService
{
    private function __construct()
    {
        $weChatConfig = ConfigLoader::getConfig('WECHAT');
    }

    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new WeChatPayService();
        }
        return $instance;
    }

    //生成一个订单
    public function createOrder($openId)
    {
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID . date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
    }

}