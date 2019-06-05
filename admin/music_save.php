<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:22:45
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__music';
$gourl  = 'music.php';


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
	$sql = "INSERT INTO `$tbname` (title, url, num, codeurl, addtime, orderid) VALUES ('$title', '$url', $num, '$save_path', '$addtime', $orderid)";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//修改留言
else if($action == 'update')
{
	if(!isset($htop)) $htop = '';
	if(!isset($rtop)) $rtop = '';
	$posttime = GetMkTime($posttime);

	$sql = "UPDATE `$tbname` SET siteid='$cfg_siteid', contact='$contact', content='$content', recont='$recont', orderid='$orderid', posttime='$posttime', htop='$htop', rtop='$rtop', checkinfo='$checkinfo' WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
