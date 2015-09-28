<?php
date_default_timezone_set('PRC');
define('DIR', __DIR__);

require DIR . '/../../lib/base.php';

//$runtime = 600;
$runtime = 300;

$crontab = new \Crontabmanager\Crontab();
//$list = $crontab->getProcess();
//var_dump($list);
//exit;
$begintime = time();

echo '==开始执行'.PHP_EOL;

echo date('Y-m-d H:i:s',$begintime).PHP_EOL;

$endtime = $begintime + $runtime;

do {

    $second = intval(date('s'));
    echo date('Y-m-d H:i:s') . PHP_EOL;

    if (0 != $second) {

        $sleep = min(60 - $second, 30);
        echo 'sleep:' . $sleep . PHP_EOL;
        sleep($sleep);
    } else {

        echo 'exec' . PHP_EOL;

        $list = $crontab->getProcess();
        if (!empty($list)) {
            foreach ($list as $pro => $num) {
                for ($i = 1; $i <= $num; $i++) {
                    echo $pro . PHP_EOL;
//                    exec('/usr/bin/env php /Users/abu/develop/trade/code/tool/public/run.php ' . $pro . '  &');
                }

            }
        }

        sleep(1);
    }
} while (time() < $endtime);

echo date('Y-m-d H:i:s') . PHP_EOL;
echo '==执行时长：'.(time() - $begintime).'秒';



