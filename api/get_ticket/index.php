<?php
    /**
	   * 链接地址：get_ticket  获取所有的景区
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
     * @提供返回参数账号 type 会员类型  会员id
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){


      $one=1;
      $two=2;
      $three=3;

      $dosql->Execute("SELECT * FROM `#@__ticket` where types='1' and checkinfo=1",$one);
      for($i=0;$i<$dosql->GetTotalRow($one);$i++){
       $row1 = $dosql->GetArray($one);
       $picarrVar=$row1['picarr'];
       $picarrArr=json_decode($picarrVar,true);

       if(is_array($picarrArr)){
       array_walk(
          $picarrArr,
          function(&$value, $key, $prefix){$value = $prefix.$value;},
          $cfg_weburl."/"
       );
     }

       $picarrVar1=json_encode($picarrArr);

       $Data['one'][$i]=$row1;

       $Data['one'][$i]['picarr']=$picarrVar1;
      }



      $dosql->Execute("SELECT * FROM `#@__ticket` where types='2'  and checkinfo=1",$two);
      for($i=0;$i<$dosql->GetTotalRow($two);$i++){
       $row2 = $dosql->GetArray($two);
       $picarrVar=$row2['picarr'];
       $picarrArr=json_decode($picarrVar,true);
if(is_array($picarrArr)){
       array_walk(
          $picarrArr,
          function(&$value, $key, $prefix){$value = $prefix.$value;},
          $cfg_weburl."/"
       );
     }

       $picarrVar1=json_encode($picarrArr);

       $Data['two'][$i]=$row1;

       $Data['two'][$i]['picarr']=$picarrVar1;
      }


      $dosql->Execute("SELECT * FROM `#@__ticket` where types='3'  and checkinfo=1",$three);

      for($i=0;$i<$dosql->GetTotalRow($three);$i++){
       $row3 = $dosql->GetArray($three);
       $picarrVar=$row3['picarr'];
       $picarrArr=json_decode($picarrVar,true);
if(is_array($picarrArr)){
       array_walk(
          $picarrArr,
          function(&$value, $key, $prefix){$value = $prefix.$value;},
          $cfg_weburl."/"
       );
     }

       $picarrVar1=json_encode($picarrArr);

       $Data['three'][$i]=$row1;

       $Data['three'][$i]['picarr']=$picarrVar1;
      }

      $State = 1;
      $Descriptor = '内容获取成功！';
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
