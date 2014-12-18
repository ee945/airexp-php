<?php
/*
 * 分单基础信息修改页面
 *
 */
include_once("global.php");

$db->Get_user_shell_check($uid, $shell);

//面包屑导航信息
$tpl->assign("position","分单管理 &gt; 分单修改");

$today=date("Y-n-j",time());
$tomorrow = date("Y-n-j",mktime(0,0,0,date("m",time()),date("d",time())+1,date("Y",time())));
$tpl->assign("today",$today);
$tpl->assign("tomorrow",$tomorrow);

//显示已存在的分单内容
if (isset($_GET[hawbno])){
  $list = $db->query("select * from `exp_hawb` where `hawb`='".$_GET[hawbno]."'");
  $row=$db->fetch_array($list);
  $tpl->assign("hawb",$row[hawb]);
  $tpl->assign("mawb",$row[mawb]);
  $tpl->assign("opdate",$row[opdate]);
  $tpl->assign("dest",$row[dest]);
  $tpl->assign("desti",$row[desti]);
  $tpl->assign("fltno",$row[fltno]);
  $tpl->assign("fltdate",$row[fltdate]);
  $tpl->assign("forward",$row[forward]);
  $tpl->assign("seller",$row[seller]);
  $tpl->assign("factory",$row[factory]);
  $tpl->assign("carrier",$row[carrier]);
  $tpl->assign("carriername",$row[carriername]);
  $tpl->assign("paymt",$row[paymt]);

  $tpl->assign("arranged",$row[arranged]);
  if($row[arranged]==0){
    $arranged0="checked=\"checked\"";
    $tpl->assign("arranged0",$arranged0);
  }
  if($row[arranged]==1){
    $arranged1="checked=\"checked\"";
    $tpl->assign("arranged1",$arranged1);
  }

  $tpl->assign("num",$row[num]);
  $tpl->assign("gw",$row[gw]);
  $tpl->assign("cw",$row[cw]);
  $tpl->assign("cbm",$row[cbm]);
  $tpl->assign("remark",$row[remark]);
}

//修改分单部分
if (isset($_POST[edithawb])){

  $hawb = addslashes($_POST[hawb]);
  //原则上不允许修改分单号，以免造成意外的错误
  //此处为防止用特殊方法突破表单限制，对分单号的修改也做了处理
  if($hawb!=$row[hawb]){
    $query = $db->query("select * from `exp_hawb` where `hawb` = '$hawb'");
    $count = $db->db_num_rows($query);
    if(!empty($count)){ ?>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <script type="text/javascript">
      alert("分单号已存在！");
      javascript:history.go(-1);
      </script>
      <?php
      exit;
    }
  }
  $mawb = addslashes($_POST[mawb]);
  $opdate = addslashes($_POST[opdate]);
  $dest = strtoupper(addslashes($_POST[dest]));
  $desti = strtoupper(addslashes($_POST[desti]));
  $fltno = strtoupper(addslashes($_POST[fltno]));
  $fltdate = addslashes($_POST[fltdate]);
  $forward = strtoupper(addslashes($_POST[forward]));
  $seller = strtoupper(addslashes($_POST[seller]));
  //若货源的对应揽货人（销售人）未指定，则在提交时默认为SCS，以便之后可查询修改
  if($seller=="")$seller="SCS";
  $factory = strtoupper(addslashes($_POST[factory]));
  $carrier = strtoupper(addslashes($_POST[carrier]));
  $carriername = strtoupper(addslashes($_POST[carriername]));
  $paymt = strtoupper(addslashes($_POST[paymt]));
  $arranged = addslashes($_POST[arranged]);
  $num = addslashes($_POST[num])+0;
  $gw = addslashes($_POST[gw])+0;
  $cw = addslashes($_POST[cw])+0;
  $cbm = addslashes($_POST[cbm])+0;
  $remark = addslashes($_POST[remark]);

  $db->query("UPDATE  `exp_hawb` SET  " .
    "`mawb` = '$mawb'," .
    "`opdate` = '$opdate'," .
    "`dest` = '$dest'," .
    "`desti` = '$desti'," .
    "`fltno` = '$fltno'," .
    "`fltdate` = '$fltdate'," .
    "`forward` = '$forward'," .
    "`seller` = '$seller'," .
    "`factory` = '$factory'," .
    "`carrier` = '$carrier'," .
    "`carriername` = '$carriername'," .
    "`paymt` = '$paymt'," .
    "`arranged` = '$arranged'," .
    "`num` = '$num'," .
    "`gw` = '$gw'," .
    "`cw` = '$cw'," .
    "`cbm` = '$cbm'," .
    "`remark` = '$remark' " .
    "WHERE  `exp_hawb`.`hawb` ='$_GET[hawbno]' LIMIT 1 ;");
  $db->Get_admin_msg("hawb_edit.php?hawbno=$_GET[hawbno]","更新成功！");
}

$tpl->display("hawb_edit.html");

?>