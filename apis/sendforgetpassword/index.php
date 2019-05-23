<?php
    /**
	   * 链接地址：sendforgetpassword  获取忘记密码的短信验证码接口
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

     * @param array $Data 数据
     *
     * @return string
     *
     * @发送登陆的手机号码信息 提供返回参数账号，  手机号码 telephone
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
$s=$dosql->GetOne("select telephone from `#@__members` where telephone='$telephone'");
if(!is_array($s)){
  $State = 3;
  $Descriptor = '手机号还未注册！';
  $Data[]="";
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
  			 	        'Version' => $Version,
                  'Data' => $Data,
          );
  echo phpver($result);

}else{
//当填写的号码已经注册了，则发送忘记密码的短信
$content=sendcode($cfg_message_id,$cfg_message_pwd,$cfg_message_signid,$cfg_forgetpassword,$telephone);
if($content==0){
  $State = 0;
  $Descriptor = '验证码发送失败!';
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
  			 	        'Version' => $Version,
                  'Data' => $Data,
          );
  echo phpver($result);
}else{
//判断验证码发送成功的时候
$start_time=date("Y-m-d H:i:s");
$date=date("Y-m-d");
$s=$dosql->GetOne("select * from `#@__yzm` where phone='$telephone'");
if(is_array($s)){
$r = $dosql->GetOne("SELECT MAX(num) AS `num` FROM `#@__yzm` where phone='$telephone' and date='$date'");
if(is_array($r)){
$num = (empty($r['num']) ? 1 : ($r['num'] + 1));
}else{
$num=1;
}
$sql = "UPDATE `#@__yzm` SET code='$content',start_time='$start_time',num='$num',date='$date' where phone='$telephone'";
$dosql->ExecNoneQuery($sql);
}else{
$sql = "INSERT INTO  `#@__yzm` (phone,code,start_time,num,date) VALUES ('$telephone','$content','$start_time',1,'$date')";
$dosql->ExecNoneQuery($sql);
}
$Data=array(
  "telephone"=>$telephone,
  "code"=>$content
 );
$State = 1;
$Descriptor = '验证码发送成功！';
$result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
				         'Version' => $Version,
                 'Data' => $Data,
                 );
echo phpver($result);
}
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
