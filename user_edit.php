<?php
/*
 * 用户修改
 *
 * 编辑用户信息
 * 
 */
include_once("global.php");
$db->Get_user_shell_check($uid, $shell);

//判断权限
if(ifpermit($_SESSION[expaccess],7)==0){
    $db->Get_admin_alert("没有访问权限!");
    exit;
}


$tpl->assign("position","用户管理 &gt; 用户资料修改");

$today=date("Y-n-j",time());
$tomorrow = date("Y-n-j",mktime(0,0,0,date("m",time()),date("d",time())+1,date("Y",time())));
$tpl->assign("today",$today);
$tpl->assign("tomorrow",$tomorrow);

//显示已存在的用户信息
if (isset($_GET[editid])){
  $list = $db->query("select * from `exp_user` where `id`='".$_GET[editid]."'");
  $row=$db->fetch_array($list);
  $regdate=substr($row[regdate],0,10);

  if($_GET[editid]==1){
  	$tpl->assign("readonly","onclick=\"alert('管理员不能取消任何权限！');return false;\"");
  }

  $gprm=$row[grade];
  $tpl->assign("gchk1",getchecked($gprm,1));
  $tpl->assign("gchk2",getchecked($gprm,2));
  $tpl->assign("gchk3",getchecked($gprm,3));
  $tpl->assign("gchk4",getchecked($gprm,4));
  $tpl->assign("gchk5",getchecked($gprm,5));
  $tpl->assign("gchk6",getchecked($gprm,6));
  $tpl->assign("gchk7",getchecked($gprm,7));
  $tpl->assign("gchk8",getchecked($gprm,8));

  $aprm=$row[access];
  $tpl->assign("achk1",getchecked($aprm,1));
  $tpl->assign("achk2",getchecked($aprm,2));
  $tpl->assign("achk3",getchecked($aprm,3));
  $tpl->assign("achk4",getchecked($aprm,4));
  $tpl->assign("achk5",getchecked($aprm,5));
  $tpl->assign("achk6",getchecked($aprm,6));
  $tpl->assign("achk7",getchecked($aprm,7));
  $tpl->assign("achk8",getchecked($aprm,8));

  $tpl->assign("name",$row[name]);
  $tpl->assign("unick",$row[nick]);
  $tpl->assign("udept",$row[dept]);
  $tpl->assign("regdate",$regdate);
  $tpl->assign("remark",$row[remark]);
}

//编辑提交用户新信息
if (isset($_POST[edituser])){
  //判断操作权限
  if(ifpermit($_SESSION[expgrade],3)==0){
      $db->Get_admin_alert("没有修改权限!");
      exit;
  }

  $nick = addslashes($_POST[nick]);
  $dept = addslashes($_POST[dept]);
  $remark = addslashes($_POST[remark]);
  $grade = getpermit($_POST[grade])+0;
  $access = getpermit($_POST[access])+0;

  $db->query("UPDATE  `exp_user` SET  " .
    "`nick` = '$nick'," .
    "`dept` = '$dept'," .
    "`grade` = '$grade'," .
    "`remark` = '$remark'," .
    "`access` = '$access' " .
    "WHERE  `exp_user`.`id` ='$_GET[editid]' LIMIT 1 ;");
  //判断密码是否修改
  if($_POST[pass]<>""){
        $md5pass = md5("$_POST[pass]");
        $db->query("UPDATE  `exp_user` SET `pass` = '$md5pass' " .
        "WHERE  `exp_user`.`id` ='$_GET[editid]' LIMIT 1 ;");
  }
  //判断是否修改当前用户
  if($_GET[editid]==$_SESSION[expid]){
    $alertmsg="当前用户信息已更新！请重新登录";
    session_destroy();
    $db->Get_admin_msg("login.php",$alertmsg);
  }else{
  	$db->Get_admin_msg("user_list.php","更新成功！");
  }
}

$tpl->display("user_edit.html");

?>