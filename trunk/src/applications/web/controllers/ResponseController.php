<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/17
 * Time: 22:46
 */
class ResponseController extends Action
{
    const AUTO_RESPONSE = 'auto_response';
    private $_redis;

    public function __construct()
    {
        parent::__construct();
        $this->_redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
    }

    public function preDispatch()
    {
        $password = $this->_redis->get(self::AUTO_RESPONSE);
    }

    public function indexAction()
    {
        $currentResponse = $this->_redis->get(self::AUTO_RESPONSE);
        if ($currentResponse) {
            $this->_smarty->assign('cur_response', $currentResponse);
        }
        $this->_smarty->display('admin/response.tpl');
    }

    public function editAction()
    {
        $response = $this->getParam('auto_response');
        $responseArr = json_decode($response, true);
        if (!empty($responseArr)) {
            $this->_redis->set(self::AUTO_RESPONSE, $response);
        }
        header("Location: /response/index");
    }
}