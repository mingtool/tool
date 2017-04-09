<?php

/**
 * 客户
 * Created by PhpStorm.
 * User: abu
 * Date: 17/4/7
 * Time: 下午9:17
 */
class Customer
{
    private $name;
    private $rentals = array();

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addRentals($rental)
    {
        $this->rentals[] = $rental;
    }

    public function statement()
    {
        $totalAmount          = 0;
        $frequentRenterPoints = 0;
        $result               = "Rental Record for " . $this->getName();

        /* @var $rental Rental*/
        foreach ($this->rentals as $a => $rental) {
            /* @var $movie Movie*/
            $movie = $rental->getMovie();
            $daysRented = $rental->getDayRented();
            $thisAmount = 0;
            switch($movie->getPriceCode()){
                case Movie::$regular:
                    $thisAmount +=2;
                    if($daysRented>2){
                        $thisAmount += ($daysRented-2)*1.5;
                    }
                    break;
                case Movie::$newRelease:
                    $thisAmount += $daysRented*3;
                    break;
                case Movie::$childrens:
                    $thisAmount += 1.5;
                    if($daysRented>3){
                        $thisAmount +=($daysRented-3)*1.5;
                    }
                    break;
                default:
                    break;

            }
            $frequentRenterPoints++;
            if($movie->getPriceCode()==Movie::$newRelease && $daysRented>1){
                $frequentRenterPoints ++;
            }

            $result .= $movie->getTitle().' '.$thisAmount;
            $totalAmount +=$thisAmount;
        }

        $result .= 'totalamount'.$totalAmount;
        $result .= "earned ".$frequentRenterPoints." points";
        return $result;
    }


}