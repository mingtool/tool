<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/24
 * Time: 下午9:37
 */
error_reporting(E_ALL);
class stu{
    private $a;
    private $b = 0;
    public $c;
    public $d = 0;

    //这里的 private 可以用 protected public 替代
    public function __get($name) {
        return 123;
    }

    //这里的 private 也可以用 protected public 替代
    public function __set($name, $value) {
        echo "This is set function";
    }
}

$s = new stu();

var_dump($s->a);  //output: 123
var_dump($s->b);  //output: 123
var_dump($s->c);  //output: null
var_dump($s->d);  //output: 0
var_dump($s->e);  //output: 123

$s->a = 3;   //output: This is set function
$s->c = 3;  //no output
$s->f = 3;  //output: This is set function