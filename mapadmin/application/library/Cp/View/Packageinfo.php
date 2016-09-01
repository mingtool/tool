<?php

namespace Cp\View;

/**
 * 供视图层调用, 用于获取前端的包关系和版本等信息
 */
class Packageinfo
{

    /**
     * 单例, 所有的子类都必需定义此属性
     *
     * @var Packageinfo
     */
    protected static $instance = null;

    /**
     * 包的配置文件路径
     *
     * @var string
     */
    protected $path = '';

    /**
     * 通过 package.json 解码出来
     *
     * @var array
     */
    protected $cache = array();

    public function __construct()
    {
        $this->path = \APPLICATION_PATH . '/public/static/package.json';
        $this->init();
    }

    public function init()
    {
        if (!is_readable($this->path)) {
            throw new \Exception('package.json 文件不可读', 1011);
        }
        $this->cache = json_decode(file_get_contents($this->path), true);

        if ((empty($this->cache)) and ( \JSON_ERROR_NONE !== json_last_error())) {
            throw new \Exception('package.json 格式不正确(' . json_last_error_msg() . ')', 1012);
        }
    }

    /**
     * 单例接口
     *
     * @return Packageinfo
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * 返回所有配置信息
     *
     * @return array|null
     */
    public function get()
    {
        return $this->cache;
    }

}
