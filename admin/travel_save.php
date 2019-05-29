<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');

/*
**************************
(C)2010-2017 phpMyWind.com
update: 2014-5-30 17:22:45
person: Feng
**************************
*/


//初始化参数
$tbname = 'pmw_travel';
$gourl  = 'travel_list.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//发布活动行程
if($action == 'add')
{

	$posttime=time();  //添加时间
	$arr=array();
	$arr = explode(" -- ",$time);
	$endtime = strtotime($arr[1]);
	$starttimes = strtotime($arr[0]);
	$day=($endtime-$starttimes) / (60 * 60 * 24) +1;  //行程的天数
	$jiesuanmoney = $cfg_jiesuan * $day;


  $contents= add_travel($_POST);
	$r=$dosql->GetOne("SELECT company from pmw_agency where id=$aid");
	$company=$r['company'];
	$sql = "INSERT INTO `#@__travel` (title,starttime,endtime,num,origin,content,money,other,posttime,aid,jiesuanmoney,company) VALUES ('$title',$starttimes,$endtime,$num,'$origin','$contents',$money,'$other',$posttime,$aid,'$jiesuanmoney','$company')";
		if($dosql->ExecNoneQuery($sql))
		{
			header("location:$gourl");
			exit();
		}
}
elseif($action=="update"){

	$posttime=strtotime($posttime);  //更新时间
	$arr=array();
	$arr = explode(" -- ",$time);
	$endtime = strtotime($arr[1]);
	$starttimes = strtotime($arr[0]);
	$day=($endtime-$starttimes) / (60 * 60 * 24) +1;  //行程的天数
	$jiesuanmoney = $cfg_jiesuan * $day;

	$content=add_travel($_POST);
	$sql = "UPDATE `$tbname` SET title='$title', starttime=$starttimes,endtime=$endtime,num=$num,origin='$origin',money='$money',jiesuanmoney='$jiesuanmoney',other='$other',posttime=$posttime,content='$content' WHERE `id`=$id";

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
