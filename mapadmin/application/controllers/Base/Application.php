<?php

namespace Base;

use Sess\LoginModel;
use Yaf\Controller_Abstract;
use Cp\Counter\Mca;

/**
 * 控制器的基类.
 */
class ApplicationController extends Controller_Abstract
{

    /**
     * 布局模板的名称
     * @var string
     */
    protected $layout = 'main';

    /**
     * Layout 的目录是否跟随 Module 到对应的目录.
     * @var boolean
     */
    protected $layoutFollowModule = false;

    /**
     * @return void
     */
    public function init()
    {
        $this->initView();
    }


    /**
     * 视图路径配置
     *
     * @param array $options
     * @return \St\Layout
     */
    public function initView(array $options = NULL)
    {
        parent::initView($options);
        $view = $this->getView();

        if (!empty($this->layout)) {
            $config  = \Bootstrap::getConfig();
            $prePath = $config->get('application.directory');

            if ($this->layoutFollowModule and 'Index' !== $this->getModuleName()) {
                $prePath .= 'modules' . DS . $this->getModuleName() . DS;
            }

            $view->setLayoutPath($prePath . 'views' . DS . 'layouts');
            $view->setLayout($this->layout);
        }

        return $view;
    }



    /**
     * 向视图注册属性(变量)
     *
     * @param string|array $name 属性名
     * @param mixed $value 属性值
     * @return boolean
     */
    public function assign($name, $value)
    {
        return $this->getView()->assign($name, $value);
    }

    /**
     * 通用JSONP输出方法.
     *
     * <b>注意:</b>
     *
     * 1) 调用此方法, 会自动关闭 Layout 和 View.
     *
     * 2) 为了安全 callback 只允许 字母+数字+_ 的组合(第一个不能是数字).
     *    如果为空, 或者格式不合法, 将会只输出JSON数据.
     *
     * @param mixed $result 需要输出的数据
     * @param boolean $status 状态.
     * @param int $errno 错误码, 默认为0
     * @param string $errmsg 错误内容, 默认为空.
     * @param string $callback 回调用函数.
     * @return boolean
     */
    public function jsonp($result = array(), $status = true, $errno = 0, $errmsg = '', $callback = '')
    {
        /* 关闭视图 */
        $this->disableView();

        // JSON结构初始化
        $jsonArr = array();

        $jsonArr['status']    = $status ? true : false;
        $jsonArr['errno']     = intval($errno);
        $jsonArr['errmsg']    = $errmsg;
        $jsonArr['result']    = $result;
        $jsonArr['timestamp'] = time();

        $callback = trim($callback);

        // 为了安全 callback 只允许 字母+数字+_ 的组合
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $callback)) {
            $callback = null;
        }

        // 是否有 callback, 如果没有, 那么直接输出JSON
        if ($callback) {
            // 输出文件头信息
            header('Content-type: application/javascript; charset=utf-8');
            // 输出JSONP
            $this->getResponse()->setBody($callback, '(', \json_encode($jsonArr), ');');
        } else {
            // 输出文件头信息
            header('Content-type: application/json; charset=utf-8');
            // 输出JSON
            $this->getResponse()->setBody(\json_encode($jsonArr));
        }

        return true;
    }

    /**
     * 取得JSONP的回调函数名.
     * @return string
     */
    public function getCallbackName()
    {
        return $this->getRequest()->get('callback');
    }

    /**
     * 重定向到一个新的URL
     *
     * <b>注意:</b>
     * 1) 会自动关闭视图
     * 2) 在调用此方法前, 不要输出内容
     *
     * @param string $url
     * @return boolean
     */
    public function redirect($url)
    {
        $this->disableView();

        return parent::redirect($url);
    }

    /**
     * 关闭模板的默认渲染设置
     *
     * @return boolean
     */
    public function disableView()
    {
        return \Yaf\Dispatcher::getInstance()->disableView();
    }

    /**
     * 输出 Ajax, 并且自动关闭视图
     *
     * @param \St\Response\Ajax $ajax
     * @return boolean
     */
    protected function sendAjax(\St\Response\Ajax $ajax)
    {
        $this->disableView();
        $ajax->sendContentType('json');
        $this->getResponse()->setBody($ajax->toJson());
        return true;
    }



    /**
     * 获取链接
     * @param $path
     * @return string
     */
    protected function getUrl($path)
    {
        return \Bootstrap::getUrl($path);
    }
}
