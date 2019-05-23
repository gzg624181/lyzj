<?php
    /**
	   * 链接地址：feedback  帮助与反馈
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
     * @return string   会员mid   留言内容message
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
$addtime=time();
$addip=GetIP();
$sql = "INSERT INTO `#@__feedback` (mid,message,addtime,addip) VALUES ($mid,'$message',$addtime,'$addip')";
$dosql->ExecNoneQuery($sql);
$row = $dosql->GetOne("SELECT * FROM `#@__feedback` WHERE addtime=$addtime");
if(is_array($row)){
$State = 1;
$Descriptor = '留言成功！';
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
$Descriptor = '留言失败！';
$result = array (
            'State' => $State,
            'Descriptor' => $Descriptor,
            'Version' => $Version,
            'Data' => $Data,
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
