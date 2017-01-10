<?php
namespace PHPExcel;

class ExcelToArray extends Base
{


    public function excel2array()
    {
        $objReader = \PHPExcel\IOFactory::createReader('Excel5');//use excel2007 for 2007 format
        $objPHPExcel = $objReader->load($uploadfile);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();           //取得总行数
        $highestColumn = $sheet->getHighestColumn(); //取得总列数

        /* 第一种方法

        //循环读取excel文件,读取一条,插入一条
        for($j=1;$j<=$highestRow;$j++)                        //从第一行开始读取数据
        {
            for($k='A';$k<=$highestColumn;$k++)            //从A列读取数据
            {
                //
                这种方法简单，但有不妥，以'\\'合并为数组，再分割\\为字段值插入到数据库
                实测在excel中，如果某单元格的值包含了\\导入的数据会为空
                //
                $str .=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';//读取单元格
            }
            //echo $str; die();
            //explode:函数把字符串分割为数组。
            $strs = explode("\\",$str);
            $sql = "INSERT INTO te(`1`, `2`, `3`, `4`, `5`) VALUES (
            '{$strs[0]}',
            '{$strs[1]}',
            '{$strs[2]}',
            '{$strs[3]}',
            '{$strs[4]}')";
            //die($sql);
            if(!mysql_query($sql))
            {
                return false;
                echo 'sql语句有误';
            }
            $str = "";
        }
        unlink($uploadfile); //删除上传的excel文件
        $msg = "导入成功！";
        */

        /* 第二种方法*/
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        echo 'highestRow='.$highestRow;
        echo "<br>";
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
        echo 'highestColumnIndex='.$highestColumnIndex;
        echo "<br>";
        $headtitle=array();
        for ($row = 1;$row <= $highestRow;$row++)
        {
            $strs=array();
            //注意highestColumnIndex的列数索引从0开始
            for ($col = 0;$col < $highestColumnIndex;$col++)
            {
                $strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            $sql = "INSERT INTO te(`1`, `2`, `3`, `4`, `5`) VALUES (
            '{$strs[0]}',
            '{$strs[1]}',
            '{$strs[2]}',
            '{$strs[3]}',
            '{$strs[4]}')";
            //die($sql);
            if(!mysql_query($sql))
            {
                return false;
                echo 'sql语句有误';
            }
        }
    }




}
