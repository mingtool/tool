<?php

namespace Cp;

use Yaf\Application;
use Yaf\View\Simple;

/**
 * 继承于 \Yaf\View\Simple 的一个简单视图类
 *
 * @author ghost
 */
class View extends Simple
{

    /**
     * 模板中的变量
     *
     * @var array
     */
    protected $_tpl_vars = array();

    /**
     * @var string
     */
    protected $layoutPath;

    /**
     * 用 htmlspecialchars 转码
     *
     * @param string $str   需要转码的字符串
     * @return string
     */
    public function _($str = '')
    {
        return htmlspecialchars($str, \ENT_QUOTES | \ENT_COMPAT | \ENT_HTML401);
    }

    public function get($name = null, $default = null)
    {
        return $this->__isset($name) ? parent::get($name) : $default;
    }

    /**
     * 渲染视图
     *
     * @param string $tpl       模板的路经
     * @param array $tpl_vars   模板变量
     * @return string
     */
    public function render($tpl, $tpl_vars = NULL)
    {
        if ($tpl_vars) {
            $this->_tpl_vars = array_merge($this->_tpl_vars, $tpl_vars);
        }

        return parent::render($tpl, $tpl_vars);
    }

    /**
     * 取得当前项目的运行环境
     * 由PHP的 yaf.environ 决定
     *
     * @return string
     */
    public function environ()
    {
        return Application::app()->environ();
    }

    /**
     * 读取配置文件中 view. 前缀的配置
     *
     * @param string $key 键名
     *
     * @return \Yaf\Config\Ini|null|string
     */
    public function getConfig($key = null)
    {
        $config = Application::app()->getConfig()->get('view');

        if (!$key) {
            return $config;
        }
        else {
            return $config ? $config->get((string) $key) : null;
        }
    }

    /**
     * 读取Layout目录
     * @return string
     */
    public function getLayoutPath()
    {
        return $this->layoutPath;
    }

    /**
     * 设置Layout目录
     *
     * @param string $path
     * @return \St\View
     */
    public function setLayoutPath($path)
    {
        $this->layoutPath = $path;
        return $this;
    }

    /**
     * 渲染其它的模板, 用于实现模板的嵌套功能.
     *
     * @param string $tpl      The template to process.
     * @param array  $tpl_vars Additional variables to be assigned to template.
     *
     * @return string The view or include template output.
     */
    public function renderTpl($tpl, $tpl_vars = array())
    {
        /*
         * 嵌套的模板, 为了防止里层的变量穿透到外层, 所以才用了 clone.
         * 这与Layout的嵌套是不一样的.
         */
        $view = clone $this;
        return $view->render($this->getScriptPath() . DIRECTORY_SEPARATOR . $tpl, $tpl_vars);
    }

    /**
     * 渲染其它的Layout模板, 用于实现Layout模板的嵌套功能.
     *
     * @param string $tpl      The template to process.
     * @param array  $tpl_vars Additional variables to be assigned to template.
     *
     * @return string The view or include template output.
     */
    public function renderLayoutTpl($tpl, $tpl_vars = array())
    {
        return $this->render($this->getLayoutPath() . DIRECTORY_SEPARATOR . $tpl, $tpl_vars);
    }

    /**
     * @return \Yaf\Request\Http
     */
    public function getRequest()
    {
        return \Yaf\Dispatcher::getInstance()->getRequest();
    }

    /**
     * 生成URI
     *
     * @param array     $opt    URI参数
     * @param boolean   $isGet  默认false, 键值对(key/value)的方式. 为true时, 以 GET方式.
     * @param boolean   $allGet 默认false, 为true时, 全部参数以GET方式.
     * @return string
     */
    public function uri($opt = array(), $isGet = false, $allGet = false)
    {
        $request = $this->getRequest();

        $baseUri    = $request->getBaseUri();
        $module     = strtolower($request->getModuleName());
        $controller = strtolower($request->getControllerName());
        $action     = strtolower($request->getActionName());

        if (isset($opt['module'])) {
            $module = $opt['module'];
            unset($opt['module']);
        }

        if (isset($opt['controller'])) {
            $controller = $opt['controller'];
            unset($opt['controller']);
        }

        if (isset($opt['action'])) {
            $action = $opt['action'];
            unset($opt['action']);
        }

        $uriPre = "{$baseUri}/{$module}/{$controller}/{$action}/";

        if ($isGet) {
            if ($allGet) {
                return $opt ? ($uriPre . '?' . http_build_query($opt)) : $uriPre;
            }
            else {
                $yafParams = $request->getParams();
                $uriOpt    = '';
                foreach ($opt as $key => $value) {
                    $key   = trim($key);
                    $value = trim($value);
                    if ($key and $value and isset($yafParams[$key])) {
                        $uriOpt .= urlencode($key) . '/' . urlencode($value) . '/';
                        unset($opt[$key]);
                    }
                }

                return $uriPre . $uriOpt . ($opt ? ( '?' . http_build_query($opt)) : '');
            }
        }
        else {
            $uriOpt = '';
            foreach ($opt as $key => $value) {
                $key   = trim($key);
                $value = trim($value);

                if ($key and $value) {
                    $uriOpt .= urlencode($key) . '/' . urlencode($value) . '/';
                }
            }

            return $uriPre . $uriOpt;
        }
    }

}
