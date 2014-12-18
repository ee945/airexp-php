<?php
// 输入代码获取内容（json）：销售（揽货）人代码 >> 销售（揽货）人名称
include_once("global.php");
$db->Get_user_shell_check($uid, $shell);

$list = $db->query("select * from `exp_seller` where `forward`='".$_GET[forward]."'");
$rowseller = $db->fetch_array($list);
//转化为json格式后返回
echo $str = json_encode($rowseller);
?>
