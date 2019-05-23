<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员推荐注册记录</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/listajax.js"></script>
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="topToolbar"> <span class="title">会员推荐码：</span><span class="num" style="color:red;"><?php echo $ucode;?></span> <a title="刷新" href="javascript:location.reload();" class="reload"><i class="fa fa-refresh" aria-hidden="true"></i></a></div>
<form name="form" id="form" method="post" action="product_save.php">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="center" class="head">
			<td width="11%" height="36" align="center">会员账号</td>
			<td width="17%" align="left">昵称</td>
			<td width="20%" align="center">账户余额</td>
			<td width="15%" align="center">提现姓名</td>
			<td width="18%" align="center">登陆设备</td>
		  <td width="19%" align="center">时间</td>
		</tr>
		<?php
		$jiuqian_la=0;
		$dopage->GetPage("SELECT * FROM `#@__members` where bcode='$ucode'",10);	
		$nums=$dosql->GetTotalRow(); 
		while($row = $dosql->GetArray())
		{
		switch($row['devicetype'])
			{
				case '1':
					$devicetype = 'Ios';
					break;
				case '0':
					$devicetype = 'Android';
					break;

			}
		?>
		<tr align="left" class="dataTr">
			<td height="70" align="center"><?php echo $row['telephone']; ?></td>
			<td align="center"><?php echo $row['nickname']; ?></td>
			<td align="center" class="num" style="color:red;"><?php echo $row['money']; ?></td>
			<td align="center"><?php echo $row['getname']; ?></td>
			<td align="center"><?php echo $devicetype; ?></td>
			<td align="center"><span class="number"><?php echo date("Y-m-d H:i:s",$row['regtime']);?></span>
            </td>
		</tr>
		<?php
		}
		?>
	</table>
</form>
<?php
if($dosql->GetTotalRow() != 0){ ?>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php }?>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="bottomToolbar"> 

<span style="text-align:right; display:block">
合计推荐会员数：<a class="num" style="color:red;">
<?php
$two=2;
$dosql->Execute("SELECT * FROM `#@__members` where bcode='$ucode'",$two);
$num=$dosql->GetTotalRow($two);
if($num == 0){
	echo 0;
	}else{
    echo $num;	
    }
	?>
</a>个
</span>
</div>

</body>
</html>