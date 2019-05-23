<?php
    /**
	   * 链接地址：system_tc，封盘的时候来执行这个请求，不断的来修改开奖的时间
	   *
     * 下面直接来连接操作数据库进而得到json串
     *
     * 按json方式输出通信数据
     *
     * @param unknown $State 状态码
     *
     * @param string $Descriptor  提示信息
     *
	   * @param string $Version  操作时间

     * @param array $Data 返回数据
     *
     * @return string
     *
     */
  require_once(dirname(__FILE__).'/include/config.inc.php');
   $now=time();
   $summary=array();
   $tbname='pmw_lotterynumber';
  //
    $one=1;
    $two=2;
    $zero=0;
    $arr = file_get_contents(stripslashes("http://api.dabai28.com/?service=Get.jnd28"));  //去除对象里面的斜杠
    $srr = json_decode($arr,true);
    $state=$srr['ret'];
    if($state==200){
    $array=$srr['data'][0];
    $issue=$array['issue']+1;

     //获取当前准备开奖的开奖时间，不停的将即将开奖的时间+30s，更改开奖时间
     $r=$dosql->GetOne("select kj_times from pmw_lotterynumber where  kj_times=$issue");
     $kj_endtime_sjc=time()+30; //根据上一期的开奖时间大概推算出下期的开奖时间
     $kj_mdhi=date("m-d H:i:s",$kj_endtime_sjc);
     $kj_endtime=date("Y-m-d H:i:s",$kj_endtime_sjc);
     $sql = "UPDATE `$tbname` SET kj_endtime_sjc=$kj_endtime_sjc, kj_endtime='$kj_endtime', kj_mdhi='$kj_mdhi' WHERE kj_times=$issue";
      $dosql->ExecNoneQuery($sql);
    }

?>
