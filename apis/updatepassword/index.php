<?php
    /**
	   * 链接地址：updatepassword  忘记登陆密码
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
     * @return string   telephone  password
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
    $password=md5(md5($password));//修改后的新的登陆密码
    $r = $dosql->GetOne("SELECT moneypassword  from  `#@__members` WHERE telephone='$telephone'");
    $moneypassword=$r['moneypassword'];
    if($moneypassword!=$password){
      $sql = "UPDATE `#@__members` SET password='$password' WHERE telephone='$telephone'";
      $dosql->ExecNoneQuery($sql);
      $State = 1;
      $Descriptor = '密码修改成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                   );
      echo phpver($result);
    }else{
      $State = 2;
      $Descriptor = '新密码不能与资金密码相同';
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