<?php
/*
 * 打印分运单清单功能（导出excel格式）
 *
 * 通过总单号查找出对应的所有分单数据，按特定格式生成excel表格，用于打印清单
 *
 * 本功能用phpexcel插件生产，详细语法可参照phpexcel官方插件手册：http://phpexcel.codeplex.com/
 *
 */
include_once("global.php");
require_once 'common/PHPExcel.php';
$r=$db->Get_user_shell_check($uid, $shell);

if(isset($_POST[manimawbxls])){
ob_clean();
$sheet_title = "清单打印";
$filename = "manifest-".$_POST[manimawbxls].".xls";

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("ee945")
                             ->setLastModifiedBy("ee945")
                             ->setTitle("Office 2003 XLS Backup Document")
                             ->setSubject("Office 2003 XLS Backup Document")
                             ->setDescription("Record Database Backup, generated using PHP classes.")
                             ->setKeywords("office 2003 openxml php")
                             ->setCategory("manifest");

$mlist = $db->query("select * from `exp_hawb` where `mawb`='".$_POST[manimawbxls]."'");
$mrow=$db->fetch_array($mlist);
$mcnee = $db->query("select * from `exp_mawb` where `mawb`='".$_POST[manimawbxls]."'");
if($db->db_num_rows($mcnee)>=1){
  $mcneerow=$db->fetch_array($mcnee);
  $mcnee_arry=explode("\n",$mcneerow[consignee]);
  $mconsignee=trim($mcnee_arry[0]);
}elseif($mrow[dest]=="TPE"){
  $mconsignee="UNIVERSAL LOGISTICS CO.,LTD.";
}elseif($mrow[dest]=="OSA"){
  $mconsignee="THE SUMITOMO WAREHOUSE CO.,LTD.";
}elseif($mrow[dest]=="HKG"){
  $mconsignee="SUMITOMO WAREHOUSE (HONG KONG) LIMITED";
}
$mawb=$mrow[mawb];
$dest=$mrow[dest];
$fltno=$mrow[fltno];
$fltdate=$mrow[fltdate];

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'HOUSE CARGO MANIFEST')
            ->setCellValue('A2', 'Air Freight Agent')
            ->setCellValue('D2', 'Master AWB No.')
            ->setCellValue('E2', 'Port of Discharge')
            ->setCellValue('G2', 'Total No.of Shipment')
            ->setCellValue('H2', 'Flight No.')
            ->setCellValue('I2', 'Date')

            ->setCellValue('A5', 'Hawb No.')
            ->setCellValue('B5', "No. of \nPkg")
            ->setCellValue('C5', "WT. in \nKilo(Lb)")
            ->setCellValue('D5', "Nature of Goods")
            ->setCellValue('E5', "Port of \nLading")
            ->setCellValue('F5', "Final \nDest")
            ->setCellValue('G5', 'Name & Address of Shipper')
            ->setCellValue('H5', 'Name & Address of Consignee')
            ->setCellValue('I5', "For Offical \nUse Only")

            ->mergeCells('A1:I1')
            ->mergeCells('A2:C2')
            ->mergeCells('E2:F2')
            ->mergeCells('A3:C3')
            ->mergeCells('E3:F3');

$i=6;
$hcount=0;
$amount_num=0;
$amount_gw=0;
$manlist = $db->query("select * from `exp_v_manifest` where `mawb`='".$_POST[manimawbxls]."' order by `hawb` asc");
while ($manrow = $db->fetch_array($manlist)){
    $hawb=$manrow[hawb];
    $num=$manrow[num];
    $gw=$manrow[gw];
    $cgodescp_arry=explode("\n",$manrow[cgodescp]);
    $cgodescp=trim(str_replace("\n"," ",$cgodescp_arry[0]));
    $depa="SHA";
    $dest=$manrow[dest];
    $shipper=$manrow[shipper];
    $consignee=$manrow[consignee];
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $hawb,PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $num)
                ->setCellValue('C'.$i, $gw)
                ->setCellValue('D'.$i, $cgodescp)
                ->setCellValue('E'.$i, $depa)
                ->setCellValue('F'.$i, $dest)
                ->setCellValue('G'.$i, $shipper)
                ->setCellValue('H'.$i, $consignee)
                ->setCellValue('I'.$i, " ");

    $i++;
    $hcount++;
    $amount_num=$amount_num+$num;
    $amount_gw=$amount_gw+$gw;
}

for($j=5;$j<$i;$j++){
    $rowa="A".$j;
    $rowb="B".$j;
    $rowc="C".$j;
    $rowd="D".$j;
    $rowe="E".$j;
    $rowf="F".$j;
    $rowg="G".$j;
    $rowh="H".$j;
    $rowi="I".$j;

$objPHPExcel->getActiveSheet()->getStyle("$rowa")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowa")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowa")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowa")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$rowb")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowb")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowb")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowb")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$rowc")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowc")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowc")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowc")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$rowd")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowd")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowd")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowd")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$rowe")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowe")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowe")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowe")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$rowf")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowf")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowf")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowf")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$rowg")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowg")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowg")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowg")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$rowh")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowh")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowh")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowh")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$rowi")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowi")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowi")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$rowi")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

}

$y=$i+1;
$a_total="A".$y;
$b_num="B".$y;
$c_gw="C".$y;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValueExplicit($a_total, "TOTAL:",PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue($b_num, $amount_num)
            ->setCellValue($c_gw, $amount_gw);

for($x=2;$x<=3;$x++){
    $row1a="A".$x;
    $row1b="B".$x;
    $row1c="C".$x;
    $row1d="D".$x;
    $row1e="E".$x;
    $row1f="F".$x;
    $row1g="G".$x;
    $row1h="H".$x;
    $row1i="I".$x;

$objPHPExcel->getActiveSheet()->getStyle("$row1a")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1a")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1a")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1a")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$row1b")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1b")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1b")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1b")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$row1c")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1c")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1c")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1c")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$row1d")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1d")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1d")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1d")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$row1e")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1e")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1e")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1e")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$row1f")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1f")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1f")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1f")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$row1g")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1g")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1g")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1g")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$row1h")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1h")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1h")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1h")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$objPHPExcel->getActiveSheet()->getStyle("$row1i")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1i")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1i")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("$row1i")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
}

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup:: ORIENTATION_LANDSCAPE);

$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.4);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.4);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.4);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', $mconsignee)
            ->setCellValue('D3', $mawb)
            ->setCellValue('E3', $dest)
            ->setCellValue('G3', $hcount)
            ->setCellValue('H3', $fltno)
            ->setCellValue('I3', $fltdate);

// sheet名称
$objPHPExcel->getActiveSheet()->setTitle($sheet_title);
// 默认sheet
$objPHPExcel->setActiveSheetIndex(0);

//设置单元格宽度
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(30);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Times New Roman');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setName('Times New Roman');
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setSize(8);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:I5')->getFont()->setName('Times New Roman');
$objPHPExcel->getActiveSheet()->getStyle('A5:I5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:I5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A6:I40')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A6:I40')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A6:I40')->getFont()->setSize(8);
$objPHPExcel->getActiveSheet()->getStyle('A6:I40')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A6:I40')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header ('Cache-Control: cache, must-revalidate');
header ('Pragma: public');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;

}

?>
