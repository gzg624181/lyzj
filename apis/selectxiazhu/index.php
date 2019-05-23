<?php
    /**
	   * 链接地址：select下注  筛选下注栏目（暂时只做加拿大28）
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
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $arr=array();
  $dosql->Execute("SELECT id,gametypes,gamepic FROM `#@__game` where game='Canada28'");
  for($i=0;$i<$dosql->GetTotalRow();$i++)
  {
      $row = $dosql->GetArray();
      // $arr=explode('-',$row['gametypes']);
      // $Data[$i]['game']=$arr[0];
      // $Data[$i]['number']=$arr[1];
      $Data[]=$row;
  }
  foreach($Data as $key=> $val){

    $arr=explode('-',$val['gametypes']);
    $Data[$key]['game']=$arr[0];
    $Data[$key]['type']=$arr[1];
    
  }
  if($dosql->GetTotalRow()>0){
    $State = 1;
    $Descriptor = '数据获取成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => array_reverse($Data),
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '数据获取失败';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
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
