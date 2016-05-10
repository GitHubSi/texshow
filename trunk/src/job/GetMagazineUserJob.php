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
    $weChatService = WeChatMagazineService::getInstance($appId, $appKey);
    $paramArr = array(
        'access_token' => $weChatService->getAccessToken(),
        'next_openid' => $nextOpenId
    );
    $result = $weChatService::urlGet($weChatUserListUrl, $paramArr);
    $openIdList = $result['data']['openid'];

    Logger::getRootLogger()->info(json_encode($openIdList));
    foreach ($openIdList as $openId) {
        try {
            $wechatClientUser = new WechatClientUserMapper();
            echo $openId;
            exit;
            //WeChatMagazineService::getInstance()->subscribe($openId);
            $totalInsert ++;
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
