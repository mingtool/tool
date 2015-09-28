<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 15/7/3
 * Time: 上午11:05
 */

define('DIR', __DIR__);

require DIR . '/../../lib/base.php';


$dbconfig     = [
    'sms' => [
        'ip'       => '172.16.2.9',
        'dbname'   => 'stsms',
        'username' => 'dev',
        'password' => 'huanleguang123',
    ],
];
$keywordModel = new \Db\Keyword($dbconfig);


$file = '../../data/keywords';

$text = file($file);
foreach ($text as $l) {
    $i = $keywordModel->insert(trim($l));
    echo $i . PHP_EOL;
}

