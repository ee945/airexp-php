<?php
/*
 * 货源统计
 * 按货源合并汇总按月统计货量信息
 *
 */
include_once("global.php");

$db->Get_user_shell_check($uid, $shell);
$tpl->assign("position","统计报表 &gt; 货源统计");

//判断访问权限：统计报表
if(ifpermit($_SESSION[expgrade],5)==0){
    $db->Get_admin_alert("没有报表访问权限!");
    exit;
}

$firstday = date('Y-m-d', mktime(0,0,0,date('n'),1,date('Y')));  //本月第一天
$lastday = date('Y-m-d', mktime(0,0,0,date('n'),date('t'),date('Y')));  //本月最后一天
$thisyear = date('Y');  //当前年份
$thismonth = date('m');  //当前月份
$nextmonth = $thismonth+1;  //下月
//sql查询语句，从分单数据表中查询并汇总数据
$filt_sql="select `forward` as `forward`,sum(`sumgw`) as `sumgw`,sum(`sumcw`) as `sumcw`,sum(`sumcbm`) as `sumcbm`,count(`hawb`) as `sumhawb`,count(distinct `mawb`) as `summawb`,`seller` as `seller` from `exp_v_qty` where `fltdate`!=''";
//通过查询表单附加查询条件
if($_POST[thismonth]!="")
{
    $filt_sql.=" && fltdate>='".$_POST[thisyear].".".$_POST[thismonth].".01 00:00:00' &&  fltdate<'".$_POST[thisyear].".".($_POST[thismonth]+1).".01 00:00:00'";
    $thismonth=$_POST[thismonth];
}else{  //默认统计本月货量
    $filt_sql.=" && fltdate>='".$firstday." 00:00:00' && fltdate<='".$lastday." 23:59:59'";
}
if($_POST[s_dest]!=""){  //若目的港不为空，附加查询条件：匹配目的港
    $filt_sql.=" && `dest`like '%".$_POST[s_dest]."%'";
    $s_dest = $_POST[s_dest];
}

if($_POST[s_carrier]!=""){  //若承运人不为空，附加查询条件：匹配承运人
    $filt_sql.=" && `carrier`like '%".$_POST[s_carrier]."%'";
    $s_carrier = $_POST[s_carrier];
}

$limit_sql=$filt_sql." group by `forward` order by `sumgw` desc";  //按货源汇总，并按货量从大到小排序
$mlist=$db->query($limit_sql);
while ($row = $db->fetch_array($mlist)){
  $m_list[] = array(
    "sumgw"=>$row[sumgw],
    "sumcw"=>$row[sumcw],
    "sumcbm"=>round($row[sumcbm],2),
    "sumhawb"=>$row[sumhawb],
    "summawb"=>$row[summawb],
    "forward"=>$row[forward]);  //循环输出各货源本月实际重量，收费重量，体积，分单票数，总单票数
    $allgw+=$row[sumgw];
    $allcw+=$row[sumcw];
    $allcbm+=$row[sumcbm];
    $allhawb+=$row[sumhawb];
    $allmawb+=$row[summawb];  //循环累积计算出总数，统计出总计数
    $forward=$row[forward];
}

//输出总计数结果
$tpl->assign("forward",$forward);
$tpl->assign("allgw",$allgw);
$tpl->assign("allcw",$allcw);
$tpl->assign("allcbm",$allcbm);
$tpl->assign("allhawb",$allhawb);
$tpl->assign("allmawb",$allmawb);

$tpl->assign("firstday",$firstday);
$tpl->assign("lastday",$lastday);

//输出上一次查询条件
if($_POST[thisyear]){
    $tpl->assign("thisyear",$_POST[thisyear]);
}else{
    $tpl->assign("thisyear",$thisyear);
}
if($_POST[thismonth]){
	$tpl->assign("thismonth",$_POST[thismonth]);
}else{
	$tpl->assign("thismonth",$thismonth);
}
$tpl->assign("s_dest",$s_dest);
$tpl->assign("s_forward",$s_forward);
$tpl->assign("s_carrier",$s_carrier);

$tpl->assign("m_list",$m_list);
$tpl->display("rpt_hyqty.html");


?>
