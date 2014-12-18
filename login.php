<?php
//登录页面
session_start();
include_once ('./common/config.php');
include_once ('./common/function.php');
include_once ('./common/smarty/Smarty.class.php');
include_once ('./common/mysql.class.php');
include_once ('./common/action.class.php');

$db = new action($dbhost,$dbuser,$dbpass,$dbname,$dbconn,$dbcode);
$tpl = new Smarty();
$tpl->caching = $smarty_caching;
$tpl->template_dir = $smarty_template_dir;

if(!empty($_POST[name])&& !empty($_POST[pass])) $db->Get_user_login($_POST[name],$_POST[pass]);

if($_GET[action]=='logout'){
    $db->Get_user_out();
}

$tpl->display("login.html");
?>