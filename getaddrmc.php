<?php
// 输入代码获取内容（json）：总单收货人代码 >> 总单收货人地址
include_once("global.php");
$db->Get_user_shell_check($uid, $shell);

$list = $db->query("select * from `exp_address` where `cata`='总单收货人' and `code`='".$_GET[code]."'");
$rowabbr = $db->fetch_array($list);
//转化为json格式后返回
echo $str = json_encode($rowabbr);
?>
