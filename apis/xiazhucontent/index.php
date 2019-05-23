<?php
    /**
	   * 链接地址：用户下注的详情（下注的注数，下注的详情号码）
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
     *  params: uid, 用户id
     *          xiazhu_times当期开奖期数   当前游戏的gameid
     *
     */
header('content-type:application/json;charset=utf8');
require_once("../../include/config.inc.php");
$Data = array();
$money= array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  $now=time();
  $Data=array();
  $one=1;
  $dosql->Execute("SELECT id from pmw_xiazhucontent  where  userid =$uid  and xiazhu_times='$xiazhu_times' and gameid=$gameid",$one);
  $num=$dosql->GetTotalRow($one);
  if($num>0){
  $Data['serial']=$xiazhu_times;
  $Data['number']=$num;
  $Data['uid']=$uid;

  $two=2;
  $dosql->Execute("SELECT id,xiazhu_type,xiazhu_money,gameid from pmw_xiazhucontent  where  userid =$uid  and xiazhu_times='$xiazhu_times' and gameid=$gameid",$two);
  for($i=0;$i<$num;$i++)
  {
      $row = $dosql->GetArray($two);
      $money[]=$row['xiazhu_money'];
      $Data['kjnumber'][$i]=$row;
  }

  $Data['money']=array_sum($money);
  $Data['gameid']=$gameid;
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
