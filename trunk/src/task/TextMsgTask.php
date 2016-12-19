<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/11
 * Time: 21:24
 */

require dirname(dirname(__FILE__)) . '/www/WebAutoLoader.php';

/**
 * 统一处理用户回复的文本消息，消息队列为msg_list,消息队列中消息的组成：openid||userId
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

    $voteUserMapper = new VoteUserMapper();
    $voteLogMapper = new VoteLogMapper();
    foreach ($messageList as $message) {
        $msgPiece = explode($separator, $message);
        if (count($msgPiece) != 2) {
            continue;
        }

        $openId = trim($msgPiece[0]);
        $number = trim($msgPiece[1]);
        if (!ctype_digit($number)) {
            continue;
        }

        //当前Task处理：你任性-我买单活动
        $existedUser = $voteUserMapper->getUserById($number);
        if (empty($existedUser)) {
            //参赛选手不存在
            WeChatMagazineService::getInstance()->customSendText($openId, "抱歉，因为输入错误的用户代码无法投票");
            continue;
        }

        $likedLog = $voteLogMapper->getVoteLog($openId, $number, date("Y-m-d"));
        if (!empty($likedLog)) {
            //已经给参数选手投过票了
            WeChatMagazineService::getInstance()->customSendText($openId, "抱歉，您已经成功投过票了，无法再次投票");
            continue;
        }

        $db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
        $db->startTrans();
        try {
            $voteUserMapper->updateLiked($number, 1);
            $voteLogMapper->addLog($number, $openId);
            $db->commit();

            $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
            $userRank = $redis->zIncrBy("liked_rank", 1, $number);
        } catch (Exception $e) {
            $db->rollback();
            Logger::getRootLogger()->info("{$openId} vote {$number} failed");
            WeChatMagazineService::getInstance()->customSendText($openId, "Tex君说：系统出了点小故障，工程狮正在修复！");
            continue;
        }

        //WeChatMagazineService::getInstance()->customSendText($openId, "恭喜您投票成功，点击链接按步骤领取红包");
        $picTextMsg = "{\"touser\":\"{$openId}\",\"msgtype\":\"news\",\"news\":{\"articles\":[{\"title\":\"恭喜您投票成功！点击消息领取新用户1元现金红包\",\"description\":\"\",\"url\":\"http:\/\/h5.8pig.com\/subject\/couponQRCode.html\",\"picurl\":\"http:\/\/p1.ifengimg.com\/04a169a73a8934ac\/2016\/51\/226033312852326226.jpg\"}]}}";
        WeChatMagazineService::getInstance()->customSendPicText($picTextMsg);
    }
    $redis->lTrim($messagePipe, $stop + 1, -1);
}
