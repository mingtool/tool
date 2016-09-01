<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/8
 * Time: 下午3:28
 */
abstract  class Base
{
    /**
     * 输出
     */
    protected function exportLog($string)
    {
        echo '|--'.$string.PHP_EOL;
    }

}