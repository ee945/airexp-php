<?php
/*
 *  打印总单输入页面
 *  
 * 该页面通过总单号查找对应的分单数据，显示出基本总单信息（日期，目的港，件重体等）
 * 未输入部分按条件默认预设特定值，全部输入完后保存更新该条总单数据
 * 按下方打印按钮，转向打印总单显示页面
 *
 */
include_once ("global.php");
$r=$db->Get_user_shell_check($uid, $shell);

if (isset($_POST[saveprtmawb])){

  $mprtlist = $db->query("select * from `exp_mawb` where `mawb`='".$_GET[prtmawb]."'");
  if ($db->db_num_rows($mprtlist)==0){
    //如果总单数据表中没有找到，则添加该票
    $mawb = addslashes($_POST[mawb]);  //总单号
    $oversea = strtoupper(addslashes($_POST[oversea]));  //海外代理代码
    $dest = strtoupper(addslashes($_POST[dest]));  //目的港
    $desti = strtoupper(addslashes($_POST[desti]));  //目的港全称
    $depa = strtoupper(addslashes($_POST[depa]));  //始发港（一般固定为PVG）
    $depar = strtoupper(addslashes($_POST[depar]));  //始发港全称（一般固定为SHANGHAI）
    $shipper = strtoupper(addslashes($_POST[shipper]));  //发货人地址（一般为本公司地址）
    $consignee = strtoupper(addslashes($_POST[consignee]));  //收货人地址（一般为海外代理地址）
    $agentabbr = strtoupper(addslashes($_POST[agentabbr]));  //代理缩写（公司三字代码/城市代码）
    $agentcode = strtoupper(addslashes($_POST[agentcode]));  //代理编号（公司在IATA注册编号）
    $agentaccount = strtoupper(addslashes($_POST[agentaccount]));  //代理账号（公司在注册账号）
    $carrier = strtoupper(addslashes($_POST[carrier]));  //承运人，承运航空公司2字代码（一般是航班号前缀）
    $fltno = strtoupper(addslashes($_POST[fltno]));  //航班号
    $fltdate = addslashes($_POST[fltdate]);  //航班日期
    $special = strtoupper(addslashes($_POST[special]));  //特别操作信息
    $package = strtoupper(addslashes($_POST[package]));  //包装
    $num = addslashes($_POST[num]);  //件数
    $gw = addslashes($_POST[gw]);  //实际重量
    $cw = addslashes($_POST[cw]);  //收费重量
    $cbm = addslashes($_POST[cbm]);  //体积
    $rclass = strtoupper(addslashes($_POST[rclass]));  //运费级别
    $up = addslashes($_POST[up]);  //运费单价
    $freight = addslashes($_POST[freight]);  //运费（运费单价*收费重量）
    $awn = strtoupper(addslashes($_POST[awn]));  //制单费名称 AWC，AIR WAYBILL COST
    $myn = strtoupper(addslashes($_POST[myn]));  //油费名称 MYC等
    $scn = strtoupper(addslashes($_POST[scn]));  //战争险名称 SCC等
    $myup = addslashes($_POST[myup]);  //油费单价
    $scup = addslashes($_POST[scup]);  //战险单价
    $aw = addslashes($_POST[aw]);  //制单费
    $my = addslashes($_POST[my]);  //油费总价
    $sc = addslashes($_POST[sc]);  //战险总价
    $other = addslashes($_POST[other]);  //杂费总价 （制单费+油费+战险）
    $amount = addslashes($_POST[amount]);  //总费用 （运费freight+杂费other）
    $cgodescp = addslashes($_POST[cgodescp]);  //货物品名+描述
    $signature = strtoupper(addslashes($_POST[signature]));  //代理签名，一般为代理缩写agentabbr
    $atplace = strtoupper(addslashes($_POST[atplace]));  //制单地，一般为SHANGHAI
    $operator = strtoupper(addslashes($_POST[operator]));  //操作员（制单人）
    $opdate = addslashes($_POST[opdate]);  //制单日期

    $sql="insert into `exp_mawb` (`id`,`mawb`,`oversea`,`dest`,`desti`,`depa`,`depar`,`shipper`,`consignee`,`agentabbr`,`agentcode`,`agentaccount`,`carrier`,`fltno`,`fltdate`,`special`,`package`,`num`,`gw`,`cw`,`cbm`,`rclass`,`up`,`freight`,`awn`,`myn`,`scn`,`myup`,`scup`,`aw`,`my`,`sc`,`other`,`amount`,`cgodescp`,`signature`,`atplace`,`operator`,`opdate`,`regtime`) " .
        "value(NULL,'$mawb','$oversea','$dest','$desti','$depa','$depar','$shipper','$consignee','$agentabbr','$agentcode','$agentaccount','$carrier','$fltno','$fltdate','$special','$package','$num','$gw','$cw','$cbm','$rclass','$up','$freight','$awn','$myn','$scn','$myup','$scup','$aw','$my','$sc','$other','$amount','$cgodescp','$signature','$atplace','$operator','$opdate','".date("Y-m-d H:i:s",time())."')";
    $db->query($sql);
    $db->Get_admin_insert("prt_mawb.php?prtmawb=$mawb","保存成功！");
  }else{
    //如果找到该票总单信息，说明已保存过，再次保存使用update更新
    $mawb = addslashes($_POST[mawb]);
    $oversea = strtoupper(addslashes($_POST[oversea]));
    $dest = strtoupper(addslashes($_POST[dest]));
    $desti = strtoupper(addslashes($_POST[desti]));
    $depa = strtoupper(addslashes($_POST[depa]));
    $depar = strtoupper(addslashes($_POST[depar]));
    $shipper = strtoupper(addslashes($_POST[shipper]));
    $consignee = strtoupper(addslashes($_POST[consignee]));
    $agentabbr = strtoupper(addslashes($_POST[agentabbr]));
    $agentcode = strtoupper(addslashes($_POST[agentcode]));
    $agentaccount = strtoupper(addslashes($_POST[agentaccount]));
    $carrier = strtoupper(addslashes($_POST[carrier]));
    $fltno = strtoupper(addslashes($_POST[fltno]));
    $fltdate = addslashes($_POST[fltdate]);
    $special = strtoupper(addslashes($_POST[special]));
    $package = strtoupper(addslashes($_POST[package]));
    $num = addslashes($_POST[num]);
    $gw = addslashes($_POST[gw]);
    $cw = addslashes($_POST[cw]);
    $cbm = addslashes($_POST[cbm]);
    $rclass = strtoupper(addslashes($_POST[rclass]));
    $up = addslashes($_POST[up]);
    $freight = addslashes($_POST[freight]);
    $awn = strtoupper(addslashes($_POST[awn]));
    $myn = strtoupper(addslashes($_POST[myn]));
    $scn = strtoupper(addslashes($_POST[scn]));
    $myup = addslashes($_POST[myup]);
    $scup = addslashes($_POST[scup]);
    $aw = addslashes($_POST[aw]);
    $my = addslashes($_POST[my]);
    $sc = addslashes($_POST[sc]);
    $other = addslashes($_POST[other]);
    $amount = addslashes($_POST[amount]);
    $cgodescp = addslashes($_POST[cgodescp]);
    $signature = strtoupper(addslashes($_POST[signature]));
    $atplace = strtoupper(addslashes($_POST[atplace]));
    $operator = strtoupper(addslashes($_POST[operator]));
    $opdate = addslashes($_POST[opdate]);

    $db->query("UPDATE `exp_mawb` SET  " .
      "`oversea` = '$oversea'," .
      "`dest` = '$dest'," .
      "`desti` = '$desti'," .
      "`depa` = '$depa'," .
      "`depar` = '$depar'," .
      "`shipper` = '$shipper'," .
      "`consignee` = '$consignee'," .
      "`agentabbr` = '$agentabbr'," .
      "`agentcode` = '$agentcode'," .
      "`agentaccount` = '$agentaccount'," .
      "`carrier` = '$carrier'," .
      "`fltno` = '$fltno'," .
      "`fltdate` = '$fltdate'," .
      "`special` = '$special'," .
      "`package` = '$package'," .
      "`num` = '$num'," .
      "`gw` = '$gw'," .
      "`cw` = '$cw'," .
      "`cbm` = '$cbm'," .
      "`rclass` = '$rclass'," .
      "`up` = '$up'," .
      "`freight` = '$freight'," .
      "`awn` = '$awn'," .
      "`myn` = '$myn'," .
      "`scn` = '$scn'," .
      "`myup` = '$myup'," .
      "`scup` = '$scup'," .
      "`aw` = '$aw'," .
      "`my` = '$my'," .
      "`sc` = '$sc'," .
      "`other` = '$other'," .
      "`amount` = '$amount'," .
      "`cgodescp` = '$cgodescp'," .
      "`signature` = '$signature'," .
      "`atplace` = '$atplace'," .
      "`operator` = '$operator'," .
      "`opdate` = '$opdate'" .
      "WHERE `mawb` ='$_POST[mawb]' LIMIT 1 ;");
    $db->Get_admin_msg("prt_mawb.php?prtmawb=$mawb","更新成功！");
  }
}

if (isset($_GET[prtmawb])){
  $getprtno=1;
  //在分单数据表中查找总单，未找到则退出
  $hlist = $db->query("select * from `exp_hawb` where `mawb`='".$_GET[prtmawb]."'");
  if ($db->db_num_rows($hlist)==0){
    $db->Get_admin_alert("没有找到这票总单！");
    exit;
  }
  //若在分单数据表中查到该总单，则继续在总单数据表中查找
  $mprtlist = $db->query("select * from `exp_mawb` where `mawb`='".$_GET[prtmawb]."'");
  if ($db->db_num_rows($hprtlist)==0){
    //若总单尚未保存，则汇总基本分单数据（件重体，航班号/日期，始发地目的地）
    $mlist = $db->query("select `mawb`,`dest`,`fltno`,`fltdate`,`opdate`,sum(`num`) as `num`,sum(`gw`) as `gw`,`depar`,`desti` from `exp_hawb` where `mawb`='".$_GET[prtmawb]."' group by `mawb` limit 1");
    $row=$db->fetch_array($mlist);
    //将分单汇总基本信息显示在总单页面，作为总单基本信息默认预设值
    $mawb=$row[mawb];
    $fltno=$row[fltno];
    $fltdate=$row[fltdate];
    $num=$row[num];
    $gw=$row[gw];
    $opdate=$row[opdate];
    if($row[dest]=="OSA"&&substr($_GET[prtmawb],0,3)=="999"){$dest="KIX";}else{$dest=$row[dest];}
    $depa="PVG";
    $desti=$row[desti];
    $operator=$_SESSION[expnick];
	
    //预设部分剩余总单数据
	//发货人，默认公司地址
    $shipper="SHANGHAI CARGOSTAR LTD.
ROOM 203,NO.58,JINWEN ROAD,
PUDONG AIRPORT,SHANGHAI,CHINA
POST CODE:201323,TEL:(021)58109361
FAX:(021)38100126 ATTN:MR.ZHANG";

    //代理简称
    $agentabbr="SCS/SHA";
    $signature="SCS/SHA";

    //IATA code
    $agentcode="08-30655";

    //Account No
    if(substr($mawb,0,3)=="999"){
      $agentaccount="083-5054-00";
    }else{
      $agentaccount="";
    }
    //始发港
    $depar="SHANGHAI";
    $atplace="SHANGHAI";

    //承运人，默认为航班号前缀
    $carrier=substr($fltno,0,2);
    
	//总单品名，一般为"混载货物"
    $cgodescp="CONSOL CARGO";

	//预设常用航空公司运单杂费名称
    if(substr($_GET[prtmawb],0,3)=="999"){
      $awn="AWC";
      $myn="MYC";
      $scn="MSC";
    }elseif(substr($_GET[prtmawb],0,3)=="205"){
      $awn="AWC";
      $myn="MYC";
      $scn="SCC";
    }else{
      $awn="AWC";
      $myn="MYC";
      $scn="MSC";
    }

    //默认制单费，f_aw在config页面可以配置，一般是50
    $aw=$f_aw;

  }else{
    //若找到总单数据，则从数据库中提取已输入数据
    $mlist = $db->query("select * from `exp_mawb` where `mawb`='".$_GET[prtmawb]."'");
    $row=$db->fetch_array($mlist);
      $oversea=$row[oversea];
      $mawb=$row[mawb];
      $dest=$row[dest];
      $desti=$row[desti];
      $depa=$row[depa];
      $depar=$row[depar];
      $shipper=$row[shipper];
      $consignee=$row[consignee];
      $agentabbr=$row[agentabbr];
      $agentcode=$row[agentcode];
      $agentaccount=$row[agentaccount];
      $carrier=$row[carrier];
      $fltno=$row[fltno];
      $fltdate=$row[fltdate];
      $special=$row[special];
      $package=$row[package];
      $num=$row[num];
      $gw=$row[gw];
      $cw=$row[cw];
      $cbm=$row[cbm];
      $rclass=$row[rclass];
      $up=$row[up];
      $freight=$row[freight];
      $awn=$row[awn];
      $myn=$row[myn];
      $scn=$row[scn];
      $myup=$row[myup];
      $scup=$row[scup];
      $aw=$row[aw];
      $my=$row[my];
      $sc=$row[sc];
      $other=$row[other];
      $amount=$row[amount];
      $cgodescp=$row[cgodescp];
      $signature=$row[signature];
      $atplace=$row[atplace];
      $operator=$row[operator];
      $opdate=$row[opdate];
    
  }
}
    //将各变量传入smarty，以显示内容
    $tpl->assign("mawb",$mawb);
    $tpl->assign("oversea",$oversea);
    $tpl->assign("dest",$dest);
    $tpl->assign("depa",$depa);
    $tpl->assign("desti",$desti);
    $tpl->assign("depar",$depar);
    $tpl->assign("shipper",$shipper);
    $tpl->assign("consignee",$consignee);
    $tpl->assign("agentabbr",$agentabbr);
    $tpl->assign("agentcode",$agentcode);
    $tpl->assign("agentaccount",$agentaccount);
    $tpl->assign("carrier",$carrier);
    $tpl->assign("fltno",$fltno);
    $tpl->assign("fltdate",$row[fltdate]);
    $tpl->assign("special",$special);
    $tpl->assign("package",$package);
    $tpl->assign("num",$num);
    $tpl->assign("gw",$gw);
    $tpl->assign("cw",$cw);
    $tpl->assign("cbm",$cbm);
    $tpl->assign("rclass",$rclass);
    $tpl->assign("up",$up);
    $tpl->assign("freight",$freight);
    $tpl->assign("awn",$awn);
    $tpl->assign("myn",$myn);
    $tpl->assign("scn",$scn);
    $tpl->assign("myup",$myup);
    $tpl->assign("scup",$scup);
    $tpl->assign("aw",$aw);
    $tpl->assign("my",$my);
    $tpl->assign("sc",$sc);
    $tpl->assign("other",$other);
    $tpl->assign("amount",$amount);
    $tpl->assign("cgodescp",$cgodescp);
    $tpl->assign("signature",$signature);
    $tpl->assign("atplace",$atplace);
    $tpl->assign("operator",$operator);
    $tpl->assign("opdate",$opdate);

$tpl->assign("getprtno",$getprtno);
$tpl->display("prt_mawb.html");
?>