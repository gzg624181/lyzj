<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('member');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:16:14
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__guide';
$gourl  = 'guide.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');

//修改导游信息
if($action == 'update')
{
  $ymdtime=substr($regtime,0,10);
  $regtime=strtotime($regtime);
  if(!check_str($card,$cfg_weburl)){
    $card=$cfg_weburl."/".$card; //导游证件
  }
  if($password==""){ //密码不修改
    $sql = "UPDATE `$tbname` SET name='$name', sex=$sex,card = '$card', cardnumber='$cardnumber', images='$images', content='$content',regtime=$regtime,ymdtime='$ymdtime' WHERE id=$id";
  }else{
    $password=md5(md5($password));
    $sql = "UPDATE `$tbname` SET name='$name', sex=$sex,card = '$card', cardnumber='$cardnumber', images='$images', password='$password', content='$content',regtime=$regtime,ymdtime='$ymdtime' WHERE id=$id";
  }

	if($dosql->ExecNoneQuery($sql))
	{

		header("location:$gourl");
		exit();
	}
}
//ajax获取导游简介
else if($action == 'checkguide')
{
  if($type=="content"){
	$r=$dosql->GetOne("SELECT content FROM $tbname WHERE id=$id");
  $content = $r['content'];
  }elseif($type=="pics"){
  $r=$dosql->GetOne("SELECT pics FROM $tbname WHERE id=$id");
  $content = $r['pics'];
  }elseif($type=="card"){
  $r=$dosql->GetOne("SELECT card,name FROM $tbname WHERE id=$id");
  $contents = $r['card'];
  $content =  "<span style='font-size:18px;font-weight:bold;margin-bottom:10px;'>".$r['name']."的导游证件"."</span>";
  $content .= "<img src='".$contents."' width=90% style='margin-top:17px;'>";
  }
	echo $content;
}

//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
