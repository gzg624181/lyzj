<?php	require_once(dirname(__FILE__).'/include/config.inc.php');
$uid = empty($uid) ? 16 : intval($uid);
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
<title>推广链接</title>
<script src="templates/default/regjs/jquery.js"></script>
<script src="templates/default/regjs/common.js"></script>
<script src="templates/default/regjs/fastClick.js"></script>
<script src="layer/layer.js"></script>
<script type="text/javascript">$(function(){FastClick.attach(document.body);});</script>
</head>
<body>
  <?php
  $s=$dosql->GetOne("select * from `#@__members` where id=$uid");
  $avater=$cfg_weburl."/admin/templates/images/avatar/".$s['images'].".jpg";
  ?>
<link href="templates/default/style/agentlink.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="templates/default/style/mui.css">
<div class="main">
  <div class="tuig-box">
    <div class="qrcode_top"></div>
    <div class="qrcode_center">
      <div class="remessage">我的UID是：<b><?php echo $s['ucode'];?></b>，诚邀您加入<b>业界最优秀</b>的彩票购买平台</div>
      <div class="head-img"><img class="img-responsive" src="<?php echo $avater;?>"></div>
      <div class="box_top"></div>
      <div class="er-img"><a class="downqrcode" href="javascript:;">
      <img class="img-responsive" src="<?php echo $cfg_weburl."/".$s['qrcode'];?>"></a></div>
      <p class="joinlink"><?php echo $s['links'];?></p>
     <div class="btn_group">
        <dt data-value="<?php echo $s['links'];?>" class="qrbtn copylink" id="copydata0" style="cursor:pointer;">复制链接</dt>
        <dt data-href="<?php echo $s['links'];?>" class="qrbtn saveqrcode" id="copydata1" style="cursor:pointer;">保存二维码</dt>
      </div>
    </div>
    <div class="qrcode_bottom"></div>
  </div>


</div>
<script src="templates/default/regjs/mui.js"></script>
<script src="templates/default/regjs/clipboard.js"></script>
<script>
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
$('.saveqrcode').click(function(){
  var device = $(this).attr('data-device');
  if(device =='ios'){
  //  layer.open({content:"请长按二维码保存到相册",skin:"msg",time:2});
    layer.alert("请长按二维码保存到相册!",{icon:3});
  }else{
   location.href = $(this).attr('data-href');
 }
})

$('body').on('click', '.qrcode img', function(){
  yPhone.openBrowser($(this).attr('src'));
});


</script>


</body></html>
