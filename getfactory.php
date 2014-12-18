<?php
// 输入代码获取内容（json）：生产单位代码 >> 生产单位名称
include_once("global.php");
$db->Get_user_shell_check($uid, $shell);

$list = $db->query("select * from `exp_client` where `cata`='生产单位' and `code`='".$_GET[code]."'");
$rowabbr = $db->fetch_array($list);
//转化为json格式后返回
echo $str = json_encode($rowabbr);
?>
