<?php
    /**
	   * 链接地址：system_kj，(需要定时器插入开奖消息),定时器定时更新开奖数据
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
   $kj_endtime = substr($r['kj_endtime'],5,11);
   $kj_varchar = $r['kj_varchar'];
   $kj_code = $r['kj_number'];

   $content=array(
     "serial" => $kj_times, //期数
     "time"   => $kj_endtime, //时间
     "value"  => $kj_varchar, //开奖结果
     "code"   => $kj_code,
     "timestamp" => $now
   );

    $sql = "INSERT INTO `#@__message` (type,content,timestamp) VALUES ('system_kj','$content',$now)";
    $dosql->ExecNoneQuery($sql);



?>
