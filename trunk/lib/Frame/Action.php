<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 13:25
 * All controller class extends this class
 */
class Action
{
    public $useSmarty = true;
    public $_smarty = null;

    public function __construct()
    {
        if ($this->useSmarty) {
            $this->initSmarty();
        }
        $this->init();
    }

    public function initSmarty()
    {
        include_once '/Smarty.class.php';
        $this->_smarty = new Smarty();
        $this->_smarty->compile_dir = "/templates_c/";
        $this->_smarty->config_dir = "/configs/";
        $this->_smarty->cache_dir = "/cache/";
        $this->_smarty->template_dir = '/tpls/';
        $this->_smarty->left_delimiter = "{%";
        $this->_smarty->right_delimiter = "%}";
        $this->_smarty->force_compile = false;
    }

    public function dispatch($action)
    {
        $this->preDispatch();
        $this->$action();
        $this->postDispatch();
    }

    public function init()
    {
    }

    public function preDispatch()
    {
    }

    public function postDispatch()
    {
    }

    public function assign($spec, $value = null)
    {

    }

    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {

    }

    public function getControllerName()
    {
        return Http::$curController;
    }

    public function getActionName()
    {
        return Http::$curAction;
    }

    public function getParam($key, $default = null)
    {
        $value = Request::getInstance()->get($key);
        return (null == $value && null !== $default) ? $default : $value;
    }
}