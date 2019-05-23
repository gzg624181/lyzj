<?php
    /**
	   * 链接地址：reg  会员注册接口
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
     * @会员注册账号 提供返回参数账号，  手机号码 telephone,code,password,nickname,bcode(选填)
     */
require_once("../../include/config.inc.php");
require_once("getcode.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
$r=$dosql->GetOne("SELECT * FROM `#@__members` WHERE telephone=$telephone");
if(!is_array($r)){
$r = $dosql->GetOne("SELECT code FROM `#@__yzm` WHERE phone=$telephone");
$getcode=$r['code'];
if(is_array($r) && $r['code']==$code){
$regtime=time();
$regip=GetIP();
$password=md5(md5("$password"));
$ucode=getcode();
$links=$cfg_weburl."/?code=".$ucode;
$qrcode=createQr($links);
$getcity=get_city($regip);
$sql = "INSERT INTO `#@__members` (telephone,password,nickname,ucode,bcode,regtime,regip,qrcode,ymdtime,getcity,links) VALUES ('$telephone','$password','$nickname','$ucode','$bcode',$regtime,'$regip','$qrcode','$Version','$getcity','$links')";
$dosql->ExecNoneQuery($sql);

//判断bcode是否为空
if($bcode!=""){

   //判断当前注册的推荐人是否有推荐人
    $s=$dosql->GetOne("SELECT bcode FROM `#@__members` where ucode=$bcode");
    $bbcode=$s['bcode']; //推荐人的推荐人
    if($bbcode!=""){
    //如果推荐人的推荐人不为空，则给推荐人添加二级代理
    $jointime=time();
    $sql = "INSERT INTO `#@__erjidaili` (ucode,zscode,ejcode,jointime) VALUES ($bbcode,$bcode,$ucode,$jointime)";
    $dosql->ExecNoneQuery($sql);
    }

}


$row = $dosql->GetOne("SELECT telephone,nickname,ucode FROM `#@__members` WHERE regtime=$regtime");
if(is_array($row)){
$State = 1;
$Descriptor = '会员注册成功！';
$Data[]=$row;
$result = array (
            'State' => $State,
            'Descriptor' => $Descriptor,
            'Version' => $Version,
            'Data' => $Data,
             );
echo phpver($result);
}else{
$State = 0;
$Descriptor = '会员注册失败！';
$result = array (
            'State' => $State,
            'Descriptor' => $Descriptor,
            'Version' => $Version,
            'Data' => $Data,
    );
echo phpver($result);
}

}else{
  $State = 2;
  $Descriptor = '验证码错误！';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data,
      );
echo phpver($result);
}
}

}

else{
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
