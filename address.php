<?php
/*
 * 收发货人地址库
 * 1. 该地址库用于制单，输入地址代码，直接跳出对应地址
 * 2. 地址有分5类，分单收货人 分单发货人 分单通知人 总单收货人 总单发货人
 * 3. 输入代码时会自动判断当前表单属于哪类地址，并从库中调用对应类型的地址，不同类地址不会互相冲突
 *
 */
 
include_once("global.php");
include_once ("common/page.class.php");
$db->Get_user_shell_check($uid, $shell);

$tpl->assign("position","<a href=\"address.php\">地址管理</a>");  // 页面导航标题（面包屑breadcrumb）
/*
 * 地址库页面分3部分：列表页面list，添加页面add，修改页面update
 * 设置3个值add update list，用地址栏传$_GET方法传值，0为不显示，1为显示
 *
 */
$tpl->assign("add","0");  // 设置add标签默认为0
$tpl->assign("update","0");  //设置update标签默认为0
$tpl->assign("list","1");  //设置list标签默认为1

if (isset($_GET[add])){
  $tpl->assign("add","1");
  $tpl->assign("list","0");
}
if (isset($_POST[search])){
  $tpl->assign("add","0");
  $tpl->assign("list","1");
}


//客户地址修改页面update：显示已存在的客户信息
if (isset($_GET[addressid])){
  $tpl->assign("update","1");
  $tpl->assign("list","0");

  $list = $db->query("select * from `exp_address` where `id`='".$_GET[addressid]."'");
  $row=$db->fetch_array($list);
  $tpl->assign("id",$row[id]);
  $tpl->assign("code",$row[code]);
  $tpl->assign("name",$row[name]);
  $tpl->assign("addr",$row[addr]);
  if($row[cata]=="分单收货人"){
    $hcnee="checked=\"checked\"";
    $tpl->assign("hcnee",$hcnee);
  }
  if($row[cata]=="分单发货人"){
    $hshipper="checked=\"checked\"";
    $tpl->assign("hshipper",$hshipper);
  }
  if($row[cata]=="分单通知人"){
    $hnotify="checked=\"checked\"";
    $tpl->assign("hnotify",$hnotify);
  }
  if($row[cata]=="总单收货人"){
    $mcnee="checked=\"checked\"";
    $tpl->assign("mcnee",$mcnee);
  }
  if($row[cata]=="总单发货人"){
    $mshipper="checked=\"checked\"";
    $tpl->assign("mshipper",$mshipper);
  }
}

//添加页面
if(isset($_POST[add])){
  $code = strtoupper(addslashes($_POST[code]));
  $cata = addslashes($_POST[cata]);

  $query = $db->query("select * from `exp_address` where `code` = '$code' and `cata` = '$cata'");
  $count = $db->db_num_rows($query);
  // 地址库不允许代码重复
  if(!empty($count)){ ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript">
    alert("代码已存在！");
    javascript:history.go(-1);
    </script>
    <?php
  }else{
      $name = addslashes($_POST[name]);
      $addr = addslashes($_POST[addr]);
      
      $db->query("insert into `exp_address` (`id`,`code`,`name`,`cata`,`addr`) " .
            "value(NULL,'$code','$name','$cata','$addr')");
      $db->Get_admin_msg("address.php","添加成功！");
  }
}

//修改
if (isset($_POST[update])){
  $code = strtoupper(addslashes($_POST[code]));
  // 若修改地址代码，则先判断数据库中是否已存在
  if($code!=$row[code]){
    $query = $db->query("select * from `exp_address` where `code` = '$code'");
    $count = $db->db_num_rows($query);
    if(!empty($count)){ ?>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <script type="text/javascript">
      alert("代码已存在！");
      javascript:history.go(-1);
      </script>
      <?php
      exit;
    }
  }
  $name = addslashes($_POST[name]);
  $cata = addslashes($_POST[cata]);
  $addr = addslashes($_POST[addr]);

  $db->query("UPDATE  `exp_address` SET  " .
    "`code` = '$code'," .
    "`name` = '$name'," .
    "`cata` = '$cata'," .
    "`addr` = '$addr' " .
    "WHERE  `exp_address`.`id` ='$_POST[id]' LIMIT 1 ;");
  $db->Get_admin_msg("address.php","更新成功！");
}

//判断是否有删除权限，有则显示删除按钮
if(ifpermit($_SESSION[expgrade],4)!==0){
    $tpl->assign("delbtn",1);
}
if ($_GET[delid]){
  if(ifpermit($_SESSION[expgrade],4)==0){
    $db->Get_admin_alert("没有操作权限!");
    exit;
  }
  $db->query("DELETE FROM `exp_address` WHERE `id` = '$_GET[delid]' LIMIT 1");
  $db->Get_admin_msg("address.php","删除地址成功！");
}

// 默认显示的地址库列表
// 1. 搜索表单：按条件筛选地址
$filt_sql="select * from `exp_address` where `id`!=''";
if($_POST[s_code]!=""){$filt_sql.=" && `code`like '%".$_POST[s_code]."%'";}
if($_POST[s_name]!=""){$filt_sql.=" && `name`like '%".$_POST[s_name]."%'";}
if($_POST[s_cata]!=""){$filt_sql.=" && `cata`='".$_POST[s_cata]."'";}
if($_POST[s_addr]!=""){$filt_sql.=" && `addr`like '%".$_POST[s_addr]."%'";}
// 调用分页类
$listnum = $db->query($filt_sql);
$total = $db->db_num_rows($listnum);
if($_POST[displaypg]!=""){$_SESSION[exppg] = $_POST[displaypg];}
$displaypg=$_SESSION[exppg];
$tpl->assign("displaypg",$displaypg);
pageft($total,$displaypg);
if ($firstcount < 0){$firstcount = 0;}
$limit_sql=$filt_sql." order by `code` asc,`id` asc limit $firstcount,$displaypg";
$hlist = $db->query($limit_sql);
while ($row = $db->fetch_array($hlist)){
  $addr_arry=explode("\n",$row[addr]);
  $addr=$addr_arry[0];  // 列表中默认只显示地址第一行
  $h_list[] = array(
    "id"=>$row[id],
    "code"=>$row[code],
    "name"=>$row[name],
    "addr"=>$addr,
    "cata"=>$row[cata]);
}

$tpl->assign("h_list",$h_list);
$tpl->assign("pagenav", $pagenav);
$tpl->display("address.html");

?>
