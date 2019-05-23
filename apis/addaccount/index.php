<?php
    /**
	   * 链接地址：addaccount  添加提现账号（支付宝 银行卡号）
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
     * @return string   type(alipay,cardpay) ,会员id: mid
     * 如果是alipay ，则需要提供参数：name,account
     * 如果是cardpay, 则需要提供参数：name,account,bankname,lastbankname
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $addtime=time();
  if($type=="alipay"){
    $tips="* 支付宝提现额度限制：100 ~ 5000，超出请选择银行卡提现";
    $r=$dosql->GetOne("SELECT id FROM `#@__account` where mid=$mid");
    if(is_array($r)){
    $sql = "INSERT INTO `#@__account` (name,account,type,addtime,mid,tips) VALUES ('$name','$account','$type',$addtime,$mid,'$tips')";
    $dosql->ExecNoneQuery($sql);
    }else{
    $sql = "INSERT INTO `#@__account` (name,account,type,addtime,mid,tips,sets) VALUES ('$name','$account','$type',$addtime,$mid,'$tips',1)";
    $dosql->ExecNoneQuery($sql);
    }

  }elseif($type=="cardpay"){
    $tips="* 银行卡提现额度限制：100 ~ 99999999，小于额度请选择支付宝提现";
    $r=$dosql->GetOne("SELECT id FROM `#@__account` where mid=$mid");
    if(is_array($r)){
    $sql = "INSERT INTO `#@__account` (name,account,type,addtime,mid,bankname,lastbankname,tips) VALUES ('$name','$account','$type',$addtime,$mid,'$bankname','$lastbankname','$tips')";
    }else{
    $sql = "INSERT INTO `#@__account` (name,account,type,addtime,mid,bankname,lastbankname,tips,sets) VALUES ('$name','$account','$type',$addtime,$mid,'$bankname','$lastbankname','$tips',1)";
    }
    $dosql->ExecNoneQuery($sql);
  }
  $r=$dosql->GetOne("SELECT * FROM `#@__account` WHERE addtime=$addtime");
  if($r['account']==$account){
    $State = 1;
    $Descriptor = '提现账号添加成功！';
    $Data[]=$r;
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
