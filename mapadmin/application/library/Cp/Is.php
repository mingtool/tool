<?php

namespace Cp;

/**
 * 一些常见的判断函数
 */
class Is
{

    /**
     * 是否是邮箱地址
     *
     * @param string $email
     * @return boolean
     */
    static public function email($email)
    {
        return ($email === \filter_var($email, \FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    /**
     * 是否是有效的大陆手机号码
     *
     * @param string|int $num   号码
     * @return boolean
     */
    static public function mobileNum($num)
    {
        $num = trim($num);

        // 是否是11位
        if (11 !== strlen($num)) {
            return false;
        }

        $pattern = '/^1[3|4|5|7|8|9][0-9]{9}$/';

        return \preg_match($pattern, $num) ? true : false;
    }

    /**
     * 是否是有效的QQ号码
     *
     * @param string|int $num
     * @return boolean
     */
    static public function qqNum($num)
    {

        $pattern = '/^[1-9][0-9]{4,9}$/';

        return \preg_match($pattern, $num) ? true : false;
    }

    /**
     * 是否在时间区间内
     *
     * @param int $begin 开始的时间  小时:分  转化的秒数
     * @param int $end   结束的时间  小时:分  转化的秒数
     * @param int $time  需要验证的时间戳  默认为当前时间
     * @return boolean
     */
    static public function timeSection($begin, $end, $time = null)
    {
        if ($begin > $end) {
            return false;
        }

        $today     = strtotime(date('Y-m-d 00:00:00'));
        $beginTime = $today + $begin;
        $endTime   = $today + $end;

        $timestamp = empty($time) ? time() : intval($time);

        if ($timestamp >= $beginTime && $timestamp <= $endTime) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 买家旺旺
     * 淘宝会员名由5-25个字符(包括小写字母、数字、下划线、中文)组成，一个汉字为两个字符
     */

    public static function buyerNick($nick)
    {
        $strlen = \St\Str::stringLen($nick);
        $pattern = '/^[a-z0-9\_\x{4e00}-\x{9fa5}]+$/u';

        return \preg_match($pattern, $nick) && $strlen>=5 && $strlen<=25 ? true : false;
    }

    /**
     * 联通电话号码
     * 131  132  145 155 156 1709 176 185 186
     */
    public static function ltMobile($mobile)
    {
        $pattern = '/^1((3[12]|45|5[56]|76|8[56])[0-9]|709)\d{7}$/';
        return \preg_match($pattern,$mobile) ? true : false;
    }

    /**
     * 电信电话号码
     * 133 153 1700 177 180 181 189
     */
    public static function dxMobile($mobile)
    {
        $pattern = '/^1((33|53|77|8[019])[0-9]|700)\d{7}$/';
        return \preg_match($pattern,$mobile) ? true : false;
    }
    /**
     * 移动电话号码
     * 134 135 136 137 138 139 141 143 147 150 151 152 154157 158 159 1705 178 182 183 184 187 188
     */
    public static function ydMobile($mobile)
    {
        $pattern = '/^1((3[456789]|4[137]|5[0124789]|78|8[234])[0-9]|705)\d{7}$/';
        return \preg_match($pattern,$mobile) ? true : false;
    }

    /**
     * 活动标题
     * 只包含中英文和数字 5~25
     */
    public static function Title($title)
    {
        $strlen = \St\Str::stringLen($title);
        $pattern = '/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+$/u';

        return \preg_match($pattern, $title) && $strlen>=5 && $strlen<=25 ? true : false;
    }

    /**
     * 数值区间
     * @param  int $max
     * @param  int $min
     * @param  int $num
     */
    public static function numSection($max,$min,$num)
    {
        return ($num>$max || $num<$min) ? false : true;
    }
}
