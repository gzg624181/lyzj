<?php
    /**
	   * 链接地址：tichengrecord  提成记录
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


     if($gid==""){//没有选择哪一款游戏，则，显示所有的提成记录

        $dosql->Execute("SELECT money_list,time_list,leibie,xcode,money FROM `#@__record` where mid=$uid and time_list >= $starttime and time_list < $endtime and types='ticheng' order by id desc limit 0,20");

     }else{

        $dosql->Execute("SELECT money_list,time_list,leibie,xcode,money FROM `#@__record` where mid=$uid and gid=$gid and time_list >= $starttime and time_list < $endtime and types='ticheng' order by id desc  limit 0,20");

    }

  for($i=0;$i<$dosql->GetTotalRow();$i++)
  {
      $row = $dosql->GetArray();
      $unixtime=$row['time_list'];
      $Data[$i]['time']=date("Y-m-d H:i",$unixtime);
      $Data[$i]['money_list']=$row['money_list'];
      $Data[$i]['leibie']=$row['leibie'];
      $Data[$i]['xcode']=$row['xcode'];
      $Data[$i]['money']=$row['money'];
  }
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
