<?php
/**
 * 数据库连接操作类
 * Created by PhpStorm.
 * User: abu
 * Date: 15/4/13
 * Time: 下午4:43
 */

namespace Db;

use \Pdo;

class Db extends Pdo
{
    /**
     * 所属db模块
     */
    protected $dbmodule = '';

    /**
     * 表名
     */
    protected $tablename = '';

    /**
     * 连接mysql
     * @var \mysqli|string
     */
    protected $pdo = '';

    public function __construct($dbconf)
    {
        $db = $dbconf[$this->dbmodule];
        $this->pdo = new Pdo('mysql:host=' . $db['ip']. ';dbname=' . $db['dbname'], $db['username'], $db['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }

    /**
     * 获取要操作的table
     * @param $sql
     */
    public function getTableName()
    {
        return $this->tablename;
    }

    /**
     *
     */
    public function insert($data)
    {

    }

}