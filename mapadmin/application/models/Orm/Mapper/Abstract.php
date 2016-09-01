<?php

namespace Orm\Mapper;

use Orm\Base\Mapper\AbstractModel as OrmAbstract;
use Zend\Db\TableGateway\TableGateway;

/**
 * 数据模型
 *
 */
abstract class AbstractModel extends OrmAbstract
{

    /**
     * Zend 的适配器
     *
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $dbAdapter = null;

    /**
     * 返回 Zend 的适配器
     *
     * @staticvar Object $dbAdapter
     * @return \Zend\Db\Adapter\Adapter
     * @throws \Exception
     */
    public function getDbAdapter()
    {
        return \Bootstrap::getDbAdapter('mapadmin');
    }

    /**
     * 返回 Zend 的 TableGateway
     *
     * @staticvar array $tableGateway
     * @return \Zend\Db\TableGateway\TableGateway
     */
    public function getTableGateway()
    {
        static $tableGateway = array();

        $tabName = $this->getTableName();

        if (!isset($tableGateway[$tabName])) {
            $tableGateway[$tabName] = new TableGateway($this->getTableName(), $this->getDbAdapter());
        }

        return $tableGateway[$tabName];
    }

}
