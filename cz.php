<?php	require_once(dirname(__FILE__).'/include/config.inc.php');
$uid = empty($uid) ? 1 : intval($uid);
?>
<!DOCTYPE html>
<html>
<head>
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
<body>

<script type="text/javascript" src="templates/default/regjs/pay.js"></script>
<script type="text/javascript" src="templates/default/regjs/jquery-numberKeyboard.js"></script>
<style>.body{background-color:#f3f3f3;}</style>
<div class="warp_btm">
	<?php
 $dosql->Execute("SELECT * FROM `pmw_shoukuan`");
 while($row = $dosql->GetArray()){

	 switch($row['type'])
	 {
		 case 'alipay':
			 $type = "支付宝充值";
			 break;
		 case 'bankpay':
			 $type = "银行卡充值";
			 break;
		 case 'wxpay':
				 $type = "微信充值";
				 break;
	 }
	 ?>
	 <ol id="transfer_list">

			<a data-pjax="true" href="finance.php?id=<?php echo $row['id'];?>&uid=<?php echo $uid;?>">
			<li class="<?php echo $row['type'];?>">
				<i></i><b><?php echo 	$type ;?></b><em></em>
			</li></a>

			</ol>
		<?php }?>
		</div>

		<div class="withdrawtips" style="margin-left: 20px;">* 充值金额限定：101 ~ 4999</div>
		<div class="paytips" style="margin-left: 20px;"><b>注意事项：</b><br>
		 <div class="pay_tips_desc">
			 1、不要自行线下转账，否则不能自动到账！<br>
		  2、请选择充值的方式，充值完成联系上分qq！</div>
		</div>
		<br><br><br>

</body>
</html>
