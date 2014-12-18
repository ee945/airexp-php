<?php
/*
 * 目的港 机场库
 * 1. 该库用于制单，输入机场3字代码，直接跳出对应机场全称
 * 2. 机场库同时含有3个目的地TACT等级价：M N Q
 * 3. 本页与地址库为相同架构，注释可完全参照address.php
 */
 
include_once("global.php");
include_once ("common/page.class.php");

$db->Get_user_shell_check($uid, $shell);

$tpl->assign("position","<a href=\"airport.php\">目的港管理</a>");
$tpl->assign("add","0");
$tpl->assign("update","0");
$tpl->assign("list","1");

if (isset($_GET[add])){
  $tpl->assign("add","1");
  $tpl->assign("list","0");
}
if (isset($_POST[search])){
  $tpl->assign("add","0");
  $tpl->assign("list","1");
}

//显示已存在的客户信息
if (isset($_GET[port])){
  $tpl->assign("update","1");
  $tpl->assign("list","0");

  $list = $db->query("select * from `exp_port` where `code`='".$_GET[port]."'");
  $row=$db->fetch_array($list);
  $tpl->assign("code",$row[code]);
  $tpl->assign("name",$row[name]);
  $tpl->assign("zone",$row[zone]);
  $tpl->assign("m",$row[m]);
  $tpl->assign("n",$row[n]);
  $tpl->assign("q",$row[q]);
}

//添加
if(isset($_POST[add])){
  $code = strtoupper(addslashes($_POST[code]));

  $query = $db->query("select * from `exp_port` where `code` = '$code'");
  $count = $db->db_num_rows($query);
  if(!empty($count)){ ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript">
    alert("目的港已存在！");
    javascript:history.go(-1);
    </script>
    <?php
  }else{
  $name = strtoupper(addslashes($_POST[name]));
  $zone = strtoupper(addslashes($_POST[zone]));
  $m = addslashes($_POST[m])+0;
  $n = addslashes($_POST[n])+0;
  $q = addslashes($_POST[q])+0;

  $db->query("insert into `exp_port` (`id`,`code`,`name`,`zone`,`m`,`n`,`q`) " .
        "value(NULL,'$code','$name','$zone','$m','$n','$q')");
  $db->Get_admin_msg("airport.php","添加成功！");
  }
}

//修改
if (isset($_POST[update])){
  $code = addslashes($_POST[code]);
  if($code!=$row[code]){
    $query = $db->query("select * from `exp_port` where `code` = '$code'");
    $count = $db->db_num_rows($query);
    if(!empty($count)){ ?>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <script type="text/javascript">
      alert("目的港代码已存在！");
      javascript:history.go(-1);
      </script>
      <?php
      exit;
    }
  }
  $code = strtoupper(addslashes($_POST[code]));
  $name = strtoupper(addslashes($_POST[name]));
  $zone = strtoupper(addslashes($_POST[zone]));
  $m = addslashes($_POST[m])+0;
  $n = addslashes($_POST[n])+0;
  $q = addslashes($_POST[q])+0;

  $db->query("UPDATE  `exp_port` SET  " .
    "`code` = '$code'," .
    "`name` = '$name'," .
    "`zone` = '$zone'," .
    "`m` = '$m'," .
    "`n` = '$n'," .
    "`q` = '$q'" .
    "WHERE  `exp_port`.`code` ='$_POST[code]' LIMIT 1 ;");
  $db->Get_admin_msg("airport.php","更新成功！");
}

//删除
if(ifpermit($_SESSION[expgrade],4)!==0){
    $tpl->assign("delbtn",1);
}
if ($_GET[delid]){
  if(ifpermit($_SESSION[expgrade],4)==0){
    $db->Get_admin_alert("没有操作权限!");
    exit;
  }
  $db->query("DELETE FROM `exp_port` WHERE `id` = '$_GET[delid]' LIMIT 1");
  $db->Get_admin_msg("airport.php","删除目的港成功！");
}

$filt_sql="select * from `exp_port` where `id`!=''";
if($_POST[s_code]!=""){$filt_sql.=" && `code`like '%".$_POST[s_code]."%'";}
if($_POST[s_name]!=""){$filt_sql.=" && `name`like '%".$_POST[s_name]."%'";}
if($_POST[s_zone]!=""){$filt_sql.=" && `zone`like '%".$_POST[s_zone]."%'";}
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
  $h_list[] = array(
    "id"=>$row[id],
    "code"=>$row[code],
    "name"=>$row[name],
    "zone"=>$row[zone],
    "m"=>$row[m],
    "n"=>$row[n],
    "q"=>$row[q]);
}

$tpl->assign("h_list",$h_list);
$tpl->display("airport.html");

?>