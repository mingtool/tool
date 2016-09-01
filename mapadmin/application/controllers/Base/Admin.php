<?php

namespace Base;


/**
 * Cli控制器的基类.
 */
class AdminController extends ApplicationController
{

    /**
     * 布局模板的名称
     * @var string
     */
    protected $layout = 'admin';

    /**
     * Layout 的目录是否跟随 Module 到对应的目录.
     * @var boolean
     */
    protected $layoutFollowModule = true;

    public function init()
    {
        parent::init();

//        if (!$this->checkIp()) {
//            throw new \Exception('非法访问:' . Tool::getClientIp(false), 403);
//        }

//        if (!\Bootstrap::isAdmin()) {
//            throw new \Exception('非法访问:' . \Bootstrap::getShopId(), 403);
//        }
//
//        $shopNick = $this->getRequest()->get('shop_nick', null);
//
//        if (!empty($shopNick)) {
//            $this->assign('query_shop_nick', $shopNick);
//        }
    }

    /**
     * 只有符合规则的IP才允许访问后台
     */
    protected function checkIp()
    {
        /**
         * 能访问监控页面的IP是很固定的,
         * 没必要写配置文件或者数据库
         */
        $ipArr = array(
            /* 本机 */
            '127.0.0.1',
            /* 公司内网 */
            '172.16.2.0/24',
            /* 公司固定IP */
            '120.36.131.66/32',
            '120.36.131.62/32',
            /* 监控宝IP */
            '60.195.252.106/32',
        );

        return true;

//        if (!Tool::isInNetwork(Tool::getClientIp(false), $ipArr)) {
//            return false;
//        }
//        else {
//            return true;
//        }
    }

}
