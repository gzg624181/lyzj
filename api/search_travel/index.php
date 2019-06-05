<?php
    /**
	   * 链接地址：search_travel  搜索行程
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
     * @提供返回参数账号   行程标题 title   行程起始时间 starttime_ymd   行程时间 days
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

    if(isset($keyword)){

    if(strpos($keyword,"-")){
          $starttime_ymd = $keyword;
    }elseif(is_numeric($keyword)){
          $days = $keyword;
    }else{
          $title = $keyword;
    }

    if(isset($title)){

    $dosql->Execute("SELECT * FROM pmw_travel where title like '%$title%' and  state=0 order by id desc ");

    }elseif(isset($starttime_ymd)){

    $dosql->Execute("SELECT * FROM pmw_travel where starttime_ymd ='$starttime_ymd' and state=0 order by id desc ");

    }elseif(isset($days)){

    $dosql->Execute("SELECT * FROM pmw_travel where days=$days and state=0 order by id desc ");

     }

    $num=$dosql->GetTotalRow();//获取数据条数

   }else{

   $num=0;

   }




    if($num>0){

    while($row=$dosql->GetArray()){
      $Data[]=$row;
    }
    
      $State = 1;
      $Descriptor = '搜索数据查询成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                  'Data' => $Data
                   );
      echo phpver($result);
    }else{

      $dosql->Execute("SELECT * FROM pmw_travel where state=0 order by rand() limit 4");
      while($row=$dosql->GetArray()){
        $Data[]=$row;
      }
      $State = 0;
      $Descriptor = '搜索数据为空，推荐数据获取成功！';
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
