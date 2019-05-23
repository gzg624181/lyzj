<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
//0-24点钟的开奖时间和日期 每3分钟半钟开奖一次，每两分半钟之后封盘 先生成200期 ，其实期数2418198  起始开奖时间 2019-04-24 20:53:30
$w=date('w');
if($w==1){  //星期一
  $firsttimes = GetKj_times();
  for($i=0;$i<=326;$i++){
  //  $data="2019-04-29 00:02:30---2019-04-30 19:03:30";
    $date=date("Y-m-d");
    $startzero=strtotime($date)+150;
    $next_times=$startzero+210 * $i;
    $kj_times=$firsttimes + $i;
    $kj_endtime=date("Y-m-d H:i:s",$next_times);
    $kj_endtime_sjc=strtotime($kj_endtime);
    $kj_mdhi=date("m-d H:i:s",$next_times);
    $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
    $dosql->ExecNoneQuery($sql);
  }

  //星期一
  $secondttimes = GetKj_times();
  for($i=0;$i<=50;$i++){
  //  $data="2019-04-29 21:04:00--2019-04-29-23:59:00";
    $date=date("Y-m-d");
    $startzero=strtotime($date)+75840;
    $next_times=$startzero+210 * $i;
    $kj_times=$secondttimes + $i;
    $kj_endtime=date("Y-m-d H:i:s",$next_times);
    $kj_endtime_sjc=strtotime($kj_endtime);
    $kj_mdhi=date("m-d H:i:s",$next_times);
    $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
    $dosql->ExecNoneQuery($sql);
  }
}else{
  $firsttimes = GetKj_times();
  for($i=0;$i<=326;$i++){
  //  $data="2019-04-30 00:02:30---2019-04-30 19:03:30";
    $date=date("Y-m-d");
    $startzero=strtotime($date)+150;
    $next_times=$startzero+210 * $i;
    $kj_times=$firsttimes + $i;
    $kj_endtime=date("Y-m-d H:i:s",$next_times);
    $kj_endtime_sjc=strtotime($kj_endtime);
    $kj_mdhi=date("m-d H:i:s",$next_times);
    $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
    $dosql->ExecNoneQuery($sql);
  }
  $secondttimes = GetKj_times();
  for($i=0;$i<=67;$i++){
  //  $data="2019-04-30 20:04:30--2019-04-30-23:59:00";
    $date=date("Y-m-d");
    $startzero=strtotime($date)+72270;
    $next_times=$startzero+210 * $i;
    $kj_times=$secondttimes + $i;
    $kj_endtime=date("Y-m-d H:i:s",$next_times);
    $kj_endtime_sjc=strtotime($kj_endtime);
    $kj_mdhi=date("m-d H:i:s",$next_times);
    $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
    $dosql->ExecNoneQuery($sql);
  }
}

?>
