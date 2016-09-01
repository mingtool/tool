<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时被调用
 * @link http://www.php.net/manual/zh/yaf-dispatcher.catchexception.php
 * @author ghost
 */
class ErrorController extends Base\ApplicationController
{

    /**
     * 布局模板的名称
     * @var string
     */
    protected $layout = 'outheader';

    /**
     * 从2.1开始, errorAction支持直接通过参数获取异常
     *
     * @param \Yaf\Exception $exception   Yaf定义的异常类
     * @return boolean
     */
    public function errorAction($exception)
    {
        $this->disableView();


        $server = $this->getRequest()->getServer();
        \Bootstrap::exportLog($exception,$server);

        /**
         * 是否Cli环境执行的?
         */
        if ($this->getRequest()->isCli()) {
            var_dump($exception);
            return true;
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->xml($exception);
        }
        else {
            $this->html($exception);
        }
    }

    /**
     * 提示页
     */
    public function noticeAction()
    {
        $request = $this->getRequest();
        $msg = $request->get('msg');

        $this->assign('msg',$msg);
    }

    /**
     * AJAX 方式的
     *
     * @param \Yaf\Exception|\Exception $exception
     */
    protected function xml($exception)
    {
        $callbackName = $this->getCallbackName();
        if (\Bootstrap::isDevelop()) {
            $this->jsonp($exception->__toString(), false, $exception->getCode(), $exception->getMessage(), $callbackName);
        }
        else {
            $this->jsonp(array(), false, $exception->getCode(), '服务异常, 请联系客户人员', $callbackName);
        }
    }

    /**
     * HTML 方式的
     *
     * @param \Yaf\Exception|\Exception $exception
     */
    protected function html($exception)
    {
        $view = $this->initView();

        $view->setLayout(null);
        $view->assign('exc', $exception);

        $config  = \Bootstrap::getConfig();
        $prePath = $config->get('application.directory');

        if (\Bootstrap::isDevelop()) {
            $view->display($prePath . 'views/error/develop.phtml');
        }
        else {
            $view->display($prePath . 'views/error/error.phtml');
        }
    }
}
