<?php require_once(dirname(__FILE__).'/include/config.inc.php');
$r=$dosql->GetOne("SELECT * FROM `pmw_banner` where id=$id");
$title=$r['title'];
 ?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<title><?php echo $title;?></title>
<style type="text/css">.pd10{width:96%; margin:0 auto; padding:0 2% 0 2%;}.qrcode { text-align:center; width:80%; margin:0 auto; }.step .p1 {color:#333; font-size:14px;}.p2 { font-size:18px; color:red;font-weight:bold; line-height:10px;}.red{color:#F00;}</style>
</head>
<body>
<div class="pd10">
<div class="step">
 <?php echo $r['content'];?>
</div>
</div>
</body>
</html>
