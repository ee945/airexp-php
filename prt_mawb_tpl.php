<?php
/*
 * 打印总单 显示页面
 * 本页显示总单数据无背景空白页
 * 预览总单制单完成前的最终效果（无背景）
 *
 */
include_once ("global.php");
$r=$db->Get_user_shell_check($uid, $shell);

if (isset($_GET[prtmawbtpl])){
  $mprtsql = "select * from `exp_mawb` where `mawb`='".$_GET[prtmawbtpl]."'";
  $mprtlist = $db->query($mprtsql);
  if ($db->db_num_rows($mlist)==0){
  	$db->Get_admin_alert("该总单尚未保存！");
    exit;
  }
  //从数据库中提取绝大部分需要打印的数据，部分需经过处理
  $row=$db->fetch_array($mprtlist);
  $tpl->assign("mawb",$row[mawb]);
  $tpl->assign("dest",$row[dest]);
  $tpl->assign("desti",$row[desti]);
  $tpl->assign("depa",$row[depa]);
  $tpl->assign("depar",$row[depar]);
  $shipper = str_replace("\n","<br>",$row[shipper]);
  $tpl->assign("shipper",$shipper);
  $consignee = str_replace("\n","<br>",$row[consignee]);
  $tpl->assign("consignee",$consignee);
  $tpl->assign("agentabbr",$row[agentabbr]);
  $tpl->assign("agentcode",$row[agentcode]);
  $tpl->assign("agentaccount",$row[agentaccount]);
  $tpl->assign("carrier",$row[carrier]);
  $flt=$row[fltno]." / ".date("d,M",strtotime($row[fltdate]));
  $tpl->assign("flt",$flt);
  $tpl->assign("special",$row[special]);
  $tpl->assign("package",$row[package]);
  $tpl->assign("num",$row[num]);
  $tpl->assign("numb",$row[num]);
  $tpl->assign("gw",$row[gw]);
  $tpl->assign("gwb",$row[gw]);
  $tpl->assign("cw",$row[cw]);
  $cbm = $row[cbm]." CBM";
  $tpl->assign("cbm",$cbm);
  $tpl->assign("rclass",$row[rclass]);
  $tpl->assign("up",$row[up]);
  $tpl->assign("freight",$row[freight]);
  $tpl->assign("freightb",$row[freight]);
  $aw=$row[awn]." : ".$row[aw];
  $tpl->assign("aw",$aw);
  $my=$row[myn]." : ".$row[my];
  $tpl->assign("my",$my);
  $sc=$row[scn]." : ".$row[sc];
  $tpl->assign("sc",$sc);
  $tpl->assign("other",$row[other]);
  $tpl->assign("amount",$row[amount]);
  $cgodescp = str_replace("\n","<br>",$row[cgodescp]);
  $tpl->assign("cgodescp",$cgodescp);
  $tpl->assign("signature",$row[signature]);
  $tpl->assign("atplace",$row[atplace]);
  $tpl->assign("operator",$row[operator]);
  $opdate = date("M. d, Y",strtotime($row[opdate]));
  $tpl->assign("opdate",$opdate);

  $tpl->assign("pvg","PVG");
  $tpl->assign("curr","CNY");
  $tpl->assign("nvd","NVD.");
  $tpl->assign("kg","K.");
  $tpl->assign("pay","FREIGHT PREPAID");
  if(substr($row[mawb],0,3)=="205")$tpl->assign("scs","SCS");
  

}

//打印位置部分 -- 开始
//3个打印基本常数（已调整，基本不用改变）
$showrate=3.6;  //显示比例
$mleft=-2;  //整体左边距
$mtop=1;    //整体上边距

//以下为每个数据单独指定左边距，括号中第一个数字为真实总单上测量出的距离（减去整体左边距），单位毫米，可自行测量调整
$mawb1= (150+$mleft)*$showrate;
$dest1= (21+$mleft)*$showrate;
$desti1= (23+$mleft)*$showrate;
$depa1= (30+$mleft)*$showrate;
$depar1= (50+$mleft)*$showrate;
$shipper1= (25+$mleft)*$showrate;
$consignee1= (25+$mleft)*$showrate;
$agentabbr1= (25+$mleft)*$showrate;
$agentcode1= (25+$mleft)*$showrate;
$agentaccount1= (70+$mleft)*$showrate;
$carrier1= (35+$mleft)*$showrate;
$flt1= (65+$mleft)*$showrate;
$special1= (30+$mleft)*$showrate;
$package1= (21+$mleft)*$showrate;
$num1= (22+$mleft)*$showrate;
$numb1= (22+$mleft)*$showrate;
$gw1= (32+$mleft)*$showrate;
$gwb1= (32+$mleft)*$showrate;
$cw1= (82+$mleft)*$showrate;
$cbm1= (165+$mleft)*$showrate;
$rclass1= (51+$mleft)*$showrate;
$up1= (100+$mleft)*$showrate;
$freight1= (125+$mleft)*$showrate;
$freightb1= (30+$mleft)*$showrate;
$aw1= (95+$mleft)*$showrate;
$my1= (120+$mleft)*$showrate;
$sc1= (155+$mleft)*$showrate;
$other1= (30+$mleft)*$showrate;
$amount1= (30+$mleft)*$showrate;
$cgodescp1= (150+$mleft)*$showrate;
$signature1= (130+$mleft)*$showrate;
$atplace1= (135+$mleft)*$showrate;
$operator1= (175+$mleft)*$showrate;
$opdate1= (95+$mleft)*$showrate;
$pay1= (130+$mleft)*$showrate;
$kg1= (44+$mleft)*$showrate;
$curr1= (111+$mleft)*$showrate;
$wpp1= (125+$mleft)*$showrate;
$opp1= (135+$mleft)*$showrate;
$nvd1= (155+$mleft)*$showrate;
$scs1= (130+$mleft)*$showrate;

//以下为每个数据单独指定上边距，括号中第一个数字为真实总单上测量出的距离（减去整体上边距），单位毫米，可自行测量调整
$mawb2= (10+$mtop)*$showrate;
$dest2= (105+$mtop)*$showrate;
$desti2= (114+$mtop)*$showrate;
$depa2= (12+$mtop)*$showrate;
$depar2= (97+$mtop)*$showrate;
$shipper2= (21+$mtop)*$showrate;
$consignee2= (46+$mtop)*$showrate;
$agentabbr2= (75+$mtop)*$showrate;
$agentcode2= (88+$mtop)*$showrate;
$agentaccount2= (88+$mtop)*$showrate;
$carrier2= (105+$mtop)*$showrate;
$flt2= (114+$mtop)*$showrate;
$special2= (125+$mtop)*$showrate;
$package2= (145+$mtop)*$showrate;
$num2= (150+$mtop)*$showrate;
$numb2= (195+$mtop)*$showrate;
$gw2= (150+$mtop)*$showrate;
$gwb2= (195+$mtop)*$showrate;
$cw2= (150+$mtop)*$showrate;
$cbm2= (195+$mtop)*$showrate;
$rclass2= (150+$mtop)*$showrate;
$up2= (150+$mtop)*$showrate;
$freight2= (150+$mtop)*$showrate;
$freightb2= (206+$mtop)*$showrate;
$aw2= (210+$mtop)*$showrate;
$my2= (210+$mtop)*$showrate;
$sc2= (210+$mtop)*$showrate;
$other2= (239+$mtop)*$showrate;
$amount2= (255+$mtop)*$showrate;
$cgodescp2= (150+$mtop)*$showrate;
$signature2= (241+$mtop)*$showrate;
$atplace2= (258+$mtop)*$showrate;
$operator2= (258+$mtop)*$showrate;
$opdate2= (258+$mtop)*$showrate;
$pay2= (80+$mtop)*$showrate;
$kg2= (150+$mtop)*$showrate;
$curr2= (105+$mtop)*$showrate;
$wpp2= (105+$mtop)*$showrate;
$opp2= (105+$mtop)*$showrate;
$nvd2= (105+$mtop)*$showrate;
$scs2= (85+$mtop)*$showrate;

//将左边距和上边距通过smarty传入html页面的css，以设置数据显示位置

$tpl->assign("mawb1",$mawb1);
$tpl->assign("dest1",$dest1);
$tpl->assign("desti1",$desti1);
$tpl->assign("depa1",$depa1);
$tpl->assign("depar1",$depar1);
$tpl->assign("shipper1",$shipper1);
$tpl->assign("consignee1",$consignee1);
$tpl->assign("agentabbr1",$agentabbr1);
$tpl->assign("agentcode1",$agentcode1);
$tpl->assign("agentaccount1",$agentaccount1);
$tpl->assign("carrier1",$carrier1);
$tpl->assign("flt1",$flt1);
$tpl->assign("special1",$special1);
$tpl->assign("package1",$package1);
$tpl->assign("num1",$num1);
$tpl->assign("numb1",$numb1);
$tpl->assign("gw1",$gw1);
$tpl->assign("gwb1",$gwb1);
$tpl->assign("cw1",$cw1);
$tpl->assign("cbm1",$cbm1);
$tpl->assign("rclass1",$rclass1);
$tpl->assign("up1",$up1);
$tpl->assign("freight1",$freight1);
$tpl->assign("freightb1",$freightb1);
$tpl->assign("aw1",$aw1);
$tpl->assign("my1",$my1);
$tpl->assign("sc1",$sc1);
$tpl->assign("other1",$other1);
$tpl->assign("amount1",$amount1);
$tpl->assign("cgodescp1",$cgodescp1);
$tpl->assign("signature1",$signature1);
$tpl->assign("atplace1",$atplace1);
$tpl->assign("operator1",$operator1);
$tpl->assign("opdate1",$opdate1);
$tpl->assign("pay1",$pay1);
$tpl->assign("kg1",$kg1);
$tpl->assign("curr1",$curr1);
$tpl->assign("wpp1",$wpp1);
$tpl->assign("opp1",$opp1);
$tpl->assign("nvd1",$nvd1);
$tpl->assign("scs1",$scs1);

//上边距传值
$tpl->assign("mawb2",$mawb2);
$tpl->assign("dest2",$dest2);
$tpl->assign("desti2",$desti2);
$tpl->assign("depa2",$depa2);
$tpl->assign("depar2",$depar2);
$tpl->assign("shipper2",$shipper2);
$tpl->assign("consignee2",$consignee2);
$tpl->assign("agentabbr2",$agentabbr2);
$tpl->assign("agentcode2",$agentcode2);
$tpl->assign("agentaccount2",$agentaccount2);
$tpl->assign("carrier2",$carrier2);
$tpl->assign("flt2",$flt2);
$tpl->assign("special2",$special2);
$tpl->assign("package2",$package2);
$tpl->assign("num2",$num2);
$tpl->assign("numb2",$numb2);
$tpl->assign("gw2",$gw2);
$tpl->assign("gwb2",$gwb2);
$tpl->assign("cw2",$cw2);
$tpl->assign("cbm2",$cbm2);
$tpl->assign("rclass2",$rclass2);
$tpl->assign("up2",$up2);
$tpl->assign("freight2",$freight2);
$tpl->assign("freightb2",$freightb2);
$tpl->assign("aw2",$aw2);
$tpl->assign("my2",$my2);
$tpl->assign("sc2",$sc2);
$tpl->assign("other2",$other2);
$tpl->assign("amount2",$amount2);
$tpl->assign("cgodescp2",$cgodescp2);
$tpl->assign("signature2",$signature2);
$tpl->assign("atplace2",$atplace2);
$tpl->assign("operator2",$operator2);
$tpl->assign("opdate2",$opdate2);
$tpl->assign("pay2",$pay2);
$tpl->assign("kg2",$kg2);
$tpl->assign("curr2",$curr2);
$tpl->assign("wpp2",$wpp2);
$tpl->assign("opp2",$opp2);
$tpl->assign("nvd2",$nvd2);
$tpl->assign("scs2",$scs2);

//打印位置部分 -- 结束

$tpl->display("prt_mawb_tpl.html");
?>