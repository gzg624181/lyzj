<?php
    /**
	   * 链接地址：updateaccount  更改提现账号（支付宝 银行卡号）
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
     * @return string   type(alipay,cardpay) ,选择当前账户的id，会员mid
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $r=$dosql->GetOne("SELECT id FROM `#@__account` WHERE mid=$mid and sets=1");
  $sid=$r['id'];//当前默认的选择的账户的id
  if($sid!=$id){
   //将当前默认的账户的sets改为0
   $dosql->ExecNoneQuery("UPDATE `#@__account` SET sets=0 WHERE id=$sid");
   //同时将默认的账户更改为新的账户上面去
   $dosql->ExecNoneQuery("UPDATE `#@__account` SET sets=1 WHERE id=$id");
  }
  $s=$dosql->GetOne("SELECT * FROM `#@__account` WHERE id=$id");
  if(is_array($s)){
    $State = 1;
    $Descriptor = '提现账号更改成功！';
    $Data[]=$s;
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '提现账号添加失败！';
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
