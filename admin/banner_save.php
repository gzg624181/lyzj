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



	if($type=="reg"){
		//注册的banner、图片
		$sql = "INSERT INTO `$tbname` (title, pic, type, pictime,typename,checkinfo) VALUES ('$title','$pic', '$type','$pictime','$typename',$checkinfo)";

	}elseif($type=="text"){
    $content=stripslashes($content);
    $content1=rePic($content, $cfg_weburl);
		$sql = "INSERT INTO `$tbname` (title, pic, type,content, pictime,typename,checkinfo) VALUES ('$title','$pic', '$type','$content1','$pictime','$typename',$checkinfo)";

	}elseif($type=="ticket"){

	$sql = "INSERT INTO `$tbname` (title, pic,type, linkurl, pictime,typename,checkinfo) VALUES ('$title','$pic', '$type','$linkurl','$pictime','$typename',$checkinfo)";

}elseif($type=="no"){
    	$sql = "INSERT INTO `$tbname` (title, pic, type, pictime,typename,checkinfo) VALUES ('$title','$pic', '$type','$pictime','$typename',$checkinfo)";
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
  if($type=="reg" || $type=="no"){
	$sql = "UPDATE `$tbname` SET title='$title',pictime=$pictime, pic='$pic',typename='$typename',checkinfo=$checkinfo WHERE id=$id";
}elseif($type=="ticket"){
  $sql = "UPDATE `$tbname` SET title='$title',pictime=$pictime, pic='$pic',typename='$typename',linkurl='$linkurl',checkinfo=$checkinfo WHERE id=$id";
}elseif($type=="text"){

  $sql = "UPDATE `$tbname` SET title='$title',pictime=$pictime, pic='$pic',typename='$typename',content='$content',checkinfo=$checkinfo WHERE id=$id";
}

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
