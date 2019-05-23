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
<meta name="app-name" content="<?php echo $cfg_webname;?>">
<title><?php echo $cfg_webname."-开奖结果";?></title>
<script src="templates/default/regjs/jquery_002.js"></script>
<script src="templates/default/regjs/common.js"></script>
<script src="templates/default/regjs/fastClick.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<link href="templates/default/style/allstatic.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="templates/default/style/notice.css">
<link rel="stylesheet" type="text/css" href="templates/default/style/lottery.css">
<script src="templates/default/regjs/jquery_003.js"></script>
<script src="templates/default/regjs/pjaxloading.js"></script>
<script type="text/javascript" src="templates/default/regjs/jquery.js"></script>
<script type="text/javascript">$(function(){FastClick.attach(document.body);});</script>
</head>
<body>
<?php
$start_date=isset($start_date) ? $start_date : '';
$end_date=isset($end_date) ? $end_date : '';
 ?>
<script type="text/javascript" src="templates/default/regjs/LCalendar.js"></script>
<link rel="stylesheet" type="text/css" href="templates/default/style/LCalendar.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<div class="result-box-wrap" style="padding: 0rem 0 1rem 0;">

		   <ul class="prize-result-wrap">
      <?php
      $dosql->Execute("SELECT id,game_name,game_pic FROM `#@__gamedes` order by id desc");
      while($row=$dosql->GetArray()){
        $gourl="wfjsshow.php?id=".$row['id'];
         ?>
         <li>
           <div class="clearfix content-box">
            <a href="<?php echo $gourl;?>">
						<span class="fl">
            <img src="<?php echo $row['game_pic'];?>" width="50px" height="50px">
          <?php echo $row['game_name'];?>
						</span>
            </a>
						<span class="fr fr-itm" style="margin-top:20px;">
            <a href="<?php echo $gourl;?>"><i class="fa fa-angle-right"></i></a>
          </span>
					</div>
				</li>
<?php }?>
</ul>
	</div>

</body></html>
