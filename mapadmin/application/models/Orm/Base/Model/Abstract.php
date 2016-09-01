<?php

namespace Orm\Base\Model;

/**
 * ORM Model 的基类
 *
 * @author ghost
 */
abstract class AbstractModel
{

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * 传入的关联数组, 将用于填充对象的属性
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        if ($options) {
            $this->mSet($options);
        }
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (!method_exists($this, $method)) {
            throw new \Exception('Invalid model property');
        }

        $this->$method($value);
    }

    /**
     *
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (!method_exists($this, $method)) {
            throw new \Exception('Invalid model property');
        }

        return $this->$method();
    }

    /**
     * 供 isset() 调用
     *
     * <b>注意:</b><br />
     * 实际上是通过去掉下划线, 再判断是否存在 getXxx() 的方法.<br />
     * 那么, username, userName, _user_name 的效果都是一致的.<br />
     * 在某些此场景下, 这逻辑可能会是一个坑, 调用时需要注意啦!
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        /* 去掉下划线 */
        $method = 'get' . str_replace('_', '', $name);
        return method_exists($this, $method);
    }

    /**
     * 通用设置方法
     *
     * @param array $options    参数. 如果是类, 必需实现了toArray(), 或者实现Traversabl接口的类.
     * @return \Orm\Base\Model\AbstractModel
     */
    public function mSet($options)
    {
        if (is_object($options)) {
            if (method_exists($options, 'toArray')) {
                $options = $options->toArray();
            }
            else if (!($options instanceof \Traversable)) {
                return $this;
            }
        }
        else if (!is_array($options)) {
            return $this;
        }

        foreach ($options as $key => $value) {
            /* 去掉下划线 */
            $method = 'set' . str_replace('_', '', $key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * 对数据进行JSON序列化
     *
     * @return string|null
     */
    public function toJson()
    {
        return \json_encode($this->toArray());
    }

    /**
     *  支持序列化成JSON的接口
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

}
