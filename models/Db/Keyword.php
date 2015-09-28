<?php


namespace Db;

use \Db\Db as DbModel;

class Keyword extends DbModel
{
    /**
     * 所属db模块
     * @var string
     */
    protected $dbmodule = 'sms';

    /**
     * 表名
     * @var string
     */
    protected $tablename = 'keyword';


    /**
     * 获取当前所在时间的分表
     */
    public function getTableName()
    {
        return $this->tablename;
    }

    /**
     * @param $word
     * @return int
     */
    public function insert($word)
    {
        $sql = "insert into " . $this->getTableName() . " set ";
        $sql .= "word='" . $word . "',created=" . time();

        $this->pdo->query($sql);

        $res = $this->pdo->lastInsertId();

        return $res;
    }

}