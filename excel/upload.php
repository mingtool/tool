<?php

/** PHPExcel root directory */
    define('PHPEXCEL_ROOT', dirname(__FILE__) . '/');
    require(PHPEXCEL_ROOT . 'Autoload.php');
Autoload::register();


if($_FILES){

    $file = $_FILES['file'];

    $uploadfile = $file['tmp_name'];
    $objReader = PHPExcel\IOFactory::createReader('Excel5');//use excel2007 for 2007 format
    $objPHPExcel = $objReader->load($uploadfile);
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();           //取得总行数
    $highestColumn = $sheet->getHighestColumn(); //取得总列数


    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow();
    echo 'highestRow='.$highestRow;
    echo "<br>";
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel\Cell::columnIndexFromString($highestColumn);//总列数
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
       var_dump($strs);
    }

}












?>

<form name="test" method="post" action="" enctype="multipart/form-data" >
    <input name="file" type="file" />
    <br />
    <input type="submit" value="sm">

</form>

