<?php

namespace Orm\Base\Mapper;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\SqlInterface;

/**
 * Mapper 的基类
 *
 * <b>注意:</b>
 * 所有子类, 都必需定义 $instance 属性, 以保存单例的实例
 *
 * @package Orm
 */
abstract class AbstractModel implements InterfaceModel
{

    /**
     * 默认缓存时间
     * @var int
     */
    protected $ormCacheTTL = 86400;



    public function __construct()
    {
        $this->dbAdapter = $this->getDbAdapter();

        if (!$this->dbAdapter instanceof \Zend\Db\Adapter\Adapter) {
            throw new \Exception('Not DbAdapter.', 500);
        }

    }

    /**
     * 单例模式
     *
     * @return mixed
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * 数据写入 $this->modelClass, ORM 处理
     *
     * @param array $data
     * @return $this->modelClass
     */
    public function map($data)
    {
        $modelClass = $this->getModelClassName();

        return new $modelClass($data);
    }

    /**
     * 返回 Zend 的 Sql
     *
     * @staticvar array $sql
     * @return \Zend\Db\Sql\Sql
     */
    public function getSql()
    {
        return $this->getTableGateway()->getSql();
    }

    /**
     * 返回 Zend 的 Select
     *
     * @staticvar array $select
     * @return \Zend\Db\Sql\Select
     */
    public function getSelect()
    {
        return $this->getSql()->select();
    }

    /**
     * 执行 Select
     *
     * 简单的, 跟Mapper有强关联的Select.
     * 如果是复杂的, 建议直接用 Adapter 去执行, 并自己处理异常
     *
     * @param \Zend\Db\Sql\Select $select
     * @return ResultSet
     */
    public function querySelect(Select $select)
    {
        try {
            $resultSet = $this->getTableGateway()->selectWith($select);
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $exc) {
            /*
             * 处理 分表时, 子表不存在的问题
             */
            $preExc = $exc->getPrevious();

            if (($preExc instanceof \PDOException)) {
                /* Zend Db 新版本 */
                if (isset($preExc->errorInfo[1]) and 1146 == $preExc->errorInfo[1]) {
                    if ($this->tableDoesNotExist($this->getTableName())) {
                        return new ResultSet();
                    }
                }

                /* Zend Db 旧版本 */
                if ('42S02' === $preExc->getCode() and ($this->tableDoesNotExist($this->getTableName()))) {
                    return new ResultSet();
                }
            }

            /*
             * tableDoesNotExist() 没有处理, 或者处理失败时
             * 异常接着向上级抛
             */
            throw $exc;
        }

        return $resultSet;
    }

    /**
     * 获取 符合条件的一行
     *
     * 注意:
     * 需要确保$where最多只能命中一行数, 因为此方法仅返回单个model
     *
     * @param  \Closure|string|array|\Zend\Db\Sql\Predicate\PredicateInterface $where
     * @param string|array $order 排序规则
     * @param int $offset 偏移量
     *
     * @return mixed|null
     */
    public function fetchOne($where = null, $order = null, $offset = null)
    {
        $sql    = $this->getSql();
        $select = $sql->select();
        $select->where($where);
        $select->limit(1);

        if (null !== $offset) {
            $select->offset(intval($offset));
        }

        if ($order) {
            $select->order($order);
        }

        $resultSet = $this->querySelect($select);

        if (!$resultSet->count()) {
            return null;
        }

        return $this->map($resultSet->current());
    }

    /**
     * 获取 符合条件的所有行
     *
     * @param  \Closure|string|array|\Zend\Db\Sql\Predicate\PredicateInterface $where
     * @param string|array $order 排序规则
     * @param int $limit 结果集大小
     * @param int $offset 偏移量
     * @return array
     */
    public function fetchAll($where = null, $order = null, $limit = null, $offset = null)
    {
        $sql    = $this->getSql();
        $select = $sql->select();

        if (null !== $where) {
            $select->where($where);
        }

        if ($limit) {
            $select->limit($limit);
        }

        if (null !== $offset) {
            $select->offset(intval($offset));
        }

        if ($order) {
            $select->order($order);
        }
        $resultSet = $this->querySelect($select);


        if (!$resultSet->count()) {
            return array();
        }

        $modelArr = array();

        foreach ($resultSet as $item) {
            $modelArr[] = $this->map($item);
        }

        return $modelArr;
    }

    /**
     * 插入数据
     *
     * Table gateway insert
     *
     * @param array $data
     * @return int 最后的自增ID
     */
    protected function tgInsert($data)
    {
        try {
            $this->getTableGateway()->insert($data);
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $exc) {
            /* 处理 分表时, 子表不存在的问题 */
            $preExc = $exc->getPrevious();

            if (($preExc instanceof \PDOException) and '42S02' === $preExc->getCode()) {
                if ($this->tableDoesNotExist($this->getTableName())) {
                    $this->getTableGateway()->insert($data);
                }
            } else {
                /* tableDoesNotExist() 没有处理, 或者处理失败时, 异常接着向上级抛 */
                throw $preExc;
            }
        }

        return (int)$this->getLastInsertId();
    }

    /**
     * 更新数据
     *
     * Table gateway Update
     *
     * @param array $data
     * @return int 影响的行数
     */
    protected function tgUpdate($data, $where)
    {
        return $this->getTableGateway()->update($data, $where);
    }

    /**
     * remove existing model.
     *
     * @param array|string $where
     * @return int The number of rows deleted.
     */
    protected function remove($where)
    {
        return $this->getTableGateway()->delete($where);
    }

    /**
     * 取得最后写入的自增ID
     *
     * @return int (last id)
     */
    public function getLastInsertId()
    {
        return $this->getTableGateway()->getLastInsertValue();
    }

    /**
     * 统计 一些基础的数值
     *
     * @param array|Where $where 过滤条件
     * @return int
     */
    public function count($where = array())
    {
        $tableGateway = $this->getTableGateway();
        $select       = $tableGateway->getSql()->select();

        if ($where) {
            $select->where($where);
        }

        $select->columns(array('count' => new Expression('COUNT(*)')));

        try {
            $row = $tableGateway->selectWith($select)->current();
        } catch (\Exception $exc) {
            return 0;
        }

        return isset($row['count']) ? intval($row['count']) : 0;
    }

    /**
     * 计算 一些基础的数值
     *
     * @param array|Where $where 过滤条件
     * @param string $column 需要计算的字段
     * @return int
     */
    public function sum($where, $column)
    {
        $tableGateway = $this->getTableGateway();
        $select       = $tableGateway->getSql()->select();

        if ($where) {
            $select->where($where);
        }

        $select->columns(array('sum' => new Expression('SUM(`' . trim($column) . '`)')));
        try {
            $row = $tableGateway->selectWith($select)->current();
        } catch (\Exception $exc) {
            return 0;
        }

        return isset($row['sum']) ? intval($row['sum']) : 0;
    }

    /**
     * 统计 $select 的结果集条数
     *
     * 这只是一个语法糖
     *
     * 主要为了应对 select 是多联表的情况.<br />
     * 实际上, 相当于执行: SELECT COUNT(*) AS `count` FROM ($select) AS `temp`
     *
     * 注意:
     * 1. 如果 $select 有重复的字段, 会出错, 需要调用者自己重置字段.
     * 2. 如果发生异常, 返回 0
     *
     * @param Select $select
     * @return int
     */
    public function countForSelect(Select $select)
    {
        $adapter = $this->getDbAdapter();
        $select->reset(Select::LIMIT)
            ->reset(Select::OFFSET)
            ->reset(Select::ORDER);

        $selectCount = new Select(array('temp' => $select));
        $selectCount->columns(array('count' => new Expression('count(*)')));

        $sqlStr = $selectCount->getSqlString($adapter->getPlatform());

        try {
            $Result = $adapter->query($sqlStr, Adapter::QUERY_MODE_EXECUTE);
            $current = $Result->current();
            $count  = $Result ? intval($current['count']) : 0;
        } catch (\Exception $exc) {
            $count = 0;
        }

        return $count;
    }

    /**
     * 处理 分表时, 子表不存在的问题
     *
     * 如果对应的Mapper没有分表, 直接 return false
     *
     * @param string $tableName 子表表名
     * @return boolean 处理成功/失败
     */
    public function tableDoesNotExist($tableName)
    {
        if ($tableName == $this->getTableNameBase()) {
            return false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` LIKE `{$this->getTableNameBase()}`;";

        return $this->getDbAdapter()->query($sql)->execute() ? true : false;
    }

    /**
     * 对于有分表的Mapper, 需要自己覆盖此方法
     *
     * @return string 需要分表的基表表名
     */
    public function getTableNameBase()
    {
        return $this->getTableName();
    }

    /**
     * 转成SQL语句
     *
     * @param \Zend\Db\Sql\SqlInterface $sql
     * @return string
     */
    public function toSqlString(SqlInterface $sql)
    {
        return $sql->getSqlString($this->getDbAdapter()->getPlatform());
    }

}
