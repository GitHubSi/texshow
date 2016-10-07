<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/17
 * Time: 22:46
 */
class ResponseController extends AbstractSecurityAction
{
    //temp
    const MAGAZINE_RESPONSE = 'magazine_response';
    const CLIENT_RESPONSE = 'client_response';

    private $_redis;

    public function __construct()
    {
        parent::__construct();
        $this->_redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
    }

    public function indexAction()
    {
        $this->_smarty->display('admin/index.tpl');
    }

    public function loginAction()
    {
        $username = $this->getParam('username');
        $password = $this->getParam('password');

        $userConfig = ConfigLoader::getConfig('USER');
        if ($username == $userConfig['username'] && $password == $userConfig['password']) {
            //the cookie only have one day to live
            $cookie = md5($username . $userConfig['cookie'] . date('Y-m-d'));
            setcookie('verify_key', $cookie, time() + 86400, '/');
            setcookie('username', $username, time() + 86400, '/');
            header("Location: /response/detail");
        } else {
            header("Location: /response/index");
        }
    }

    public function detailAction()
    {
        $type = $this->getParam("type");
        if ($type == 'client') {
            $clientResponse = $this->_redis->get(self::CLIENT_RESPONSE);
            $this->_smarty->assign('client_response', $clientResponse);
            $this->_smarty->assign('tpl', 'admin/auto-reply-client.tpl');
        } else {
            $magazineResponse = $this->_redis->get(self::MAGAZINE_RESPONSE);
            $this->_smarty->assign('magazine_response', $magazineResponse);
            $this->_smarty->assign('tpl', 'admin/auto-reply-magazine.tpl');
        }

        $this->_smarty->display('admin/b-index.tpl');
    }

    public function editAction()
    {
        $type = trim($this->getParam('type'));
        $response = $this->getParam('response');

        $responseArr = json_decode($response, true);
        if (!empty($responseArr)) {
            if ($type == 'client') {
                $this->_redis->set(self::CLIENT_RESPONSE, $response);
            } elseif ($type == 'magazine') {
                $this->_redis->set(self::MAGAZINE_RESPONSE, $response);
            }
        }
        header("Location: /response/detail");
    }
}