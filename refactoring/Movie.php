<?php
/**
 * 影片
 * Created by PhpStorm.
 * User: abu
 * Date: 17/4/7
 * Time: 下午8:25
 */
class Movie
{
    public static $childrens = 2;
    public static $regular   = 0;
    public static $newRelease = 1;

    public $title = 0;
    public $priceCode = '';

    public function __construct($title,$priceCode)
    {
        $this->title = $title;
        $this->priceCode = $priceCode;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setPriceCode($code)
    {
        $this->priceCode = trim($code);
    }

    public function getPriceCode()
    {
        return $this->priceCode;
    }
}