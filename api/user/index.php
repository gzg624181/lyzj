<?php
    /**
	   * 链接地址：user  获取旅行社或者导游信息
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
     * @提供返回参数账号 id  导游或者旅行社信息  类型 type    agency   guide
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
    if($type=="agency"){
      $r=$dosql->GetOne("SELECT * FROM `#@__agency` WHERE id=$id");
      if(!is_array($r)){  //如果传递过来的账号不存在，则不能更新他的formid
        $State = 0;
        $Descriptor = '此账号不存在，请重新注册！';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' => $Data
                     );
        echo phpver($result);
      }else{
      $Data[]=$r;
      $State = 1;
      $Descriptor = '旅行社信息获取成成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                  'Data' => $Data
                   );
      echo phpver($result);
      }
    }elseif($type=="guide"){
      $r=$dosql->GetOne("SELECT * FROM `#@__guide` WHERE id=$id");
      if(!is_array($r)){  //如果传递过来的账号不存在，则不能更新他的formid
        $State = 0;
        $Descriptor = '此账号不存在，请重新注册！';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' => $Data
                     );
        echo phpver($result);
      }else{
      $Data[]=$r;
      $State = 1;
      $Descriptor = '导游信息获取成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                  'Data' => $Data
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
