<?php
session_start();
include_once ('./common/config.php');
include_once ('./common/function.php');
include_once ('./common/smarty/Smarty.class.php');
include_once ('./common/mysql.class.php');
include_once ('./common/action.class.php');

$db = new action($dbhost,$dbuser,$dbpass,$dbname,$dbconn,$dbcode);
$tpl = new Smarty();
$tpl->caching = false;
$tpl->template_dir = $smarty_template_dir;
$uid = $_SESSION[expid];
$shell = $_SESSION[expshell];
$db->Get_user_ontime($logouttime);
$tpl->assign("nick",$_SESSION[expnick]);
$tpl->assign("dept",$_SESSION[expdept]);
$tpl->assign("site_title",$site_title);


?>
