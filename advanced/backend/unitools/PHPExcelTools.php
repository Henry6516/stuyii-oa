<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-12-05
 * Time: 17:19
 */

namespace backend\unitools;


class PHPExcelTools
{
    public static function stringFromColumnIndex($pColumnIndex = 0)
    {
        //  Using a lookup cache adds a slight memory overhead, but boosts speed
        //  caching using a static within the method is faster than a class static,
        //      though it's additional memory overhead
        static $_indexCache = array();
        if (!isset($_indexCache[$pColumnIndex])) {
            // Determine column string
            if ($pColumnIndex < 26) {
                $_indexCache[$pColumnIndex] = \chr(65 + $pColumnIndex);
            } elseif ($pColumnIndex < 702) {
                $_indexCache[$pColumnIndex] = \chr(64 + ($pColumnIndex / 26)) .
                    \chr(65 + $pColumnIndex % 26);
            } else {
                $_indexCache[$pColumnIndex] = \chr(64 + (($pColumnIndex - 26) / 676)) .
                    \chr(65 + ((($pColumnIndex - 26) % 676) / 26)) .
                    \chr(65 + $pColumnIndex % 26);
            }
        }
        return $_indexCache[$pColumnIndex];
    }

    /**
     * @brief 导入excel文件
     * @param $fileName string
     * @param $sheetName string
     * @param $headers array
     * @param $data array
     * @throws
     */
    public static function exportExcel($fileName,$sheetName,$headers,$data)
    {
        $excel = new \PHPExcel();
        $file_name = $fileName .'-'. date('Y-m-d') . '.xlsx';
        $sheet_num = 0;
        $excel->getActiveSheetIndex($sheet_num);
        $excel->getActiveSheet()->setTitle($sheetName);
        header('Content-type: text/xlsx');
        header('Content-Disposition: attachment;filename=' . $file_name . ' ');
        header('Cache-Control: max-age=0');

        //一个单元格一个单元格写入表头
        foreach ($headers as $index => $name) {
            $excel->getActiveSheet()->setCellValue(self::stringFromColumnIndex($index) . '1', $name);

        }

        //按单元格写入每行数据
        foreach ($data as $row_num => $row) {
            if (!\is_array($row)) {
                return;
            }
            $cell_num = 0;
            foreach ($row as $index => $name) {
                $excel->getActiveSheet()->setCellValue(self::stringFromColumnIndex($cell_num) . ($row_num + 2), $name);
                ++$cell_num;
            }
        }
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }

    /**
     * @brief 读excel2007
     * @param $pathName string
     * @param $map array
     * @return array
     * @throws
     */
    public static function readExcel($pathName, $map) :array
    {
        $pathName = iconv('UTF-8','GB2312',$pathName);
        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        $excel = $reader->load($pathName);
        $sheet = $excel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $rows = [];
        for ($row=2; $row<=$highestRow;$row++) {
            $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,Null,true,false);
            $ret = array_combine($map,$rowData[0]);
            $rows[] = $ret;
        }
        unlink($pathName);
        return $rows;
    }
}