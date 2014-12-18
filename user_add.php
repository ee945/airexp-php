<?php
/*
 * 用户添加
 *
 * 添加新用户
 * 
 */
include_once("global.php");
$db->Get_user_shell_check($uid, $shell);

//判断权限
if(ifpermit($_SESSION[expaccess],7)==0){
    $db->Get_admin_alert("没有访问权限!");
    exit;
}

if(ifpermit($_SESSION[expgrade],2)==0){
    $db->Get_admin_alert("没有操作权限!");
    exit;
}

$tpl->assign("position","用户管理 &gt; 注册用户");

$now=date("Y-m-d",time());
$tpl->assign("now",$now);  //显示注册日期（提交为当前时间）

if (isset($_POST[adduser])){
  $name = addslashes($_POST[name]);
  //判断用户是否已经存在
  $query = $db->query("select * from `exp_user` where `name` = '$name'");
  $count = $db->db_num_rows($query);
  if(!empty($count)){ ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript">
    alert("用户名已存在！");
    javascript:history.go(-1);
    </script>
    <?php
  }else{
  $pass = md5("$_POST[pass]");
  $nick = addslashes($_POST[nick]);
  $dept = addslashes($_POST[dept]);
  $grade = getpermit($_POST[grade])+0;
  $access = getpermit($_POST[access])+0;
  $regdate = addslashes($_POST[regdate]);
  $lastdate = addslashes($_POST[lastdate]);
  $remark = addslashes($_POST[remark]);

  $db->query("insert into `exp_user` (`id`,`name`,`pass`,`nick`,`dept`,`grade`,`access`,`regdate`,`remark`) " .
        "value(NULL,'$name','$pass','$nick','$dept','$grade','$access','".date("Y-m-d H:i:s",time())."','$remark')");
  $db->Get_admin_alert("添加成功！");
  }
}

$tpl->display("user_add.html");

?>