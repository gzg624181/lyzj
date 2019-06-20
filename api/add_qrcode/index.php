<?php
    /**
	   * 链接地址：add_qrcode  生成二维码
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
     * @购票订单   提供返回参数账号，
     * id
     * time
     * poster
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //1.第一步 先生成小程序二维码
  $xiaochengxu_path="pages/play/index";  //默认扫码之后进入的页面
	$erweima_name=date("Ymdhis");
	$url="uploads/erweima/".$erweima_name.".png";
	$save_path="../../".$url;         //生成成功之后的二维码地址
	$access_token=token($cfg_music_appid,$cfg_music_appsecret);
//  $access_token=token($cfg_music_appid,$cfg_music_appsecret);
	$erweima= save_erweima($access_token,$xiaochengxu_path,$save_path,$url,$id,$time,$poster);

  $srcImg=$cfg_weburl."/templates/default/images/img.jpg";
  $waterImg= $cfg_weburl."/".$erweima;

  $savename="new_".$erweima_name.".png";
  $savepath="../../uploads/erweima";
  $newimg=img_water_mark($srcImg, $waterImg, $savepath, $savename, $positon=5, $alpha=100);

  $State = 1;
  $Descriptor = '小程序码生成成功!';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $newimg
               );
  echo phpver($result);

}else{
  $State = 520;
  $Descriptor = 'token验证失败！';
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
  				         'Version' => $Version,
                   'Data' => $newimg,
                   );
  echo phpver($result);
}

?>
