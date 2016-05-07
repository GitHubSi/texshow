<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/2
 * Time: 9:13
 */
class AbstractWeChatAction extends Action
{
    protected $_token;
    private $_dom;
    protected $_openId;

    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        if (!isset($this->_token)) {
            throw new RuntimeException("No token was found when handling requests from WeChat.");
        }

        $tokenValid = self::isIncomeTokenValid(
            $this->getParam("signature"),
            $this->getParam("timestamp"),
            $this->getParam("nonce"),
            $this->_token
        );

        if (!$tokenValid) {
            echo "WeChat auth error";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $entityBody = file_get_contents('php://input');
            $dom = new DOMDocument();
            $dom->loadXML($entityBody);
            $this->_dom = $dom;
            $this->_weChatDispatch();
        } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
            echo $_GET["echostr"];
        }
    }

    protected function _weChatDispatch()
    {
        $MsgType = $this->getValue("MsgType");
        $this->_openId = $this->getValue("FromUserName");

        if ($MsgType == "text") {
            $response = $this->textHandler();
        } else if ($MsgType == "event") {
            $Event = $this->getValue("Event");
            if ($Event == "subscribe") {
                // subscribe event
                $response = $this->subscribeHandler();
            } else if ($Event == "unsubscribe") {
                // cancel subscribe event
                $response = $this->unSubscribeHandler();
            } else if ($Event == "CLICK") {
                // click menu for pulling message
                $response = $this->clickHandler();
            } else if ($Event == "VIEW") {
                // click menu for redirect to url
                $response = $this->viewHandler();
            }
        } else if ($MsgType == "image") {
            $response = $this->imageHandler();
        }

        if (empty($response)) {
            echo "";
            return;
        }

        $response["ToUserName"] = $this->_openId;
        $response["FromUserName"] = $this->getValue("ToUserName");
        $response["CreateTime"] = time();
        $xml = SLXml::arr2XMLStr($response);
        echo $xml;
    }

    protected function textHandler()
    {
        return NULL;
    }

    protected function imageHandler()
    {
        return NULL;
    }

    protected function clickHandler()
    {
        return NULL;
    }

    protected function subscribeHandler()
    {
        return NULL;
    }

    protected function unSubscribeHandler()
    {
        return NULL;
    }

    protected function viewHandler()
    {
        return NULL;
    }

    protected function getValue($tagName)
    {
        if (!$this->_dom instanceof DOMNode) {
            throw new BadFunctionCallException("No DOM was found when trying to find the value.");
        }

        $targetTags = $this->_dom->getElementsByTagName($tagName);
        if (empty($targetTags)) {
            return NULL;
        }

        return $targetTags->item(0)->textContent;
    }

    static function isIncomeTokenValid($signature, $timestamp, $nonce, $token)
    {
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr != $signature) {
            return FALSE;
        }
        return TRUE;
    }
}