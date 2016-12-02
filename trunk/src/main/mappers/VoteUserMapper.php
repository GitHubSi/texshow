<?php

class VoteUserMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addUser($nickName, $msg)
    {
        return $this->_db->execute(
            "INSERT INTO t_vote_user (`nick_name`, `msg`, `create_time`, `update_time`) VALUES (?, ?, NOW(), NOW())",
            array($nickName, $msg)
        );
    }

    public function deleteUser($id)
    {
        return $this->_db->execute(
            "DELETE FROM t_vote_user  WHERE id = ?",
            $id
        );
    }

    public function updateLiked($id, $liked)
    {
        return $this->_db->execute(
            "UPDATE t_vote_user SET liked = liked + ? WHERE id = ?",
            array($liked, $id)
        );
    }

    public function updateUser($id, $nickName, $msg)
    {
        return $this->_db->execute(
            "UPDATE t_vote_user SET nick_name = ?, msg = ? WHERE id = ?",
            array($nickName, $msg, $id)
        );
    }

    public function getUserById($id)
    {
        return $this->_db->getRow(
            "SELECT `id`, `nick_name`, `msg`, `liked`, `create_time`, `update_time` FROM t_vote_user WHERE id = ?",
            $id
        );
    }

    public function getAllUser($lastId, $pageSize)
    {
        return $this->_db->getAll(
            "SELECT `id`, `nick_name`, `msg`, `create_time`, `update_time` FROM t_vote_user 
            WHERE id < ? ORDER BY liked DESC LIMIT {$pageSize}",
            $lastId
        );
    }
}