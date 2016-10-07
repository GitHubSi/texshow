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

        $userConfig = ConfigLoader::getConfig('USER');
        $username = $this->getParam('username');
        $verifyKey = $this->getParam('verify_key');

        if (empty($username) || empty($verifyKey)) {
            header("Location: /response/index");
            exit;
        }

        $calculateValue = md5($username . $userConfig['cookie'] . date('Y-m-d'));
        if ($verifyKey != $calculateValue) {
            header("Location: /response/index");
            exit;
        }
    }

}