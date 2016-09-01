<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/5
 * Time: 下午12:54
 */
class Tool
{
    static function getConf($filename)
    {
        return require_once __DIR__.'/../conf/'.$filename.'.php';
    }
}