<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/11
 * Time: 21:24
 */

require dirname(dirname(__FILE__)) . '/www/WebAutoLoader.php';

/**
 * 统一处理用户回复的文本消息
 **/

$messagePipe = "msg_list";
$separator = "||";
$start = 0;
$stop = 100;

while (true) {
    $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
    $messageList = $redis->lRange($messagePipe, $start, $stop);
    if (empty($messageList)) {
        sleep(1);
        continue;
    }


    foreach ($messageList as $message) {

    }

    $redis->lTrim($messagePipe, $stop + 1, -1);
}

function vote()
{

}
