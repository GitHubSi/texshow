<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/12/15
 * Time: 22:49
 */
class HtmlUtil
{
    const PREG_RETURN = '/(\n|\r\n|\r)/';
    const PREG_LINK = '/\b(?:https?:\/\/)(?:[^\s()<>]+|\((?:[^\s()<>]+|\([^\s()<>]+\))*\))+(?:\((?:[^\s()<>]+|\([^\s()<>]+\))*\)|[^\s`!()\[\]{};:\'".,<>?？«»“”‘’）】。，])/i';

    public static function toHtml($text)
    {
        $html = "";
        if (empty($text)) {
            return $html;
        }

        $paragraphs = preg_grep(self::PREG_RETURN, $text);
        foreach ($paragraphs as $paragraph) {
            if (preg_match(self::PREG_LINK)) {
                $html += "<img src='{$paragraph}'>";
            } else {
                $html += "<p>{$paragraph}</p>";
            }
        }

        return $paragraphs;
    }
}