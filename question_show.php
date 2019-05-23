<?php	require_once(dirname(__FILE__).'/include/config.inc.php');
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
<title>问题列表</title>
<script src="templates/default/regjs/jquery_002.js"></script>
<script src="templates/default/regjs/common.js"></script>
<script src="templates/default/regjs/clipboard.js"></script>
<link href="templates/default/style/allstatic.css" rel="stylesheet" type="text/css">
<link href="templates/default/style/allpjax.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="templates/default/style/notice.css">
<link rel="stylesheet" type="text/css" href="templates/default/style/lottery.css">
<script src="templates/default/regjs/jquery.js"></script>
<script src="templates/default/regjs/pjaxloading.js"></script>
<script type="text/javascript" src="templates/default/regjs/jquery_003.js"></script>
<script type="text/javascript">$(function(){FastClick.attach(document.body);});</script>
</head>
<body>
<style type="text/css">
 .introduce-content i,.introduce-content p{display:block;float:left;line-height:1.8rem;}
 .introduce-content i{width:10%;}
 .introduce-content p{width:98%;color:#666;}
 .introduce-content b{display:block;clear:both;}
 .play-introduce h4{height:auto;line-height:2rem;padding:.5rem;}
 .space20{height:8%;}
 .topheader{position:fixed;left:0;width:100%;padding:1rem;background:white;}
 .topheader a,.topheader h4{display:block;float:left;font-size:1.2rem;}
 .topheader a{width:16%;border-right:1px solid #ddd;color:#888;-webkit-tap-highlight-color:rgba(0,0,0,0); }
 .topheader h4{width:80%;margin-left:4%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
 p.bottomcontent{color:#999;text-align:right;font-weight:normal;font-size:.8rem;line-height: 1.5rem;}
</style>
<?php
$r=$dosql->GetOne("SELECT * from pmw_question where id=$id");
 ?>
<div class="topheader clearfix" style="align:center;">
    <h4><?php echo $r['title'];?></h4></div>
   <div class="play-introduce-wrap" style="background:white;height:auto">
   	<div class="play-introduce" style="margin-top:1.8rem;">
		<ol class="introduce-content" style="overflow:auto;">
      <p><?php echo $r['content'];?></p>      <br><br>
    
		 </ol>
	</div></div>

</body>
</html>
