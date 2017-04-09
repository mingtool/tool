<?php
/**
 * 租赁
 * Created by PhpStorm.
 * User: abu
 * Date: 17/4/7
 * Time: 下午9:13
 */

class Rental
{
    private $dayRented = null;
    private $movie = null;


    public function __construct($dayRendted,$movie)
    {
        $this->dayRented = $dayRendted;
        $this->movie = $movie;
    }

    public function getDayRented()
    {
        return $this->dayRented;
    }
    public function getMovie()
    {
        return $this->movie;
    }

}