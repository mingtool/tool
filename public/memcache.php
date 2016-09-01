<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/6/28
 * Time: 下午4:34
 */

$m = new Memcache();

$m->connect('127.0.0.1',11211);
$m->set('s','123');
$v = $m->get('s');
var_dump($v);