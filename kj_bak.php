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
   	require_once(dirname(__FILE__).'/include/config.inc.php');

    $arr = file_get_contents(stripslashes("http://api.dabai28.com/?service=Get.jnd28"));  //去除对象里面的斜杠
    $srr = json_decode($arr,true);
    $state=$srr['ret'];
  if($state==200){
    $array=$srr['data'][0];
    $issue=$array['issue'];                    //开奖期数

   $r=$dosql->GetOne("SELECT kj_times,addtime,kj_varchar,kj_number,kj_he,kj_endtime_sjc FROM pmw_lotterynumber where kj_number <> '' order by id desc limit 0,1");
   $kj_times = $r['kj_times'];
   if($kj_times==$issue){  //判断当前的开奖号码是否已经开出来了
   $kj_endtime = substr($r['addtime'],5,14);
   $kj_varchar = $r['kj_varchar'];
   $kj_code = $r['kj_number'];
   $kj_he = $r['kj_he'];
   $now=time();
   $content=array(
     "serial" => $kj_times, //期数
     "time"   => $kj_endtime, //时间
     "value"  => $kj_varchar, //开奖结果
     "code"   => $kj_code,
     "timestamp" => intval($now)
   );
    $actiontime=date("Y-m-d H:i:s",$now);
    $content=phpver($content);
    $sql = "INSERT INTO `#@__message` (type,content,timestamp,kjtime) VALUES ('system_kj','$content',$now,'$actiontime')";
    $dosql->ExecNoneQuery($sql);

    //开奖完毕之后，通过开奖期数进行开奖计算,目前仅仅只是针对canada28加拿大28的游戏，后续再来添加
    $one=1;
    $two=2;
    $dosql->Execute("SELECT xiazhu_orderid,uid,gameid FROM pmw_xiazhuorder where xiazhu_qishu='$kj_times'",$one);
    while($row=$dosql->GetArray($one)){
         if(is_array($row)){
           $xiazhu_orderid=$row['xiazhu_orderid'];
           $uid=$row['uid'];
           $gameid=$row['gameid'];
           $dosql->Execute("SELECT xiazhu_type,xiazhu_money FROM pmw_xiazhucontent where xiazhu_times='$kj_times' and xiazhu_orderid='$xiazhu_orderid'",$two);
           $b=0;
           while($show = $dosql->GetArray($two)){
               if(is_array($show)){
               $xiazhu_type = $show['xiazhu_type'];  //下注详情
               $xiazhu_money = $show['xiazhu_money']; //下注金额
               $arr=explode("+",$xiazhu_type);
               $ml=$arr[0];   //一级目录
               $lb=$arr[1];  //下注的类别
               $bl=$arr[2];   //开奖的倍率
              $str=canada28($kj_code,$kj_he,$ml); //本期开奖的所有类别
              if($gameid==4){ //加拿大28开奖结果算法
               if(check_str($str,$lb."/")){
                   //买小单，开奖结果为13：回本
                   if(check_str($str,"小单/") && $ke_he==13){
                    $b  += $xiazhu_money * 1;
                   //买大双，开奖结果为14：回本
                  }elseif(check_str($str,"大双/") && $ke_he==14){
                    $b  += $xiazhu_money * 1;
                  //买小或单，开奖结果为13，总下注大于1000：1.6倍（含本金）
                }elseif(check_str($str,$lb."小/") || check_str($str,$lb."单/") &&  $xiazhu_money<=1000 && $ke_he==13){
                    $b  += $xiazhu_money * 1.6;
                  //买大或双，开奖结果为14，总下注小于或等于1000：1.6倍（含本金）
                }elseif(check_str($str,$lb."大/") || check_str($str,$lb."双/") &&  $xiazhu_money<=1000 && $ke_he==14){
                    $b  += $xiazhu_money * 1.6;
                  }else{
                   $b  += $xiazhu_money * $bl;
                   }
               }else{
                 $b  +=0;
               }
             }
             }
           }
    }
    //更改用户的下注订单状态，
    $sql = "UPDATE `#@__xiazhuorder` SET xiazhu_kjstate=1,xiazhu_jiangjin='$b' where xiazhu_orderid='$xiazhu_orderid'";
    $dosql->ExecNoneQuery($sql);

    //同时向用户的账户里面添加中奖金额
    if($b!=0){
    $b=intval($b);
    $sql = "UPDATE `#@__members` SET money=money + $b where id=$uid";
    $dosql->ExecNoneQuery($sql);
    }
    }
    //更改开奖号码的开奖状态
    $sql = "UPDATE `#@__lotterynumber` SET kj_state=1 where kj_times='$kj_times'";
    $dosql->ExecNoneQuery($sql);
}else{
include("system_kj.php");
}
}

?>
