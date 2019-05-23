<?php
    /**
	   * 链接地址：xiazhulist  下注记录
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
     * @return string   用户会员id  uid 默认当天一天时间之内的所有下注记录，默认游戏起始gid=4  加拿大28 2.0
     *
     * 中奖状态 （全部，已中奖 1，未中奖 0，待开奖 2）
     */
require_once("../../include/config.inc.php");
$Data=array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $starttime = empty($starttime) ?  strtotime(date("Y-m-d",time())) : $starttime;
  $endtime = empty($endtime) ?  strtotime(date("Y-m-d",time()+24*3600)) : $endtime;
  $gid =empty($gid)? "": $gid;
  $kj_statue =empty($kj_statue)? "": $kj_statue;
  if($kj_statue==""){  //默认选择全部
     if($gid==""){
  $dosql->Execute("SELECT a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,b.gametypes,a.xiazhu_qishu,a.xiazhu_timestamp  FROM `#@__xiazhuorder` a inner join `#@__game` b on a.gameid=b.id where a.uid=$uid and a.xiazhu_timestamp >= $starttime and a.xiazhu_timestamp < $endtime order by a.id desc");
      }else{
  $dosql->Execute("SELECT a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,b.gametypes,a.xiazhu_qishu,a.xiazhu_timestamp  FROM `#@__xiazhuorder` a inner join `#@__game` b on a.gameid=b.id where a.uid=$uid and a.gameid=$gid and a.xiazhu_timestamp >= $starttime and a.xiazhu_timestamp < $endtime order by a.id desc");
       }
  }else{  //当中奖状态为已中奖 1，未中奖 0，待开奖 2
     if($gid==""){//没有选择哪一款游戏，则，显示所有的中奖状态
        if($kj_statue==1){       //已经中奖
           $dosql->Execute("SELECT a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,b.gametypes,a.xiazhu_qishu,a.xiazhu_timestamp  FROM `#@__xiazhuorder` a inner join `#@__game` b on a.gameid=b.id where a.uid=$uid and a.xiazhu_kjstate=1 and  a.xiazhu_jiangjin>0  and
             a.xiazhu_timestamp >= $starttime and a.xiazhu_timestamp < $endtime order by a.id desc");
        }elseif($kj_statue==3){  //未中奖
          $dosql->Execute("SELECT a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,b.gametypes,a.xiazhu_qishu,a.xiazhu_timestamp  FROM `#@__xiazhuorder` a inner join `#@__game` b on a.gameid=b.id where a.uid=$uid and a.xiazhu_kjstate=1 and  a.xiazhu_jiangjin ='0'
          and a.xiazhu_timestamp >= $starttime and a.xiazhu_timestamp < $endtime order by a.id desc");
        }elseif($kj_statue==2){  //待开奖
          $dosql->Execute("SELECT a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,b.gametypes,a.xiazhu_qishu,a.xiazhu_timestamp  FROM `#@__xiazhuorder` a inner join `#@__game` b on a.gameid=b.id where a.uid=$uid and a.xiazhu_kjstate=0 and a.xiazhu_timestamp >= $starttime and a.xiazhu_timestamp < $endtime order by a.id desc");
        }
     }else{
       if($kj_statue==1){       //已经中奖
         $dosql->Execute("SELECT a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,b.gametypes,a.xiazhu_qishu,a.xiazhu_timestamp  FROM `#@__xiazhuorder` a inner join `#@__game` b on a.gameid=b.id where a.gameid=$gid and a.uid=$uid and a.xiazhu_kjstate=1 and  a.xiazhu_jiangjin>0  and
           a.xiazhu_timestamp >= $starttime and a.xiazhu_timestamp < $endtime order by a.id desc");
       }elseif($kj_statue==3){ //未中奖
         $dosql->Execute("SELECT a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,b.gametypes,a.xiazhu_qishu,a.xiazhu_timestamp  FROM `#@__xiazhuorder` a inner join `#@__game` b on a.gameid=b.id where a.gameid=$gid and a.uid=$uid and a.xiazhu_kjstate=1 and  a.xiazhu_jiangjin ='0'
         and a.xiazhu_timestamp >= $starttime and a.xiazhu_timestamp < $endtime order by a.id desc");
       }elseif($kj_statue==2){ //待开奖
         $dosql->Execute("SELECT a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,b.gametypes,a.xiazhu_qishu,a.xiazhu_timestamp  FROM `#@__xiazhuorder` a inner join `#@__game` b on a.gameid=b.id where a.gameid=$gid and a.uid=$uid and a.xiazhu_kjstate=0 and a.xiazhu_timestamp >= $starttime and a.xiazhu_timestamp < $endtime order by a.id desc");
       }
     }
  }
  $content=array();
  $num=$dosql->GetTotalRow();
  if($num>0){
  for($i=0;$i<$dosql->GetTotalRow();$i++)
  {
        $row = $dosql->GetArray();

        if($row['xiazhu_kjstate']==1){// 已经开奖（中奖或者未中奖）
        $cha=$row['xiazhu_jiangjin']-$row['xiazhu_sum'];
        $xiazhu_jiangjin=$row['xiazhu_jiangjin'];
        $content[$i]['xiazhumoney']= $row['xiazhu_sum']; //下注金额
        $content[$i]['jiangjin']= $row['xiazhu_jiangjin']; //中奖金额
        $content[$i]['yingkui']= sprintf("%.2f",$cha); //当前下注盈亏
        $content[$i]['xiazhutime']= date("Y-m-d H:i",$row['xiazhu_timestamp']); //下注时间
        $content[$i]['xiazhutitle']= $row['gametypes']."--".$row['xiazhu_qishu']."期"; //下注游戏类别和期数
          if($xiazhu_jiangjin=="0"){
            $jiangjin_state="未中奖";
          }else{
            $jiangjin_state="已中奖";
          }
        $content[$i]['jiangjin_state']= $jiangjin_state; //是否中奖
      }elseif($row['xiazhu_kjstate']==0){// 还未开奖
        $content[$i]['xiazhumoney']= $row['xiazhu_sum']; //下注金额
        $content[$i]['jiangjin']= 0;  //中奖金额
        $content[$i]['yingkui']="0.00"; //当前下注盈亏
        $content[$i]['xiazhutime']= date("Y-m-d H:i",$row['xiazhu_timestamp']); //下注时间
        $content[$i]['xiazhutitle']= $row['gametypes'] .$row['xiazhu_qishu']."期"; //下注游戏类别和期数
        $jiangjin_state="未开奖";
        $content[$i]['jiangjin_state']= $jiangjin_state; //是否开奖
      }
  }
  $Data['content']=$content;
  $sum=0;
  $jiangjin=0;
  $cha=0;
   foreach($content as $val){
     $sum += $val['xiazhumoney']; //投注总金额
     $jiangjin += $val['jiangjin']; //中奖总金额
     $cha += $val['yingkui'];   //下注盈亏
   }
   $Data['touzhu']=$sum;
   $Data['zhongjiang']=$jiangjin;
   $Data['yingli']=$cha;
    $State = 1;
    $Descriptor = '数据获取成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data,
                 );
    echo phpver($result);

  }else{
      $content=(object)null;
      $Data['touzhu']=0;
      $Data['zhongjiang']=0.00;
      $Data['yingli']=0.00;
      $Data['content']=$content;
     $State = 0;
     $Descriptor = '没有下注记录！';
     $result = array (
                 'State' => $State,
                 'Descriptor' => $Descriptor,
                 'Version' => $Version,
                 'Data' => $Data,
                  );
     echo phpver($result);
  }

}else{
  $State = 520;
  $Descriptor = 'token验证失败！';
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
  				         'Version' => $Version,
                   'Data' => $Data,
                   );
  echo phpver($result);
}

?>
