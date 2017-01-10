<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/6/6
 * Time: 下午4:01
 */
namespace PHPExcel;

class Base
{
    public function __construct()
    {
        include_once __DIR__ . '/Autoload.php';
        \PHPExcel\Autoload::register();
    }
}