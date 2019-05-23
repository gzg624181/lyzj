<?php
    /**
	   * 链接地址：login  会员登陆接口
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
     * @return string   telephone,password
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $password=md5(md5($password));
  $r = $dosql->GetOne("SELECT id,ucode,telephone,nickname,getname,qq,imagesurl,images FROM `#@__members` WHERE telephone='$telephone' and password='$password'");
  if(is_array($r)){
    $State = 1;
    $Descriptor = '会员登陆成功！';
    $Data[]=$r;
    // if($r['images']!=""){
    //   $Data['imagesurl']=$cfg_weburl."/".$r['imagesurl'];
    // }
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data,
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '用户名或密码不正确！';
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
