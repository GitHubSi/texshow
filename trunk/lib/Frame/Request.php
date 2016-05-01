<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 10:36
 * To deal with http/https request, get http header or http body data
 */
class Request
{
    protected $_params = array();
    protected $_requestUri = NULL;
    protected $_pathInfo = NULL;

    private function __construct()
    {
        $this->setRequestUri();
    }

    public static function getInstance()
    {
        static $request = null;
        if (is_null($request)) {
            $request = new Request();
        }
        return $request;
    }

    public function get($key)
    {
        switch (true) {
            case isset($this->_params[$key]):
                return $this->_params[$key];
            case isset($_GET[$key]):
                return $_GET[$key];
            case isset($_POST[$key]):
                return $_POST[$key];
            case isset($_COOKIE[$key]):
                return $_COOKIE[$key];
            case isset($_SERVER[$key]):
                return $_SERVER[$key];
            case isset($_ENV[$key]):
                return $_ENV[$key];
            default:
                return null;
        }
    }

    public function setRequestUri($requestUri = null)
    {
        $this->_requestUri = $requestUri;
    }

    public function getRequestUri()
    {
        if ($this->_requestUri === null) {
            if (isset($_SERVER['HTTP_X_REWRITE_URL']) && !empty($_SERVER['HTTP_X_REWRITE_URL'])) {
                $this->_requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
            } elseif (isset($_SERVER['REQUEST_URI'])) {
                $this->_requestUri = $_SERVER['REQUEST_URI'];
            } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
                $this->_requestUri = $_SERVER['ORIG_PATH_INFO'];
                if (!empty($_SERVER['QUERY_STRING']))
                    $this->_requestUri .= '?' . $_SERVER['QUERY_STRING'];
            } else {
                return '';
            }
        }
        return $this->_requestUri;
    }

    public function setParam($key, $value)
    {
        $this->_params[$key] = $value;
    }

    public function setParams($params)
    {
        if (!empty($params)) {
            foreach ($params as $key => $name) {
                $this->_params[$key] = $name;
            }
        }
    }

    //get path info to build controller and action
    public function getPathInfo()
    {
        if ($this->_pathInfo === null) {
            $pathInfo = $this->getRequestUri();
            if (($pos = strpos($pathInfo, '?')) !== false) {
                $pathInfo = substr($pathInfo, 0, $pos);
            }
            $pathInfo = $this->decodePathInfo($pathInfo);
            $this->_pathInfo = trim($pathInfo, '/');
        }
        return $this->_pathInfo;
    }

    protected function decodePathInfo($pathInfo)
    {
        return urldecode($pathInfo);
    }
}