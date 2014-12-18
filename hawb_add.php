<?php
/*
 * 分单基础信息添加录入页面
 *
 */
include_once("global.php");

$db->Get_user_shell_check($uid, $shell);

$tpl->assign("position","分单管理 &gt; 分单输入");

$today=date("Y-n-j",time());
$tomorrow = date("Y-n-j",mktime(0,0,0,date("m",time()),date("d",time())+1,date("Y",time())));
$tpl->assign("today",$today);  //用于表单默认操作日期，默认为今天
$tpl->assign("tomorrow",$tomorrow);  //用于表单默认航班日期，默认货物为明天航班

if (isset($_POST[addhawb])){
  $hawb = addslashes($_POST[hawb]);
  //判断分单号是否已存在（空运业务中，分单号不可重复）
  $query = $db->query("select * from `exp_hawb` where `hawb` = '$hawb'");
  $count = $db->db_num_rows($query);
  if(!empty($count)){ ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript">
    alert("分单号已存在！");
    javascript:history.go(-1);
    </script>
    <?php
  }else{

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

  $db->query("insert into `exp_hawb` (`id`,`hawb`,`mawb`,`opdate`,`dest`,`desti`,`fltno`,`fltdate`,`forward`,`seller`,`factory`,`carrier`,`carriername`,`paymt`,`arranged`,`num`,`gw`,`cw`,`cbm`,`remark`,`regtime`) " .
        "value(NULL,'$hawb','$mawb','$opdate','$dest','$desti','$fltno','$fltdate','$forward','$seller','$factory','$carrier','$carriername','$paymt','$arranged','$num','$gw','$cw','$cbm','$remark','".date("Y-m-d H:i:s",time())."')");
  $db->Get_admin_insert("hawb_edit.php?hawbno=$hawb","添加成功！");
  }
}

$tpl->display("hawb_add.html");

?>