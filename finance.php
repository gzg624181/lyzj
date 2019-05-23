<?php	require_once(dirname(__FILE__).'/include/config.inc.php');?>
<!DOCTYPE html>
<html style="overflow: auto; height: auto;"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<title>我要充值</title>
<script src="templates/default/regjs/jquery_002.js"></script>
<script src="templates/default/regjs/common.js"></script>
<script src="templates/default/regjs/clipboard.js"></script>
<link href="templates/default/style/allstatic.css" rel="stylesheet" type="text/css">
<link href="templates/default/style/allpjax.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="templates/default/regjs/jquery.js"></script>
<script type="text/javascript" src="templates/default/regjs/pjaxloading.js"></script>
<script src="templates/default/regjs/fastClick.js"></script>
<script src="layer/layer.js"></script>
<script type="text/javascript">$(function(){FastClick.attach(document.body);});</script>
</head>
<body style="overflow: auto; height: auto;">
<?php
$r=$dosql->GetOne("select * from `#@__shoukuan` where id=$id");
if($r['type']=="bankpay"){
?>
<div class="transfer_card card9">
	<div class="trans_title"><?php echo $r['bankname'];?></div>
		<div class="trans_name" style="margin-top: 4px;">上分客服QQ：<?php echo $cfg_qq;?></div>
		<dl class="account_body ">
		<dt class="account"><?php echo $r['account'];?></dt>
		<dt class="account_name">账户名：<?php echo $r['name'];?></dt>
	</dl>
</div>
<?php }elseif($r['type']=="alipay"){?>
  <div class="transfer_card card6">
  		<div class="trans_name" style="margin-top: 4px;">上分客服QQ：<?php echo $cfg_qq;?></div>
  		<dl class="account_body ">
  		<dt class="account"><?php echo $r['account'];?></dt>
  		<dt class="account_name">账户名：<?php echo $r['name'];?></dt>
  	</dl>
  </div>
<?php }elseif($r['type']=="wxpay"){?>
  <div class="transfer_card card7">
  		<div class="trans_name" style="margin-top: 4px;">上分客服QQ：<?php echo $cfg_qq;?></div>
  		<dl class="account_body ">
  		<dt class="account"><?php echo $r['account'];?></dt>
  		<dt class="account_name">账户名：<?php echo $r['name'];?></dt>
  	</dl>
  </div>
<?php }?>
<?php
$s=$dosql->GetOne("select * from `#@__members` where id=$uid");
?>
<div class="description">请在转账附言中填写您的UID，您的UID是： <font color="#4c8fff"><?php echo $s['ucode'];?></font>   （转账成功后联系客服<?php echo $cfg_qq;?>，人工审核到账哦 最低充值50起）</div>
<dl class="btn_group">
	<dt data-value="<?php echo $r['name'];?>" id="copydata0" style="cursor:pointer;">复制账户姓名</dt>
	<dt data-value="<?php echo $s['telephone'];?>" id="copydata1" style="cursor:pointer;">复制上分账号</dt>
	<div class="clear"></div>
</dl>
<a href="javascript:jeker.backurl();" class="backpaylist">返回充值列表</a>

<script type="text/javascript">
		var clipboard = [];
		$('.btn_group dt').each(function(index){
			$('.btn_group dt').eq(index).attr('id','copydata'+index);
			clipboard[index] = new Clipboard('#copydata'+index, {
				text: function() {
					return $('#copydata'+index).attr('data-value');
				}
			});
			clipboard[index].on('success', function(e) {
        layer.alert("复制成功！",{icon:1});
			//	layer.open({content:"复制成功",skin:"msg",time:2});
			});
			clipboard[index].on('error', function(e) {
			//	layer.open({content:"复制失败，请长按并复制内容",skin:"msg",time:2});
        layer.alert("复制失败，请长按并复制内容!",{icon:5});
			});
		});

		$('body').on('click', '.qrcode img', function(){
			yPhone.openBrowser($(this).attr('src'));
		});
</script>
</body>
</html>
