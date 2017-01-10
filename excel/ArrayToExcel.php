<?php
namespace PHPExcel;

class ArrayToExcel extends Base
{
    /**
     * 将数组转化为excel文件
     * @param $objPHPExcel \PHPExcel\Spreadsheet
     */
    public function array2excel($objPHPExcel,$filename)
    {


        $objPHPExcel->setActiveSheetIndex(0);



        $this->head5($objPHPExcel,$filename);

    }

    /**
     * @param $objPHPExcel \PHPExcel\Spreadsheet
     * @throws Writer\Exception
     */
    private function head5($objPHPExcel,$filename)
    {

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel\IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        exit;
    }


    /**
     * @param $objPHPExcel \PHPExcel\Spreadsheet
     * @throws Writer\Exception
     */
    private function head2007($objPHPExcel,$filename)
    {


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel\IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * 上线前清理 @todo
     */
    public function example()
    {

//$objPHPExcel->getActiveSheet()->setTitle('title');
//
//        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', 'Hello')
//            ->setCellValue('B2', 'world!')
//            ->setCellValue('C1', 'Hello')
//            ->setCellValue('D2', 'world!');
//
//
//
//        $sheet = new \PHPExcel\Worksheet($objPHPExcel,'simple2');
//        $objPHPExcel->addSheet($sheet,1);
//        $objPHPExcel->setActiveSheetIndex(1)
//            ->setCellValue('A1', 'a1')
//            ->setCellValue('B2', 'b2!')
//            ->setCellValue('C1', 'c1')
//            ->setCellValue('D2', 'd2!');
//        $sheet = new \PHPExcel\Worksheet($objPHPExcel,'simple3');
//        $objPHPExcel->addSheet($sheet,2);
//        $objPHPExcel->setActiveSheetIndex(2)
//            ->setCellValue('A1', 'a1')
//            ->setCellValue('B2', 'b2!')
//            ->setCellValue('C1', 'c1')
//            ->setCellValue('D2', 'd2!');


    }


}
