<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/7/3
 * Time: 15:41
 */
class HomeController extends Action
{
    const BASE_URL = "http://act.wetolink.com/home/index";
    const PAGE_SIZE = 20;
    private $_salt;
    private $_prizeMapper;

    public function __construct()
    {
        parent::__construct();

        $this->_salt = ConfigLoader::getConfig("SALT");
        $this->_prizeMapper = new PrizeMapper();
    }

    public function preDispatch()
    {
        $openId = $this->_simpleCheckCookieValid();
        if (!$openId) {
            $code = $this->getParam("code");
            try {
                $userInfo = WeChatClientService::getInstance()->getOAuthAccessToken($code);
                $openId = $userInfo['openid'];
            } catch (Exception $e) {
                $redirectUrl = WeChatClientService::getInstance()->getUserOpenidUrl("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                //$redirectUrl = WeChatClientService::getInstance()->getUserOpenidUrl(self::BASE_URL);
                header('Location: ' . $redirectUrl);
                exit;
            }
        }

        $this->_setCookie($openId);
        Request::getInstance()->setParam("openid", $openId);
    }

    public function indexAction()
    {
        $openId = $this->getParam("openid");
        if (empty($openId)) {
            return;
        }

        $userInfo = WeChatClientService::getInstance()->getUserInfoByOpenID($openId);
        $this->_smarty->assign("userInfo", $userInfo);

        $dbUserInfo = WeChatClientService::getInstance()->getUserInfo($openId);
        $this->_smarty->assign("score", $dbUserInfo['score']);

        $salveList = UserRelationService::getInstance()->listUserScore($dbUserInfo["unionid"], PHP_INT_MAX, UserRelationMapper::NO_VALID, 10);
        $this->_smarty->assign("salveList", $salveList);

        $this->_smarty->display('activity/home.tpl');
    }

    public function listUserAction()
    {
        $openId = $this->getParam("openid");
        if (empty($openId)) {
            return;
        }

        $dbUserInfo = WeChatClientService::getInstance()->getUserInfo($openId);
        if (empty($dbUserInfo)) {
            return;
        }

        $lastId = $this->getParam("last_id");
        if (empty($lastId)) {
            $lastId = PHP_INT_MAX;
        }

        $salveList = UserRelationService::getInstance()->listUserScore($dbUserInfo["unionid"], $lastId);
        header('Content-Type:application/json');
        echo json_encode($salveList);
    }

    //because this function to small to don't need to create a new controller
    public function prizeAction()
    {
        $openId = $this->getParam("openid");
        if (empty($openId)) {
            return;
        }

        $dbUserInfo = WeChatClientService::getInstance()->getUserInfo($openId);
        $this->_smarty->assign("score", $dbUserInfo['score']);

        $prizeList = $this->_prizeMapper->getPrizeList();
        $this->_smarty->assign("prizeList", $prizeList);

        $this->_smarty->display('activity/prize.tpl');
    }

    public function exchangeAction()
    {
        $prizeId = $this->getParam('prize_id');
        if (!ctype_digit($prizeId)) {
            $prizeId = 0;
        }
        $this->_smarty->assign("prizeId", $prizeId);
        $this->_smarty->display('activity/exchange.tpl');
    }

    //订阅号个人中心
    public function magazineAction()
    {
        $magazineInfo = WeChatOpenService::getInstance()->getMagazineByClient($this->getParam("openid"));
        $userInfo = WeChatMagazineService::getInstance()->getUserInfoByOpenID($magazineInfo["openid"]);
        $userInfo = array_merge($magazineInfo, $userInfo);

        //获取购买记录
        $msgList = OneShareService::getInstance()->getCurrentBuyHistory($this->getParam("openid"));
        $this->_smarty->assign("msgList", $msgList);
        $this->_smarty->assign("inviteCode", $magazineInfo["id"] + OneShareService::EXTRA_ADD_NUM);
        $this->_smarty->assign("userInfo", $userInfo);
        $this->_smarty->display('activity/magazine-home.tpl');
    }

    //兑换奖品
    public function exchangeSubmitAction()
    {
        header('Content-Type:application/json');
        $result = array(
            "req" => 0
        );

        try {
            $openId = $this->getParam("openid");
            if (empty($openId)) {
                throw new Exception("openid is empty", 0);
            }

            $prizeId = $this->getParam('prize_id');
            if (!ctype_digit($prizeId)) {
                throw new Exception('prize got error', 1);
            }

            $name = $this->getParam('name');
            if (empty($name)) {
                throw new Exception("name is empty", 2);
            }

            $phone = $this->getParam('phone');
            if (empty($phone)) {
                throw new Exception("phone is empty", 3);
            }

            $province = $this->getParam('province');
            $city = $this->getParam('city');
            $region = $this->getParam('region');
            $detail = $this->getParam('detail');
            if (empty($province) || empty($city) || empty($region) || empty($detail)) {
                throw new Exception("address must be complete", 4);
            }

            $addressInfo = array(
                'name' => $name,
                'phone' => $phone,
                'province' => $province,
                'city' => $city,
                'region' => $region,
                'detail' => $detail
            );

            $exchangeResult = PrizeService::getInstance()->exchange($openId, $prizeId, $addressInfo);
            if (!$exchangeResult) {
                throw new Exception("exchange error", 5);
            }

        } catch (Exception $e) {
            $result['req'] = $e->getCode();
        }

        echo json_encode($result);
    }

    private function _simpleCheckCookieValid()
    {
        $encodeUserId = $this->getParam("wx");
        $openId = $this->getParam("openid");

        if (empty($encodeUserId) || empty($openId)) {
            return false;
        }

        $weChatSalt = ConfigLoader::getConfig("SALT");
        if (md5($openId . $weChatSalt) == $encodeUserId) {
            return $openId;
        }
        return false;
    }

    private function _setCookie($openId)
    {
        setcookie('wx', md5($openId . $this->_salt));
        setcookie("openid", $openId);
    }
}