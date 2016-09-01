<?php

namespace Orm\Map;

use Orm\Base\Model\AbstractModel;

/**
 * 数据模型
 * 
 * Table: map_group
 */
class GroupModel extends AbstractModel
{

    /**
     * 
     *
     * @var Int
     */
    protected $id = null;

    /**
     * 
     *
     * @var String
     */
    protected $name = null;

    /**
     * 
     *
     * @var String
     */
    protected $sort = null;

    /**
     * 
     *
     * @var String
     */
    protected $isDelete = '0';

    /**
     * 
     *
     * @var Int
     */
    protected $creattime = null;

    /**
     * 获取 
     *
     * @return Int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 设置 
     *
     * database: int(11)
     * @param Int $id 
     * @return \Orm\Map\GroupModel
     */
    public function setId($id)
    {
        $this->id = intval($id);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 设置 
     *
     * database: varchar(45)
     * @param String $name 
     * @return \Orm\Map\GroupModel
     */
    public function setName($name)
    {
        $this->name = trim($name);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * 设置 
     *
     * database: varchar(45)
     * @param String $sort 
     * @return \Orm\Map\GroupModel
     */
    public function setSort($sort)
    {
        $this->sort = trim($sort);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * 设置 
     *
     * database: varchar(45)
     * @param String $isDelete 
     * @return \Orm\Map\GroupModel
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = trim($isDelete);
        return $this;
    }

    /**
     * 获取 
     *
     * @return Int
     */
    public function getCreattime()
    {
        return $this->creattime;
    }

    /**
     * 设置 
     *
     * database: int(11)
     * @param Int $creattime 
     * @return \Orm\Map\GroupModel
     */
    public function setCreattime($creattime)
    {
        $this->creattime = intval($creattime);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'        => $this->id,
            'name'      => $this->name,
            'sort'      => $this->sort,
            'is_delete' => $this->isDelete,
            'creattime' => $this->creattime,
        );
    }

}
