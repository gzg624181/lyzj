<?php	require_once(dirname(__FILE__).'/include/config.inc.php');?>
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
<title>幸运大转盘</title>
<script src="templates/default/js/jquery.js"></script>
<script src="templates/default/regjs/common.js"></script>
<script src="templates/default/regjs/fastClick.js"></script>
<script src="templates/default/regjs/layer.js"></script>
<script type="text/javascript">$(function(){FastClick.attach(document.body);});</script>
</head>
<body>

<script src="templates/default/regjs/awardRotate.js"></script>
<script src="templates/default/regjs/drawlottery.js"></script>
<link rel="stylesheet" type="text/css" href="templates/default/style/drawlottery.css">
<div id="raffle">
	<h4 class="tit" style="font-size:1rem;">28次有效下注期数获得一次抽奖机会<br>下注期数越多，抽奖越多，100%中奖</h4>
	<div class="tip">你还有 <i id="raffles">--</i> 次抽奖机会<br><span>今日结余有效期数 <b id="raffles_exps">--</b>，再记<b id="raffles_surplus_exps">28</b>期将获得<b>1</b>次机会</span></div>
    <img src="templates/default/images/2.png" id="shan-img" style="display:none;">
    <img src="templates/default/images/2.png" id="sorry-img" style="display:none;">
	<div class="banner">
		<div class="turnplate" style="background-image:url(templates/default/images/turnplate-bg.png);background-size:100% 100%;">
	<canvas class="item" id="wheelcanvas" width="256px" height="256px"></canvas>
	<img class="pointer" src="templates/default/images/turnplate-pointer.png">
		</div>
	</div>
	<div class="shadow-box"></div>
   <div class="ratips">
	<h4>累计有效下注期数抽奖！计数说明：</h4>
	<p>• 单期300以上下注，记有效期数1；</p>
	<p>• 单期3000以上下注，记有效期数2；</p>
	<p>• 单期9000以上下注，记有效期数3；</p>
	<p>• 单期20000以上下注，记有效期数4；</p>
	<p>• 活动时间：2019-01-31 00:00 - 2019-02-28 23:00</p>
	<p>• 注：加拿大1.88倍场单期/单注下注低于1000不累积有效期数；</p>
    <p>• 今日有效下注期数未满28次，次日自动清零；</p>
    <p>• 今日剩余机会（不包含累计机会），次日参与抽奖活动，将自动累积，否则将自动过期。</p>
	<br><br><br><br>
   </div>
    <script type="text/javascript">
	var turnplate={
			restaraunts:[],				 //大转盘奖品名称
			colors:[],					   //大转盘奖品区块对应背景颜色
			outsideRadius:192,			//大转盘外圆的半径
			textRadius:155,				//大转盘奖品位置距离圆心的距离
			insideRadius:68,			//大转盘内圆的半径
			startAngle:0,				//开始角度
			bRotate:false				//false:停止;ture:旋转
	};
	</script>
</div>

</body></html>
