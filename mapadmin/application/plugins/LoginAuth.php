<?php

use Yaf\Plugin_Abstract;
use Yaf\Response\Cli;
use Sess\LoginModel;

/**
 * 登录验证
 *
 * 实际上, Index/Index 相当于在管理授权的功能
 */
class LoginAuthPlugin extends Plugin_Abstract
{

    /**
     * 不需要登录验证的模块
     * 首字母大写
     *
     * @var array
     */
    protected $moduleArr = array('Callback' => true,) ;


    /**
     * 不需要登录验证的模块+控制器
     * 首字母大写
     *
     * @var array
     */
    protected $modelContArr = array('Index/Index' => true,
                                    'Index/Proxy' => true,);

    /**
     * 路由解析结束, 判断当前是否有已登录的卖家
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return boolean
     */
    public function routerShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
        /**
         * Cli 模式下, 不限制
         */
        if ($response instanceof Cli) {
            return true;
        }

        return true;


        $moduleName = $request->getModuleName();
        $conName    = $request->getControllerName();

        if (isset($this->moduleArr[$moduleName])) {
            return true;
        }

        if (isset($this->modelContArr[$moduleName . '/' . $conName])) {
            return true;
        }

        if ('Index/Index' != $moduleName . '/' . $conName) {
            /* 没有 shopId 的session, 被认为是未登录状态 */
            if (!LoginModel::getInstance()->isLogined()) {

            }
        }
    }



}
