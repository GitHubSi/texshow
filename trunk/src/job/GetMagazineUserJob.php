<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/10
 * Time: 23:43
 */
require dirname(dirname(__FILE__)) . '/www/WebAutoLoader.php';

//订阅号配置
$weChatPass = ConfigLoader::getConfig('WECHAT');
$appId = $weChatPass["magazine"]["id"];
$appKey = $weChatPass["magazine"]["secret"];

//服务号配置
//$wechatPass = ConfigLoader::getConfig('WECHAT');
//$appId = $wechatPass["client"]["id"];
//$appKey = $wechatPass["client"]["secret"];

$weChatUserListUrl = "https://api.weixin.qq.com/cgi-bin/user/get";
$nextOpenId = "";

while (1) {
    $paramArr = array(
        'access_token' => WeChatMagazineService::getInstance()->getAccessToken(),
        'next_openid' => $nextOpenId
    );
    $result = WeChatMagazineService::urlGet($weChatUserListUrl, $paramArr);
    $openIdList = $result['data']['openid'];

    foreach ($openIdList as $openId) {
        try {
            $userInfo = WeChatMagazineService::getInstance()->getUserInfo($openId);
            if (empty($userInfo)) {
                WeChatMagazineService::getInstance()->subscribe($openId);
                $totalInsert++;
            }
        } catch (Exception $e) {
            Logger::getRootLogger()->info("{$openId} insert failed");
        }
    }

    if (empty($result['next_openid'])) {
        Logger::getRootLogger()->info("insert over");
        break;
    }

    $nextOpenId = $result['next_openid'];
    $count += $result['count'];
    Logger::getRootLogger()->info("Count has write :" . $count);
}
Logger::getRootLogger()->info("count :" . $totalInsert . "task is over");
