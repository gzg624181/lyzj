<?php require_once(dirname(__FILE__).'/inc/config.inc.php');?>
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
<div class="topToolbar"> <span class="title">订单统计&nbsp;&nbsp;&nbsp;<i class="fa fa-line-chart" aria-hidden="true"></i></span>
 <a href="javascript:location.reload();" class="reload"><?php echo $cfg_reload;?></a></div>
<?php
date_default_timezone_set('PRC');
$dates2="";

$dosql->Execute("SELECT *,sum(totalamount) as money,sum(nums) as nums from `pmw_order` group by ymd asc limit 15");
while($row=$dosql->GetArray()){
      $pv[] = floatval($row['money']);//购买金额  //注意这里必须要用intval强制转换，不然图表不能显示
	  $tz[] = floatval($row['nums']);
	  $dates2.="'".$row['ymd']."',";
}


$data = array(
array(
"name"=>"购票金额(元)",
"data"=>$pv)
,
array(
"name"=>"购票数量(张)",
"data"=>$tz
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
                text: '<?php echo $cfg_webname;?>'+ "15天购票金额,购票数量统计表",
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
			<td align="center">微信支付</td>
			<td align="center">线下支付</td>
			<td width="17%" align="center">支付合计</td>
		</tr>
        <?php
	 $wxpay=array();
	 $dosql->Execute("SELECT sum(totalamount) as wxpay from `pmw_order` where paytype='wxpay' group by ymd asc limit 15");
while($row1=$dosql->GetArray()){
	if(is_array($row1)){
    $wxpay[]= floatval($row1['wxpay']);
	}else{
	$wxpay[] =array();
	}
}

	 $outpay=0;
	 $dosql->Execute("SELECT sum(totalamount) as outpay from `pmw_order` where paytype='outline' group by ymd asc limit 15");
while($row1=$dosql->GetArray()){
	if(is_array($row1)){
    $outpay += floatval($row1['outpay']);
	}else{
	$outpay =0;
	}
}

		$wxpay=0;
		$outpay=0;
		$dopage->GetPage("SELECT *,sum(totalamount) as heji from `pmw_order` group by ymd asc",15);
		while($row = $dosql->GetArray())
		{
		 $ymd=$row['ymd']; 	
         $sumheji[]=$row['heji'];
		
			$r=$dosql->GetOne("SELECT sum(totalamount) as wxpay  FROM pmw_order where ymd='$ymd' and paytype='wxpay'");
			
			$wxpay=$r['wxpay'];
			
			if($wxpay==null){
				$wxpay=0;
			}
			
			$r=$dosql->GetOne("SELECT sum(totalamount) as outpay  FROM pmw_order where ymd='$ymd' and paytype='outline'");
			
			$outpay=$r['outpay'];
			
			if($outpay==null){
				$outpay=0;
			}
		 

      ?>
		<tr align="left" class="dataTr">
			<td height="42" align="center"><?php  echo $row['ymd'];?></td>
			<td align="center"><?php echo $wxpay;?></td>
			<td align="center"><?php  echo $outpay;?></td>
			<td align="center" class="num"><?php echo $row['heji'];?></td>
		</tr>
		<?php
		}

		?>
      <tr align="left" class="dataTr">
			<td height="42" align="center"></td>
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
