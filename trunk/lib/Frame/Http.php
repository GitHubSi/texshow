<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 11:07
 */
class Http
{
    protected $_defaultController = "index";
    protected $_defaultAction = "index";
    public static $curController = '';
    public static $curAction = '';
    protected $_controllerPath = NULL;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        static $webApp = null;
        if (is_null($webApp)) {
            $webApp = new Http();
        }
        return $webApp;
    }

    public function run()
    {
        try {
            $this->processRequest();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function setControllerName($name)
    {
        self::$curController = $name;
        return $this;
    }

    public function setActionName($name)
    {
        self::$curAction = $name;
        return $this;
    }

    protected function processRequest()
    {
        $pathInfo = Request::getInstance()->getPathInfo();
        $this->runController($pathInfo);
    }

    protected function runController($pathInfo)
    {
        Router::getInstance()->route($pathInfo);
        if ('' === self::$curController) self::$curController = $this->_defaultController;
        if ('' === self::$curAction) self::$curAction = $this->_defaultAction;

        $this->dispatch();
    }

    public function dispatch()
    {
        $className = $this->createControllerClassName(self::$curController);
        //todo :need to judge the controller file whether existed. now,user try-catch to catch this type exception
        try {
            $controller = new $className;
        } catch (Exception $e) {
            throw new Exception('controller file is not existed');
        }
        $action = $this->createActionName(self::$curAction);
        if (!method_exists($controller, $action)) {
            throw new Exception('action is not existed in this controller');
        }
        $controller->dispatch($action);
    }

    private function createControllerClassName($controlName)
    {
        return ucfirst($controlName) . "Controller";
    }

    private function createActionName($actionName)
    {
        return strtolower($actionName) . "Action";
    }

    //todo: this function need to fix in future
    public function getControllerPath()
    {
        if (NULL != $this->_controllerPath) {
            return $this->_controllerPath;
        } else {
            $controllerPath = realpath('../');
        }
        return;
    }

    public function setControllerPath($path)
    {
        $this->_controllerPath = $path;
    }

}