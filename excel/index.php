<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/2/23
 * Time: 下午5:35
 */

require_once 'PHPExcel.php';





$objPHPExcel = new PHPExcel();



$objPHPExcel->setActiveSheetIndex(0)

    //Excel的第A列，uid是你查出数组的键值，下面以此类推
    ->setCellValue('A1', 1)
    ->setCellValue('B1', 2)
    ->setCellValue('C1', 3)
    ->setCellValue('B2', 2)
    ->setCellValue('C2', 3)
    ->setCellValue('B3', 2)
    ->setCellValue('C3', 3)

;

//合并单元格
$objPHPExcel->getActiveSheet()->mergeCells('A1:A3')
->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);



$file = '备货单_'.date('ymdhis').'.xls';
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-execl");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header("Content-Transfer-Encoding:binary");


header('Content-Disposition:attachment;filename=" '.$file.' "');

$objWriter->save('php://output');






$array = [
    'dafdfa','sfsdfds'
];

//$excel->getProperties()->setCreate();
