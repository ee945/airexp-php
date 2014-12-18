<?php
/*
 * 首页
 * 
 * 默认显示3天的出货信息，昨天、今天、明天
 *
 */
include_once("global.php");

$db->Get_user_shell_check($uid, $shell);

$tpl->assign("position","首页");

//昨天、今天、明天，该组变量用于设置查询条件
$lastday = date("Y-m-d",strtotime("-1 day"));
$today = date("Y-m-d",time());
$nextday = date("Y-m-d",strtotime("+1 day"));
//昨天、今天、明天（带星期），该组变量用于页面显示
$lastdayw = date("m-d D",strtotime("-1 day"));
$todayw = date("m-d D",time());
$nextdayw = date("m-d D",strtotime("+1 day"));

//显示带星期的日期标签
$tpl->assign("lastday",$lastdayw);
$tpl->assign("today",$todayw);
$tpl->assign("nextday",$nextdayw);

/*  三日航班信息列表 开始 */

//昨天
$sql1="select * from `exp_hawb` where `fltdate`='".$lastday."' order by `hawb` asc";
$hlist1=$db->query($sql1);
if($db->db_num_rows($hlist1)==0){
    $tpl->assign("nolastday",1);  //若找不到该日分单，则在模板中显示当日没有出货
}else{
    while($row1=$db->fetch_array($hlist1)){
        $h_list1[] = array(
        "id"=>$row1[id],
        "hawb"=>$row1[hawb],
        "mawb"=>$row1[mawb],
        "dest"=>$row1[dest],
        "fltno"=>$row1[fltno],
        "fltdate"=>$row1[fltdate],
        "num"=>$row1[num],
        "gw"=>$row1[gw],
        "cw"=>$row1[cw],
        "cbm"=>$row1[cbm],
        "paymt"=>$row1[paymt],
        "forward"=>$row1[forward],
        "factory"=>$row1[factory],
        "carriername"=>$row1[carriername],
        "opdate"=>$row1[opdate]);
        $allnum1+=$row1[num];
        $allgw1+=$row1[gw];
        $allcbm1+=$row1[cbm];  //当日出运信息汇总
    }
    $tpl->assign("allnum1",$allnum1);
    $tpl->assign("allgw1",$allgw1);
    $tpl->assign("allcbm1",$allcbm1);
    $tpl->assign("h_list1",$h_list1);
}

//今天
$sql2="select * from `exp_hawb` where `fltdate`='".$today."' order by `hawb` asc";
$hlist2=$db->query($sql2);
if($db->db_num_rows($hlist2)==0){
    $tpl->assign("notoday",1);
}else{
    while($row2=$db->fetch_array($hlist2)){
        $h_list2[] = array(
        "id"=>$row2[id],
        "hawb"=>$row2[hawb],
        "mawb"=>$row2[mawb],
        "dest"=>$row2[dest],
        "fltno"=>$row2[fltno],
        "fltdate"=>$row2[fltdate],
        "num"=>$row2[num],
        "gw"=>$row2[gw],
        "cw"=>$row2[cw],
        "cbm"=>$row2[cbm],
        "paymt"=>$row2[paymt],
        "forward"=>$row2[forward],
        "factory"=>$row2[factory],
        "carriername"=>$row2[carriername],
        "opdate"=>$row2[opdate]);
        $allnum2+=$row2[num];
        $allgw2+=$row2[gw];
        $allcbm2+=$row2[cbm];
    }
    $tpl->assign("allnum2",$allnum2);
    $tpl->assign("allgw2",$allgw2);
    $tpl->assign("allcbm2",$allcbm2);
    $tpl->assign("h_list2",$h_list2);
}

//明天
$sql3="select * from `exp_hawb` where `fltdate`='".$nextday."' order by `hawb` asc";
$hlist3=$db->query($sql3);
if($db->db_num_rows($hlist3)==0){
    $tpl->assign("nonextday",1);
}else{
    while($row3=$db->fetch_array($hlist3)){
        $h_list3[] = array(
        "id"=>$row3[id],
        "hawb"=>$row3[hawb],
        "mawb"=>$row3[mawb],
        "dest"=>$row3[dest],
        "fltno"=>$row3[fltno],
        "fltdate"=>$row3[fltdate],
        "num"=>$row3[num],
        "gw"=>$row3[gw],
        "cw"=>$row3[cw],
        "cbm"=>$row3[cbm],
        "paymt"=>$row3[paymt],
        "forward"=>$row3[forward],
        "factory"=>$row3[factory],
        "carriername"=>$row3[carriername],
        "opdate"=>$row3[opdate]);
        $allnum3+=$row3[num];
        $allgw3+=$row3[gw];
        $allcbm3+=$row3[cbm];
    }
    $tpl->assign("allnum3",$allnum3);
    $tpl->assign("allgw3",$allgw3);
    $tpl->assign("allcbm3",$allcbm3);
    $tpl->assign("h_list3",$h_list3);
}

/*  三日航班信息 结束 */

/*  今明两日预报 直接调用航班信息结果  */

$tpl->display("index.html");

?>
