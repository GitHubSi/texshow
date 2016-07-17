<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/12
 * Time: 23:28
 */
class PrizeService
{
    private $_weChatClientUserMapper;
    private $_prizeMapper;
    private $_prizeRecordMapper;

    private function __construct()
    {
        $this->_weChatClientUserMapper = new WeChatClientUserMapper();
        $this->_prizeMapper = new PrizeMapper();
        $this->_prizeRecordMapper = new PrizeRecordMapper();
    }

    public static function getInstance()
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new PrizeService();
        }
        return $instance;
    }

    public function exchange($openId, $prizeId, $addressInfo)
    {
        $userInfo = WeChatClientService::getInstance()->getUserInfo($openId);
        if (empty($userInfo)) {
            return false;
        }

        //prize num had not leaved
        $prize = $this->_prizeMapper->getPrizeById($prizeId);
        if (empty($prize) || $prize['num'] == 0) {
            return false;
        }

        if ($userInfo['score'] < $prize['num']) {
            return false;
        }

        $db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
        $db->startTrans();
        try {
            $this->_prizeMapper->updatePrizeNum($prizeId);
            $this->_weChatClientUserMapper->updateScoreByOpenId($openId, -$prize['score']);
            $this->_prizeRecordMapper->addRecord($prizeId, $openId, $addressInfo['name'], $addressInfo['phone'], $addressInfo['city'],
                $addressInfo['province'], $addressInfo['region'], $addressInfo['detail']);
            $db->commit();
            return true;
        } catch (Exception $e) {
            Logger::getRootLogger()->info($e->getMessage());
            $db->rollback();
        }

        return false;
    }
}