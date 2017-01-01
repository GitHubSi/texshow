<?php
/*vote two*/
class BallotMapper
{
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = DB::getInstance(ConfigLoader::getConfig('MYSQL'));
    }

    public function addBallotItem($nickName, $msg, $type)
    {
        return $this->_db->execute(
            "INSERT INTO t_ballot (`name`, `msg`, `type`, `create_time`, `update_time`) VALUES (?, ?, ?, NOW(), NOW())",
            array($nickName, $msg, $type)
        );
    }

    public function deleteBallotItem($id)
    {
        return $this->_db->execute(
            "DELETE FROM t_ballot  WHERE id = ?", $id
        );
    }

    public function updateLiked($id, $liked)
    {
        return $this->_db->execute(
            "UPDATE t_ballot SET liked = liked + ? WHERE id = ?",
            array($liked, $id)
        );
    }

    public function updateBallotItem($id, $nickName, $msg, $type)
    {
        return $this->_db->execute(
            "UPDATE t_ballot SET `name` = ?, `msg` = ?, `type` = ? WHERE id = ?",
            array($nickName, $msg, $type, $id)
        );
    }

    public function getBallotItemById($id)
    {
        return $this->_db->getRow(
            "SELECT `id`, `name`, `msg`, `liked`, `type`, `create_time`, `update_time` FROM t_ballot WHERE id = ?", $id
        );
    }

    public function getBallotByType($lastId, $lastLiked, $type, $pageSize)
    {
        return $this->_db->getAll(
            "SELECT `id`, `name`, `msg`, `liked`, `type`, `create_time`, `update_time` FROM t_ballot 
            WHERE (`liked` < ?  OR ( `liked` = ? AND `id` < ?)) AND  `type` = ? ORDER BY `liked` DESC, `id` DESC LIMIT {$pageSize}",
            array($lastLiked, $lastLiked, $lastId, $type)
        );
    }

    public function getAllBallot()
    {
        return $this->_db->getAll(
            "SELECT `id`, `name`, `msg`, `liked`, `type`, `create_time`, `update_time` FROM t_ballot"
        );
    }
}