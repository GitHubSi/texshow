<?php
/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/4/30
 * Time: 23:15
 */

$ROOT_DIR = dirname(dirname(dirname(__FILE__)));
ini_set("include_path", ini_get('include_path') . ":/{$ROOT_DIR}/lib/");
spl_autoload_register(array("WebAutoLoader", "autoload"));

class WebAutoLoader
{
    private static $classes = array(
        //web
        'AbstractWeChatAction' => '/src/applications/web/controllers/AbstractWeChatAction.php',
        'RedPacketController' => '/src/applications/web/controllers/RedPacketController.php',
        'WeChatClientController' => '/src/applications/web/controllers/WeChatClientController.php',
        'WeChatMagazineController' => '/src/applications/web/controllers/WeChatMagazineController.php',

        //api

        //mappers
        'WeChatClientUserMapper' => '/src/main/mappers/WeChatClientUserMapper.php',
        'WeChatMagazineUserMapper' => '/src/main/mappers/WeChatMagazineUserMapper.php',

        //services
        'RedPacketService' => '/src/main/services/RedPacketService.php',
        'WeChatClientService' => '/src/main/services/WeChatClientService.php',
        'WeChatMagazineService' => '/src/main/services/WeChatMagazineService.php',
        'WeChatService' => '/src/main/services/WeChatService.php',

        //utils
        'ConfigLoader' => '/src/main/utils/ConfigLoader.php',
        'RedisClient' => '/src/main/utils/RedisClient.php',
        'SLXml' => '/src/main/utils/SLXml.php',
    );

    /**
     * Loads a class.
     * @param string $className The name of the class to load.
     */
    public static function autoload($className)
    {
        if (isset(self::$classes[$className])) {
            global $ROOT_DIR;
            include $ROOT_DIR . self::$classes[$className];
        }
    }
}
