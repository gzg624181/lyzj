<?php
    /**
	   * 链接地址：editimages   修改用户头像
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
		 * @return string   新的头像的序号 images    会员id  會員ucode
		 *
	*/
require_once("../../include/config.inc.php");
require_once("changepic.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $s=$dosql->GetOne("SELECT qrcode  from  `#@__members` WHERE id=$id");
  $qrcode=$s['qrcode'];
  $links=$cfg_weburl."/?code=".$ucode;
  $qrcode=select_pic($links,$id,$images,$qrcode);
  $imagesurl="templates/default/images/avatar/".$images.".jpg";
  $sql = "UPDATE `#@__members` SET images='$images',qrcode='$qrcode',imagesurl='$imagesurl' WHERE id=$id";
  $dosql->ExecNoneQuery($sql);
  $r=$dosql->GetOne("SELECT telephone,imagesurl,images  from  `#@__members` WHERE id=$id");
  $oldimages=$r['images'];
  if($oldimages==$images){
    $State = 1;
    $Descriptor = '头像修改成功！';
    $Data[]=$r;
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '昵称修改失败！';
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
