<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
$id = empty($id) ? "" : intval($id);
?>
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
<title>忘记密码</title>

<script src="templates/default/js/jquery.js"></script>
<script src="layer/layer.js"></script>
<link href="templates/default/style/reg.css" type="text/css" rel="styleSheet" id="layermcss">
<script type="text/javascript">$(function(){FastClick.attach(document.body);});</script>
</head>
<body>
	<script type="text/javascript">

function forgetpasswords(){

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

	}


	</script>
<?php
$r=$dosql->GetOne("select telephone from `#@__members` where id=$id");
$phone=$r['telephone'];
?>
<div class="phone_register_list">
	<div class="list-wrap">
		<form id="regdata" onsubmit="return forgetpasswords();" action="save.php" method="post">
			<ul class="list plist">
        <input type="hidden" id="phone" name="phone" value="<?php echo $phone;?>">
				<li class="chcode">
					<input name="code"  id="code" type="tel" class="verifyd" placeholder="请输入验证码" />
					<i></i>
					<a href="javascript:;" class="code" id="getcodetag" name="getcodetag" onClick="getcodes();" data-token="" data-timeout="-1554382094">发送</a>
			  	</li>

				<li>
					<input name="password" id="password" type="password" class="verifyd" autocomplete="false" placeholder="请输入密码" />
					<i></i>
				</li>

				<li>
					<input name="repassword" id="repassword" type="password" class="verifyd" autocomplete="false" placeholder="请输入确认密码" />
					<i></i>
				</li>

			</ul>

	<div class="reg-btn">
	 <input type="submit" id="getup" class="shit" value="提交" style="cursor:pointer;">
	 <input type="hidden" id="action" name="action" value="getup">

	</div>
	</form>
	<div class="reg-end">
		<a href="javascript:yPhone.goLogin();" class="tc"><i>已有账号？</i>去登录</a>
	</div>
</div>
<script>


	var InterValObj; //timer变量，控制时间
	var count = 60; //间隔函数，1秒执行
	var curCount;//当前剩余秒数

	function getcodes(){
	  var phone = $("#phone").val()
	  var ajax_urls='save.php?telephone='+phone+'&action=forgetpassword_next';
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
</body>
</html>
