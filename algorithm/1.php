<?php
/**
 *
 * 选择排序
 *
 * Created by PhpStorm.
 * User: abu
 * Date: 16/3/7
 * Time: 下午3:20
 */

$a = [3,5,8,7,7,6,4];
$b = [];

$len = count($a);

for($i=0;$i<$len-1;$i++){

    for($j=$i+1;$j<$len;$j++){
        if($a[$j]<$a[$i]){
            $l = swap($a[$j],$a[$i]);
            $a[$j] = $l[0];
            $a[$i] = $l[1];
        }

    }
}
var_dump($a);

function swap($m,$n){
    $temp = $m;
    $m = $n;
    $n=$temp;
    return [$m,$n];
}