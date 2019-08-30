<?php
    /**
	   * 链接地址：get_days  获取所有待预约的行程的行程天数
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
     *
     * @param array $Data 数据
     *
     * @return string
     *
     * @提供返回参数账号
     */
require_once("../../include/config.inc.php");
header("Content-type:application:application/json; charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

      $dosql->Execute("SELECT distinct days FROM pmw_travel where state=0 ORDER BY days asc");
      $num =$dosql->GetTotalRow();
      if($num==0){
        $State = 0;
        $Descriptor = '数据查询为空！';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' =>$Data
                     );
        echo phpver($result);
      }else{
      while($row=$dosql->GetArray()){
        $Data[]=$row;
      }
      $State = 1;
      $Descriptor = '数据获取成功！';
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
