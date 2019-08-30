<?php
    /**
	   * 链接地址：get_sharepic  获取海报图片
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
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $r = $dosql->GetOne("SELECT * FROM pmw_share where id=3");
  //分享后跳转的页面
  $shareimage = $cfg_weburl."/".$r['imagesurl'];
  //分享页面
  $share = $cfg_weburl."/".$r['share'];
  // 分享弹框
  $tubiaopic = $cfg_weburl."/".$r['tubiaopic'];

      $Data = array(
        "shareimage" => $tubiaopic,
        "poster"     => $share,
        "share"      => $shareimage
      );
      $State = 1;
      $Descriptor = '数据获取成功！';
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
