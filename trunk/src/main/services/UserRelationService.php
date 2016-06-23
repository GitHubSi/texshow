<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/6/23
 * Time: 22:44
 */
class UserRelationService
{
    private $_userRelationMapper;
    private $_weChatClientUserMapper;

    protected function __construct()
    {
        $this->_userRelationMapper = new UserRelationMapper();
        $this->_weChatClientUserMapper = new WeChatClientUserMapper();
    }

    public static function getInstance()
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new UserRelationService();
        }
        return $instance;
    }

    /**
     * when you subscribe thought scan poster,
     *  first, the scan user should subscribe this official account
     *  second, make relation between scan user and poster user
     * @param $sceneId
     * @param $openId
     * @return bool
     */
    public function makeRelationByQrCode($sceneId, $openId)
    {
        $sceneUserInfo = $this->_weChatClientUserMapper->getUserInfoById($sceneId);
        if (empty($sceneUserInfo) || empty($sceneUserInfo["unionid"])) {
            return false;
        }

        $currentUserInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($openId);
        WeChatClientService::getInstance()->subscribe($openId, $currentUserInfo['unionid']);

        $verifyUser = $this->_userRelationMapper->getSlave($currentUserInfo['unionid']);
        if (!empty($verifyUser)) {
            return false;
        }
        $this->_userRelationMapper->addRelation($sceneUserInfo["unionid"], $currentUserInfo["unionid"]);
        return true;
    }


}