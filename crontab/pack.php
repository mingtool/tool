<?php
/**
 * pack函数用于 按 ASCII编码标准 把 其它进制的字符串 打包成  二进制字符串。
 *   由于PHP有自己的类型系统，所以需要原始的二进制时，需要用pack进行打包。
 *
 * pack/unpack函数来进行二进制打包和二进制解包
 * pack/unpack允许使用修饰符*和数字，紧跟在格式字符之后，用于指定该格式的个数；
 *
 * ASCII是（美国标准信息交换代码 ）American Standard Code for Information Interchange缩写
 *   是基于  拉丁字母  的一套电脑编码系统，主要用于显示现代英语和其他西欧语言
 */


/*
a和A都是用来打包字符串的，它们的唯一区别就是当小于定长时的填充方式。
a以NULL填充，NULL事实上是'\0'的表示，代表空字节，8个位上全是0。A以空格填充，空格也即ASCII码为32的字符
bin2hex  将ASCII 码 转为二进制

chr — 返回 ASCII 码指定的字符
*/

$bin = pack("a", "d");
echo "output: " . $bin . "\n";
echo "output: 0x" . bin2hex($bin) . "\n";

$bin = pack("a3", "中");
echo "output: 0x" . bin2hex($bin) . "\n";
echo "output: " . chr(0xe4) . chr(0xb8) . chr(0xad) . "\n";
echo "output: " . $bin{0} . $bin{1} . $bin{2} . "\n";

$bin = pack("A4","中");
echo 'A4 output:'.$bin."|\n";

/*
 * h和H的描述看起来有些奇怪。它们都是读取十进制，以十六进制方式读取，以半字节(4位)为单位
 * h和H的差别在于h是低位在前，H是高位在前
*/

/*0x47为十进制的71，因为读取半个字节，所以变成0x7，后面补0变成0x70，则刚好是字符p的ASCII码。
如果换成是h格式化，则最终的结果是0x07，因为低位在前。*/
echo "output: " . pack("H", 0x47) . "\n";//output: p

/*
 * 0x47是十进制的71，由于使用H2格式化，所以一次读取8位，最后变成十六进制的0x71，即字符q的ASCII码。
   0x56是十进制的86，由于使用h2格式化，所以一次读取8位，最后变成十六进制的0x86，但是由于h表示低位在前，因此0x86变成0x68，即字符h的ASCII码*/
echo "output: " . pack("H2h2", 0x47, 0x56) . "\n";//output: qh


/*
 * s为有符号短整数；S为无符号短整数。它们都为主机字节序，并且为16位。
   通常为主机字节序的格式化字符，一般只用于单机的操作，
   因为您无法确定主机字节序究竟是大端还是小端。
   当然，也是有办法来获取本机字节序是属于大端或小端，但那样是没有必要的。
*/
$bin1 = pack('s',11);
$bin2 = pack('S',332);
var_dump(unpack("skey1", $bin1));//Array([key1] => 11)
var_dump(unpack("Skey2",$bin2));//Array([key2] => 332)

/*
 * n和v除了明确指定了字节序，其它行为跟s和S是一样的。

i和I依赖于机器大小及字节序，很少用它们。

l、L、N、V跟s、S、n、v类似，除了表示的大小不同，前者都为32位，后者都为16位。

f、d是因为float和double与CPU无关。一般来说，编译器是按照IEEE标准解释的，即把float/double看作4/8个字符的数组进行解释。因此，只要编译器是支持IEEE浮点标准的，就不需要考虑字节顺序。

剩下的x、X和@用得比较少，对此不作深究。
 * */