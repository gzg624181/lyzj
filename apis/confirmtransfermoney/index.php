<?php
    /**
	   * 链接地址：confirmtransfermoney  转账给下线会员
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
     * @return string
     *  会员账号id
     *  转账金额 transfer_money
     *  资金密码 moneypassword
     *  转账会员uid  transfer_uid
     *  转账到下线会员uid  transfer_bid
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $moneypassword=md5(md5($moneypassword));
  $r=$dosql->GetOne("SELECT * FROM `#@__members` WHERE id=$id and moneypassword='$moneypassword'");
  if(is_array($r)){
  transfer($id,$transfer_bid,$transfer_uid,$transfer_money);
  $State = 1;
  $Descriptor = '转账成功！';
  $Data[]=$r;
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
               );
  echo phpver($result);
  }else{
    $State = 2;
    $Descriptor = '资金密码错误！';
    $Data[]=$r;
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
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
