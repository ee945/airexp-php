<?php
/*
 * 货量统计
 * 按日期范围统计货量信息
 *
 */
include_once("global.php");

$db->Get_user_shell_check($uid, $shell);
$tpl->assign("position","统计报表 &gt; 货量统计");

//判断访问权限：统计报表
if(ifpermit($_SESSION[expgrade],5)==0){
    $db->Get_admin_alert("没有报表访问权限!");
    exit;
}


$firstday = date('Y-m-d', mktime(0,0,0,date('n'),1,date('Y')));  //本月第一天
$lastday = date('Y-m-d', mktime(0,0,0,date('n'),date('t'),date('Y')));  //本月最后一天
$thismonth = date("Y.m",time());  //当前月份
//sql查询语句，从分单数据表中查询并汇总数据
$filt_sql="select `fltdate`,sum(`gw`) as `sumgw`,sum(`cw`) as `sumcw`,sum(`cbm`) as `sumcbm`,count(`hawb`) as `sumhawb`,count(DISTINCT `mawb`) as `summawb` from `exp_hawb` where `hawb`!=''";
//通过查询表单附加查询条件
if($_POST[s_fltdate_s]!=""){
    $filt_sql.=" && fltdate>='".$_POST[s_fltdate_s]." 00:00:00'";
    $s_fltdate_s=$_POST[s_fltdate_s];
}else{  //若开始日期为空，默认开始日期为本月第一天
	$filt_sql.=" && fltdate>='".$firstday." 00:00:00'";
    $s_fltdate_s=$firstday;
}
if($_POST[s_fltdate_e]!=""){
    $filt_sql.=" && fltdate<='".$_POST[s_fltdate_e]." 23:59:59'";
    $s_fltdate_e=$_POST[s_fltdate_e];
}else{  //若结束日期为空，默认开始日期为本月第一天
	$filt_sql.=" && fltdate<='".$lastday." 23:59:59'";
    $s_fltdate_e=$lastday;
}
if($_POST[s_dest]!=""){  //若目的港不为空，附加查询条件：匹配目的港
    $filt_sql.=" && `dest`like '%".$_POST[s_dest]."%'";
    $s_dest = $_POST[s_dest];
}
if($_POST[s_forward]!=""){  //若货源不为空，附加查询条件：匹配货源
    $filt_sql.=" && `forward`like '%".$_POST[s_forward]."%'";
    $s_forward = $_POST[s_forward];
}
if($_POST[s_carrier]!=""){  //若承运人不为空，附加查询条件：匹配承运人
    $filt_sql.=" && `carrier`like '%".$_POST[s_carrier]."%'";
    $s_carrier = $_POST[s_carrier];
}
$limit_sql=$filt_sql." group by `fltdate` order by `fltdate` asc";  //按日期汇总
$mlist=$db->query($limit_sql);

while ($row = $db->fetch_array($mlist)){
  $m_list[] = array(
    "fltdate"=>date('m-d',strtotime($row[fltdate])),
    "sumgw"=>$row[sumgw],
    "sumcw"=>$row[sumcw],
    "sumcbm"=>round($row[sumcbm],2),
    "sumhawb"=>$row[sumhawb],
    "summawb"=>$row[summawb]);  //循环输出每日实际重量，收费重量，体积，分单票数，总单票数
  $allgw+=$row[sumgw];
  $allcw+=$row[sumcw];
  $allcbm+=$row[sumcbm];
  $allhawb+=$row[sumhawb];
  $allmawb+=$row[summawb];  //循环累积计算出总数，统计出总计数
}
  $allcbm=round($allcbm,2);

//输出总计数结果
$tpl->assign("allgw",$allgw);
$tpl->assign("allcw",$allcw);
$tpl->assign("allcbm",$allcbm);
$tpl->assign("allhawb",$allhawb);
$tpl->assign("allmawb",$allmawb);
//输出上一次查询条件
$tpl->assign("s_fltdate_s",$s_fltdate_s);
$tpl->assign("s_fltdate_e",$s_fltdate_e);
$tpl->assign("s_dest",$s_dest);
$tpl->assign("s_forward",$s_forward);
$tpl->assign("s_carrier",$s_carrier);

$tpl->assign("m_list",$m_list);
$tpl->display("rpt_dailyqty.html");


?>
