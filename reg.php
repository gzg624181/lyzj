<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no" />
<meta name="format-detection" content="email=no"/>
<title>用户注册</title>

<script src="templates/default/js/jquery.js"></script>
<script src="layer/layer.js"></script>
<link href="templates/default/style/reg.css" type="text/css" rel="styleSheet" id="layermcss">
<script type="text/javascript">$(function(){FastClick.attach(document.body);});</script>
</head>
<body>
	<script type="text/javascript">
	function checkphones() {
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
	function checkreg(){
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
	 // var reg = /^d{1,5}$/;
	 // var bcode = $("#bcode").val();
	 // if(bcode!=""){
	 // if(!reg.test(bcode))
	 // { layer.alert("推荐人UID填写错误！",{icon:0});}
	 // return false;
   // }
	 }

	var InterValObj; //timer变量，控制时间
	var count = 60; //间隔函数，1秒执行
	var curCount;//当前剩余秒数

	function getcode(){
	  var phone = $("#phone").val();
		if( phone !=""){
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
	}else{
		layer.alert("手机号码不能为空！",{icon:0});
		return false;
	}
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

<div class="phone_register_list">
	<div class="list-wrap">
		<form id="regdata" onsubmit="return checkreg();" action="api/reg/save.php" method="post">
			<ul class="list plist">
				<li>
					<input name="phone" type="tel" id="phone" class="verifyd" placeholder="请输入手机号"  onblur="checkphones()" />
					<i></i>
				</li>
				<li class="chcode">
					<input name="code"  id="code" type="tel" class="verifyd" placeholder="请输入验证码" />
					<i></i>
					<a href="javascript:;" class="code" id="getcodetag" name="getcodetag" onClick="getcode();" data-token="" data-timeout="-1554382094">发送</a>
				</li>
				<li>
					<input name="password" id="password" type="password" class="verifyd" autocomplete="false" placeholder="请输入密码" />
					<i></i>
				</li>
				<li>
					<input name="repassword" id="repassword" type="password" class="verifyd" autocomplete="false" placeholder="请输入确认密码" />
					<i></i>
				</li>
				<li>
					<input name="nickname" id="nickname" type="text" class="verifyd" placeholder="请输入昵称" />
					<i></i>
				</li>
				<li>
					<input name="bcode" id="bcode" type="number" class="verifyd" placeholder="推荐人UID（选填）" />
					<i></i>
				</li>
			</ul>

	<div class="reg-btn">
	 <input type="submit" id="reg" class="shit" value="注册" style="cursor:pointer;">
	 <input type="hidden" id="action" name="action" value="reg">
	</div>
	</form>
	<div class="reg-end">
		<a href="javascript:yPhone.goLogin();" class="tc"><i>已有账号？</i>去登录</a>
	</div>
</div>
</body>
</html>
