<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/5
 * Time: 下午12:38
 */

define('DIR', __DIR__);
define('PS', PATH_SEPARATOR);
spl_autoload_register(function ($className) {
    $paths[] = DIR . '/Lib';
    $paths[] = DIR . '/Lib/Jpgraph';
    $paths[] = DIR . '/Models';
    $appPath = implode(PS, $paths);
    set_include_path($appPath . PS . get_include_path());
    if (!class_exists($className)) {
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        include_once $className . ".php";
    }
});
include_once DIR . '/Lib/PHPMailer/PHPMailerAutoload.php';

echo PHP_EOL;
echo '==begin:' . date('Y-m-d H:i:s') . PHP_EOL;
$log = new \Couples();

// echo $log->savegrow2db();
echo $log->monthData();

echo '==end:' . date('Y-m-d H:i:s') . PHP_EOL;
