<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 10:34
 * Create a rule for specified request router to different controller or action
 */
class Router
{
    const URI_DELIMITER = '/';

    private function __construct()
    {
    }

    public static function getInstance()
    {
        static $router = null;
        if (is_null($router)) {
            $router = new Router();
        }
        return $router;
    }

    //the request format like http://host:port/controller/action?query_string
    public function route($pathInfo)
    {
        $pathInfo = trim($pathInfo, self::URI_DELIMITER);
        if ($pathInfo != '') {
            $path = explode(self::URI_DELIMITER, $pathInfo);
            if (count($path) && !empty($path[0])) {
                Http::getInstance()->setControllerName($path[0]);
            }
            if (count($path) && !empty($path[1])) {
                Http::getInstance()->setActionName($path[1]);
            }
        }
    }
}