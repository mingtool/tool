<?php

namespace Orm\Map;

use Orm\Base\Model\AbstractModel;

/**
 * 数据模型
 * 
 * Table: map_point
 */
class PointModel extends AbstractModel
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
    protected $point = null;

    /**
     * 
     *
     * @var String
     */
    protected $groupId = null;

    /**
     * 
     *
     * @var String
     */
    protected $remark = null;

    /**
     * 
     *
     * @var String
     */
    protected $pics = null;

    /**
     * 
     *
     * @var String
     */
    protected $phone = null;

    /**
     * 
     *
     * @var String
     */
    protected $address = null;

    /**
     * 
     *
     * @var String
     */
    protected $urlAddress = null;

    /**
     * 
     *
     * @var Int
     */
    protected $createtime = null;

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
     * @return \Orm\Map\PointModel
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
     * @return \Orm\Map\PointModel
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
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * 设置 
     *
     * database: varchar(30)
     * @param String $point 
     * @return \Orm\Map\PointModel
     */
    public function setPoint($point)
    {
        $this->point = trim($point);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * 设置 
     *
     * database: varchar(45)
     * @param String $groupId 
     * @return \Orm\Map\PointModel
     */
    public function setGroupId($groupId)
    {
        $this->groupId = trim($groupId);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * 设置 
     *
     * database: varchar(500)
     * @param String $remark 
     * @return \Orm\Map\PointModel
     */
    public function setRemark($remark)
    {
        $this->remark = trim($remark);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getPics()
    {
        return $this->pics;
    }

    /**
     * 设置 
     *
     * database: varchar(150)
     * @param String $pics 
     * @return \Orm\Map\PointModel
     */
    public function setPics($pics)
    {
        $this->pics = trim($pics);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * 设置 
     *
     * database: varchar(20)
     * @param String $phone 
     * @return \Orm\Map\PointModel
     */
    public function setPhone($phone)
    {
        $this->phone = trim($phone);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * 设置 
     *
     * database: varchar(100)
     * @param String $address 
     * @return \Orm\Map\PointModel
     */
    public function setAddress($address)
    {
        $this->address = trim($address);
        return $this;
    }

    /**
     * 获取 
     *
     * @return String
     */
    public function getUrlAddress()
    {
        return $this->urlAddress;
    }

    /**
     * 设置 
     *
     * database: varchar(150)
     * @param String $urlAddress 
     * @return \Orm\Map\PointModel
     */
    public function setUrlAddress($urlAddress)
    {
        $this->urlAddress = trim($urlAddress);
        return $this;
    }

    /**
     * 获取 
     *
     * @return Int
     */
    public function getCreatetime()
    {
        return $this->createtime;
    }

    /**
     * 设置 
     *
     * database: int(11)
     * @param Int $createtime 
     * @return \Orm\Map\PointModel
     */
    public function setCreatetime($createtime)
    {
        $this->createtime = intval($createtime);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'          => $this->id,
            'name'        => $this->name,
            'point'       => $this->point,
            'group_id'    => $this->groupId,
            'remark'      => $this->remark,
            'pics'        => $this->pics,
            'phone'       => $this->phone,
            'address'     => $this->address,
            'url_address' => $this->urlAddress,
            'createtime'  => $this->createtime,
        );
    }

}
