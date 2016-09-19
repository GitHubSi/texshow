<?php
/**
 * 文件名：GetWinnerJob
 * 作用：抽取一元夺宝中奖用户
 * 备注：当前用户数不足2000，直接获取所有数据，进行随机
 */
require dirname(dirname(__FILE__)) . '/www/WebAutoLoader.php';

$db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
$sql = "SELECT openid, score FROM t_one_share";

//获取所有参赛用户，用户多参加一次，多增加一次夺宝的机会
$particulars = $db->getAll($sql);
$userPools = array();

foreach ($particulars as $value) {
    if ($value["score"] == 1) {
        $userPools[] = $value["openid"];
    } else {
        for ($i = 0; $i < $value["score"]; $i++) {
            $userPools[] = $value["openid"];
        }
    }
}

//对用户数组进行随机
shuffle($userPools);
shuffle($userPools);
shuffle($userPools);

//随机获取用户的openid
$winnerIndex = mt_rand(1, count($userPools));
$winnerOpenid = $userPools[$winnerIndex];

$userInfo = WeChatMagazineService::getInstance()->getUserInfoByOpenID($winnerOpenid);
var_dump($userInfo);



