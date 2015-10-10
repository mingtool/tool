<?php
/**

 *
 * unpack是用来解包经过pack打包的数据包，如果成功，则返回数组。
 * 其中格式化字符和执行pack时一一对应，但是需要额外的指定一个key，用作返回数组的key。
 * 多个字段用/分隔。
 *
 *  pack.php
 *
 * Created by PhpStorm.
 * User: abu
 * Date: 15/9/30
 * Time: 下午5:54
 */



$bin = @pack("a9SS", "陈一回", 20, 1);
$data = @unpack("a9name/sage/Sgender", $bin);

if (is_array($data))
{
    print_r($data);
}

//$ php  -f pack.php
//Array
//(
//    [name] => 陈一回
//    [age] => 20
//    [gender] => 1
//)

/*判断大小端*/


function IsBigEndian()
{
    $bin = pack("L", 0x12345678);
    $hex = bin2hex($bin);
    if (ord(pack("H2", $hex)) === 0x78)
    {
        return FALSE;
    }

    return TRUE;
}

if (IsBigEndian())
{
    echo "大端序";
}
else
{
    echo "小端序";
}

echo "\n";
//$ php -f pack.php
//小端序