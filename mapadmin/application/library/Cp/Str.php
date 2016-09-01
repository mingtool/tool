<?php

namespace Cp;

/**
 * 一些字符串的处理小函数
 */
class Str
{

    /**
     * 对字符串进行编码
     *
     * @param string $str
     * @return string
     */
    static public function htmlspecialchars($str)
    {
        return htmlspecialchars($str, \ENT_QUOTES | \ENT_COMPAT | \ENT_HTML401);
    }

    /**
     * 对字符串进行反编码
     *
     * @param string $str
     * @return string
     */
    static public function htmlspecialcharsDecode($str)
    {
        return htmlspecialchars_decode($str, \ENT_QUOTES | \ENT_COMPAT | \ENT_HTML401);
    }

    /**
     * 支持 utf-8 的字符串分割
     *
     * @param string $str   所要分割的字符串
     * @param int $l
     * @return array
     */
    static function strSplitUnicode($str, $l = 0)
    {
        $return = array();

        if ($l > 0) {
            $len = \mb_strlen($str, 'UTF-8');
            for ($i = 0; $i < $len; $i += $l) {
                $return[] = \mb_substr($str, $i, $l, 'UTF-8');
            }
        }
        else {

            $return = \preg_split("//u", $str, -1, \PREG_SPLIT_NO_EMPTY);
        }

        return $return;
    }

    /**
     * 判断字符串是否仅是 英文, 中文(utf-8), 数字 组合
     *
     * @param string $str   所要判断的字符串
     * @return boolean
     */
    static public function matchAsciiChineseNum($str = '')
    {
        return preg_match('/^[a-z|A-Z|0-9|\x{4e00}-\x{9fa5}]$/u', $str) ? true : false;
    }

    /**
     * 模糊化的字符串
     * 支持UTF-8
     *
     * @example \St\Str::fuzzy(13800138123) 返回 138*****123
     *
     * @param string $str 所要模糊处理的字符串
     * @return string
     */
    public static function fuzzy($str, $encoding = 'UTF-8')
    {
        $len = mb_strlen($str, $encoding) / 2;
        return self::substrReplace($str, str_repeat('*', $len), ceil(($len) / 2), $len, $encoding);
    }

    /**
     * 返回模糊化的Email, 同时保存Email的后缀部分
     *
     * @example \St\Str::fuzzyEmail(89932995@qq.com) 返回 89****95@qq.com
     *
     * @param string $email
     * @return string
     */
    public static function fuzzyEmail($email)
    {
        if (!Is::email($email)) {
            return self::fuzzy($email);
        }

        list($name, $domain) = explode('@', $email);

        return self::fuzzy($name) . '@' . $domain;
    }

    /**
     * 支持UTF8的字符串替换
     *
     * @param string $string
     * @param string $replacement
     * @param int $start
     * @param int $length
     * @param string $encoding
     * @return string
     */
    public static function substrReplace($string, $replacement, $start, $length = null, $encoding = 'UTF-8')
    {
        $string_length = mb_strlen($string, $encoding);

        if ($start < 0) {
            $start = max(0, $string_length + $start);
        }
        else if ($start > $string_length) {
            $start = $string_length;
        }

        if ($length < 0) {
            $length = max(0, $string_length - $start + $length);
        }
        else if ((is_null($length) === true) || ($length > $string_length)) {
            $length = $string_length;
        }

        if (($start + $length) > $string_length) {
            $length = $string_length - $start;
        }

        return mb_substr($string, 0, $start, $encoding) . $replacement . mb_substr($string, abs($start + $length), ceil($string_length - $start - $length), $encoding);
    }

    /**
     * 替换指定的字符为 #
     *
     * 慎用
     *
     * @param string $subjects
     * @param array $find
     * @return string
     */
    public static function strReplace($subjects, $find = array())
    {
        if (!$find) {
            $find = array('>', '<', '/*', '*/', '"', '\'', ')', '(', '}', '{', '\\x', '\\u', '`', '&#', '^', ';', '\\');
        }

        $uri = trim(str_ireplace($find, '#', $subjects));

        if (mb_strlen($uri) < 2083) {
            while (strpos($uri, '##') > 0) {
                $uri = str_replace('##', '#', $uri);
            }
        }
        return $uri;
    }

    /**
     * 格式化为 Y-m-d 格式的时间
     *
     * @param string $day 能被 strtotime() 支持的字符串
     * @return string
     */
    public static function toYmd($day)
    {
        return date('Y-m-d', strtotime($day));
    }

    /**
     * 距离当天开始的秒数转数小时:分
     */
    public static function toHi($time)
    {
        return date('H:i',strtotime(date('Y-m-d 00:00:00',time()))+$time);
    }

    /**
     * 将小时:分转为秒数
     */
    public static function toSecond($date)
    {
        list($h,$i) = explode(':',$date);
        return $h*3600+$i*60;
    }

    /**
     * 计算具体字符串长度 一个汉字两个字符 字母 数字 一个字符
     */
    public static function stringLen($string)
    {
        return (strlen($string)+mb_strlen($string, 'utf8'))/2;
    }

    /**
     * 分转为以元为单位带两个小数点的价格
     */
    public static function formatPrice($price)
    {
        $y = intval($price/100);
        $t = $price%100;
        $f = $t>=10? str_pad($t,2,'0') : str_pad($t,2,'0',STR_PAD_LEFT) ;
        return $y.'.'.$f;
    }



}
