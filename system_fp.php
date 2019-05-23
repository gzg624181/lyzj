<?php
    /**
	   * 链接地址：system_fp，封盘的时候来执行这个请求，执行封盘操作
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

     //获取当前准备开奖的开奖时间，如果是第一次数据库是空的话，则插入一条新的即将开奖的数据
     $r=$dosql->GetOne("SELECT kj_times from pmw_lotterynumber where  kj_times=$issue");
     if(is_array($r)){
       //获取当前准备封盘的时间 ，在上期开奖之后的3分钟之后
       $fp_time = strtotime($array['time'])+180;
       $fptime=date("m-d H:i:s",$fp_time);
       $sql = "UPDATE pmw_lotterynumber SET state='fp' WHERE kj_times=$issue";
     	 $dosql->ExecNoneQuery($sql);
       }else{
       //表里面没有当前开奖的数据的时候，则提前生成一个最新的
       //获取当前准备封盘的时间 ，在上期开奖之后的3分钟之后
       $fp_time = strtotime($array['time'])+180;
       $fptime=date("m-d H:i:s",$fp_time);
       $kj_endtime_sjc=time()+30; //根据上一期的开奖时间大概推算出下期的开奖时间
       $kj_mdhi=date("m-d H:i:s",$kj_endtime_sjc);
       $kj_endtime=date("Y-m-d H:i:s",$kj_endtime_sjc);
       $state='fp'; //当前处于封盘状态
       $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi,state) VALUES ($issue, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi', '$state')";
       $dosql->ExecNoneQuery($sql);
     }

   $summary=array();
   $xiazhu_qishu=$issue;
   $dosql->Execute("SELECT  * from  pmw_xiazhuorder where xiazhu_qishu='$xiazhu_qishu'",$zero);
   $num=$dosql->GetTotalRow($zero);
   if($num==0){
      $rand=rand(1,6);
     //当前如果没有人下注的情况下，则随机从原先的下注记录里面找出10条用户下注记录
     $dosql->Execute("SELECT a.xiazhu_qishu,b.telephone,b.nickname,a.xiazhu_sum,a.xiazhu_orderid,b.bcode,b.id,a.gameid,b.ucode from pmw_xiazhuorder a inner join pmw_members b on a.uid=b.id GROUP BY nickname order by rand() limit $rand",$one);
   }else{
    $dosql->Execute("SELECT a.xiazhu_qishu,b.telephone,b.nickname,a.xiazhu_sum,a.xiazhu_orderid,b.bcode,b.id,a.gameid,b.ucode  from pmw_xiazhuorder a inner join pmw_members b on a.uid=b.id where a.xiazhu_qishu='$xiazhu_qishu'",$one);
    }
   for($i=0;$i<$dosql->GetTotalRow($one);$i++)
   {
     $row1 = $dosql->GetArray($one);
     $summary[$i]['username']=$row1['nickname'];
     $summary[$i]['telephone']=substr($row1['telephone'],-4);
     $summary[$i]['sum']=$row1['xiazhu_sum'];
     $xiazhu_orderid=$row1['xiazhu_orderid'];
     //在封盘的时候开始处理用户的提成
     //判断是否有提成,放在封盘的时候来执行
     $bcode=$row1['bcode'];
     $ucode=$row1['ucode']; //当前下注的ucode
     if( $bcode !=""){
       $money=$row1['xiazhu_sum']; //下注总金额
       $mid=$row1['id'];           //下注用户id
       $gid=$row1['gameid'];       //游戏id
       if($num!=0){
       ticheng($money,$mid,$bcode,$gid,$ucode); //计算用户的提成
       }
     }
     $dosql->Execute("SELECT xiazhu_mulu,xiazhu_type,xiazhu_money from pmw_xiazhucontent where xiazhu_orderid='$xiazhu_orderid'",$two);
     for($j=0;$j<$dosql->GetTotalRow($two);$j++){
         $row2 = $dosql->GetArray($two);
         $xiazhu_type =  $row2['xiazhu_type'];

           $summary[$i]['content'][$j]['type']=$xiazhu_type;

           $summary[$i]['content'][$j]['money']=intval($row2['xiazhu_money']);
     }

   }

   $content=array(
     "serial" => strval($issue), //期数
     "time"   => $fptime,  //时间
     "summary"  => $summary, //下注结果
     "timestamp" => $now
   );
    $actiontime=date("Y-m-d H:i:s",$now);
    $content=phpver($content);
    $one=1;
    $dosql->Execute("SELECT  id from pmw_game where game='Canada28'",$one);
    for($i=0;$i<$dosql->GetTotalRow($one);$i++)
    {
     $show=$dosql->GetArray($one);
     $gameid=$show['id'];
     $sql = "INSERT INTO `#@__message` (type,content,timestamp,kjtime,gid) VALUES ('system_fp','$content',$now,'$actiontime',$gameid)";
     $dosql->ExecNoneQuery($sql);
   }

  }

?>
