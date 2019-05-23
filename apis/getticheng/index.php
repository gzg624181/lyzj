<?php
    /**
	   * 链接地址：getticheng  昨日提成，总提成
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
     * 中奖状态 （全部，已中奖 1，未中奖 0，待开奖 2）
     */
require_once("../../include/config.inc.php");
$Data=array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $r=$dosql->GetOne("SELECT regtime,ucode FROM `pmw_members` where id= $uid");
  $bcode=$r['ucode'];
  if($bcode!=''){

  $starttime = empty($starttime) ?  strtotime(date("Y-m-d",time()-24*3600)) : $starttime; //昨日提成时间
  $endtime = empty($endtime) ?  strtotime(date("Y-m-d",time())) : $endtime;
  $one=1;
  $two=2;
  //昨日提成
  $dosql->Execute("SELECT sum(money_list) as money_list FROM `#@__record` where mid=$uid and types='ticheng' and time_list >= $starttime and time_list < $endtime",$one);

  for($i=0;$i<$dosql->GetTotalRow($one);$i++)
  {
      $row = $dosql->GetArray($one);
      if(is_array($row)){
        $yesterday_ticheng =$row['money_list'];
        $Data1[$i]['yesterday'] = $yesterday_ticheng;
      }else{
        $Data1[0]['yesterday']=intval(0.00);
      }
  }
//总提成
  $dosql->Execute("SELECT sum(money_list) as tc FROM `#@__record` where mid=$uid and types='ticheng'",$two);
  for($j=0;$j<$dosql->GetTotalRow($two);$j++)
  {
      $show = $dosql->GetArray($two);
      if(is_array($show)){
        $all_ticheng =$show['tc'];
        $Data2[$j]['all'] = $all_ticheng;
      }else{
        $Data2[0]['all']=intval(0.00);
      }
  }
    foreach($Data1 as $key=>$vo){
    		$Data[] = array_merge($vo,$Data2[$key]);
    	}
    $State = 1;
    $Descriptor = '提成记录获取成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data,
                 );
    echo phpver($result);
 }else{
   $State = 0;
   $Descriptor = '没有提成记录！';
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
