<?php
    /**
	   * 链接地址： add_bank    添加用户提现账号
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
     * @导游或者旅行社添加提现账号
     * uid             用户id
     * type           用户的类型（agency,guide）
     * openid          用户的openid
     * name           提现用户的姓名
     * tel           提现用户的电话号码
     * cardname      提现银行名称
     * cardnumber     提现银行卡号
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){


  $addtime=time();  //添加时间


  $sql = "INSERT INTO `#@__bank`(uid,name,tel,cardname,cardnumber,openid,type,addtime) VALUES ($uid,'$name','$tel','$cardname',  '$cardnumber','$openid','$type',$addtime)";

  if($dosql->ExecNoneQuery($sql)){
    $State = 1;
    $Descriptor = '用户银行卡信息添加成功!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '用户银行卡信息添加失败!';
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
