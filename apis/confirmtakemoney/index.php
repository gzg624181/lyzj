<?php
    /**
	   * 链接地址：confirmtakemoney  确认提现下分
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
     * @return string   会员账号id 提现金额take_money  提现资金密码 moneypassword  提现到银行卡或者支付宝账号类型id   pick_typesid
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $moneypassword=md5(md5($moneypassword));
  $r=$dosql->GetOne("SELECT * FROM `#@__members` WHERE id=$id and moneypassword='$moneypassword'");
  if(is_array($r)){
  if($r['money']>= $take_money){
  $last_money=$r['money'] - $take_money;
  $dosql->ExecNoneQuery("UPDATE `#@__members` SET money='$last_money' WHERE id=$id");
  //保存提现记录
  $randnumber=rand(100000,999999);
  $pick_order=date("YmdHis").$randnumber;
  $tbnames='pmw_pickmoney';
  $pick_uid=$r['ucode'];
  $pick_telephone=$r['telephone'];
  $pick_time=time();
  $pick_ymd=date("Y-m-d");
  $sql = "INSERT INTO `$tbnames` (mid, pick_uid, pick_number, pick_time, pick_typesid, pick_telephone,randnumber,pick_order,pick_ymd) VALUES ($id, $pick_uid, '$take_money', $pick_time, $pick_typesid ,'$pick_telephone',$randnumber,'$pick_order','$pick_ymd')";
  $dosql->ExecNoneQuery($sql);
  records($take_money,"take_money",$id,$pick_order);

  $State = 1;
  $Descriptor = '提现成功！';
  $Data[]=$r;
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
               );
  echo phpver($result);
  }else{
    $State = 1;
    $Descriptor = '提现金额不足！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                 );
    echo phpver($result);
  }
  }else{
    $State = 2;
    $Descriptor = '提现资金密码错误！';
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
