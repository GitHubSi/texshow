<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/17
 * Time: 22:46
 */
class ResponseController extends Action
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

    public function preDispatch()
    {
        $password = $this->_redis->get(self::MAGAZINE_RESPONSE);
    }

    public function indexAction()
    {
        $clientResponse = $this->_redis->get(self::CLIENT_RESPONSE);
        $magazineResponse = $this->_redis->get(self::MAGAZINE_RESPONSE);
        if ($clientResponse) {
            $this->_smarty->assign('client_response', $clientResponse);
            $this->_smarty->assign('magazine_response', $magazineResponse);
        }
        $this->_smarty->display('admin/response.tpl');
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

        header("Location: /response/index");
    }
}