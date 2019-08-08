<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('postmode');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:37:10
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__tixian_record';
$gourl  = 'tixian.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


if($action == 'pick_money'){ //添加打款记录

//打款成功之后，将状态改为2
$reason = "提现成功";
$dosql->ExecNoneQuery("UPDATE pmw_tixian SET state=2,reason='$reason' WHERE id=$id");

//打款成功之后，将个人提现佣金的开关关闭掉
if($type == "agency"){
 $tbnames = "pmw_agency";
}elseif($type == "guide"){
 $tbnames = "pmw_guide";
}

// 判断用户的账号里面是否还有余额
$k = $dosql->GetOne("SELECT money from $tbnames where id=$uid");
if($k['money'] == 0){
$dosql->ExecNoneQuery("UPDATE $tbnames set cashmoney=0 where id=$uid");
}


$chargetime = strtotime($chargetime);
$dosql->ExecNoneQuery("INSERT INTO `$tbname` (uid, type, cardname,cardnumber,money,addtime) VALUES ($uid, '$type', '$cardname','$cardnumber',$money,$chargetime)");
$gourl= "success_pickmoney.php?action=success&id=".$id."&uid=".$uid."&type=".$type."&money=".$money;
header("location:$gourl");

}elseif($action="pick_money_failed"){  //申请提现失败
  //更改状态为1，和添加理由
  $dosql->ExecNoneQuery("UPDATE pmw_tixian set state=1,reason='$reason' where id=$id");

  //将提现失败的金额原路返回到当前会员的账号余额里面去
  if($type == "agency"){
   $tbnames = "pmw_agency";
  }elseif($type == "guide"){
   $tbnames = "pmw_guide";
  }

  $k = $dosql->GetOne("SELECT money from $tbnames where id=$uid");
  $moneys = $money + intval($k['money']);

  $dosql->ExecNoneQuery("UPDATE $tbnames set money= '$moneys' where id=$uid");

  $gourl= "success_pickmoney.php?action=failed&id=".$id."&uid=".$uid."&type=".$type;
  header("location:$gourl");

}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
