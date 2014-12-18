<?php
/*
 * 客户管理
 * 1. 该地址库用于制单，输入地址代码，直接跳出对应地址
 * 2. 客户有分3类，货源（托运人：二级代理、同行、直接客户等委托我司运货的单位），生产单位（货主：货物实际所有人，即分单的发货人），承运人（航空公司及提供舱位的同行）
 * 3. 输入代码时会自动判断当前表单属于哪类客户，并从库中调用对应类型的客户名称
 * 4. 本页与地址库为相同架构，注释可完全参照address.php
 *
 */
 
include_once("global.php");
include_once ("common/page.class.php");

$db->Get_user_shell_check($uid, $shell);

$tpl->assign("position","<a href=\"client.php\">客户管理</a>");
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
if (isset($_GET[clientid])){
  $tpl->assign("update","1");
  $tpl->assign("list","0");

  $list = $db->query("select * from `exp_client` where `id`='".$_GET[clientid]."'");
  $row=$db->fetch_array($list);
  $tpl->assign("id",$row[id]);
  $tpl->assign("code",$row[code]);
  $tpl->assign("name",$row[name]);
  if($row[cata]=="货源"){
    $forward="checked=\"checked\"";
    $tpl->assign("forward",$forward);
  }
  if($row[cata]=="生产单位"){
    $factory="checked=\"checked\"";
    $tpl->assign("factory",$factory);
  }
  if($row[cata]=="承运人"){
    $carrier="checked=\"checked\"";
    $tpl->assign("carrier",$carrier);
  }
}

//添加
if(isset($_POST[add])){
  $code = strtoupper(addslashes($_POST[code]));
  $cata = addslashes($_POST[cata]);

  $query = $db->query("select * from `exp_client` where `code` = '$code' and `cata` = '$cata'");
  $count = $db->db_num_rows($query);
  if(!empty($count)){ ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript">
    alert("代码已存在！");
    javascript:history.go(-1);
    </script>
    <?php
  }else{
  $name = addslashes($_POST[name]);

  $db->query("insert into `exp_client` (`id`,`code`,`name`,`cata`) " .
        "value(NULL,'$code','$name','$cata')");
  $db->Get_admin_msg("client.php","添加成功！");
  }
}

//修改
if (isset($_POST[update])){
  $code = strtoupper(addslashes($_POST[code]));
  $cata = addslashes($_POST[cata]);
  if($code!=$row[code] OR $cata!=$row[cata]){
    $query = $db->query("select * from `exp_client` where `code` = '$code' and `cata` = '$cata'");
    $count = $db->db_num_rows($query);
    if(!empty($count)){ ?>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <script type="text/javascript">
      alert("代码已存在！<?php echo $count;?>");
      javascript:history.go(-1);
      </script>
      <?php
      exit;
    }
  }
  $name = addslashes($_POST[name]);

  $db->query("UPDATE `exp_client` SET  " .
    "`code` = '$code'," .
    "`name` = '$name'," .
    "`cata` = '$cata'" .
    "WHERE  `exp_client`.`id` ='$_POST[id]' LIMIT 1 ;");
  if($cata=="承运人"){
    $db->query("UPDATE `exp_hawb` SET  " .
      "`carriername` = '$name'" .
      "WHERE  `exp_hawb`.`carrier` ='$code';");

  }
  $db->Get_admin_msg("client.php","更新成功！");
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
  $db->query("DELETE FROM `exp_client` WHERE `id` = '$_GET[delid]' LIMIT 1");
  $db->Get_admin_msg("client.php","删除客户成功！");
}


$filt_sql="select * from `exp_client` where `id`!=''";
if($_POST[s_code]!=""){$filt_sql.=" && `code`like '%".$_POST[s_code]."%'";}
if($_POST[s_name]!=""){$filt_sql.=" && `name`like '%".$_POST[s_name]."%'";}
if($_POST[s_cata]!=""){$filt_sql.=" && `cata`='".$_POST[s_cata]."'";}
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
    "cata"=>$row[cata]);
}

$tpl->assign("h_list",$h_list);
$tpl->display("client.html");

?>