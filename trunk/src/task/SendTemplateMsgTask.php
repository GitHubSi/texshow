<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/3
 * Time: 21:23
 */

require dirname(dirname(__FILE__)) . '/www/WebAutoLoader.php';

/**
 * when user get valid score, send template msg remind
 */
while (true) {
    $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
    $unionIdStr = $redis->rPop("template:msg:list");
    $unionIdArr = explode(" ", $unionIdStr);
    if (count($unionIdArr) != 2) {
        continue;
    }

    $masterUnionId = $unionIdArr[0];
    $salveUnionId = $unionIdArr[1];
    $masterUserInfo = WeChatClientService::getInstance()->getUserInfoByUnionId($masterUnionId);
    $salveUserInfo = WeChatClientService::getInstance()->getUserInfoByUnionId($salveUnionId);
    if (empty($masterUserInfo) || empty($salveUserInfo)) {
        continue;
    }

    $salveDetailInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($salveUserInfo["openid"]);

    $templateMsg = array(
        "first" => array(
            "value" => "您好，您的积分账户变更提醒",
            "color" => "#173177"
        ),
        "keyword1" => array(
            "value" => $salveDetailInfo["nickname"],
            "color" => "#173177"
        ),
        "keyword2" => array(
            "value" => "通过扫码，给你增加了2积分",
            "color" => "#173177"
        ),
        "keyword3" => array(
            "value" => "当前积分" . $masterUserInfo["score"],
            "color" => "#173177"
        ),
        "remark" => array(
            "value" => "感谢你关注Tex",
            "color" => "#173177"
        )
    );

    WeChatClientService::getInstance()->sendTemplateMsg($masterUserInfo["openid"],
        "ZSdJu0fEqYRLaRhAKZROKq3xiPV71c-zU65XQSJGkeM", "http://act.wetolink.com/home/index", $templateMsg);
    sleep(1);
}
