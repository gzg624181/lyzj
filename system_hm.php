<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
date_default_timezone_set('Asia/Shanghai');
$arr = file_get_contents(stripslashes("http://api.dabai28.com/?service=Get.jnd28"));  //去除对象里面的斜杠
$srr = json_decode($arr,true);
$state=$srr['ret'];


if($state==200){

  $array=$srr['data'][0];
  $issue=$array['issue'];                    //开奖期数
  $kj_number=$array['c1'].$array['c2'].$array['c3'];
  $kj_he=$array['c4'];
  $kj_varchar=$array['c1']."+".$array['c2']."+".$array['c3']."=".$kj_he.results($kj_he);
  $addtime=date("Y-m-d H:i:s");
  $addtimestamp=time();
  $r=$dosql->GetOne("SELECT kj_times FROM pmw_lotterynumber where kj_number <> '' order by id desc limit 0,1");
  $kj_times=$r['kj_times'];   //上一期的开奖期数
  // $kj_times=2422753;
  $kj_maketime=date("Y-m-d");
  if($kj_times!=$issue){  //自动获取最新的开奖号码
  $sql = "UPDATE `#@__lotterynumber` SET kj_number='$kj_number',kj_varchar='$kj_varchar',kj_he=$kj_he,addtime='$addtime',kj_maketime='$kj_maketime',addtimestamp=$addtimestamp where kj_times='$issue'";
  $dosql->ExecNoneQuery($sql);
  }else{
  include("system_hm.php");
  }
}
?>
