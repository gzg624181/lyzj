<?php
    /**
	   * 链接地址：get_recommand_scenic  获取所有已经上线的景点信息
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
     * @提供返回参数账号  通过当前经纬度，判断当前的具体定位地址，省份
     */
require_once("../../include/config.inc.php");
header("Content-type:applicaton/json; charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
    $r=$dosql->GetOne("SELECT imagesurl FROM pmw_share  where id=3");
    $cfg_default = $r['imagesurl'];

    $dosql->Execute("SELECT id,names,province,city,label,solds,types,flag,lowmoney,remarks,level,picarr FROM pmw_ticket where checkinfo=1 and live_province ='$live_province' and live_city='$live_city' order by id desc ");

    $num=$dosql->GetTotalRow();//获取数据条数

      if($num!=0){
        for($i=0;$i<$num;$i++){
         $row = $dosql->GetArray();
         $Data[$i]=$row;
         $picarr=stripslashes($row['picarr']);
         if($picarr==""){
         $picarrTmp=array("0"=>$cfg_weburl."/".$cfg_default);
         $picarr = json_encode($picarrTmp);
         }else{
         $picarr=Common::GetPic($picarr, $cfg_weburl);
         }

         $Data[$i]['picarr']=$picarr;
        }

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
        $Data=array();
        $State = 0;
        $Descriptor = '数据查询为空！';
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
