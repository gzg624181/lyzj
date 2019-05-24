<?php
    /**
	   * 链接地址：reg_guide  导游注册接口
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
     * @导游注册接口 提供返回参数账号，
     * name         导游姓名(varchar)
     * sex          导游性别(int)
     * card         导游证(varchar)
     * cardnumber   导游证号(varchar)
     * tel          导游电话(varchar)
     * images       导游头像(varchar)默认第一次拉取微信头像
     * account      账号(varchar)
     * password     密码(varchar)
     * content      简介(text)
     * pics         导游相册 (选填)(text)
     * regtime      注册时间 (选填)(int)
     */
require_once("../../include/config.inc.php");
require_once("../../admin/sendmessage.php");
$body = file_get_contents('php://input');
$json = json_decode($body,true);
header('Content-Type: application/json; charset=utf-8');

$name=$json['name'];
$sex=$json['sex'];
$card=$json['card'];
$cardnumber=$json['cardnumber'];
$tel=$json['tel'];
$account=$json['account'];
$password=$json['password'];
$content=$json['content'];
$pics=$json['pics'];
$token=$json['token'];
$images=$json['images'];
$code=$json['code'];
$formid=$json['formid'];


$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
$r=$dosql->GetOne("SELECT * FROM `#@__guide` WHERE account='$account'");
if(is_array($r)){ //判断当前注册的手机账号是否已经被注册过
  $State = 0;
  $Descriptor = '此电话号码已经被注册，请重新注册！';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);
}else{
  $appid=$cfg_appid;
  $appsecret=$cfg_appsecret;
  $openid=get_openid($code,$appid,$appsecret);
  $regtime=time();
  $regip=GetIP();
  $getcity=get_city($regip);
  $ymdtime=date("Y-m-d");
  $password=md5(md5($password));
  $sql = "INSERT INTO `#@__guide` (name,sex,card,cardnumber,tel,account,password,content,pics,regtime,regip,ymdtime,images,getcity,openid,formid) VALUES ('$name',$sex,'$card','$cardnumber','$tel','$account','$password','$content','$pics',$regtime,'$regip','$ymdtime','$images','$getcity','$openid','$formid')";
  $dosql->ExecNoneQuery($sql);
  $State = 1;
  $Descriptor = '导游信息注册成功！';
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
