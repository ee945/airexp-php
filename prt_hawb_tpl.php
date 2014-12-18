<?php
/*
 * 打印分单 显示页面
 * 本页显示分单数据无背景空白页
 * 预览分单制单完成前的最终效果（无背景）
 *
 */
include_once ("global.php");
$r=$db->Get_user_shell_check($uid, $shell);

if (isset($_GET[prthawbtpl])){
  $hprtsql = "select * from `exp_hawb` where `hawb`='".$_GET[prthawbtpl]."'";
  $hprtlist = $db->query($hprtsql);
  if ($db->db_num_rows($hlist)==0){
  	$db->Get_admin_alert("该分单尚未输入！");
    exit;
  }
  //从数据库中提取绝大部分需要打印的数据，部分需经过处理
  $row=$db->fetch_array($hprtlist);
  $tpl->assign("hawb",$row[hawb]);
  $tpl->assign("mawb",$row[mawb]);
  $tpl->assign("dest",$row[dest]);
  $flt=$row[fltno]." / ".date("d,M",strtotime($row[fltdate]));
  $tpl->assign("flt",$flt);
  $opdate = date("M. d, Y",strtotime($row[opdate]));
  $tpl->assign("prtdate",$opdate);
  $tpl->assign("num",$row[num]);
  $tpl->assign("gw",$row[gw]);
  $tpl->assign("cw",$row[cw]);
  $cbm = $row[cbm]." CBM";
  $tpl->assign("cbm",$cbm);
  $tpl->assign("depar",$row[depar]);
  $tpl->assign("desti",$row[desti]);
  $consignee = str_replace("\n","<br>",$row[consignee]);
  $tpl->assign("consignee",$consignee);
  $shipper = str_replace("\n","<br>",$row[shipper]);
  $tpl->assign("shipper",$shipper);
  $notify = str_replace("\n","<br>",$row[notify]);
  $tpl->assign("notify",$notify);
  $tpl->assign("curr",$row[curr]);
  $tpl->assign("package",$row[package]);
  $tpl->assign("rclass",$row[rclass]);
  $tpl->assign("special",$row[special]);
  $cgodescp = str_replace("\n","<br>",$row[cgodescp]);
  $tpl->assign("cgodescp",$cgodescp);

  $tpl->assign("sha","SHANGHAI");
  $tpl->assign("agent",$row[agentabbr]);
  $tpl->assign("nvd",$row[nvd]);
  $tpl->assign("kg","K.");

  //处理付费方式
  if($row[paymt]=="CP"){
    $paymtli.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C&nbsp;&nbsp;&nbsp;&nbsp;P&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $curr="USD";
    $farrange="FREIGHT COLLECT";
  }elseif($row[paymt]=="CC"){
    $paymtli.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C";
    $curr="USD";
    $farrange="FREIGHT COLLECT";
  }else{
    $paymtli.="P&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;P&nbsp;&nbsp;&nbsp;&nbsp;";
    $curr="USD";
    $farrange="FREIGHT PREPAID";
  }
  if($row[arranged]==0){
  	$farrange.=" AS ARRANGED";
  }

  $tpl->assign("paymtli",$paymtli);
  $tpl->assign("farrange",$farrange);

}

//打印位置部分 -- 开始
//3个打印基本常数（已调整，基本不用改变）
$showrate=3.6;  //显示比例
$hleft=5;  //整体左边距
$htop=7;   //整体上边距

//以下为每个数据单独指定左边距，括号中第一个数字为真实分单上测量出的距离（减去整体左边距），单位毫米，可自行测量调整
$depar1= (20+$hleft)*$showrate;
$desti1= (70+$hleft)*$showrate;
$mawb1= (115+$hleft)*$showrate;
$dest1= (5+$hleft)*$showrate;
$flt1= (20+$hleft)*$showrate;
$consignee1= (20+$hleft)*$showrate;
$notify1= (20+$hleft)*$showrate;
$shipper1= (20+$hleft)*$showrate;
$prtdate1= (128+$hleft)*$showrate;
$sha1= (162+$hleft)*$showrate;
$agent1= (110+$hleft)*$showrate;
$curr1= (5+$hleft)*$showrate;
$paymt1= (26+$hleft)*$showrate;
$nvd1= (55+$hleft)*$showrate;
$case1= (4+$hleft)*$showrate;
$num1= (7+$hleft)*$showrate;
$gw1= (18+$hleft)*$showrate;
$kg1= (33+$hleft)*$showrate;
$rclass1= (41+$hleft)*$showrate;
$cw1= (75+$hleft)*$showrate;
$special1= (10+$hleft)*$showrate;
$cgoname1= (130+$hleft)*$showrate;
$cgodescp1= (130+$hleft)*$showrate;
$cbm1= (150+$hleft)*$showrate;
$farrange1= (50+$hleft)*$showrate;
//以下为每个数据单独指定上边距，括号中第一个数字为真实分单上测量出的距离（减去整体上边距），单位毫米，可自行测量调整
$depar2= (9+$htop)*$showrate;
$desti2= (9+$htop)*$showrate;
$mawb2= (9+$htop)*$showrate;
$dest2= (30+$htop)*$showrate;
$flt2= (30+$htop)*$showrate;
$consignee2= (47+$htop)*$showrate;
$notify2= (75+$htop)*$showrate;
$shipper2= (112+$htop)*$showrate;
$prtdate2= (112+$htop)*$showrate;
$sha2= (112+$htop)*$showrate;
$agent2= (126+$htop)*$showrate;
$curr2= (145+$htop)*$showrate;
$paymt2= (145+$htop)*$showrate;
$nvd2= (145+$htop)*$showrate;
$case2= (162+$htop)*$showrate;
$num2= (170+$htop)*$showrate;
$gw2= (170+$htop)*$showrate;
$kg2= (170+$htop)*$showrate;
$rclass2= (170+$htop)*$showrate;
$cw2= (170+$htop)*$showrate;
$special2= (208+$htop)*$showrate;
$cgodescp2= (162+$htop)*$showrate;
$cbm2= (237+$htop)*$showrate;
$farrange2= (182+$htop)*$showrate;

//将左边距和上边距通过smarty传入html页面的css，以设置数据显示位置
$tpl->assign("depar1",$depar1);
$tpl->assign("desti1",$desti1);
$tpl->assign("mawb1",$mawb1);
$tpl->assign("dest1",$dest1);
$tpl->assign("flt1",$flt1);
$tpl->assign("consignee1",$consignee1);
$tpl->assign("notify1",$notify1);
$tpl->assign("shipper1",$shipper1);
$tpl->assign("prtdate1",$prtdate1);
$tpl->assign("sha1",$sha1);
$tpl->assign("agent1",$agent1);
$tpl->assign("curr1",$curr1);
$tpl->assign("paymt1",$paymt1);
$tpl->assign("nvd1",$nvd1);
$tpl->assign("case1",$case1);
$tpl->assign("num1",$num1);
$tpl->assign("gw1",$gw1);
$tpl->assign("kg1",$kg1);
$tpl->assign("rclass1",$rclass1);
$tpl->assign("cw1",$cw1);
$tpl->assign("special1",$special1);
$tpl->assign("cgoname1",$cgoname1);
$tpl->assign("cgodescp1",$cgodescp1);
$tpl->assign("cbm1",$cbm1);
$tpl->assign("farrange1",$farrange1);

$tpl->assign("depar2",$depar2);
$tpl->assign("desti2",$desti2);
$tpl->assign("mawb2",$mawb2);
$tpl->assign("dest2",$dest2);
$tpl->assign("flt2",$flt2);
$tpl->assign("consignee2",$consignee2);
$tpl->assign("notify2",$notify2);
$tpl->assign("shipper2",$shipper2);
$tpl->assign("prtdate2",$prtdate2);
$tpl->assign("sha2",$sha2);
$tpl->assign("agent2",$agent2);
$tpl->assign("curr2",$curr2);
$tpl->assign("paymt2",$paymt2);
$tpl->assign("nvd2",$nvd2);
$tpl->assign("case2",$case2);
$tpl->assign("num2",$num2);
$tpl->assign("gw2",$gw2);
$tpl->assign("kg2",$kg2);
$tpl->assign("rclass2",$rclass2);
$tpl->assign("cw2",$cw2);
$tpl->assign("special2",$special2);
$tpl->assign("cgoname2",$cgoname2);
$tpl->assign("cgodescp2",$cgodescp2);
$tpl->assign("cbm2",$cbm2);
$tpl->assign("farrange2",$farrange2);

//打印位置部分 -- 结束

$tpl->display("prt_hawb_tpl.html");
?>