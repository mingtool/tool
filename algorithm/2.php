<?php
/**
 * 插入排序
 * Created by PhpStorm.
 * User: abu
 * Date: 16/3/8
 * Time: 下午5:53
 *
 */

$a = [2,7,3,6,9,4];
$len = count($a);

for($i=0;$i<$len;$i++){
    $j=$i;
    $taget = $a[$i];

    while($j>0 && $taget<$a[$j-1]){
        $a[$j] = $a[$j-1];
        $j--;
        echo $j.'---'.$taget.PHP_EOL;
    }
    $a[$j] = $taget;
}
var_dump($a);