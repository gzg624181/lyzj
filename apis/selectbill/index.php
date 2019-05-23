<?php
    /**
	   * 链接地址：selectbill  筛选账单栏目
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
  $dosql->Execute("SELECT types FROM `#@__record` group by types");
  for($i=0;$i<$dosql->GetTotalRow();$i++)
  {
      $row = $dosql->GetArray();
      switch($row['types']){
        case "fanshui":
        $types="返水";
        break;
        case "recharge":
        $types="充值";
        break;
        case "take_money":
        $types="提现";
        break;
        case "ticheng":
        $types="提成";
        break;
        case "fenhong":
        $types="分红";
        break;
        case "jieshao":
        $types="介绍";
        break;
        case "yewu":
        $types="业务";
        break;
        case "active":
        $types="活动";
        break;
        case "all":
        $types="全部";
        break;
      }
      $Data[$i]['types']=$types;
      $Data[$i+1]['types']="全部";
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
