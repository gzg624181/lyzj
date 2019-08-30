<?php
    /**
	   * 链接地址：get_task  获取营销活动的开关
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
     * @提供返回参数账号  开关变量名称  cfg_task  :  Y 开启   N 关闭
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

      $r = $dosql->GetOne("SELECT varvalue FROM pmw_webconfig where varname='cfg_task'");

      if(!is_array($r)){
        $State = 0;
        $Descriptor = '暂无此活动开关';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' =>$Data
                     );
        echo phpver($result);
      }else{
      $State = 1;
      $Descriptor = '活动开关获取成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                  'Data' => $r
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
