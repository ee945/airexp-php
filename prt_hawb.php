<?php
/*
 *  打印分单输入页面
 *  
 * 该页面先显示已输入的基本分单信息（日期，目的港，件重体等）
 * 未输入部分按条件默认预设特定值，全部输入完后保存更新该条分单数据
 * 按下方打印按钮，转向打印分单显示页面
 *
 */
include_once ("global.php");
$r=$db->Get_user_shell_check($uid, $shell);

//保存分单
if (isset($_POST[saveprthawb])){

  $hprtlist = $db->query("select * from `exp_hawb` where `hawb`='".$_GET[prthawb]."'");
  if ($db->db_num_rows($hprtlist)==0){
    //如找不到分单号，说明尚未输入该分单基本信息，防止从地址栏直接保存运单
    $db->Get_admin_alert("该分单尚未输入！");
  }else{
    $hawb = addslashes($_POST[hawb]); //分单号
    $mawb = addslashes($_POST[mawb]); //总单号
    $opdate = addslashes($_POST[opdate]);  //操作日期
    $dest = strtoupper(addslashes($_POST[dest]));  //目的港
    $fltno = strtoupper(addslashes($_POST[fltno]));  //航班号
    $fltdate = addslashes($_POST[fltdate]); //航班日期
    $forward = strtoupper(addslashes($_POST[forward]));  //货源
    $factory = strtoupper(addslashes($_POST[factory]));  //生产单位(发货人)
    $carrier = strtoupper(addslashes($_POST[carrier]));  //承运人
    $paymt = strtoupper(addslashes($_POST[paymt]));  //付费方式
    $arranged = addslashes($_POST[arranged]);  //费用是否显示
    $num = addslashes($_POST[num])+0;  //件数
    $gw = addslashes($_POST[gw])+0;  //实际重量
    $cw = addslashes($_POST[cw])+0;  //收费重量
    $cbm = addslashes($_POST[cbm])+0;  //体积
    $remark = addslashes($_POST[remark]);  //备注

    $depar = strtoupper(addslashes($_POST[depar]));  //始发地全称（SHANGHAI）
    $desti = strtoupper(addslashes($_POST[desti]));  //目的港全称
    $consignee = strtoupper(addslashes($_POST[consignee]));  //收货人地址
    $notify = strtoupper(addslashes($_POST[notify]));  //通知人地址
    $shipper = strtoupper(addslashes($_POST[shipper]));  //发货人地址
    $curr = strtoupper(addslashes($_POST[curr]));  //币制
    $nvd = strtoupper(addslashes($_POST[nvd]));  //无申报价值 NO VALUE DECLARED
    $ncv = strtoupper(addslashes($_POST[ncv]));  //无商业价值 NO COMMERCIAL VALUE
    $package = strtoupper(addslashes($_POST[package]));  //包装
    $rclass = strtoupper(addslashes($_POST[rclass]));  //费率级别
    $special = strtoupper(addslashes($_POST[special]));  //特殊操作信息
    $cgodescp = addslashes($_POST[cgodescp]);  //品名栏（货物描述）
    $agentabbr = strtoupper(addslashes($_POST[agentabbr]));  //代理人缩写

    $db->query("UPDATE `exp_hawb` SET  " .
      "`num` = '$num'," .
      "`gw` = '$gw'," .
      "`cw` = '$cw'," .
      "`cbm` = '$cbm'," .
      "`depar` = '$depar'," .
      "`desti` = '$desti'," .
      "`fltno` = '$fltno'," .
      "`fltdate` = '$fltdate'," .
      "`consignee` = '$consignee'," .
      "`notify` = '$notify'," .
      "`shipper` = '$shipper'," .
      "`curr` = '$curr'," .
      "`nvd` = '$nvd'," .
      "`ncv` = '$ncv'," .
      "`package` = '$package'," .
      "`rclass` = '$rclass'," .
      "`special` = '$special'," .
      "`cgodescp` = '$cgodescp'," .
      "`agentabbr` = '$agentabbr'" .
      "WHERE `hawb` ='$_POST[hawb]' LIMIT 1 ;");
    $db->Get_admin_msg("prt_hawb.php?prthawb=$_POST[hawb]","更新成功！");
  }
}

if (isset($_GET[prthawb])){
  $getprtno=1;

  $hlist = $db->query("select * from `exp_hawb` where `hawb`='".$_GET[prthawb]."'");
  //进入打印分单页面前先判断该分单基本信息是否已输入
  if ($db->db_num_rows($hlist)==0){
    $db->Get_admin_alert("该分单尚未输入！");
    exit;
  }else{
	//调取基本分单信息
    $row=$db->fetch_array($hlist);
    $tpl->assign("hawb",$row[hawb]);
    $tpl->assign("mawb",$row[mawb]);
    $tpl->assign("opdate",$row[opdate]);
    $tpl->assign("dest",$row[dest]);
    $tpl->assign("desti",$row[desti]);
    $tpl->assign("fltno",$row[fltno]);
    $tpl->assign("fltdate",$row[fltdate]);
    $tpl->assign("num",$row[num]);
    $tpl->assign("gw",$row[gw]);
    $tpl->assign("cw",$row[cw]);
    $tpl->assign("cbm",$row[cbm]);
    $arranged=$row[arranged];
	
    /*
	 *  更新或输入其他分单打印信息
	 *  若未输入，则部分根据分单信息预设默认内容
	 *  若已输入，则从数据库提取已输入内容
	 *  
	 */
    if($row[depar]==""){ //若始发地未输
      $depar="SHANGHAI"; //始发地默认为SHANGHAI
    }else{
      $depar=$row[depar]; //否则从数据库调取已输入内容
    }
    $tpl->assign("depar",$depar);  //如无意外，本单元格永远为"SHANGHAI"

	//收货人地址
	$rmk=explode(",",$row[remark]); //此处为OMS客户专用设置，提取备注内容，按","分割为：发票种类,托盘数,箱数
    if($row[consignee]=="" and $row[factory]=="欧姆龙"){  //如果收货人地址未输，并且该票生产单位为欧姆龙
	    if($rmk[0]=="OT"){  //若发票种类为OT(此为台湾货发票)，则预设以下地址：
	          $consignee="OMRON TAIWAN ELECTRONICS INC.\n6TH FLOOR HOME YOUNG BLDG\nNO.363,FU-SHING NORTH ROAD\nTAIPEI,TAIWAN";
	    }elseif($rmk[0]=="AT"){  //若发票种类为AT:
            $consignee="OMRON ASO CO.,LTD.\n4429 MIYAJI,ICHINOMIYA-CHO\nASO-SHI,KUMAMOTO 869-2696 JAPAN";
        }elseif($rmk[0]=="AB"||$rmk[0]=="KS"||$rmk[0]=="S"){  //若发票种类为AB,KS,S:
            $consignee="OMRON CORPORATION\nSHIOKOJI HORIKAWA\nSHIMOGYO-KU\nKYOTO ";
        }else{ //若发票种类为其他欧姆龙发票（JP等），则留空，自行输入
	        $consignee="";
	    }
    }else{
	        $consignee=$row[consignee];
    }
    $tpl->assign("consignee",$consignee);

	//通知人地址，默认为SAME AS CONSIGNEE
    if($row[notify]==""){
      $notify="SAME AS CONSIGNEE";
    }else{
      $notify=$row[notify];
    }
    $tpl->assign("notify",$notify);

	//发货人地址
    if($row[shipper]=="" and $row[factory]=="欧姆龙"){  //如果发货人地址未输，并且该票生产单位为欧姆龙
	    if($rmk[0]=="S"){  //若发票种类为S(S1,S2)，则预设以下地址：
	        $shipper="OMRON (SHANGHAI) CO.,LTD.\nNO.789,JINJI RD.\nJINQIAO EXPORT AREA\nPUDONG,SHANGHAI CHINA\nT:86-21-50504535";
        }else{  //否则：
            $shipper="OMRON (SHANGHAI) CO.,LTD.\nNO.789,JINJI RD.\nJINQIAO EXPORT AREA\nPUDONG,SHANGHAI CHINA\nT:86-21-50509988";
	    }  //欧姆龙发货人地址基本是同一个，仅有电话区别
    }else{
	        $shipper=$row[shipper];
    }
    $tpl->assign("shipper",$shipper);

	//分单上付费方式位置有4格，将该CP/CC/PP转化4个单独的数据显示在分单对应位置上
	//同时，根据付费方式可判断出运费币制，C为到付USD，P为预付CNY
	//另外，paymtli原本用于格式化付费方式，输出到分单正确位置，改为4个独立字段处理后，在本页中已无作用
    $paymt=$row[paymt];
    if($paymt=="CC"){
      $paymtli.="&nbsp;&nbsp;C&nbsp;&nbsp;&nbsp;&nbsp;C";
      $curr="USD";
      $wtp="";
      $wtc="C";
      $otp="";
      $otc="C";
    }elseif($paymt=="CP"){
      $paymtli.="&nbsp;&nbsp;C&nbsp;&nbsp;P&nbsp;&nbsp;";
      $curr="USD";
      $wtp="";
      $wtc="C";
      $otp="P";
      $otc="";
    }elseif($paymt=="PP"){
      $paymtli.="&nbsp;P&nbsp;&nbsp;&nbsp;P&nbsp;&nbsp;";
      $curr="CNY";
      $wtp="P";
      $wtc="";
      $otp="P";
      $otc="";
    }elseif($paymt=="PC"){
      $paymtli.="&nbsp;P&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C";
      $curr="CNY";
      $wtp="P";
      $wtc="";
      $otp="";
      $otc="C";
    }else{
      $curr="ERROR"; //若付费方式是CC,PP,CP,PC以外的值，则币制报错，另外，PC(运费预付杂费到付)在实际工作中几乎用不到
    }
    if($row[curr]<>""){
        $curr = $row[curr];
    }
    $tpl->assign("curr",$curr);
    $tpl->assign("wtp",$wtp);
    $tpl->assign("wtc",$wtc);
    $tpl->assign("otp",$otp);
    $tpl->assign("otc",$otc);

	//根据业务特性，一般分单上需显示NVD.，不显示NCV.
    if($row[nvd]==""){
      $nvd="NVD.";
    }else{
      $nvd=$row[nvd];
    }
    $tpl->assign("nvd",$nvd);
    $tpl->assign("ncv",$row[ncv]);
	
	//为欧姆龙预设包装种类
    if($row[package]=="" and $row[factory]=="欧姆龙"){
      $package="CASE";
    }else{
      $package=$row[package];
    }
    $tpl->assign("package",$package);

	//为欧姆龙预设运费级别，其他客户需自行输入
    if($row[rclass]=="" and $row[factory]=="欧姆龙"){
	  if($row[cw]<8){
        $rclass="M";
	  }else if($row[cw]<45){
	    $rclass="N";
	  }else{
	    $rclass="Q";
	  }
    }else{
      $rclass=$row[rclass];
    }
    $tpl->assign("rclass",$rclass);
	
	//为欧姆龙预设特别操作信息
    if($row[special]=="" and $row[factory]=="欧姆龙"){
        if($rmk[1]>0)$special="&nbsp;&nbsp;NORMAL PALLET:".$rmk[1];  //托盘数 (根据备注获得)
        if($rmk[2]>0)$special.="&nbsp;&nbsp;CARTON:".$rmk[2];  //箱数
    }else{
        $special=$row[special];
    }
    $tpl->assign("special",$special);

	//为欧姆龙预设品名
    if($row[cgodescp]=="" and $row[factory]=="欧姆龙"){
      $cgodescp="OMRON ELECTRONIC GOODS\nI/NO:\n"; //欧姆龙货物一般品名固定为这个，后面I/NO需自行补输
    }else{
      $cgodescp=$row[cgodescp];
    }
    $tpl->assign("cgodescp",$cgodescp);

	//代理人（本公司）代码/缩写
    if($row[agentabbr]==""){
      $agentabbr="SCS/SHA";
    }else{
      $agentabbr=$row[agentabbr];
    }
    $tpl->assign("agentabbr",$agentabbr);
  }
}

$tpl->assign("getprtno",$getprtno);
$tpl->display("prt_hawb.html");
?>
