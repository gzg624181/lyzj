<?php
    /**
	   * 链接地址：add_pickmoney  添加提现申请
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
     * @旅行社发布旅游行程   提供返回参数账号，
     * uid             此用户的id
     * type           用户的类别
     * applytime      申请时间
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //备注 ：添加行程的时候content 内容以json字符串的形式保存在数据库中去
  if($type =="agency"){
     $tbname = "pmw_agency";
  }else{
     $tbname = "pmw_guide";
  }

  $r = $dosql->GetOne("SELECT money from $tbname where id=$uid");
  $money = $r['money'];

  if($money == "0"){
    $State = 0;
    $Descriptor = '账号余额不足，暂不能申请提现!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);

}else{
  $applytime=time();  //申请时间
  $sql = "INSERT INTO `#@__tixian` (uid,type,money,applytime) VALUES ($uid,'$type','$money',$applytime)";
  $dosql->ExecNoneQuery($sql);

  $dosql->ExecNoneQuery("UPDATE $tbname set money = '0' where id=$uid");
  $State = 1;
  $Descriptor = '提现申请提交成功 我们将在7个工作日内审核完成！';
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
