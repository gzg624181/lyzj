<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');

/*
**************************
(C)2010-2017 phpMyWind.com
update: 2014-5-30 17:22:45
person: Feng
**************************
*/


//初始化参数
$tbname = 'pmw_shoukuan';
$gourl  = 'shoukuan.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');



//添加充值列表
if($action == 'add')
{

    $addtime=time();
    if($type="bankpay"){
    $sql = "INSERT INTO `$tbname` (name, account, type, online, bankname, lastbankname, tips, addtime, orderid) VALUES ('$name', '$account', '$type', $online, '$bankname', '$lastbankname', '$tips', $addtime, $orderid)";
  }elseif($type=="alipay" || $type=="wxpay"){
    $sql = "INSERT INTO `$tbname` (name, account, type, online,  tips, addtime, orderid) VALUES ('$name', '$account', '$type', $online, '$tips', $addtime, $orderid)";
    }
		if($dosql->ExecNoneQuery($sql))
		{
			header("location:$gourl");
			exit();
		}
}


//修改充值简介
else if($action == 'update'){
  $addtime=time();
  if($type="bankpay"){
	$sql = "UPDATE `$tbname` SET name='$name',account='$account',type='$type',online=$online,bankname='$bankname',lastbankname='$lastbankname',tips='$tips',orderid=$orderid,addtime=$addtime WHERE id=$id";
  }else{
  $sql = "UPDATE `$tbname` SET name='$name',account='$account',type='$type',online=$online,tips='$tips',orderid=$orderid,addtime=$addtime WHERE id=$id";
  }
	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}

//删除游戏列表介绍
else if($action == 'del3'){
	$sql = "delete  from `$tbname` where id=$id";
	$dosql->ExecNoneQuery($sql);
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
