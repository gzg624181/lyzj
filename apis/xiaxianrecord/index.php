<?php
    /**
	   * 链接地址：xiaxianrecord  下线记录
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
     * @return string   用户会员id  uid  默认起始时间为用户注册的那一天计算起
     *
     */
require_once("../../include/config.inc.php");
$Data=array();
$Data1=array();
$Data2=array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $r=$dosql->GetOne("SELECT regtime,ucode FROM `pmw_members` where id= $uid");
  $bcode=$r['ucode'];
  if($bcode!=''){

  $starttime = empty($starttime) ?  strtotime(date("Y-m-d",$r['regtime'])) : $starttime;
  $endtime = empty($endtime) ?  strtotime(date("Y-m-d",time()+24*3600)) : $endtime;
  $one=1;
  $two=2;
  $dosql->Execute("SELECT a.ucode,b.time_list,sum(b.money_list) as moneys FROM `#@__members` a inner join `#@__record` b on a.id=b.xid  where a.bcode='$bcode' and b.types='ticheng' and  b.time_list >= $starttime and b.time_list < $endtime group by a.ucode",$one);



  for($i=0;$i<$dosql->GetTotalRow($one);$i++)
  {
      $row = $dosql->GetArray($one);
      if(is_array($row)){
        $sum_ticheng =$row['moneys'];
        $Data1[$i]['ticheng'] = "+".$sum_ticheng;
        $Data1[$i]['bcode'] = $row['ucode'];
        $Data1[$i]['time'] = date("Y-m-d H:i:s",$row['time_list']);
      }else{
        $Data1=array();
      }
  }

  $dosql->Execute("SELECT b.uid,sum(b.xiazhu_sum) as xiazhu_sum,sum(b.xiazhu_jiangjin) as xiazhu_jiangjin FROM `#@__members` a inner join `#@__xiazhuorder` b on a.id=b.uid  where a.bcode='$bcode' and b.xiazhu_kjstate=1 and  b.xiazhu_timestamp >= $starttime and b.xiazhu_timestamp < $endtime group by b.uid",$two);
  for($j=0;$j<$dosql->GetTotalRow($two);$j++)
  {
      $show = $dosql->GetArray($two);
      if(is_array($show)){
        $xiazhu_sum =$show['xiazhu_sum'];
        $xiazhu_jiangjin=$show['xiazhu_jiangjin'];
        $Data2[$j]['yingkui'] = $xiazhu_jiangjin - $xiazhu_sum;
      }else{
        $Data2=array();
      }
  }
  //  $Data=array_merge($Data1,$Data2);
    foreach($Data1 as $key=>$vo){
    		$Data[] = array_merge($vo,$Data2[$key]);
    	}
    $State = 1;
    $Descriptor = '下线记录获取成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data,
                 );
    echo phpver($result);
 }else{
   $State = 0;
   $Descriptor = '没有下线记录！';
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
