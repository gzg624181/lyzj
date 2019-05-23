<?php
    /**
	   * 链接地址：system_fp，(需要定时器插入封盘消息),定时器定时更新开奖数据
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
   require_once("../../include/config.inc.php");
   $now=time();
   $r=$dosql->GetOne("select * from pmw_lotterynumber where  kj_endtime_sjc >= $now order by id asc");
   $kj_times = $r['kj_times'];
   $kj_endtime_sjc = $r['kj_endtime_sjc'];

   if($now + 30 > $r['kj_endtime_sjc']){
       $fptime="";
   }else{
       $fptime=date("m-d H:i",$kj_endtime_sjc - 30);
   }
   $one=1;
   $two=2;
   $summary=array();
   $dosql->Execute("select a.xiazhu_qishu,b.telephone,b.nickname from pmw_xiazhuorder a inner join pmw_members b on a.uid=b.id where a.xiazhu_qishu=$kj_times",$one);
   while($row1=$dosql->GetArray($one)){
     $summary['username']=$row1['nickname'];
     $summary['telephone']=substr($row1['telephone'],-4);
     $xiazhu_qishu=$row1['xiazhu_qishu'];
     $dosql->Execute("select xiazu_type,xiazhu_money from pmw_xiazhucontent where xiazhu_times=$xiazhu_qishu",$two);
     while($row2=$dosql->GetArray($two)){
       $summary['content'][]=$row2;
     }
   }
   $content=array(
     "serial" => $kj_times, //期数
     "time"   => $fptime,  //时间
     "summary"  => $summary, //开奖结果
     "timestamp" => $now
   );

    $content=json_encode($content);
    $sql = "INSERT INTO `#@__message` (type,content,timestamp) VALUES ('system_fp','$content',$now)";
    $dosql->ExecNoneQuery($sql);

?>
