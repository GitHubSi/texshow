<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/29
 * Time: 11:30
 */
class RedPacketSettingController extends AbstractSecurityAction
{
    const RED_PACKET_SWITCH = 'redis_red_packet';
    const RED_PACKET_START = 'start';
    const RED_PACKET_STOP = 'stop';
    const RED_PACKET_PERCENTAGE = 'redis_red_percentage';

    public function indexAction()
    {
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $redState = $redis->get(self::RED_PACKET_SWITCH);

        //red activity is opening or closing
        if ($redState == self::RED_PACKET_START) {
            $this->_smarty->assign('switch', 1);
        } else if ($redState == self::RED_PACKET_STOP) {
            $this->_smarty->assign('switch', 0);
        }

        //the percentage of getting red packet
        $percentage = $redis->get(self::RED_PACKET_PERCENTAGE);
        $percentage = empty($percentage) ? 5 : $percentage;
        $this->_smarty->assign('percentage', $percentage);

        $this->_smarty->assign('tpl', 'admin/red_setting.tpl');
        $this->_smarty->display('admin/frame.tpl');
    }

    //start or close activity
    public function switchAction()
    {
        $switch = $this->getParam('red_switch');
        if (!in_array($switch, array(self::RED_PACKET_START, self::RED_PACKET_STOP))) {
            header("Location: /RedPacketSetting/index");
            return;
        }

        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $redState = $redis->get(self::RED_PACKET_SWITCH);
        if ($redState != $switch) {
            $redis->set(self::RED_PACKET_SWITCH, $switch);
        }
        header("Location: /RedPacketSetting/index");
    }

    //set red packet win percent
    public function percentAction()
    {
        $percent = $this->getParam('percent');
        if (!ctype_digit($percent)) {
            $this->_locationIndex();
            return;
        }
        $redis = RedisClient::getInstance(ConfigLoader::getConfig("REDIS"));
        $originPercent = $redis->get(self::RED_PACKET_PERCENTAGE);
        if ($originPercent != $percent) {
            $percent = intval($percent);
            $redis->set(self::RED_PACKET_PERCENTAGE, $percent);
        }
        $this->_locationIndex();
    }

    //maybe
    private function _locationIndex()
    {
        header("Location: /RedPacketSetting/index");
        return;
    }
}