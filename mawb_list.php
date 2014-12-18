<?php
/*
 * 总单列表
 *
 */
include_once("global.php");
include_once ("common/page.class.php");
$db->Get_user_shell_check($uid, $shell);

$tpl->assign("position","总单管理 &gt; 总单列表");

//显示总单列表
$filt_sql="select * from `exp_mawb` where `id`!=''";
//为sql查询语句附加查询条件
if($_POST[s_mawb]!=""){
    $filt_sql.=" && `mawb`like '%".$_POST[s_mawb]."%'";
    $s_mawb = $_POST[s_mawb];
}
if($_POST[s_dest]!=""){
    $filt_sql.=" && `dest`like '%".$_POST[s_dest]."%'";
    $s_dest = $_POST[s_dest];
}
if($_POST[s_oversea]!=""){
    $filt_sql.=" && `oversea`like '%".$_POST[s_oversea]."%'";
    $s_oversea = $_POST[s_oversea];
}
if($_POST[s_carrier]!=""){
    $filt_sql.=" && `carrier`like '%".$_POST[s_carrier]."%'";
    $s_carrier = $_POST[s_carrier];
}
if($_POST[s_fltdate_s]!=""){
    $filt_sql.=" && fltdate>='".$_POST[s_fltdate_s]." 00:00:00'";
    $s_fltdate_s = $_POST[s_fltdate_s];
}
if($_POST[s_fltdate_e]!=""){
    $filt_sql.=" && fltdate<='".$_POST[s_fltdate_e]." 23:59:59'";
    $s_fltdate_e = $_POST[s_fltdate_e];
}

$listnum = $db->query($filt_sql);
$total = $db->db_num_rows($listnum);  //查询结果总数
//每页显示数目，存入session变量，默认20条，可通过config文件配置
if($_POST[displaypg]!=""){$_SESSION[exppg] = $_POST[displaypg];}
$displaypg=$_SESSION[exppg];
$tpl->assign("displaypg",$displaypg);
pageft($total,$displaypg);  //调用分页类
if ($firstcount < 0){$firstcount = 0;}
$limit_sql=$filt_sql." order by `fltdate` desc,`regtime` desc limit $firstcount,$displaypg";  //按航班日期、添加时间倒序排列
$hlist = $db->query($limit_sql);
while ($row = $db->fetch_array($hlist)){
  $h_list[] = array(
    "id"=>$row[id],
    "mawb"=>$row[mawb],
    "dest"=>$row[dest],
    "fltno"=>$row[fltno],
    "fltdate"=>$row[fltdate],
    "num"=>$row[num],
    "gw"=>$row[gw],
    "cw"=>$row[cw],
    "cbm"=>$row[cbm],
    "oversea"=>$row[oversea],
    "carrier"=>$row[carrier],
    "opdate"=>$row[opdate]);
    $allnum+=$row[num];
    $allgw+=$row[gw];
    $allcw+=$row[cw];
    $allcbm+=$row[cbm];  //累积获取总数
}
//输出总计数结果
$tpl->assign("allnum",$allnum);
$tpl->assign("allgw",$allgw);
$tpl->assign("allcw",$allcw);
$tpl->assign("allcbm",$allcbm);

//输出上一次查询条件
$tpl->assign("s_mawb",$s_mawb);
$tpl->assign("s_dest",$s_dest);
$tpl->assign("s_oversea",$s_oversea);
$tpl->assign("s_carrier",$s_carrier);
$tpl->assign("s_fltdate_s",$s_fltdate_s);
$tpl->assign("s_fltdate_e",$s_fltdate_e);

$tpl->assign("pagenav", $pagenav);

//删除分单部分：
//通过权限判断是否显示删除按钮
if(ifpermit($_SESSION[expgrade],4)!==0){
    $tpl->assign("delbtn",1);
    $tpl->assign("delid",$_GET[editid]);
}
//为防止通过url直接调用删除功能，对删除操作也进行权限判断
if ($_GET[delmawb]){
  if(ifpermit($_SESSION[expgrade],4)==0){
    $db->Get_admin_alert("没有操作权限!");
    exit;
  }
  $db->query("DELETE FROM `exp_mawb` WHERE `mawb` = '$_GET[delmawb]' LIMIT 1");
  $db->Get_admin_msg("mawb_list.php","删除总单成功！");
}

$tpl->assign("h_list",$h_list);
$tpl->display("mawb_list.html");
?>