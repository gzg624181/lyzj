<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:22:45
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__member';
$gourl  = 'user.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加音频文件
if($action == 'add')
{

	$addtime = GetMkTime($addtime);
  $appid=$cfg_music_appid;  //音频小程序appid
  $secret=$cfg_music_appsecret; //音频小程序秘钥
  $xiaochengxu_path="pages/play/index";  //默认扫码之后进入的页面
  $erweima_name=date("Ymdhis");
  $urls="/uploads/erweima/".$erweima_name.".png";
  $save_path=$cfg_weburl.$urls;         //生成成功之后的二维码地址
  $url=$cfg_weburl."/".$url;
	$sql = "INSERT INTO `#@__music` (title, url, num, codeurl, addtime, orderid, sharename) VALUES ('$title', '$url', $num, '$save_path', '$addtime', $orderid, '$sharename')";
	if($dosql->ExecNoneQuery($sql))
	{
		$gourl="music.php";
		header("location:$gourl");
		exit();
	}
}
else if($action == 'playmp3')
{

	$r=$dosql->GetOne("SELECT url,title FROM pmw_music WHERE id=$id");
  $url= $r['url'];
  $content =  "<span style='font-size:18px;font-weight:bold;margin-bottom:10px;'>".$r['title']."播放测试"."</span>";
	$content .="<video controls='' autoplay='' name='media'><source src=".$url." type='audio/mpeg'></video>";
	echo $content;
}

//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
