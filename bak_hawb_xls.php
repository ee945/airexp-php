<?php
/*
 * 分单数据备份功能
 *
 * 查找出所有分单数据通过excel备份
 *
 * 本功能用phpexcel插件生产，详细语法可参照phpexcel官方插件手册：http://phpexcel.codeplex.com/
 *
 */
include_once ('global.php');
require_once 'common/PHPExcel.php';

$db->Get_user_shell_check($uid, $shell);

if(ifpermit($_SESSION[expgrade],8)==0){
    $db->Get_admin_alert("没有操作权限!");
    exit;
}

if(!isset($_SESSION[expshell])){ ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script type="text/javascript">
    alert("无权备份！请先登录！");
    javascript:history.go(-1);
    </script>
    <?php
    exit;
}

$query = mysql_query("select * from `exp_hawb` order by fltdate asc,hawb asc");

$sheet_title = "HAWB";
$todaynow=strval(date("YmdHis",time()));
$filename = "hawb-".$todaynow.".xls";

// 创建excel对象
$objPHPExcel = new PHPExcel();

// 文档属性
$objPHPExcel->getProperties()->setCreator("ee945")
                             ->setLastModifiedBy("ee945")
                             ->setTitle("Office 2003 XLS Backup Document")
                             ->setSubject("Office 2003 XLS Backup Document")
                             ->setDescription("Record Database Backup, generated using PHP classes.")
                             ->setKeywords("office 2003 openxml php")
                             ->setCategory("backup hawb");


// 表头
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '分运单号')
            ->setCellValue('B1', '总运单号')
            ->setCellValue('C1', '目的港')
            ->setCellValue('D1', '航班号')
            ->setCellValue('E1', '航班日期')
            ->setCellValue('F1', '件数')
            ->setCellValue('G1', '实际重量')
            ->setCellValue('H1', '收费重量')
            ->setCellValue('I1', '体积')
            ->setCellValue('J1', '付费方式')
            ->setCellValue('K1', '货源')
            ->setCellValue('L1', '生产单位')
            ->setCellValue('M1', '承运人代码')
            ->setCellValue('N1', '承运人名称')
            ->setCellValue('O1', '价格显示')
            ->setCellValue('P1', '操作日期');

$i=2;
while ($row = mysql_fetch_array($query)){
    $dateTimeNow = time();
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $row['hawb'],PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $row['mawb'])
                ->setCellValue('C'.$i, $row['dest'])
                ->setCellValue('D'.$i, $row['fltno'])
                ->setCellValue('E'.$i, $row['fltdate'])
                ->setCellValue('F'.$i, $row['num'])
                ->setCellValue('G'.$i, $row['gw'])
                ->setCellValue('H'.$i, $row['cw'])
                ->setCellValue('I'.$i, $row['cbm'])
                ->setCellValue('J'.$i, $row['paymt'])
                ->setCellValue('K'.$i, $row['forward'])
                ->setCellValue('L'.$i, $row['factory'])
                ->setCellValue('M'.$i, $row['carrier'])
                ->setCellValue('N'.$i, $row['carriername'])
                ->setCellValue('O'.$i, $row['arranged'])
                ->setCellValue('P'.$i, $row['opdate']);
    $i++;
}

// sheet名称
$objPHPExcel->getActiveSheet()->setTitle($sheet_title);


// 默认sheet
$objPHPExcel->setActiveSheetIndex(0);

//设置单元格宽度
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(11);

ob_clean();
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;

?>