<?php
    /**
	   * 链接地址：get_ticket_three  周边景区
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

      $three=3;
<<<<<<< HEAD
      $r=$dosql->GetOne("SELECT imagesurl FROM pmw_share  where id=3");
      $cfg_default = $r['imagesurl'];
=======

>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587
      $dosql->Execute("SELECT * FROM `#@__ticket` where types='9'  and checkinfo=1",$three);

      for($i=0;$i<$dosql->GetTotalRow($three);$i++){
       $row3 = $dosql->GetArray($three);
       $Data[$i]=$row3;
<<<<<<< HEAD

       $picarr=stripslashes($row3['picarr']);
       if($picarr==""){
       $picarrTmp=array("0"=>$cfg_weburl."/".$cfg_default);
       $picarr = json_encode($picarrTmp);
       }else{
       $picarr=GetPic($picarr, $cfg_weburl);
       }
=======
       $picarr=stripslashes($row3['picarr']);
       $picarr=GetPic($picarr, $cfg_weburl);
>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587
       $content=stripslashes($row3['content']);
       $content=rePic($content, $cfg_weburl);
       $xuzhi=stripslashes($row3['xuzhi']);
       $xuzhi=rePic($xuzhi, $cfg_weburl);
       $Data[$i]['picarr']=$picarr;
       $Data[$i]['xuzhi']=$xuzhi;
       $Data[$i]['content']=$content;
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
