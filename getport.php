<?php
// 输入代码获取内容（json）：目的港代码 >> 目的港全称
include_once("global.php");
$db->Get_user_shell_check($uid, $shell);

$list = $db->query("select * from `exp_port` where `code`='".$_GET[code]."'");
$rowcode = $db->fetch_array($list);
//转化为json格式后返回
echo $str = json_encode($rowcode);
?>
