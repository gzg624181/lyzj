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
$gourl  = 'banner.php';



//引入操作类
require_once(ADMIN_INC.'/action.class.php');

if($action == 'add')
{
	$sql = "INSERT INTO `$tbname` (number,creatime) VALUES ('$money','$creatime')";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}elseif($action == 'question_add')
{
	$posttime=time();
	$tbname = 'pmw_question';
	$gourl="question.php";
	$sql = "INSERT INTO `$tbname` (title,content,posttime) VALUES ('$title','$content',$posttime)";
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}//修改问题列表
else if($action == 'question_update')
{
	$posttime=time();
	$sql = "UPDATE `$tbname` SET title='$title',content='$content',posttime=$posttime WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		$gourl="question.php";
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
//修改
else if($action == 'update')
{
	$pictime=strtotime($pictime);
	$pic=$cfg_weburl."/".$pic;
	$sql = "UPDATE `$tbname` SET title='$title',pic='$pic',content='$content',pictime=$pictime WHERE id=$id";
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
