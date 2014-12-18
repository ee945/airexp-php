<?php
/*
 * Created on 2013-12-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

/* remote db
 $dbhost    ="XX.XX.XX.XX";
 $dbuser    ="XXX";
 $dbpass    ="XXX";
 $dbname    ="XXX";
 $dbconn    ="conn";
 $dbcode    ="utf8";
*/

///* local db
 $dbhost    ="localhost";
 $dbuser    ="root";
 $dbpass    ="";
 $dbname    ="test";
 $dbconn    ="conn";
 $dbcode    ="utf8";
//*/

date_default_timezone_set ('Asia/Shanghai');
$logouttime        ="86400";    //登录超时时间

//======Smarty Config===========
$smarty_template_dir    ='./templates/sysv2/';   //smarty模板路径
$smarty_compile_dir     ='./templates_c/';
$smarty_config_dir      ='./';
$smarty_cache_dir       ='./cache/';
$smarty_caching         =false;
$smarty_delimiter       =explode("|","{|}");

//基本费用表
$f_aw="50"; //每票分单费用

//页面全局设置
$site_title="出口单证业务管理系统";
?>