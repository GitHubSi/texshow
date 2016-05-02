<?php

/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/5/2
 * Time: 12:24
 * simple xml parse
 */
class SLXml
{
    public static function arr2XMLStr($arr)
    {
        if (!is_array($arr)) {
            return null;
        }
        $document = new DomDocument('1.0', 'utf-8');
        $xml = $document->createElement("xml");
        self::appendXMLNode($arr, $xml, $document);
        return $document->saveXML($xml);
    }

    public static function appendXMLNode(&$arr, &$node, &$document)
    {
        foreach ($arr as $key => $val) {
            if (preg_match("/^\d+$/", $key)) {
                // a hack for xml-like array display
                $newNode = $document->createElement("item");
            } else {
                $newNode = $document->createElement($key);
            }
            $node->appendChild($newNode);

            if (is_numeric($val)) {
                $newChildNode = $document->createTextNode($val);
            } else if (is_string($val)) {
                $newChildNode = $document->createCDATASection($val);
            } else if (is_array($val)) {
                $newChildNode = self::appendXMLNode($val, $newNode, $document);
            }

            if (isset($newChildNode)) {
                $newNode->appendChild($newChildNode);
            }
        }

        return $newNode;
    }

    public static function getValueFromXml($dom, $tagName)
    {
        if (!$dom instanceof DOMNode) {
            throw new BadFunctionCallException("No DOM was found when trying to find the value.");
        }
        $targetTags = $dom->getElementsByTagName($tagName);
        if (empty($targetTags)) {
            return NULL;
        }
        return $targetTags->item(0)->textContent;
    }

    /**
     * @param $xml the response from red pack interface
     * @return  $result Array | null
     **/
    public static function xmlToArray($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xml = $dom->getElementsByTagName('xml')->item(0);
        foreach ($xml->childNodes as $node) {
            $result[$node->nodeName] = $node->nodeValue;
        }
        return $result;
    }

}