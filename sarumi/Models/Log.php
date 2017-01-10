<?php

/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/5
 * Time: 下午12:38
 */
class Log extends Base
{
    protected $config = [];

    protected $taglist = [];


    protected $db = null;

    protected $now = 0;

    protected $works = ['日', '一', '二', '三', '四', '五', '六'];


    public function __construct()
    {
        $this->config  = \Tool::getConf('Config');
        $this->taglist = $this->config['taglist'];

        $this->db = new mysqli('127.0.0.1', 'root', '123');
        $this->db->select_db('sarumi');

        $this->now = time();
    }






    public function savegrow2db()
    {
        $end   = time() + 10;
        $start = strtotime(date('Y-m-d')) - 86400;
        $sql   = "select * from tag_count_log where logtime BETWEEN $start AND $end";

        $result = $this->db->query($sql);
        if ($result) {
            $a = $b = [];
             $day = '';
            while ($row = $result->fetch_array()) {
                $logtime = $row['logtime'];
                if (0 == date('H', $logtime)) {
                    $day = strtotime(date('Y-m-d', $logtime));
                    $now = $day - 86400;
                    $a[$day][$row['tag_id']] = $row['count'];
                    $b[$now][$row['tag_id']] = $row['count'];
                }
            }
            unset($a[$day],$b[$start-86400]);

            foreach ($b as $day => $tags) {
                foreach ($tags as $tag_id => $count) {
                    $data            = [];
                    $data['tag_id']  = $tag_id;

                    $data['num']     = !empty($count) && !empty($a[$day][$tag_id]) ? $count - $a[$day][$tag_id] : 0;
                    $data['daytime'] = $day;
                    $sql             = "insert tag_grow_log set
            tag_id=" . $data['tag_id'] . ",num=" . $data['num'] . ",week=" . date('w', $data['daytime']) . ",daytime=" . $data['daytime'];
                    $this->db->query($sql);
                }
            }
        }
    }


    public function getData()
    {
        $sql = "select * from tag_count_log ";

        $result = $this->db->query($sql);
        if ($result) {
            $a = $b = [];
            while ($row = $result->fetch_array()) {
                $logtime = $row['logtime'];
                if (0 == date('H', $logtime)) {
                    $day = strtotime(date('Y-m-d',$logtime));
                    $now = $day-86400;
                    $a[$day][$row['tag_id']] = $row['count'];
                    $b[$now][$row['tag_id']] = $row['count'];
                }
            }
            unset($a[1472572800]);
            unset($b[1467561600]);

            foreach($b as $day=>$tags){
                foreach($tags as $tag_id=>$count){
                    $data = [];
                    $data['tag_id'] = $tag_id;
                    $data['num'] = $count-$a[$day][$tag_id];
                    $data['daytime'] = $day;
                    $this->save2db($data);
                }
            }
        }

    }

    private function save2db($data)
    {

        $sql = "insert tag_grow_log set
            tag_id=" . $data['tag_id'] . ",num=" . $data['num'] . ",week=" . date('w',$data['daytime']) . ",daytime=" . $data['daytime'];
        $this->db->query($sql);

        $this->exportLog('成功入库');

        return true;
    }



    /**
     * 本月增值
     */
    public function monthData()
    {
        $start = strtotime(date('Y-m-1 00:00:00'));
        $end   = $start + (date('t') * 86400);

        $start = $start - ((date('N', $start) - 1) * 86400);

        $sql = "select * from tag_grow_log where tag_id in(1,2) and daytime between " . $start  . " and " . $end;

        $diff = [];
        $result = $this->db->query($sql);
        if ($result) {

            while ($row = $result->fetch_array()) {
                $diff[$row['daytime']][$row['tag_id']] = $row['num'];
            }

            return $this->createCalendar($diff);
        }

        return '';
    }

    private function createCalendar($data)
    {
        $weeks = $this->works;
        $string = "<table><tr></tr>";

        foreach ($data as $day=>$c) {

            $b = date('N',$day)%7==1 ? true:false;
            if($b ){
                if(date('d',$day)>1){
                    $string .="</tr><tr style='height: 100px;vertical-align: bottom; border-bottom-color: #0b0b0b' >";
                }else{
                    $string .="<tr style='height: 100px;vertical-align: bottom; border-bottom-color: #0b0b0b' >";
                }
            }

            $string.="<td>
                    <table>
                    <tr style='vertical-align: bottom;'>
                    <td>".$c[1]."
                        <div style='background-color: #00adee; height: " . ($c[1] * 3) . "px'></div>
                    </td>
                    <td>".$c[2] . "
                        <div style='background-color: #3e152b; height: " . ($c[2] * 3) . "px'></div>
                    </td>
                    </tr>
                    <tr>
                    <td colspan='2'>".date('md',$day)."(".($weeks[date('w',$day)]).")"."</td>
                    </tr>
                    </table>
                </td>";

        }

        $string .= "</table>";
        return $string;
    }



}