<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('goodsbrand'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>账户统计</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="topToolbar"> <span class="title">充值统计&nbsp;&nbsp;&nbsp;<i class="fa fa-line-chart" aria-hidden="true"></i></span>
 <a href="javascript:location.reload();" class="reload">刷新</a></div>
<?php
date_default_timezone_set('PRC');
$dates2="";

$dosql->Execute("SELECT *,sum(chargenumber) as heji ,count(distinct date_format( `chargetime` , '%Y-%m-%d' )) from `pmw_charge` group by date_format( `chargetime` , '%Y-%m-%d' ) order by chargetime desc limit 15");
while($row=$dosql->GetArray()){
    $heji[] = floatval($row['heji']);        //合计注意这里必须要用intval强制转换，不然图表不能显示
	  $posttime[]=ReStrLen($row['chargetime'],10);
}

$dosql->Execute("SELECT *,sum(chargenumber) as houtai ,count(distinct date_format( `chargetime` , '%Y-%m-%d' )) from `pmw_charge` where chargetype=0 group by date_format( `chargetime` , '%Y-%m-%d' ) order by chargetime desc limit 15");
while($row0=$dosql->GetArray()){
	if(is_array($row0)){
    $houtai[] = floatval($row0['houtai']);
	}else{
	$houtai[]=array();
	}
}

$dosql->Execute("SELECT *,sum(chargenumber) as alipay ,count(distinct date_format( `chargetime` , '%Y-%m-%d' )) from `pmw_charge` where chargetype=1 group by date_format( `chargetime` , '%Y-%m-%d' ) order by chargetime desc limit 15");
while($row1=$dosql->GetArray()){
	if(is_array($row1)){
    $alipay[] = floatval($row1['alipay']);
	}else{
	$alipay[]=array();
	}
}

$dosql->Execute("SELECT *,sum(chargenumber) as weixinpay ,count(distinct date_format( `chargetime` , '%Y-%m-%d' )) from `pmw_charge` where chargetype=2 group by date_format( `chargetime` , '%Y-%m-%d' ) order by chargetime desc limit 15");
while($row2=$dosql->GetArray()){
	if(is_array($row2)){
    $weixinpay[] = floatval($row2['weixinpay']);
	}else{
	$weixinpay[]=array();
	}
}

$dosql->Execute("SELECT *,sum(chargenumber) as yinlian ,count(distinct date_format( `chargetime` , '%Y-%m-%d' )) from `pmw_charge` where chargetype=3 group by date_format( `chargetime` , '%Y-%m-%d' ) order by chargetime desc limit 15");
while($row3=$dosql->GetArray()){
	if(is_array($row3)){
    $yinlian[] = floatval($row3['yinlian']);
	}else{
	$yinlian[]=array();
	}
}

$dosql->Execute("SELECT *,sum(chargenumber) as yunshanfu ,count(distinct date_format( `chargetime` , '%Y-%m-%d' )) from `pmw_charge` where chargetype=4 group by date_format( `chargetime` , '%Y-%m-%d' ) order by chargetime desc limit 15");
while($row4=$dosql->GetArray()){
	if(is_array($row4)){
    $yunshanfu[] = floatval($row4['yunshanfu']);
	}else{
	  $yunshanfu[]=array();
	}
}

$posttimes=array();
$posttimes=array_reverse($posttime);
foreach($posttimes as $key=>$va){
$dates2.="'".$posttimes[$key]."',";
}
$houtai=array_reverse($houtai);
$alipay=array_reverse($alipay);
$weixinpay=array_reverse($weixinpay);
$yinlian=array_reverse($yinlian);
$yunshanfu=array_reverse($yunshanfu);
$heji=array_reverse($heji);

// for($i=-14;$i<1;$i++){
		// $dates2.="'".date("Y-m-d",strtotime("+$i day"))."',";
	// }
$data = array(
array(
"name"=>"后台支付",
"data"=>$houtai)
,
array(
"name"=>"支付宝支付",
"data"=>$alipay
)
,
array(
"name"=>"微信支付",
"data"=>$weixinpay
)
,
array(
"name"=>"银联支付",
"data"=>$yinlian
)
,
array(
"name"=>"云闪付支付",
"data"=>$yunshanfu
)
,
array(
"name"=>"合计",
"data"=>$heji
)
);
$data = json_encode($data);    //把获取的数据对象转换成json格式

?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="public/jquery-1.8.2.min.js"></script>
<script src="public/highcharts.js"></script>
<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            title: {
                text: '<?php echo $cfg_webname;  ?>15天后台,支付宝,微信,银联,云闪付支付曲线图',
                x: -20 //center
            },
            subtitle: {
                text: '来源:<?php echo $cfg_weburl;  ?>',
                x: -20
            },
            xAxis: {
              //  categories: ['周一', '周二', '周三', '周四', '周五', '周六','周日']
				categories: [<?php echo rtrim($dates2,",");?>]
            },
            yAxis: {
                title: {
                    text: ''
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '元'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series:<?php echo $data?>
        });
    });
</script>
<div class="homeTeam">
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</div>
<form name="form" id="form" method="post" action="comment_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="3%" height="31" align="center">日期</td>
			<td width="16%" align="center">后台支付</td>
			<td width="16%" align="center">支付宝支付</td>
			<td width="16%" align="center">微信支付</td>
			<td width="16%" align="center">银联卡支付</td>
			<td width="16%" align="center">云闪付</td>
			<td width="17%" align="center">支付合计</td>
		</tr>
        <?php
		$dopage->GetPage("SELECT *,sum(chargenumber) as heji from `pmw_charge` group by date_format( `chargetime` , '%Y-%m-%d' ) asc",15);
		while($row = $dosql->GetArray())
		{
     $sumheji[]=$row['heji'];
      ?>
		<tr align="left" class="dataTr">
			<td height="42" align="center"><?php  echo substr($row['chargetime'],0,10);?></td>
			<td align="center"><?php // echo $ht;?></td>
			<td align="center"><?php // echo $zfb;?></td>
			<td align="center"><?php // echo $wx;?></td>
			<td align="center"><?php // echo $yl;?></td>
			<td align="center"><?php // echo $ysf;?></td>
			<td align="center" class="num"><?php echo $row['heji'];?></td>
		</tr>
		<?php
		}

		?>
      <tr align="left" class="dataTr">
			<td height="42" align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center" class="num">合计：<font color="red"><B><?php echo array_sum($sumheji);?></B></font>元</td>
		</tr>
	</table>
</form>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>

</body>
</html>
