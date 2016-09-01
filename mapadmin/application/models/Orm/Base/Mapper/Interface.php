<?php

namespace Orm\Base\Mapper;

/**
 * Mapper 的接口
 */
interface InterfaceModel
{

    /**
     * 返回 单例
     */
    public static function getInstance();

    /**
     * 返回 对应的数据模型类名
     *
     * @return string
     */
    public function getModelClassName();

    /**
     * 返回 Zend 的适配器
     *
     * @staticvar Object $dbAdapter
     * @return \Zend\Db\Adapter\Adapter
     * @throws \Exception
     */
    public function getDbAdapter();

    /**
     * 返回 数据库表名
     *
     * 注意, 如果涉及到分表, 需要返回分表后的名称
     *
     * @return string
     */
    public function getTableName();

    /**
     * 对于有分表的Mapper, 需要自己实现此方法
     *
     * @return string 需要分表的基表表名
     */
    public function getTableNameBase();

    /**
     * 返回 Zend 的 TableGateway
     *
     * @staticvar array $tableGateway
     * @return \Zend\Db\TableGateway\TableGateway
     */
    public function getTableGateway();

    /**
     * 返回 数据库表的主键
     *
     * @return string
     */
    public function getPrimaryKey();

    /**
     * 通过主键获取数据模型
     *
     * @param string|int|float $val 主健的值
     *
     * @return mixed|null 对应的数据模型
     */
    public function find($val);

    /**
     * 返回 Zend 的 Sql
     *
     * @staticvar array $sql
     * @return \Zend\Db\Sql\Sql
     */
    public function getSql();
}
