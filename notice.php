<?php	require_once(dirname(__FILE__).'/include/config.inc.php');
$type = empty($type) ? 0 : intval($type);
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
<title>公告中心</title>
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
body{background: #efefef;}
.layui-layer-setwin a{width:30px;height:30px;}
.layui-layer-setwin .layui-layer-close1{background-position: 15px -40px;}
.list{background:white;}
.list h4 i{display:inline-block;width:8px;height:8px;margin-left:10px;border-radius:10px;background:red;}
#topnavtab a{width:33.33%;}
</style>
	<div id="topnavtab">
    <a data-pjax="true" href="notice.php?type=0" class="<?php if($type==0){echo "on";}?> ui-link">系统消息</a>
    <a data-pjax="true" href="notice.php?type=1" class="<?php if($type==1){echo "on";}?> ui-link"> 最新公告</a>
    <a data-pjax="true" href="notice.php?type=2" class="<?php if($type==2){echo "on";}?> ui-link">会员必读</a>
    </div>
	<div id="notice_list_" class="list-wrap" style="margin-top:2.8rem;">
<?php
if($type==0){
 ?>
	 <ul class="list">
     <?php
    $dosql->Execute("SELECT id,title,content,issuetime FROM `#@__gonggao` WHERE type='newgonggao' order by id desc limit 0,10");
     while($row = $dosql->GetArray()){
       $gourl="notice_show.php?id=".$row['id'];
      ?>
     <a class="showdetail" data-pjax="true" href="<?php echo $gourl;?>">
			<h4><?php echo ReStrLen($row['title'],14); ?><i></i></h4>
			<div class="content">&nbsp;&nbsp;<?php echo ReStrLen($row['title'],60); ?></div>
			<div class="time clearfix"><span class="fl"><?php echo $cfg_webname;?></span><span class="fr"><?php echo date("Y-m-d H:i:s",$row['issuetime']);?></span></div>
		</a>
   <?php }?>
  </ul>
<?php }elseif($type==1){?>
    <ul class="list">
      <?php
     $dosql->Execute("SELECT id,title,content,issuetime FROM `#@__gonggao` WHERE type='xiaoxi' order by id desc limit 0,10");
      while($row = $dosql->GetArray()){
      $gourl="notice_show.php?id=".$row['id'];
       ?>
      <a class="showdetail" data-pjax="true" href="<?php echo $gourl;?>">
       <h4><?php echo ReStrLen($row['title'],14); ?><i></i></h4>
       <div class="content">&nbsp;<?php echo ReStrLen($row['title'],60); ?></div>
       <div class="time clearfix"><span class="fl"><?php echo $cfg_webname;?></span><span class="fr"><?php echo date("Y-m-d H:i:s",$row['issuetime']);?></span></div>
     </a>
   <?php }?>
     </ul>
 <?php }elseif($type==2){?>
     <ul class="list">
       <?php
      $dosql->Execute("SELECT id,title,content,issuetime FROM `#@__gonggao` WHERE type='bidu' order by id desc limit 0,10");
       while($row = $dosql->GetArray()){
         $gourl="notice_show.php?id=".$row['id'];
        ?>
       <a class="showdetail" data-pjax="true" href="<?php echo $gourl;?>">
        <h4>&nbsp;<?php echo ReStrLen($row['title'],14); ?><i></i></h4>
        <div class="content">&nbsp;<?php echo ReStrLen($row['title'],60); ?></div>
        <div class="time clearfix"><span class="fl"><?php echo $cfg_webname;?></span><span class="fr"><?php echo date("Y-m-d H:i:s",$row['issuetime']);?></span></div>
      </a>
     <?php }?>
    </ul>
    <?php }?>
<script type="text/javascript">
$('.showdetail').click(function(){
  $(this).find('h4 i').remove();
});
</script>
	 <!-- <div style="width: 100%; text-align: center; display: none;" class="jzMore" page="1">
            <img alt="" src="templates/default/images/load.gif">
        </div> -->
	</div>
<script type="text/javascript">
	$.ajaxPage({
            'url':"/index/index/load_notice_list.html?type=&params=dWlkPTYwNDcyJnNpZ25fcGFzc3dvcmQ9RUJDMDIwNTk5RTM5RTMwNQ%3D%3D",
            'time':0,
			'root':'#notice_list_',
            'append_list':'.list',
			'page':1
        });
var device = 'other';
$.get('/index/index/load_count_msg.html?params=dWlkPTYwNDcyJnNpZ25fcGFzc3dvcmQ9RUJDMDIwNTk5RTM5RTMwNQ%3D%3D',function(text){
  if(device=='ios'){
  webkit.messageHandlers.refreshNoticeNum.postMessage({"num": text.count});
 }else{
    yPhone.refreshNoticeNum(text.count);
  }
});

 </script>
</body></html>
