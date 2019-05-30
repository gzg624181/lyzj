<?php	require_once(dirname(__FILE__).'/include/config.inc.php');
$code  = isset($code)  ? $code : '';
?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<title>注册下载APP</title>
<script src="templates/default/js/jquery.js"></script>
<script src="templates/default/regjs/jquery.js"></script>
<script src="templates/default/regjs/common.js"></script>
<script src="templates/default/regjs/fastClick.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script type="text/javascript">
function checkphone(){
  if($("#phone").val()==""){
    layer.alert("请输入手机号码！",{icon:0});
    return false;
  }
  var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
 if(!myreg.test($("#phone").val()))
 {
  layer.alert('请输入有效的手机号码！',{icon:0});
  return false;
 }
  var phone = $("#phone").val();
  var ajax_urls='save.php?telephone='+phone+'&action=checkphone';
   //alert(ajax_urls);
	$.ajax({
    url:ajax_urls,
    type:'get',
	  data: "data" ,
	  dataType:'html',
    success:function(data){
		 if(data==1){
     layer.alert("该手机已注册！",{icon:2});
     }
    } ,
	error:function(){
       alert('error');
    }
	});

}
function check(){
  if($("#phone").val()==""){
    layer.alert("请输入手机号码！",{icon:0});
    return false;
  }
  var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
 if(!myreg.test($("#phone").val()))
 {
  layer.alert('请输入有效的手机号码！',{icon:0});
  return false;
 }
 if($("#sendcode").val()==""){
   layer.alert("请输入短信验证码！",{icon:0});
   return false;
 }
 if($("#code").val()==""){
   layer.alert("请输入短信验证码！",{icon:0});
   return false;
 }
 if($("#password").val()==""){
   layer.alert("请输入密码!",{icon:0});
   return false;
 }
 if($("#repassword").val()==""){
   layer.alert("请输入确认密码!",{icon:0});
   return false;
 }
 if($("#repassword").val() != $("#password").val()){
   layer.alert("两次密码输入不一致!",{icon:0});
   return false;
 }
 if($("#nickname").val()==""){
   layer.alert("请输入昵称!",{icon:0});
   return false;
 }
}
var InterValObj; //timer变量，控制时间
var count = 60; //间隔函数，1秒执行
var curCount;//当前剩余秒数
function getcode(){
  var phone = $("#phone").val();
  var ajax_urls='save.php?telephone='+phone+'&action=sendcode';
   //alert(ajax_urls);
	$.ajax({
    url:ajax_urls,
    type:'get',
	  data: "data" ,
	  dataType:'html',
    success:function(data){
		 if(data==0){
     layer.msg("验证码发送失败！",{icon:5});
    }else{
     layer.msg("验证码发送成功！",{icon:6});
			curCount = count;
			// 开始计时
      $("#getcodetag").removeAttr("onClick");   //移除onclick事件
			document.getElementById("getcodetag").innerHTML=curCount + "秒后重获";//更改按钮文字
			InterValObj = window.setInterval(SetRemainTime, 1000); // 启动计时器timer处理函数，1秒执行一次
    }
    } ,
	error:function(){
       alert('error');
    }
	});
}
//timer处理函数
			function SetRemainTime() {
				if (curCount == 0) {//超时重新获取验证码
          $("#getcodetag").attr("onClick","getcode();");
					window.clearInterval(InterValObj);// 停止计时器
					document.getElementById("getcodetag").innerHTML="重获验证码";
				}else {
					curCount--;
					document.getElementById("getcodetag").innerHTML=curCount + "秒后重获";
				}
			}

</script>
</head>
<body>
﻿
<link rel="stylesheet" type="text/css" href="templates/default/style/reg2.css">
<link rel="stylesheet" type="text/css" href="templates/default/style/iconfont.css">
	<div class="list-wrap">
 <form id="regdata" onsubmit="return check();" action="save.php" method="post">
    <input name="bcode" id="bcode" type="hidden" value="<?php echo $code;?>">
	<ul class="list">
		<li>
			<span class="iconfont name" style="color: rgb(239, 239, 239);"></span>
			<input class="searchkey" name="phone" type="tel" onblur="checkphone()" id="phone" placeholder="请输入手机号" required />
		</li>
		<li>
			<span class="iconfont name" style="color: rgb(239, 239, 239);"></span>
			<input class="searchkey" name="sendcode" type="tel" id="sendcode" maxlength="6" placeholder="请输入短信验证码" required />
			<a href="javascript:;" class="code" name="getcodetag" id="getcodetag" onClick="getcode();" data-token="" data-timeout="-1551942589">获取验证码</a>
		</li>
		<li>
			<span class="iconfont name"></span>
			<input class="searchkey" name="password" id="password" type="password" autocomplete="false" placeholder="请输入密码" required>
		</li>
		<li>
			<span class="iconfont name"></span>
			<input class="searchkey" name="repassword" id="repassword" type="password" autocomplete="false" placeholder="请输入确认密码" required>
		</li>
		<li>
			<span class="iconfont name"></span>
			<input class="searchkey" name="nickname" id="nickname" type="text" placeholder="请输入昵称" required>
		</li>
	</ul>
	<div class="reg-btn">
		 <input type="submit" id="reg" class="shit" value="注册并下载APP">
         <input type="hidden" id="action" name="action" value="reg">
		<br><br><br>
	</div>
  </form>

</body></html>
