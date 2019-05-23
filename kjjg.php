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
	<div class="result-box-wrap">
		<div class="result-box">
			<div class="top-select clearfix">
			 <form id="searchdata" action="kjjg.php" method="get">
			 	<input type="hidden" name="gid" value="3">
				<div class="fl left">
					<div class="select_start_date">
					 <div class="start_date_left"><input type="text" readonly="readonly" name="start_date" id="start_date" value="开始日期" placeholder="选择日期"><i class="fa fa-angle-down"></i></div>
						 </div>
					<div class="select_start_date">
					 <div class="start_date_left"><input type="text" readonly="readonly" name="end_date" id="end_date" value="结束日期" placeholder="选择日期"><i class="fa fa-angle-down"></i></div>
						 </div>
					</div>
				<div class="fr right">
					<div class="go_search"><i class="fa fa-search" aria-hidden="true"></i>搜索
            <!-- <input style="display: block;
text-align: right;
font-size: .9rem;
color: #999;
background: none;" type="submit" value="搜索"> -->
 </div>
				</div>
			 </form>
			</div>
		</div>
		   <ul class="prize-result-wrap">
      <?php
      if($start_date!="" && $end_date!=""){
      $start_date = strtotime($start_date);
      $end_date   = strtotime($end_date);
      $dosql->Execute("select kj_times , kj_mdhi, kj_he, kj_number, kj_varchar from pmw_lotterynumber  where  kj_number is not null and  kj_state = 1 and kj_endtime_sjc >=$start_date and kj_endtime_sjc <= $end_date order  by id desc limit 0,40");
      }else{
      $dosql->Execute("select kj_times , kj_mdhi, kj_he, kj_number, kj_varchar from pmw_lotterynumber  where  kj_number is not null and  kj_state = 1 order by id desc limit 0,40");
      }
      while($row=$dosql->GetArray()){
      $kj_number=$row['kj_number'];
      $array=str_split($kj_number);
         ?>
         <li> <div class="clearfix content-box">
						<span class="fl">
						<i class="blue"><?php echo $array[0];?></i>
            <i class="gray">+</i>
            <i class="blue"><?php echo $array[1];?></i>
            <i class="gray">+</i>
            <i class="blue"><?php echo $array[2];?></i>
            <i class="gray">=</i>
            <i class="red"><?php echo $row['kj_he'];?></i>
						</span>
						<span class="fr fr-itm">
            <?php
            $str="";
            $str .= jieguo($row['kj_he'],$kj_number); //本期开奖的所有类别
            echo $str;
             ?>
          </span>
					</div>
					<div class="clearfix head">
						<span class="qishu fl">第 <b><?php echo $row['kj_times'];?></b> 期开奖</span>
						<span class="time fr"><?php echo $row['kj_mdhi'];?></span>
					</div>
				</li>
<?php }?>
</ul>
	</div>

	<script type="text/javascript">
		$('.go_search').click(function(){
			var start_date=$("#start_date ").val();
      var end_date=$("#end_date ").val();
      if(start_date=="开始日期" || end_date=="结束日期"){
        layer.alert("请选择起始日期和结束日期！",{icon:3});
        return false;
      }
		  });

		var calendar = new LCalendar();
		var gid = $('input[name=gid]').val();
		calendar.init({
			'trigger': '#start_date', //标签id
			'type': 'date', //date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择,
			'minDate': (new Date().getFullYear()-3) + '-' + 1 + '-' + 1, //最小日期
			'maxDate': (new Date().getFullYear()+3) + '-' + 12 + '-' + 31, //最大日期
			 'callback':function(){ createUrl();}
		});
		var calendar = new LCalendar();
		calendar.init({
			'trigger': '#end_date', //标签id
			'type': 'date', //date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择,
			'minDate': (new Date().getFullYear()-3) + '-' + 1 + '-' + 1, //最小日期
			'maxDate': (new Date().getFullYear()+3) + '-' + 12 + '-' + 31, //最大日期
			 'callback':function(){createUrl();}
		});

		 var createUrl = function(){
		 	var start = $('#start_date').val();
            var end = $('#end_date').val();
             start = start != '开始日期'? '&start='+start : '';
             end = end != '结束日期'? '&end='+end : '';
             $('.go_search').attr('data-href','/kjjg?gid='+gid+start+end);
		 }

		//var calendar = new LCalendar();
		//calendar.init({
		//	'trigger': '#end_date', //标签id
		//	'type': 'date', //date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择,
		//	'minDate': (new Date().getFullYear()-3) + '-' + 1 + '-' + 1, //最小日期
		//	'maxDate': (new Date().getFullYear()+3) + '-' + 12 + '-' + 31 //最大日期
		//});
		//		$(function() {
		//			$('#start_date').date();
		//			$('#end_date').date();
		//		});
	</script>
<script type="text/javascript">
	jQuery.divselect = function(divselectid,inputselectid) {
    var inputselect = $(inputselectid);
    $(divselectid+" cite").click(function(){
        var ul = $(divselectid+" ul");
        if(ul.css("display")=="none"){
            ul.slideDown("fast");
        }else{
            ul.slideUp("fast");
         }
    });
    $(divselectid+" ul li a").click(function(){
        var txt = $(this).text();
        $(divselectid+" cite").html(txt);
        var value = $(this).attr("selectid");
        inputselect.val(value);
        $(divselectid+" ul").hide();
         $('#searchdata').submit();
    });
};



</script>

</body></html>
