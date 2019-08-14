<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message');

/*
**************************
(C)2010-2019 phpMyWind.com
update: 2014-5-30 17:22:45
person: Gang
**************************
*/


//初始化参数
$tbname = 'pmw_order';
$gourl  = 'allorder.php';



//引入操作类
require_once(ADMIN_INC.'/action.class.php');
$Version=date("Y-m-d H:i:s");
$posttime=date("Y-m-d");
//删除订票订单
if($action == 'del6')
{
	$sql = "delete from `$tbname` WHERE id='$id'";

	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}elseif($action=="changestates"){  //和确认状态的合并掉

 $sql="UPDATE $tbname set states=1 where id=$id";
 if($dosql->ExecNoneQuery($sql))
 {
 	header("location:$gourl");
 	exit();
 }

}elseif($action=="changenums"){

	$sql="UPDATE $tbname set infactnums='$infactnums',infacttotalamount='$infacttotalamount',states=1 where id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
   //判断传送过来的值是否和实际购买的相同，如果不同，则执行退款操作（退部分款项）

	 # ①.如果购买的数量和实际取票的数量相等，则不执行退款操作
   # ②.如果不同，则计算需要退款的金额（数量 * 单张票的金额）
	 # ③.如果支付的方式为线下支付，则直接更改退款的状态
   if($infactnums == $nums){
		 $gourl="success_states.php";
	 }else{
		 $refund_nums =   $nums - $infactnums;
		 $refund_money =  $refund_nums * $price;
		 // 如果支付的方式为线下支付，在后台直接更改退款的状态
		 if($paytype=="outline"){
     $dosql->ExecNoneQuery("UPDATE pmw_order set refund_state=1,refund_nums=$refund_nums,refund_money='$refund_money' where id=$id");
		 $gourl="success_states.php";
		 }else{
		 $gourl="../api/weixinpay/refund.php?refund_orderid=".$id."&refund_money=".$refund_money."&refund_nums=".$refund_nums;
	   }
	 }

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
