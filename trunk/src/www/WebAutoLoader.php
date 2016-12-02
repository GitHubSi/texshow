<?php
/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/4/30
 * Time: 23:15
 */

$ROOT_DIR = dirname(dirname(dirname(__FILE__)));
require $ROOT_DIR . '/bin/log/Logger.php';
Logger::configure($ROOT_DIR . '/config/log_conf.xml');

require $ROOT_DIR . '/lib/WxPay/WxPayAutoLoader.php';

spl_autoload_register(array("WebAutoLoader", "autoload"));

class WebAutoLoader
{
    private static $classes = array(
        //web
        'AbstractWeChatAction' => '/src/applications/web/controllers/AbstractWeChatAction.php',
        'AbstractActivityAction' => '/src/applications/web/controllers/AbstractActivityAction.php',
        'RedPacketController' => '/src/applications/web/controllers/RedPacketController.php',
        'WeChatClientController' => '/src/applications/web/controllers/WeChatClientController.php',
        'WeChatMagazineController' => '/src/applications/web/controllers/WeChatMagazineController.php',
        'HomeController' => '/src/applications/web/controllers/HomeController.php',
        'ShareItemController' => '/src/applications/web/controllers/ShareItemController.php',
        'ShareInviteCodeController' => '/src/applications/web/controllers/ShareInviteCodeController.php',
        'MallController' => '/src/applications/web/controllers/MallController.php',
        'LatestPublicController' => '/src/applications/web/controllers/LatestPublicController.php',

        'VoteController' => '/src/applications/web/controllers/VoteController.php',

        //manage
        'AbstractSecurityAction' => '/src/applications/manage/controllers/AbstractSecurityAction.php',
        'ResponseController' => '/src/applications/manage/controllers/ResponseController.php',
        'RedPacketSettingController' => '/src/applications/manage/controllers/RedPacketSettingController.php',
        'HeadImgController' => '/src/applications/manage/controllers/HeadImgController.php',
        'GoodsManageController' => '/src/applications/manage/controllers/GoodsManageController.php',
        'VoteManageController' => '/src/applications/manage/controllers/VoteManageController.php',
        //api

        //mappers
        'WeChatClientUserMapper' => '/src/main/mappers/WeChatClientUserMapper.php',
        'WeChatMagazineUserMapper' => '/src/main/mappers/WeChatMagazineUserMapper.php',
        'UserRelationMapper' => '/src/main/mappers/UserRelationMapper.php',
        'PrizeMapper' => '/src/main/mappers/PrizeMapper.php',
        'PrizeRecordMapper' => '/src/main/mappers/PrizeRecordMapper.php',
        'WeChatNotifyMapper' => '/src/main/mappers/WeChatNotifyMapper.php',
        'OneShareMapper' => '/src/main/mappers/OneShareMapper.php',
        'ShareItemMapper' => '/src/main/mappers/ShareItemMapper.php',
        'HeadImgMapper' => '/src/main/mappers/HeadImgMapper.php',

        'VoteLogMapper' => '/src/main/mappers/VoteLogMapper.php',
        'VoteUserMapper' => '/src/main/mappers/VoteUserMapper.php',

        //services
        'RedPacketService' => '/src/main/services/RedPacketService.php',
        'WeChatClientService' => '/src/main/services/WeChatClientService.php',
        'WeChatMagazineService' => '/src/main/services/WeChatMagazineService.php',
        'WeChatService' => '/src/main/services/WeChatService.php',
        'PosterService' => '/src/main/services/PosterService.php',
        'UserRelationService' => '/src/main/services/UserRelationService.php',
        'PrizeService' => '/src/main/services/PrizeService.php',
        'WeChatPayService' => '/src/main/services/WeChatPayService.php',
        'WeChatNotifyService' => '/src/main/services/WeChatNotifyService.php',

        'WeChatOpenService' => '/src/main/services/WeChatOpenService.php',
        'OneShareService' => '/src/main/services/OneShareService.php',

        //utils
        'ConfigLoader' => '/src/main/utils/ConfigLoader.php',
        'RedisClient' => '/src/main/utils/RedisClient.php',
        'SLXml' => '/src/main/utils/SLXml.php',
        'SystemUtil' => '/src/main/utils/SystemUtil.php',

        //system
        'Action' => '/lib/Frame/Action.php',
        'Http' => '/lib/Frame/Http.php',
        'Request' => '/lib/Frame/Request.php',
        'Router' => '/lib/Frame/Router.php',

        'DB'=> '/lib/FrameDB/DB.php',
        'DBPDO'=> '/lib/FrameDB/DBPDO.php',
        'DBStatement'=> '/lib/FrameDB/DBStatement.php',
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
