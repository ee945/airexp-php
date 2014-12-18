<?php
/*
 * 销售（揽货）人库
 * 1. 该库用于登记货源对应的销售人，分单输入时输入货源会从本库中，直接找出对应销售人，填入表单
 * 2. 未登记销售人的货源输分单时会自动选择默认值提交入库，在分单列表中查询该默认值，即可找出未统计销售人的货源及分单
 * 3. 本页与地址库为相同架构，注释可完全参照address.php
 *
 */
 
include_once("global.php");
include_once ("common/page.class.php");

$db->Get_user_shell_check($uid, $shell);

$tpl->assign("position","<a href=\"seller.php\">销售管理</a>");
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
if (isset($_GET[forward])){
  $tpl->assign("update","1");
  $tpl->assign("list","0");

  $list = $db->query("select * from `exp_seller` where `forward`='".$_GET[forward]."'");
  $row=$db->fetch_array($list);
  $tpl->assign("forward",$row[forward]);
  $tpl->assign("seller",$row[seller]);
  $tpl->assign("remark",$row[remark]);
}

//添加
if(isset($_POST[add])){
  $forward = strtoupper(addslashes($_POST[forward]));

  $query = $db->query("select * from `exp_seller` where `forward` = '$forward'");
  $count = $db->db_num_rows($query);
  if(!empty($count)){ ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript">
    alert("该货源已存在！");
    javascript:history.go(-1);
    </script>
    <?php
  }else{
  $forward = strtoupper(addslashes($_POST[forward]));
  $seller = strtoupper(addslashes($_POST[seller]));
  $remark = strtoupper(addslashes($_POST[remark]));

  $db->query("insert into `exp_seller` (`id`,`forward`,`seller`,`remark`) " .
        "value(NULL,'$forward','$seller','$remark')");
  $db->Get_admin_msg("seller.php","添加成功！");
  }
}

//修改
if (isset($_POST[forward])){
  $forward = addslashes($_POST[forward]);
  if($forward!=$row[forward]){
    $query = $db->query("select * from `exp_seller` where `forward` = '$forward'");
    $count = $db->db_num_rows($query);
    if(!empty($count)){ ?>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <script type="text/javascript">
      alert("该货源已存在！");
      javascript:history.go(-1);
      </script>
      <?php
      exit;
    }
  }
  $forward = strtoupper(addslashes($_POST[forward]));
  $seller = strtoupper(addslashes($_POST[seller]));
  $remark = strtoupper(addslashes($_POST[remark]));

  $db->query("UPDATE  `exp_seller` SET  " .
    "`forward` = '$forward'," .
    "`seller` = '$seller'," .
    "`remark` = '$remark'" .
    "WHERE  `exp_seller`.`forward` ='$_POST[forward]' LIMIT 1 ;");
  $db->Get_admin_msg("seller.php","更新成功！");
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
  $db->query("DELETE FROM `exp_seller` WHERE `id` = '$_GET[delid]' LIMIT 1");
  $db->Get_admin_msg("seller.php","删除目的港成功！");
}

$filt_sql="select * from `exp_seller` where `id`!=''";
if($_POST[s_forward]!=""){$filt_sql.=" && `forward`like '%".$_POST[s_forward]."%'";}
if($_POST[s_seller]!=""){$filt_sql.=" && `seller`like '%".$_POST[s_seller]."%'";}
if($_POST[s_remark]!=""){$filt_sql.=" && `remark`like '%".$_POST[s_remark]."%'";}
$listnum = $db->query($filt_sql);
$total = $db->db_num_rows($listnum);
if($_POST[displaypg]!=""){$_SESSION[exppg] = $_POST[displaypg];}
$displaypg=$_SESSION[exppg];
$tpl->assign("displaypg",$displaypg);
pageft($total,$displaypg);
if ($firstcount < 0){$firstcount = 0;}
$limit_sql=$filt_sql." order by `forward` asc,`id` asc limit $firstcount,$displaypg";
$hlist = $db->query($limit_sql);
while ($row = $db->fetch_array($hlist)){
  $h_list[] = array(
    "id"=>$row[id],
    "forward"=>$row[forward],
    "seller"=>$row[seller],
    "remark"=>$row[remark]);
}

$tpl->assign("h_list",$h_list);
$tpl->display("seller.html");

?>