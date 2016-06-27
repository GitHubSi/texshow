<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/27
 * Time: 22:50
 */

require dirname(dirname(__FILE__)) . '/www/WebAutoLoader.php';
$id = 1004;
$length = 1000;

do {
    try {
        $db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
        $sql = "SELECT id, openid FROM wechat_magazine_user WHERE id > ? LIMIT {$length}";
        $result = $db->getAll($sql, $id);
        $count = count($result);

        foreach ($result as $user) {
            $userInfo = WeChatMagazineService::getInstance()->getUserInfoByOpenID($user['openid']);
            $sql = "UPDATE wechat_magazine_user SET unionid = ? WHERE openid = ?";
            $db->execute($sql, array($userInfo['unionid'], $userInfo['openid']));
        }

        $id = $result[$count - 1]['id'];
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} while ($count == $length);
echo "success";
