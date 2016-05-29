<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/29
 * Time: 11:16
 */
class AbstractSecurityAction extends Action
{
    public function preDispatch()
    {
        //if login action
        if (Http::$curAction == 'login' || Http::$curAction == 'index') {
            return;
        }

        //other action need to grant authorization
        $userConfig = ConfigLoader::getConfig('USER');
        $username = $this->getParam('username');
        $responseKey = $this->getParam('response_key');
        $calculateValue = md5($username . $userConfig['cookie'] . date('Y-m-d'));
        if ($responseKey != $calculateValue) {
            header("Location: /response/index");
            exit;
        }

        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $todaySubscribe = $redis->get(AbstractWeChatAction::PREFIX_TODAY_SUBSCRIBE);
        $todayUnSubscribe = $redis->get(AbstractWeChatAction::PREFIX_TODAY_UN_SUBSCRIBE);
        $this->_smarty->assign('subscribe', $todaySubscribe);
        $this->_smarty->assign('unsubscribe', $todayUnSubscribe);
    }
}