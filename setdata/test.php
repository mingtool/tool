<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/24
 * Time: 下午8:58
 */

include_once 'AModel.php';

class test extends AModel
{
    public $a;

    public function setA($n)
    {
        $this->a = $n;
    }

    public function getA()
    {
        return $this->a;
    }

    public function toArray()
    {
        return array(
            'a' => $this->a
        );
    }
}


$a = array(
    'a'=>11
);
$obj = new test($a);
var_dump($obj);
echo $obj->getA();