<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/2
 * Time: 11:57
 */
class RedPacketService
{
    const NORMAL_RED_PACKET_TYPE = 1;
    const GROUP_RED_PACKET_TYPE = 2;
    const RED_PACKET_URL = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
    const GROUP_RED_PACKET_URL = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";
    const SEND_NAME = "[SEND_NAME]";
    const WISHING = "[WISING]";
    const REMARK = "[REMARK]";

    //Redis what many people take part in the activity and save user verify code
    const REDIS_CLIENT_RED_NUM = "client_red_num";
    const REDIS_VERIFY_CODE_SET = "client_verify_code";

    private $_appId;
    private $_appKey;
    private $_mchId;
    private $_key;
    private $_path;

    private function __construct()
    {
        $weChatConfig = ConfigLoader::getConfig('WECHAT');
        $this->_appId = $weChatConfig["client"]["id"];
        $this->_appKey = $weChatConfig["client"]["secret"];
        $this->_mchId = $weChatConfig["merchant"]["id"];
        $this->_key = $weChatConfig["merchant"]["key"];
        $this->_path = $weChatConfig["merchant"]["path"];
    }

    public static function getInstance()
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new RedPacketService();
        }
        return $instance;
    }

    /**
     * @param $type             red packet type :normal or group
     * @param $openId           send to this openid
     * @param $totalAmount      red packet amount, unit is fen
     * @param $actName          activity name
     * @param int $totalNum send the number of red packet, default is one
     * @return bool|mixed
     */
    public function sendPack($type, $openId, $totalAmount, $actName, $totalNum = 1)
    {
        if (self::NORMAL_RED_PACKET_TYPE == $type) {
            $url = self::RED_PACKET_URL;
        } else {
            $url = self::GROUP_RED_PACKET_URL;
        }
        $postXml = $this->_buildRedRequest($type, $openId, $totalAmount, $actName, $totalNum);
        return $this->curl_post_ssl($url, $postXml);
    }

    /**
     * construct xml data for accessing interface
     * return string the XML, or false if an error occurred.
     */
    private function _buildRedRequest($type, $openId, $totalAmount, $actName, $totalNum = 1)
    {
        $billNo = date("Ymd") . substr(time(), -4) . rand(100000, 999999);
        $request = array(
            'nonce_str' => WeChatService::makeNonceStr(),
            'mch_billno' => $this->_mchId . $billNo,
            'mch_id' => $this->_mchId,
            'wxappid' => $this->_appId,
            'send_name' => self::SEND_NAME,
            're_openid' => $openId,
            'total_amount' => $totalAmount,
            'total_num' => $totalNum,
            'wishing' => self::WISHING,
            'act_name' => $actName,
            'remark' => self::REMARK,
        );
        if (self::NORMAL_RED_PACKET_TYPE == $type) {
            $request['client_ip'] = $_SERVER['REMOTE_ADDR'];
        } elseif (self::GROUP_RED_PACKET_TYPE == $type) {
            $request['amt_type'] = "ALL_RAND";
        }

        //if the element in $request is empty, don't include in sign
        foreach ($request as $key => $value) {
            if (empty($value)) {
                unset($request[$key]);
            }
        }

        //get sign
        ksort($request);
        $stringA = http_build_query($request);
        $stringA = urldecode($stringA);
        $stringSignTemp = $stringA . '&key=' . $this->_key;
        $sign = mb_strtoupper(md5($stringSignTemp), "UTF-8");
        $request['sign'] = $sign;

        return SLXml::arr2XMLStr($request);
    }

    /**
     * @param $url
     * @param $vars
     * @param int $second
     * @param array $aHeader
     * @return bool|DOMDocument return DOMDocument object
     */
    private function curl_post_ssl($url, $vars, $second = 30, $aHeader = array())
    {
        $ch = curl_init();
        //time out
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, $this->_path . 'apiclient_cert.pem');
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, $this->_path . 'apiclient_key.pem');

        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            curl_close($ch);
            return false;
        }
    }
}