<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:22:45
person: Feng
**************************
*/


//初始化参数
$tbname = 'pmw_banner';
$gourl  = 'bannerss.php';



//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加首页图片
if($action == 'add')
{

  $pictime=strtotime($pictime);
	$pic=$cfg_weburl."/".$pic;

	if($type=="reg"){
		//注册的banner、图片
		$sql = "INSERT INTO `$tbname` (title, pic, type, pictime,typename) VALUES ('$title','$pic', '$type','$pictime','$typename')";

	}elseif($type=="text"){
    $content=replacePicUrl($content, $cfg_weburl);
		$sql = "INSERT INTO `$tbname` (title, pic, type,content, pictime,typename) VALUES ('$title','$pic', '$type','$content','$pictime','$typename')";

	}elseif($type=="ticket"){

	$sql = "INSERT INTO `$tbname` (title, pic,type, linkurl, pictime,typename) VALUES ('$title','$pic', '$type','$linkurl','$pictime','$typename')";

}elseif($type=="no"){
    	$sql = "INSERT INTO `$tbname` (title, pic, type, pictime,typename) VALUES ('$title','$pic', '$type','$pictime','$typename')";
  }
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}

}
else if($action=="TypeChange"){

echo $type;

}
//修改banner图片
else if($action == 'update')
{
	$pictime=strtotime($pictime);

	if(!check_str($pic,$cfg_weburl)){
    $pic=$cfg_weburl."/".$pic; //banner图片
  }
  $r=$dosql->GetOne("SELECT content from $tbname where id=$id");
  $contents=$r['content'];
  $content1=replacePicUrl($contents, $cfg_weburl);

	$sql = "UPDATE `$tbname` SET title='$title',content='$content1',pictime=$pictime, pic='$pic',linkurl='$linkurl',typename='$typename' WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}else if($action == 'del3')
{
	$tbname = 'pmw_question';
	$gourl="question.php";
	$dosql->QueryNone("DELETE FROM `$tbname` WHERE id=$id");
  header("location:$gourl");
  exit();
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
