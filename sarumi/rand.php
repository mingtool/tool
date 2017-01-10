<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/11/7
 * Time: 下午12:48
 */


$count = intval($argv[1]);
$begin = 1;

$f = round($count * 30 / 100);
$e = $count - $f;

$_1 = rand($begin, $count);
$_2 = rand($f, $e);
$_3 = rand($begin, $count);
$_4 = rand($begin, $f);
$_5 = rand($begin, $count);
$_6 = rand($begin,$e);
$_7 = rand($e, $count);
$_8 = rand($f,$count);
$_9 = rand($begin, $count);

echo '1)' . $_1 . PHP_EOL;
echo '2)' . $_2 . PHP_EOL;
echo '3)' . $_3 . PHP_EOL;
echo '4)' . $_4 . PHP_EOL;
echo '5)' . $_5 . PHP_EOL;
echo '6)' . $_6 . PHP_EOL;
echo '7)' . $_7 . PHP_EOL;
echo '8)' . $_8 . PHP_EOL;
echo '9)' . $_9 . PHP_EOL;