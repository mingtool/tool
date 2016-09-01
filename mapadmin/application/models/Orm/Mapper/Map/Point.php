<?php

namespace Orm\Mapper\Map;

use Orm\Mapper\AbstractModel as mapAbstract;
use Zend\Db\Sql\Expression;

/**
 * 数学模型
 * Class PointModel
 * @package Orm\Mapper
 */
class PointModel extends mapAbstract
{

    /**
     * 缓存名称
     */
    protected $cacheKey = '';

    /**
     * 缓存有效时间
     *   6个小时
     */
    protected $expire = 21600;


    /**
     * 单例, 所有的子类都必需定义此属性
     *
     * @var PointModel
     */
    protected static $instance = null;

    /**
     * 单例接口
     *
     * @return PointModel
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * 映射器对应的数据模型类名
     *
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Orm\\Map\\PointModel';
    }

    /**
     * 返回 数据库表的主键
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * 映射器对应的数据库表名
     *
     * 注意, 如果涉及到分表, 需要返回分表后的名称
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->getTableNameBase();
    }

    /**
     * 对于有分表的Mapper, 需要自己覆盖此方法
     *
     * @return string 需要分表的基表表名
     */
    public function getTableNameBase()
    {
        return 'map_point';
    }

    /**
     * 通过主键获取数据模型
     *
     * @param int $val tid
     *
     * @return \Orm\Map\PointModel|null
     */
    public function find($val)
    {
        return $this->fetchOne(array('id' => intval($val)));
    }


    /**
     * 插入数据
     *
     * @param   \Orm\Map\PointModel $model
     * @return int
     */
    public function insert(\Orm\Map\PointModel $model)
    {
        $model->setCreatetime(time());
        return parent::tgInsert($model->toArray());
    }


    /**
     * 更新数据
     *
     * @param   \Orm\Map\PointModel $model
     * @return int
     */
    public function update(\Orm\Map\PointModel $model)
    {
        $where = array($this->getPrimaryKey() => $model->getId());
        $data  = $model->toArray();

        unset($data[$this->getPrimaryKey()]);

        return parent::tgUpdate($data, $where);
    }
    /**
     * 删除数据
     */
    public function delete(\Orm\Map\PointModel $model)
    {
        $where = array('id' => $model->getId());

        return $this->remove($where);
    }


}
