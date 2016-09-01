<?php

namespace Cp;

/**
 * 对 phpRedis 的简单扩展
 *
 * @author ghost
 */
class Redis extends \Redis
{

    /**
     * 批量设置Hash中的成员.
     *
     * 如果 $val 是一个对象, 优先调用 toArray() 方法,
     * 如果没有 toArray() 方法, 那么对象必需是继承 Traversable 接口.
     *
     * <b>注意</b>: 不管 $val 是数组还是对象, 最终数据只支持一维的 Key->Value.
     *
     * @param string $key Redis键名
     * @param array|object|Traversable $val 带索引的二维数组
     * @link https://github.com/nicolasff/phpredis#hmset
     *
     * @return boolean
     */
    public function hMset($key, $val = array())
    {
        $data = array();

        if (is_array($val)) {
            return count($val) ? parent::hMset($key, $val) : false;
        }

        if (is_object($val)) {
            if (method_exists($val, 'toArray')) {
                $data = $val->toArray();
            }
            elseif ($val instanceof \Traversable) {
                foreach ($val as $k => $v) {
                    $data[$k] = $v;
                }
            }
        }

        return count($data) ? parent::hMset($key, $data) : false;
    }

}
