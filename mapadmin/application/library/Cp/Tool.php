<?php

namespace Cp;

/**
 * 一些可能用到的小函数
 * 尽量用 "静态方法" 实现, 这样调用时就比较方便.
 *
 * @author Ghost
 */
class Tool
{

    /**
     * 判断两个IP是否同个子网的.
     *
     * 注意: 如果 $ip = 0.0.0.0, 将来被认为是无效IP, 返回 false
     *
     * @author Ghost
     * @param string $ip 需要判断的IP
     * @param string $localIp 我的子网IP
     * @param string $netmask 我的子网掩码
     *
     * @return boolean
     */
    static public function checkNetAddress($ip, $localIp = '127.0.0.1', $netmask = '255.255.255.0')
    {
        $ipL = ip2long($ip);

        if (!$ipL) {
            return false;
        }

        $localIpL = ip2long($localIp);
        $netmaskL = ip2long($netmask);

        // 本地网络地址
        $localNetAddr = ($localIpL & $netmaskL);
        // 需要计算的网络地址
        $netAddr      = ($ipL & $netmaskL);

        return ($localNetAddr == $netAddr) ? true : false;
    }

    /**
     * 检测IP地址是否在配置规则内
     *
     * 注意: 如果规则为空时, 返回 true
     *
     * @param string $ip ipv4的格式
     * @param array $rules 规则, 例如: 192.168.1.1/24, 127.0.0.1
     * @return boolean
     */
    public static function isInNetwork($ip, array $rules)
    {
        if (!isset($rules[0])) {
            return true;
        }

        $pass = false;

        foreach ($rules as $rule) {
            $set     = explode('/', $rule);
            $netway  = (int) ((isset($set[1])) ? max(1, min($set[1], 32)) : 32);
            $ip_rule = $set[0];

            if ($netway === 32 && strcmp($ip_rule, $ip) === 0) {
                $pass = true;
                break;
            }

            $bits = 32 - $netway;

            $netmask = (0xFFFFFFFF >> $bits) << $bits;

            $ip_source = ip2long($ip_rule) & $netmask;
            $ip_target = ip2long($ip) & $netmask;

            if (strcmp($ip_target, $ip_source) === 0) {
                $pass = true;
                break;
            }
        }

        return $pass;
    }

    /**
     * 获取客户端请求的链接
     */
    static public function getRequestUrl()
    {
        $uri = '';
        if(isset($_SERVER)){
            if(isset($_SERVER['SERVER_NAME'])){
                $uri  = $_SERVER['SERVER_NAME'];
            }
            if(isset($_SERVER['REQUEST_URI'])){
                $uri .= $_SERVER['REQUEST_URI'];
            }

        }

        return $uri;
    }


    /**
     * 获取客户端的 IP
     *
     * @param boolean $ip2long 是否转换成为Long
     *
     * @return int|string
     */
    static public function getClientIp($ip2long = true)
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_REAL_IP'])) {
                $ip = $_SERVER['HTTP_X_REAL_IP'];
            }
            else if (isset($_SERVER ['HTTP_X_FORWARDED_FOR'])) {
                $ip = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];

            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            } else {
                $ip = '';
            }
        }
        else {
            if (getenv('HTTP_X_REAL_IP')) {
                $ip = getenv('HTTP_X_REAL_IP');
            }
            else if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = array_pop(explode(',', getenv('HTTP_X_FORWARDED_FOR')));
            }
            elseif (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            }
            else {
                $ip = getenv('REMOTE_ADDR');
            }
        }

        return $ip2long ? ip2long($ip) : long2ip(ip2long($ip));
    }

    /**
     * 获取客户端的 UA
     *
     * @return string
     */
    static public function getClientUa()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? self::filter($_SERVER['HTTP_USER_AGENT']) : '';
    }

    /**
     * 字符串处理
     *
     * @param string $str
     * @return string
     */
    static public function filter($str)
    {
        return addslashes(htmlentities(trim($str)));
    }

    /**
     * 手机号格式化
     *
     * @param string $mobile
     * @return string
     */
    public static function mobileFormate($mobile)
    {
        $phone = str_replace(array('+0086', '+086', '+86', '+', '-', ' '), '', $mobile);

        return substr($phone, -11);
    }

    /**
     * 分解链接
     *
     * @param string $url
     * @return  array
     */
    public static function parseUri($url)
    {
        $uris = parse_url($url);
        if (isset($uris['query']) && $uris['query']) {
            $querys = array();
            $params = explode('&', trim($uris['query'], '&'));
            if ($params) {
                foreach ($params as $val) {
                    $arr = explode('=', $val);
                    if (count($arr) == 2) {
                        list($key, $val) = $arr;
                        $querys[$key] = urldecode($val);
                    }
                }
            }

            $uris['query'] = $querys;
        }

        return $uris;
    }

    /**
     * 因为写 if(isset(...)) {...} 写得很烦
     * 所以, 就有了这个语法糖
     */
    public static function get($key, $arr, $def = null)
    {
        return isset($arr[$key]) ? $arr[$key] : $def;
    }

    /**
     * 获取具体省份名称
     */
    public static function getProvince($name)
    {
        $name = str_replace(array('省', '市'), '', $name);
        switch ($name) {
            case '新疆维吾尔自治区':
                $name = '新疆';
                break;
            case '内蒙古自治区':
                $name = '内蒙古';
                break;
            case '宁夏回族自治区':
                $name = '宁夏';
                break;
            case '西藏自治区':
                $name = '西藏';
                break;
            case '香港特别行政区':
                $name = '香港';
                break;
            case '澳门特别行政区':
                $name = '澳门';
                break;
            case '广西壮族自治区':
                $name = '广西';
                break;
            default:
                break;
        }

        return $name;
    }

    /**
     * 获取所有省份名称
     */
    public static function provinces()
    {
        return array(
            '上海', '江苏', '浙江', '福建', '安徽', '江西',
            '北京', '天津', '河北', '山东', '山西', '内蒙古',
            '广东', '广西', '海南',
            '河南', '湖北', '湖南',
            '辽宁', '吉林', '黑龙江',
            '重庆', '四川', '贵州', '云南',
            '陕西', '甘肃', '青海', '宁夏',
            '西藏', '新疆', '香港', '澳门', '台湾',
            '海外'
        );
    }

    /**
     * 获取当前毫秒数
     */
    public static function getCurentMsec()
    {
        $time_info = gettimeofday();

        return intval($time_info['sec'] * 1000 + $time_info['usec'] / 1000);
    }



}
