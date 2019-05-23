<?php
    /**
	   * 链接地址：wallet  我的钱包
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
     * @return string   会员id： id
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
      $dosql->Execute("SELECT id,name,account,type,bankname,lastbankname FROM `#@__account` WHERE mid=$id");
      if($dosql->GetTotalRow()>0){

      for($i=0;$i<$dosql->GetTotalRow();$i++)
      {
      	$row = $dosql->GetArray();
          $Data['paylist'][$i]=$row;
      }
    }else{
      $Data['paylist']=array();
    }
    $k=$dosql->GetOne("select money from pmw_members where  id = $id");
    $Data['money']=sprintf('%.2f',$k['money']);
    $State = 1;
    $Descriptor = '数据查询成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
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
