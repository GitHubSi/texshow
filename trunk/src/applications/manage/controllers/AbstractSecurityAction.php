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
        //except login action, thought cookie to verify
        if (Http::$curController == 'response') {
            if (Http::$curAction == 'index' || Http::$curAction == 'login') {
                return;
            }
        }

        //other action need to grant authorization
        $userConfig = ConfigLoader::getConfig('USER');
        $username = $this->getParam('username');
        $responseKey = $this->getParam('response_key');

        if (empty($username) || empty($responseKey)) {
            header("Location: /response/index");
            exit;
        }

        $calculateValue = md5($username . $userConfig['cookie'] . date('Y-m-d'));
        if ($responseKey != $calculateValue) {
            header("Location: /response/index");
            exit;
        }

        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $todaySubscribe = $redis->get(AbstractWeChatAction::PREFIX_TODAY_SUBSCRIBE . date('Y_m_d'));
        $todayUnSubscribe = $redis->get(AbstractWeChatAction::PREFIX_TODAY_UN_SUBSCRIBE . date('Y_m_d'));

        $this->_smarty->assign('subscribe', $todaySubscribe);
        $this->_smarty->assign('unsubscribe', $todayUnSubscribe);
    }

}