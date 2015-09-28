<?php
/**
 * pack函数用于将   其它进制的数字   压缩到位字符串之中。
 *    也就是把其它进制数字转化为ASCII码字符串。
 * User: abu
 * Date: 15/9/28
 * Time: 下午2:45
 */

$bin1 = pack('s',11);

var_dump(unpack("ss22", $bin1));

$bin2 = pack('S',332);

var_dump(unpack("Ss2s",$bin2));