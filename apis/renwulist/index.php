<?php
    /**
	   * 链接地址：renwulist  任务大厅
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
      $dosql->Execute("SELECT mtitle,msum,mmoney,mrules FROM `#@__renwu` WHERE mtype='list'",$one);
      for($i=0;$i<$dosql->GetTotalRow($one);$i++)
      {
        	$row1 = $dosql->GetArray($one);
          $Data['missions'][$i]=$row1;
          $Data['missions'][$i]['mtime']="有效期：".date("m-d")." 00:00~".date("m-d")." 23:59";
      }

      $dosql->Execute("SELECT mtitle,msum,mmoney,mrules FROM `#@__renwu` WHERE mtype='complete'",$two);
      for($j=0;$j<$dosql->GetTotalRow($two);$j++)
      {
          $row2 = $dosql->GetArray($two);
          $Data['missions2'][$j]=$row2;
          $Data['missions2'][$j]['mtime']="有效期：".date("m-d")." 00:00~".date("m-d")." 23:59";
      }

      $r=$dosql->GetOne("SELECT  mrules  FROM `#@__renwu` WHERE mtype='xuzhi'");
      $Data['missionMsg']=$r['mrules'];

    $dosql->Execute("SELECT id FROM `#@__renwu`",$four);
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
