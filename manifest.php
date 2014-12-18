<?php
/*
 * 打印分运单清单功能（直接在页面上显示）
 *
 * 通过总单号查找出对应的所有分单数据，按特定格式显示在页面上，用于查看预览或直接打印
 * 但清单为横向打印，为防止和总分单打印设置冲突，建议先导出为excel格式后再打印
 *
 *
 */
include_once("global.php");
require_once 'common/PHPExcel.php';
$r=$db->Get_user_shell_check($uid, $shell);

//如果url没有获取到manimawb的$_GET参数，则显示“请输入总单号”提示
//获取到总单号参数后：
if(isset($_GET[manimawb])){
  $getmawbno=1;  //改变此变量，使smarty模板显示清单结果

  $manlist = $db->query("select * from `exp_v_manifest` where `mawb`='".$_GET[manimawb]."' order by `hawb` asc");
  if ($db->db_num_rows($manlist)==0){
    $db->Get_admin_alert("请先制作分单！");
    exit;
  }
  $hcount=0;  //分单数
  $amount_num=0;  //总件数
  $amount_gw=0;  //总实际重量
  while($manrow=$db->fetch_array($manlist)){
    $hawb=$manrow[hawb];
    $num=$manrow[num];
    $gw=$manrow[gw];
    $cgodescp_arry=explode("\n",$manrow[cgodescp]);  //品名描述默认只显示第一行
    $cgodescp=$cgodescp_arry[0];
    $depa="SHA";  //始发地默认上海SHA
    $dest=$manrow[dest];  //目的地为目的港三字代码
    $shipper=str_replace("\n","<br>",$manrow[shipper]);
    $consignee=str_replace("\n","<br>",$manrow[consignee]);

    $m_list[]=array(
      "hawb"=>$hawb,
      "num"=>$num,
      "gw"=>$gw,
      "cgodescp"=>$cgodescp,
      "depa"=>$depa,
      "dest"=>$dest,
      "shipper"=>$shipper,
      "consignee"=>$consignee);

    $hcount+=1;
    $amount_num=$amount_num+$num;
    $amount_gw=$amount_gw+$gw;
  }
  $mlist = $db->query("select * from `exp_hawb` where `mawb`='".$_GET[manimawb]."'");
  $mrow=$db->fetch_array($mlist);

  /*
   * 收货方海外代理
   * 
   * 默认从总单收货人信息中提取第一行，作为海外代理公司名称
   * 若总单未输入的情况下，默认指定TPE,OSA,HKG三个常用代理
   *
   */
  $mcnee = $db->query("select * from `exp_mawb` where `mawb`='".$_GET[manimawb]."'");
  if($db->db_num_rows($mcnee)>=1){
    $mcneerow=$db->fetch_array($mcnee);
    $mcnee_arry=explode("\n",$mcneerow[consignee]);
    $mconsignee=$mcnee_arry[0];
  }elseif($mrow[dest]=="TPE"){
    $mconsignee="UNIVERSAL LOGISTICS CO.,LTD.";
  }elseif($mrow[dest]=="OSA"){
    $mconsignee="THE SUMITOMO WAREHOUSE CO.,LTD.";
  }elseif($mrow[dest]=="HKG"){
    $mconsignee="SUMITOMO WAREHOUSE (HONG KONG) LIMITED";
  }
  $mawb=$mrow[mawb];
  $dest=$mrow[dest];
  $fltno=$mrow[fltno];
  $fltdate=$mrow[fltdate];
}
//内容传递并显示到模板
$tpl->assign("getmawbno",$getmawbno);
$tpl->assign("mconsignee",$mconsignee);
$tpl->assign("mawb",$mawb);
$tpl->assign("dest",$dest);
$tpl->assign("hcount",$hcount);
$tpl->assign("fltno",$fltno);
$tpl->assign("fltdate",$fltdate);
$tpl->assign("m_list",$m_list);
$tpl->assign("amount_num",$amount_num);
$tpl->assign("amount_gw",$amount_gw);
$tpl->display("manifest.html");

?>
