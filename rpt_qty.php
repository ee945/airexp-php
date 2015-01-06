<?php
/*
 * 按月统计，显示每日货量
 * 按日期合并汇总统计一个月货量信息
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
$thisyear = date('Y');  //当前年份
$thismonth = date('m');  //当前月份
//sql查询语句，从分单数据表中查询并汇总数据
$filt_sql="select `fltdate`,sum(`sumgw`) as `sumgw`,sum(`sumcw`) as `sumcw`,sum(`sumcbm`) as `sumcbm`,count(`hawb`) as `sumhawb`,count(distinct `mawb`) as `summawb`,`forward` as `forward`,`carrier` as `carrier` from `exp_v_qty` where `fltdate`!=''";
//通过查询表单附加查询条件
if($_POST[thismonth]!="")
{
    if($_POST[thismonth]!=12){
        $fltdate_end=$_POST[thisyear].".".($_POST[thismonth]+1);
    }else{
		$nextyear=$_POST[thisyear]+1;
        $fltdate_end=$nextyear.".01";
    }
	$thisyear=$_POST[thisyear];
    $filt_sql.=" && fltdate>='".$_POST[thisyear].".".$_POST[thismonth].".01 00:00:00' &&  fltdate<'".$fltdate_end.".01 00:00:00'";
    $thismonth=$_POST[thismonth];
}else{  //默认统计本月货量
    $filt_sql.=" && fltdate>='".$firstday." 00:00:00' && fltdate<='".$lastday." 23:59:59'";
}
if($_POST[s_dest]!=""){  //若目的港不为空，附加查询条件：匹配目的港
    $filt_sql.=" && `dest`like '%".$_POST[s_dest]."%'";
    $s_dest = $_POST[s_dest];
}
if($_POST[s_forward]!=""){  //若货源不为空，附加查询条件：匹配货源
    $filt_sql.=" && `forward`like '%".$_POST[s_forward]."%'";
    $s_forward = $_POST[s_forward];
}
if($_POST[s_seller]!=""){  //若揽货人不为空，附加查询条件：匹配揽货人
    $filt_sql.=" && `seller`like '%".$_POST[s_seller]."%'";
    $s_seller = $_POST[s_seller];
}
if($_POST[s_carrier]!=""){  //若承运人不为空，附加查询条件：匹配承运人
    $filt_sql.=" && `carrier`like '%".$_POST[s_carrier]."%'";
    $s_carrier = $_POST[s_carrier];
}

//循环从1到当前月最后一天
for($i=1;$i<=date('t', mktime(0,0,0,$thismonth,1,$thisyear));$i++){
    //按日期分组汇总查询
    $limit_sql=$filt_sql." && fltdate='".date('Y-m-d', mktime(0,0,0,$thismonth,$i,$thisyear))."'"." group by `fltdate` order by `fltdate` asc";
    $mlist=$db->query($limit_sql);
    while ($row = $db->fetch_array($mlist)){
      $m_list[] = array(
        "fltdate"=>date('m-d',strtotime($row[fltdate])),
        "sumgw"=>$row[sumgw],
        "sumcw"=>$row[sumcw],
        "sumcbm"=>round($row[sumcbm],2),
        "sumhawb"=>$row[sumhawb],
        "summawb"=>$row[summawb]);
    $allgw+=$row[sumgw];
    $allcw+=$row[sumcw];
    $allcbm+=$row[sumcbm];
    $allhawb+=$row[sumhawb];
    $allmawb+=$row[summawb];  //汇总累积单日货量，相加得出单日总和
    }
    $allgw=round($allgw,0);
    $allcw=round($allcw,0);
    $allhawb=round($allhawb,0);
    $allmawb=round($allmawb,0);
    $allcbm=round($allcbm,2);  //小数位或取整处理
    $all_list[] = array(
        "fltdate"=>date('m-d', mktime(0,0,0,$thismonth,$i,date('Y'))),
        "sumgw"=>$allgw,
        "sumcw"=>$allcw,
        "sumcbm"=>$allcbm,
        "sumhawb"=>$allhawb,
        "summawb"=>$allmawb);  //单日货量总和汇总成数组，以备模板显示列表
    $totalgw+=$allgw;
    $totalcw+=$allcw;
    $totalhawb+=$allhawb;
    $totalmawb+=$allmawb;
    $totalcbm+=$allcbm;  //累积计算当月全部货量总和
    $allgw=0;
    $allcw=0;
    $allcbm=0;
    $allhawb=0;
    $allmawb=0;  //单日货量总和统计完成后，清零，继续循环下一日总和

}

//输出总计数结果
$tpl->assign("allgw",$allgw);
$tpl->assign("allcw",$allcw);
$tpl->assign("allcbm",$allcbm);
$tpl->assign("allhawb",$allhawb);
$tpl->assign("allmawb",$allmawb);

$tpl->assign("totalgw",$totalgw);
$tpl->assign("totalcw",$totalcw);
$tpl->assign("totalcbm",$totalcbm);
$tpl->assign("totalhawb",$totalhawb);
$tpl->assign("totalmawb",$totalmawb);

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

$tpl->assign("all_list",$all_list);
$tpl->display("rpt_qty.html");


?>
