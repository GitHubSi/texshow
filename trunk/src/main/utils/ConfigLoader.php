<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/1
 * Time: 16:54
 */
class ConfigLoader
{
    private $configFiles = array();
    private $configs = array();

    private function __construct()
    {
        $root = dirname(dirname(dirname(dirname(__FILE__))));
        $defaultConfigFile = $root . "/config/server_conf.php";
        $defaultConfigFile = realpath($defaultConfigFile);
        if (!file_exists($defaultConfigFile)) {
            throw new RuntimeException("config file does not exist, file={$defaultConfigFile}");
        }
        $this->configFiles[$defaultConfigFile] = 1;
        $this->_reload();
    }

    // element name be array's key, convert to variable
    private function _reload()
    {
        // reload all the config files and refresh the config vars
        $configFiles = array_keys($this->configFiles);
        foreach ($configFiles as $configFile) {
            include $configFile;
        }
        $this->configs = get_defined_vars();
    }

    private function _loadConfigFile($configFile)
    {
        if (!file_exists($configFile)) {
            throw new RuntimeException("Config file does not exist, configFile={$configFile}");
        }
        $this->configFiles[$configFile] = 1;
        $this->_reload();
    }

    private function _getConfig($key)
    {
        if (array_key_exists($key, $this->configs)) {
            return $this->configs[$key];
        } else {
            $this->_reload();
            if (array_key_exists($key, $this->configs)) {
                return $this->configs[$key];
            } else {
                return "";
            }
        }
    }

    private static function getInstance()
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new ConfigLoader();
        }
        return $instance;
    }

    /**
     * Get the config for a given key
     * @param $key
     * @return string
     */
    public static function getConfig($key)
    {
        $instance = self::getInstance();
        return $instance->_getConfig($key);
    }

    /**
     * Load the given config file. Configs in this config file would be merged with the previous ones.
     * @param $configFile
     */
    public static function loadConfigFile($configFile)
    {
        $instance = self::getInstance();
        return $instance->_loadConfigFile($configFile);
    }
}