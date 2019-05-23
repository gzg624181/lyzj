<?php
    /**
	   * 链接地址：tgzx推广中心  （昨日提成，总提成）
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
     * @return string   用户会员id uid
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$sumheji = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  //计算昨日提成（下级代理下注，上级的提成）
  $starttime = strtotime(date("Y-m-d",time()-24*3600));
  $endtime = strtotime(date("Y-m-d",time()));
  $one=1;
  $two=2;
  $dosql->Execute("SELECT sum(money_list) as money FROM  `#@__record` WHERE time_list >= $starttime and time_list < $endtime and mid=$uid and types='ticheng'",$one);
  while($row = $dosql->GetArray($one))
  {
   $sumheji[]=$row['money'];
   }

   $dosql->Execute("SELECT sum(money_list) as allmoney FROM  `#@__record` WHERE mid=$uid and types='ticheng'",$two);
   while($row = $dosql->GetArray($two))
   {
    $heji[]=$row['allmoney'];
    }

  $Data['zrfs']=sprintf("%.2f",array_sum($sumheji));
  $Data['zfs']=sprintf("%.2f",array_sum($heji));
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
