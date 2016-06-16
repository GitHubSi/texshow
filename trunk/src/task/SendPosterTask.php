<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/11
 * Time: 21:24
 */

require dirname(dirname(__FILE__)) . '/www/WebAutoLoader.php';

/**
 * because this operation is not limit for time,so we set 10s as a interval to request this queue,
 * and this message queue is designed by list structure.because lpop is a deleted way, we don't to
 * care concurrence, the element is openid
 **/

while (true) {
    $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
    $openId = $redis->rPop(self::POSTER_MSG_QUEUE);

    $userInfo = WeChatClientService::getInstance()->getUserInfo($openId);
    if (empty($userInfo)) {
        continue;
    }

    PosterService::getInstance()->getInstance()->generatePoster($userInfo['id'], $openId);
    sleep(1);
}
