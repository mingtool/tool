<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 15/7/14
 * Time: 下午5:42
 */
namespace Crontabmanager;

class Crontab
{
    protected $list = [];


    public function __construct()
    {
        $this->setList();
    }

    public function getProcess()
    {

        $list = $this->getList();

        list($m, $d, $h, $i, $w) = explode('/', date('m/d/H/i/w'));

        $newlist = [];
        foreach ($list as $k => $v) {


            $week   = $v['week'];
            $month  = $v['month'];
            $day    = $v['day'];
            $hour   = $v['hour'];
            $minute = $v['minute'];

            if (1 != $v['isenable'] || 1 != $v['iscycle']) {
                continue;
            }

            if (!$this->isMatch($week, $w)) {
                continue;
            }

            if (!$this->isMatch($month, $m)) {
                continue;
            }

            if (!$this->isMatch($day, $d)) {
                continue;
            }

            if (!$this->isMatch($hour, $h)) {
                continue;
            }

            if (!$this->isMatch($minute, $i)) {
                continue;
            }

            $newlist[$v['uri']] = $v['num'];

        }

        return $newlist;
    }


    protected function setList()
    {
        $this->list = [
            [
                'isenable' => 1,
                'iscycle'  => 1,
                'minute'   => '0-25',
                'hour'     => '*',
                'day'      => '*',
                'month'    => '*',
                'week'     => '*',
                'uri'      => '/task/Smspromo/work',
                'num'      => 1,
            ],
            [
                'isenable' => 1,
                'iscycle'  => 1,
                'minute'   => '*/3',
                'hour'     => 18,
                'day'      => '*',
                'month'    => '*',
                'week'     => '*',
                'uri'      => '/task/Smspromo/work2',
                'num'      => 1,
            ],
            [
                'isenable' => 1,
                'iscycle'  => 1,
                'minute'   => 0,
                'hour'     => '2,6,18',
                'day'      => '*',
                'month'    => '*',
                'week'     => '*',
                'uri'      => '/task/Smspromo/work3',
                'num'      => 1,
            ],
            [
                'isenable' => 1,
                'iscycle'  => 1,
                'minute'   => '0-57/2',
                'hour'     => '*',
                'day'      => '*',
                'month'    => '*',
                'week'     => 5,
                'uri'      => '/task/Logisticstrackclient/run',
                'num'      => 1,
            ],

        ];
    }

    protected function getList()
    {
        return $this->list;
    }

    /**
     * 是否 当前时间
     * @param $v  string  参数   格式： *  | 1 | 3-4  | * / 2 |  2-3/2 | 1，5，8
     * @param $now  int
     */
    protected function isMatch($v, $now)
    {

        $v = trim($v);

        if ('*' == $v) {

            return true;
        }

        if (is_numeric($v)) {

            return $v == $now ? true : false;
        }

        if(preg_match('/^\d+(,\d+)+$/',$v)){

            $l = explode(',',$v);
            foreach($l as $i){
                if($now == $i){
                    return true;
                }
            }
            return false;
        }

        if (preg_match('/^\d+\-\d+$/', $v)) {

            list($min, $max) = explode('-', $v);

            return ($now >= $min && $now <= $max) ? true : false;
        }

        if (preg_match('/^\*\/\d+$/', $v)) {

            list($s, $l) = explode('/', $v);

            return (0 == $now || 0 == ($now % $l)) ? true : false;
        }

        if (preg_match('/^\d+\-\d+\/\d+$/', $v)) {

            list($s, $l) = explode('/', $v);
            list($min, $max) = explode('-', $s);

            return ((0 == $now || 0 == ($now % $l)) &&  ($now >= $min && $now <= $max)) ? true : false;
        }

        return false;
    }

}