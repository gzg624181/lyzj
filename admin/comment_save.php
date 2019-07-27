<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('postmode');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:37:10
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__comment';
$gourl  = 'comment.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//删除评论
if($action == 'del2')
{
	$sql = "DELETE from $tbname WHERE id=$id";
	$dosql->ExecNoneQuery($sql);
  header("location:$gourl");
}elseif($action == 'del33'){
	$tbnames = "pmw_levea_message";
	$sql = "DELETE from $tbnames WHERE id=$id";
	$dosql->ExecNoneQuery($sql);
	$gourl= "fankui.php";
  header("location:$gourl");
}elseif($action == 'add'){
	if($userid==-1 || $orderid==-1){
		ShowMsg('请选择会员id和订单号！','-1');
	}else{
	$dosql->ExecNoneQuery("INSERT INTO `$tbname` (comment, recomment, userid,orderid,status,timestamp) VALUES ('$comment', '$recomment', '$userid','$orderid',$status,'$timestamp')");
	header("location:$gourl");
}
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
