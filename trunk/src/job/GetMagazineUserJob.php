<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/10
 * Time: 23:43
 */
require dirname(dirname(__FILE__)) . '/www/WebAutoLoader.php';

$weChatUserListUrl = "https://api.weixin.qq.com/cgi-bin/user/get";
$nextOpenId = "";

while (1) {
    $paramArr = array(
        'access_token' => WeChatMagaTechService::getInstance()->getAccessToken(),
        'next_openid' => $nextOpenId
    );
    $result = WeChatMagaTechService::urlGet($weChatUserListUrl, $paramArr);
    $openIdList = $result['data']['openid'];

    foreach ($openIdList as $openId) {
        try {
            /*$userInfo = WeChatMagaTechService::getInstance()->getUserInfo($openId);
            if (empty($userInfo)) {
                WeChatMagaTechService::getInstance()->subscribe($openId);
                $totalInsert++;
            }*/
            WeChatMagaTechService::getInstance()->subscribe($openId);
            usleep(200);
            $totalInsert++;
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
