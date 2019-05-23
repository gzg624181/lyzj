<?php
    /**
	   * 链接地址：getgonggao  公告中心
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
     * @return string   null
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
      $one=1;
      $two=2;
      $three=3;
      $four=4;
      $dosql->Execute("SELECT title,content,issuetime FROM `#@__gonggao` WHERE type='newgonggao'",$one);
      for($i=0;$i<$dosql->GetTotalRow($one);$i++)
      {
        	$row1 = $dosql->GetArray($one);
          $Data['newgonggao'][$i]=$row1;
      }

      $dosql->Execute("SELECT  title,content,issuetime  FROM `#@__gonggao` WHERE type='xiaoxi'",$two);
      for($j=0;$j<$dosql->GetTotalRow($two);$j++)
      {
          $row2 = $dosql->GetArray($two);
          $Data['xiaoxi'][$j]=$row2;
      }

      $dosql->Execute("SELECT title,content,issuetime  FROM `#@__gonggao` WHERE type='bidu'",$three);
      for($k=0;$k<$dosql->GetTotalRow($three);$k++)
      {
          $row3 = $dosql->GetArray($three);
          $Data['bidu'][$k]=$row3;
      }
    $dosql->Execute("SELECT id FROM `#@__gonggao`",$four);
    if($dosql->GetTotalRow($four)>0){
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
    $State = 0;
    $Descriptor = '数据查询失败！';
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
