<?php
    /**
	   * 链接地址：getquestion  问题列表
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

      $dosql->Execute("SELECT id,title,content,posttime FROM `#@__question`");
      for($i=0;$i<$dosql->GetTotalRow();$i++)
      {
        	$row1 = $dosql->GetArray();
          $Data[$i]=$row1;
          $url=$cfg_weburl."/question_show.php?id=".$row1['id'];
          $Data[$i]['url']=$url;
      }

    $dosql->Execute("SELECT id FROM `#@__question`");
    if($dosql->GetTotalRow()>0){
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
