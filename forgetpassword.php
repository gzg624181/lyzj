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
<title>找回密码</title>
<script src="templates/default/js/jquery.js"></script>

<script src="layer/layer.js"></script>
<script type="text/javascript" src="templates/default/js/jquery.code.js"></script>
<script>

$(function () {
$('.code').createCode({
  len:6
});
 });

 function forgetpasswords(){
   if($("#mobile").val()==""){
     layer.alert("请输入用户名!",{icon:0});
     return false;
   }
   var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
  if(!myreg.test($("#mobile").val()))
  {
   layer.alert('请输入有效的手机号码！',{icon:0});
   return false;
  }
   if($("#verify").val()==""){
     layer.alert("请输入验证码!",{icon:0});
     return false;
   }
   if($("#verify").val()!= $("#idcode").val()){
     layer.alert("验证码输入错误!",{icon:0});
     return false;
   }

 }
</script>
</head>
<body>

<link href="templates/default/style/reg.css" type="text/css" rel="stylesheet">
<div id="part1div" class="list-wrap">
<form id="forgetpasswordata" onsubmit="return forgetpasswords();" action="save.php" method="post">
		<ul class="list" style="padding-top:2rem;background: none;">
			<li>
				<input name="mobile" id="mobile" class="verifyd" type="tel" placeholder="请输入用户名" style="font-family: Verdana, Geneva, sans-serif;font-weight: bold;">
				<i></i>
			</li>
			<li>
				<input name="verify" id="verify" class="verifyd" type="tel" placeholder="请输入验证码" style="font-family: Verdana, Geneva, sans-serif;font-weight: bold;">
				<i></i>
			</li>
			<li>
        <span style="margin-top: 5px; height:30px; width:100%; border-radius:3px;letter-spacing:16px;" class="code verification_b"  title='点击切换'></span>
			</li>
		</ul>
		<div class="reg-btn">
     <input type="submit" id="forgetpassword" class="shit submit" value="下一步" style="cursor:pointer;">
     <input type="hidden" id="action" name="action" value="forgetpassword">
    </div>

		<div class="reg-end">
		<a href="https://app.changxier.com/loginService" class="tc"><i>联系客服</i> 找回密码</a>
	</div>
	</form>
 </div>

</body></html>
