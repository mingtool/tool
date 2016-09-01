<?php

/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/5
 * Time: 下午12:38
 */
class Couples extends Base
{
    protected $config = [];

    protected $taglist = [];

    /**
     * @var array
     */
    protected $users = [];

    protected $db = null;

    protected $now = 0;

    protected $works = ['日', '一', '二', '三', '四', '五', '六'];


    public function __construct()
    {
        $this->config  = \Tool::getConf('Config');
        $this->taglist = $this->config['taglist'];
        $this->users   = $this->config['users'];

        $this->db = new mysqli('127.0.0.1', 'root', '123');
        $this->db->select_db('sarumi');

        $this->now = time();
    }

    public function getRespone()
    {
        $data = [];
        foreach ($this->taglist as $tagid => $item) {
            $name    = $item[0];
            $gettype = $item[2];

            $count        = $this->getCount($name, $gettype);
            $data[$tagid] = $count;

            $this->exportLog($item[1] . '--tag数:' . $count);
        }
        $this->save2db($data);
        if(date('H')==0){
            $this->savegrow2db();
        }
        $this->sendMail($data);

        return $data;
    }

    private function getCount($tagname, $gettype)
    {
        $url  = 'http://www.lofter.com/tag/' . $tagname;
        $http = new Http();
        $http->setUseragent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:39.0) Gecko/20100101 Firefox/39.0');

        $http->setUrl($url);
        $http->send();

        $badStrs = [
            "\r\n"          => '',
            "\r"            => '',
            '<!-- 引入头部 -->' => '',
        ];

        $htmlStr = trim(str_replace(array_keys($badStrs), array_values($badStrs), $http->getResponseBody()));

        $doc = \PhpQuery\phpQuery::newDocumentHTML($htmlStr, 'utf-8');
        /**
         * 这里需要区分两种格式的页面
         */
        switch ($gettype) {
            case 1:
                return intval($doc['div.ttl']->text());
                break;
            case 2:
                return ($doc['div.m-mlist']->count()) - 1;

                break;
            default:
                return 0;
                break;
        }

    }

    private function save2db($data)
    {
        foreach ($data as $tagid => $count) {
            $sql = "insert tag_count_log set
            tag_id=" . $tagid . ",count=" . $count . ",logtime=" . $this->now;
            $this->db->query($sql);
        }
        $this->exportLog('成功入库');

        return true;
    }

    private function sendMail($data)
    {
        $mailObject = \Mail::getInstance();
        $subject    = basename('tag数--' . date('Y-m-d H:i:s', $this->now));
        $this->exportLog('邮件标题:' . $subject);
        foreach ($this->users as $user) {
            $mails  = $user[0];
            $tags   = $user[1];
            $type   = $user[2];
            $hours  = $user[3];
            $switch = $user[4];

            if (!$switch) {
                continue;
            }

            $body = '<p>' . date('Y-m-d H:i:s', $this->now) . '</p>';


            if (2 == $type) {
                if (!in_array(date('H'), $hours)) {
                    continue;
                }
                $prewData = $this->getPrewData($tags, 'd');
                if ($prewData) {
                    foreach ($tags as $tagid) {
                        $c = isset($prewData['data'][$tagid]) ? $prewData['data'][$tagid] : 0;
                        $body .= '<p>' . $this->taglist[$tagid][1] .
                            '--tag数:' . $data[$tagid] .
                            '--增速:' . ($data[$tagid] - $c) .
                            '</p>';
                    }
                    $body .= $this->diff($data);
                    $body .= '<p>------------------------</p>';
                    $body .= '<p>' . $prewData['time'] . '</p>';

                    foreach ($tags as $tagid) {
                        $c = isset($prewData['data'][$tagid]) ? $prewData['data'][$tagid] : 0;
                        $body .= '<p>' . $this->taglist[$tagid][1] .
                            '--tag数:' . $c .
                            '</p>';
                    }
                    $body .= $this->diff($prewData['data']);
                } else {
                    foreach ($tags as $tagid) {
                        $body .= '<p>' . $this->taglist[$tagid][1] . '--tag数:' . $data[$tagid] . '</p>';
                    }
                    $body .= $this->diff($data);
                }

                $body .= $this->monthData();

                $mailObject->send($subject, $body, $mails);

            } elseif (3 == $type) {
                if (!in_array(date('H'), $hours)) {
                    continue;
                }
                foreach ($tags as $tagid) {
                    $body .= '<p>' . $this->taglist[$tagid][1] . '--tag数:' . $data[$tagid] . '</p>';
                }
                if (date('H') != 0 && date('H') % 2 == 0) {
                    $mailObject->send($subject, $body, $mails);
                }
            } elseif (1 == $type) {
                if (!in_array(date('H'), $hours)) {
                    continue;
                }
                if (date('H') == 0) {
                    continue;
                }
                foreach ($tags as $tagid) {
                    $body .= '<p>' . $this->taglist[$tagid][1] . '--tag数:' . $data[$tagid] . '</p>';
                }

                $mailObject->send($subject, $body, $mails);
            } else {

            }

        }
    }

    /**
     * @param string $t d为天 h为小时
     */
    public function getPrewData($tagids, $t = 'd')
    {
        switch ($t) {
            case 'h':
                $end = $this->now - 1;
                break;
            default:
                $end = strtotime(date('Y-m-d')) - 82860;
                break;
        }
        $begin = $end - 3600;

        $sql = "select * from tag_count_log where logtime BETWEEN " . $begin . " and  " . $end;

        $result = $this->db->query($sql);
        $data   = [];
        if ($result) {
            while ($row = $result->fetch_array()) {
                $logtime = $row['logtime'];
                if (in_array($row['tag_id'], $tagids)) {

                    $data[$row['tag_id']] = $row['count'];
                }

            }

            return ['time' => date('Y-m-d H:i:s', $logtime), 'data' => $data];
        }

        return false;
    }


    /**
     * 差值
     */
    private function diff($data)
    {
        return '<p>毒差:' . ($data[1] - $data[2]) . '----冷差:' . ($data[1] - $data[5]) . '</p>';
    }


    public function savegrow2db()
    {
        $end   = time() + 10;
        $start = strtotime(date('Y-m-d')) - 86400;
        $sql   = "select * from tag_count_log where logtime BETWEEN $start AND $end";

        $result = $this->db->query($sql);
        if ($result) {
            $a   = $b = [];
            $day = '';
            while ($row = $result->fetch_array()) {
                $logtime = $row['logtime'];
                if (0 == date('H', $logtime)) {
                    $day                     = strtotime(date('Y-m-d', $logtime));
                    $now                     = $day - 86400;
                    $a[$day][$row['tag_id']] = $row['count'];
                    $b[$now][$row['tag_id']] = $row['count'];
                }
            }
            unset($a[$day], $b[$start - 86400]);

            foreach ($b as $day => $tags) {
                foreach ($tags as $tag_id => $count) {
                    $data            = [];
                    $data['tag_id']  = $tag_id;
                    $data['num']     = $count - $a[$day][$tag_id];
                    $data['daytime'] = $day;
                    $sql             = "insert tag_grow_log set
            tag_id=" . $data['tag_id'] . ",num=" . $data['num'] . ",week=" . date('w', $data['daytime']) . ",daytime=" . $data['daytime'];
                    $this->db->query($sql);
                }
            }
        }

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

        $sql = "select * from tag_grow_log where tag_id in(1,2) and daytime between " . $start . " and " . $end;

        $diff   = [];
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
        $weeks  = $this->works;
        $string = "<table><tr></tr>";

        foreach ($data as $day => $c) {

            $b = date('N', $day) % 7 == 1 ? true : false;
            if ($b) {
                if (date('d', $day) > 1) {
                    $string .= "</tr><tr style='height: 100px;vertical-align: bottom; border-bottom-color: #0b0b0b' >";
                } else {
                    $string .= "<tr style='height: 100px;vertical-align: bottom; border-bottom-color: #0b0b0b' >";
                }
            }

            $string .= "<td>
                    <table>
                    <tr style='vertical-align: bottom;'>
                    <td>" . $c[1] . "
                        <div style='background-color: #00adee; height: " . ($c[1] * 3) . "px'></div>
                    </td>
                    <td>" . $c[2] . "
                        <div style='background-color: #3e152b; height: " . ($c[2] * 3) . "px'></div>
                    </td>
                    </tr>
                    <tr>
                    <td colspan='2'>" . date('md', $day) . "(" . ($weeks[date('w', $day)]) . ")" . "</td>
                    </tr>
                    </table>
                </td>";

        }

        $string .= "</table>";

        return $string;
    }


}