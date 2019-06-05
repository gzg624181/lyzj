<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('member');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:16:14
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__agency';
$gourl  = 'agency.php';
$appid=$cfg_appid;
$appsecret=$cfg_appsecret;


//引入操作类
require_once(ADMIN_INC.'/action.class.php');
require_once('sendmessage.php');

//修改旅行社信息
if($action == 'update')
{

  if(!isset($picarr))        $picarr = '';
//合同组图
	if(is_array($picarr))
	{
		$picarrNum = count($picarr);
		$picarrTmp = '';

		for($i=0;$i< $picarrNum;$i++)
		{
			$picarrTmp[] = $cfg_weburl."/".$picarr[$i];
		}

		$picarr = json_encode($picarrTmp);
	}

  $ymdtime=substr($regtime,0,10);
  $regtime=strtotime($regtime);
  if(!check_str($cardpic,$cfg_weburl)){
    $cardpic=$cfg_weburl."/".$cardpic; //导游证件
  }
  if($password==""){ //密码不修改
    $sql = "UPDATE `$tbname` SET name='$name',company='$company', address='$address',cardpic = '$cardpic',agreement='$picarr', images='$images', regtime=$regtime,ymdtime='$ymdtime' WHERE id=$id";
  }else{
    $password=md5(md5($password));
    $sql = "UPDATE `$tbname` SET name='$name',company='$company', address='$address',cardpic = '$cardpic',agreement='$picarr', images='$images', regtime=$regtime,ymdtime='$ymdtime',password='$password' WHERE id=$id";
  }

	if($dosql->ExecNoneQuery($sql))
	{

		header("location:$gourl");
		exit();
	}
}
//ajax获取旅行社营业执照
else if($action == 'checkagency')
{
  if($type=="cardpic"){
	$r=$dosql->GetOne("SELECT cardpic,company FROM $tbname WHERE id=$id");
  $contents = $r['cardpic'];
  $content =  "<span style='font-size:18px;font-weight:bold;margin-bottom:10px;'>".$r['company']."营业执照"."</span>";
  $content .= "<img src='".$contents."' width=90% style='margin-top:17px;'>";
	echo $content;
}

} else if($action=="checkinfo"){
  if($info=="agency"){    //通过旅行社审核
     $dosql->ExecNoneQuery("UPDATE $tbname SET checkinfo=1 WHERE id=$id");
     //将用户发送成功的消息保存起来
     $s=$dosql->GetOne("SELECT * from `#@__agency` where id=$id");
     $tbnames='pmw_message';
     $tp="旅行社";
     $name=$s['company'];
     $tel=$s['tel'];
     $state='通过';
     $applytime=date("Y-m-d H:i:s",$s['regtime']);
     $sendtime=date("Y-m-d H:i:s");
     $content="亲爱的会员您好，恭喜您申请的旅行社注册信息已通过审核！";

     //============================================================================================
         //将旅行社注册成功的系统消息保存起来
         $type=$info;
         $messagetype="system";
         $tent="您的".$tp."账号已注册成功，欢迎您使用".$cfg_webname."小程序";
         $stitle="系统消息";
         $biaoti="欢迎你使用".$cfg_webname."小程序";
         $faxtime=time();
         $sql = "INSERT INTO `$tbnames` (type, messagetype, content,stitle, title, mid, faxtime) VALUES ('$type', '$messagetype', '$tent','$stitle', '$biaoti', $id, $faxtime)";
         $dosql->ExecNoneQuery($sql);
     //===========================================================================================
         //将旅行社注册成功的模板消息保存起来
         $messagetype='template';
         $templatetype='reg';  //注册成功的模板消息类型
         $tent = "恭喜你，账号注册成功！|";
         $tent .= "账户类型：".$tp."|";
         $tent .= "旅行社名称：".$name."|";
         $tent .= "联系电话：".$tel."|";
         $tent .= "审核结果：通过|";
         $tent .= "温馨提示：".$content."|";
         $tent .= "申请时间：".$applytime."|";
         $tent .= "审核时间：".$sendtime;
         $stitle="账号注册成功提醒";
         $biaoti="恭喜你，你的账号已注册成功！";

         $sql = "INSERT INTO `$tbnames` (type, messagetype, templatetype, content,stitle, title, mid, faxtime) VALUES ('$type', '$messagetype', '$templatetype', '$tent', '$stitle', '$biaoti', $id, $faxtime)";
         $dosql->ExecNoneQuery($sql);
     //===========================================================================================

     $r=$dosql->GetOne("SELECT openid,formid FROM $tbname where id=$id");
     //发送模板消息
     $openid=$r['openid'];
     $form_id=$r['formid'];  //微信小程序提交表单的formid
     $page="pages/index/index";

    // 并且发送通过的模板消息
    $ACCESS_TOKEN = get_access_token($appid,$appsecret);//ACCESS_TOKEN
    //模板消息请求URL
    $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;
    $data=getDataArray($openid,$tp,$name,$tel,$state,$content,$applytime,$sendtime,$cfg_regsuccess,$page,$form_id);
    $json_data = json_encode($data);//转化成json数组让微信可以接收
    $res = https_requests($url, urldecode($json_data));//请求开始
    $res = json_decode($res, true);
    if ($res['errcode'] == 0 && $res['errcode'] == "ok") {
      ShowMsg("恭喜，审核通过！",'-1');
      exit();
    }
  }else{
    $tbname="pmw_guide";
    $dosql->ExecNoneQuery("UPDATE $tbname SET checkinfo=1 WHERE id=$id");

    $s=$dosql->GetOne("select * from $tbname where id=$id");
    $tbnames='pmw_message';
    $tp="导游";
    $name=$s['name'];
    $tel=$s['tel'];
    $state='通过';
    $applytime=date("Y-m-d H:i:s",$s['regtime']);
    $sendtime=date("Y-m-d H:i:s");
    $content="亲爱的会员您好，恭喜您申请的导游注册信息已通过审核！";
//============================================================================================
    //将导游注册成功的系统消息保存起来
    $type=$info;
    $messagetype="system";
    $tent="您的".$tp."账号已注册成功，欢迎您使用".$cfg_webname."小程序";
    $stitle="系统消息";
    $biaoti="欢迎你使用".$cfg_webname."小程序";
    $faxtime=time();
    $sql = "INSERT INTO `$tbnames` (type, messagetype, content, stitle, title, mid, faxtime) VALUES ('$type', '$messagetype', '$tent', '$stitle', '$biaoti', $id, $faxtime)";
    $dosql->ExecNoneQuery($sql);
//===========================================================================================
    //将导游注册成功的模板消息保存起来
    $messagetype='template';
    $templatetype='reg';  //注册成功的模板消息类型
    $tent = "恭喜你，账号注册成功！|";
    $tent .= "账户类型：".$tp."|";
    $tent .= "导游姓名：".$name."|";
    $tent .= "联系电话：".$tel."|";
    $tent .= "审核结果：通过|";
    $tent .= "温馨提示：".$content."|";
    $tent .= "申请时间：".$applytime."|";
    $tent .= "审核时间：".$sendtime;
    $stitle="账号注册成功提醒";
    $biaoti="恭喜你，你的账号已注册成功！";

    $sql = "INSERT INTO `$tbnames` (type, messagetype, templatetype, content, stitle, title, mid, faxtime) VALUES ('$type', '$messagetype', '$templatetype', '$tent', '$stitle', '$biaoti', $id, $faxtime)";
    $dosql->ExecNoneQuery($sql);
//===========================================================================================
    $r=$dosql->GetOne("SELECT openid,formid FROM $tbname where id=$id");
    //发送模板消息
    $openid=$r['openid'];
    $form_id=$r['formid'];  //微信小程序提交表单的formid
    $page="pages/index/index";

   // 并且发送通过的模板消息
   $ACCESS_TOKEN = get_access_token($appid,$appsecret);//ACCESS_TOKEN
   //模板消息请求URL
   $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;
   $data=getDataArray($openid,$tp,$name,$tel,$state,$content,$applytime,$sendtime,$cfg_regsuccess,$page,$form_id);
   $json_data = json_encode($data);//转化成json数组让微信可以接收
   $res = https_requests($url, urldecode($json_data));//请求开始
   $res = json_decode($res, true);
   if ($res['errcode'] == 0 && $res['errcode'] == "ok") {
     ShowMsg("恭喜，审核通过！",'-1');
     exit();
   }
  }
}
//拒绝通过审核的方法
else if($action=="checkfailed"){
  if($type=="agency"){
    //更改旅行社的审核状态
    $dosql->ExecNoneQuery("UPDATE $tbname SET checkinfo=2 WHERE id=$id");
    //将用户的发送消息保存起来
    $tbnames='pmw_agency_message';
    $sql = "INSERT INTO `$tbnames` (tp, name, tel, state, content, applytime, sendtime) VALUES ('$tp', '$name', '$tel', '$state', '$content', '$applytime', '$sendtime')";
    $dosql->ExecNoneQuery($sql);

    //向注册的旅行社的注册用户发送注册审核未成功的消息

    $ACCESS_TOKEN = get_access_token($appid,$appsecret);//ACCESS_TOKEN
    //模板消息请求URL
    $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

    $r=$dosql->GetOne("SELECT openid,formid FROM $tbname where id=$id");
    //发送模板消息
    $openid=$r['openid'];
    $form_id=$r['formid'];  //微信小程序提交表单的formid
    $page="pages/index/index";
  	if($type=="agency"){
  		$typename="旅行社";
  	}else{
  		$typename="导游";
  		}
    $data=getDataArray($openid,$typename,$name,$tel,$state,$content,$applytime,$sendtime,$cfg_regfailed,$page,$form_id);
	  $json_data = json_encode($data);//转化成json数组让微信可以接收
    $res = https_requests($url, urldecode($json_data));//请求开始
	  $res = json_decode($res, true);
    //获取发送的微信模板消息的id
  	$s=$dosql->GetOne("SELECT id FROM pmw_agency_message  WHERE tel='$tel' and applytime='$applytime' and sendtime='$sendtime'");
  	$mid=$s['id'];

    if ($res['errcode'] == 0 && $res['errcode'] == "ok") {
	   $gourls="check_content.php?id=".$mid."&state=success";
       header("location:$gourls");
	     exit();
    }else{
		$gourls="check_content.php?id=".$mid."&state=failed";
        header("location:$gourls");
	      exit();
	}

  }elseif($type=="guide"){
  $tbname="pmw_guide";
  $dosql->ExecNoneQuery("UPDATE $tbname SET checkinfo=2 WHERE id=$id");
  //将用户的发送消息保存起来
  $tbnames='pmw_agency_message';
  $sql = "INSERT INTO `$tbnames` (tp, name, tel, state, content, applytime, sendtime) VALUES ('$tp', '$name', '$tel', '$state', '$content', '$applytime', '$sendtime')";
  $dosql->ExecNoneQuery($sql);

  //向注册的旅行社的注册用户发送注册审核未成功的消息

  $ACCESS_TOKEN = get_access_token($appid,$appsecret);//ACCESS_TOKEN
  //模板消息请求URL
  $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

  $r=$dosql->GetOne("SELECT openid,formid FROM $tbname where id=$id");
  //发送模板消息
  $openid=$r['openid'];
  $form_id=$r['formid'];  //微信小程序提交表单的formid
  $page="pages/index/index";
  if($type=="agency"){
    $typename="旅行社";
  }else{
    $typename="导游";
    }
  $data=getDataArray($openid,$typename,$name,$tel,$state,$content,$applytime,$sendtime,$cfg_regfailed,$page,$form_id);
  $json_data = json_encode($data);//转化成json数组让微信可以接收
  $res = https_requests($url, urldecode($json_data));//请求开始
  $res = json_decode($res, true);
  //获取发送的微信模板消息的id
  $s=$dosql->GetOne("SELECT id FROM $tbnames  WHERE tel='$tel' and applytime='$applytime' and sendtime='$sendtime'");
  $mid=$s['id'];

  if ($res['errcode'] == 0 && $res['errcode'] == "ok") {
    $gourls="check_content.php?id=".$mid."&state=success";
     header("location:$gourls");
     exit();
  }else{
      $gourls="check_content.php?id=".$mid."&state=failed";
      header("location:$gourls");
      exit();
}

  }
//无条件返回
}else
{
    header("location:$gourl");
	exit();
}

?>
