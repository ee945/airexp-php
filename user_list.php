<?php
/*
 * 用户列表
 *
 * 显示所有用户
 * 
 */
include_once("global.php");
$db->Get_user_shell_check($uid, $shell);

//判断权限
if(ifpermit($_SESSION[expaccess],7)==0){
    $db->Get_admin_alert("没有访问权限!");
    exit;
}

if(ifpermit($_SESSION[expgrade],1)==0){
    $db->Get_admin_alert("没有查询权限!");
    exit;
}

$tpl->assign("position","用户管理 &gt; 用户列表");

//显示分单列表
$sql="select * from `exp_user` order by `id`";
$ulist = $db->query($sql);
while ($row = $db->fetch_array($ulist)){
  $u_list[] = array(
    "id"=>$row[id],
    "name"=>$row[name],
    "nick"=>$row[nick],
    "dept"=>$row[dept],
    "regdate"=>$row[regdate],
    "lastdate"=>$row[lastdate]);
}
$tpl->assign("u_list",$u_list);
$tpl->display("user_list.html");
?>