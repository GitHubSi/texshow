<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/10/24
 * Time: 17:56
 */
class HeadImgMapper
{
    const IS_DELETE = 1;
    const NO_DELETE = 0;

    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addHeadImg($name, $imgUrl, $redirectUrl)
    {
        return $this->_db->execute(
            "INSERT INTO t_head_img(`name`, img_url, redirect_url, state, create_time) VALUES (?, ?, ?, ?, now())",
            array($name, $imgUrl, $redirectUrl, self::NO_DELETE)
        );
    }

    public function updateState($id, $state)
    {
        return $this->_db->execute(
            "UPDATE t_head_img SET state = ? WHERE id = ? ",
            array($state, $id)
        );
    }

    public function deleteHeadImg($id)
    {
        return $this->_db->execute(
            "DELETE FROM t_head_img WHERE id = ? ", $id
        );
    }

    public function getImgByState($state)
    {
        return $this->_db->getAll(
            "SELECT `id`, `name`, `img_url`, `redirect_url`, `state`, `create_time`, `update_time` FROM t_head_img WHERE `state` = ? ",
            $state
        );
    }
}