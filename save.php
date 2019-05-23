<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

if($action == 'checkphone')
{
$str = $dosql->GetOne("SELECT * FROM `pmw_members` WHERE telephone='$telephone'");
if(is_array($str)){
  echo  1;
	}else
	{
  echo 0;
	}
}elseif($action=='sendcode'){
   $content=sendcode($cfg_message_id,$cfg_message_pwd,$cfg_message_signid,$cfg_message_regid,$telephone);
   //判断验证码发送成功的时候
   if($content!=0){
   $start_time=date("Y-m-d H:i:s");
   $date=date("Y-m-d");
   $s=$dosql->GetOne("select * from `#@__yzm` where phone='$telephone'");
   if(is_array($s)){
   $r = $dosql->GetOne("SELECT MAX(num) AS `num` FROM `#@__yzm` where phone='$telephone' and date='$date'");
   if(is_array($r)){
   $num = (empty($r['num']) ? 1 : ($r['num'] + 1));
   }else{
   $num=1;
   }
   $sql = "UPDATE `#@__yzm` SET code='$content',start_time='$start_time',num='$num',date='$date' where phone='$telephone'";
   $dosql->ExecNoneQuery($sql);
   }else{
   $sql = "INSERT INTO  `#@__yzm` (phone,code,start_time,num,date) VALUES ('$telephone','$content','$start_time',1,'$date')";
   $dosql->ExecNoneQuery($sql);
   }
   echo $content;
  }else{
   echo 0;
  }
}elseif($action=='forgetpassword_next'){

  $content=forgetpassword_sendcode($cfg_message_id,$cfg_message_pwd,$cfg_message_signid,$cfg_forgetpassword,$telephone);
  //判断验证码发送成功的时候
  if($content!=0){
  $start_time=date("Y-m-d H:i:s");
  $date=date("Y-m-d");
  $s=$dosql->GetOne("select * from `#@__yzm` where phone='$telephone'");
  if(is_array($s)){
  $r = $dosql->GetOne("SELECT MAX(num) AS `num` FROM `#@__yzm` where phone='$telephone' and date='$date'");
  if(is_array($r)){
  $num = (empty($r['num']) ? 1 : ($r['num'] + 1));
  }else{
  $num=1;
  }
  $sql = "UPDATE `#@__yzm` SET code='$content',start_time='$start_time',num='$num',date='$date' where phone='$telephone'";
  $dosql->ExecNoneQuery($sql);
  }else{
  $sql = "INSERT INTO  `#@__yzm` (phone,code,start_time,num,date) VALUES ('$telephone','$content','$start_time',1,'$date')";
  $dosql->ExecNoneQuery($sql);
  }
  echo $content;
  }else{
  echo 0;
  }
}
elseif($action == 'reg'){
  $Version=date("Y-m-d H:i:s");
  $r = $dosql->GetOne("SELECT code FROM `#@__yzm` WHERE phone=$phone");
  $getcode=$r['code'];
  if(is_array($r) && $getcode==$sendcode){
  $regtime=time();
  $regip=GetIP();
  $password=md5(md5("$password"));
  $ucode=getcode();
  $links=$cfg_weburl."/?code=".$ucode;
  $qrcode=createQr($links);
  $getcity=get_city($regip);
  $devicetype=get_device_type();
  $sql = "INSERT INTO `#@__members` (telephone,password,nickname,ucode,bcode,regtime,regip,qrcode,ymdtime,getcity,links,devicetype) VALUES ('$phone','$password','$nickname','$ucode','$bcode',$regtime,'$regip','$qrcode','$Version','$getcity','$links','$devicetype')";
  $dosql->ExecNoneQuery($sql);
  header('Location: http://www.baidu.com/');
   exit;
  }else{
  ShowMsg('验证码错误！','index.php');
  }

}elseif($action=="forgetpassword"){
   $s=$dosql->GetOne("select id from `#@__members` where telephone='$mobile'");
   if(is_array($s)){
    $id=$s['id'];
    $gourls = "forgetpassword_next.php?id=".$id;
    header("location:$gourls");
		exit();
   }else{
    ShowMsg('暂无此账号，请重新注册！',-1);
   }

}elseif($action=="getup"){
  $password=md5(md5("$password"));
  $sql = "UPDATE `#@__members` SET password='$password' where telephone='$phone'";
  $dosql->ExecNoneQuery($sql);
  header('Location: http://www.baidu.com/');
  exit;
}

?>
