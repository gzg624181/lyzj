<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admin'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提现记录</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

</head>
<body>
  <?php
  //初始化参数
  $action  = isset($action)  ? $action  : '';
  if($state==0){
    $titlename ="待提现记录，合计：".$money;
  }elseif($state==1){
    $titlename ="提现失败，合计：".$money;
  }elseif($state==2){
    $titlename ="提现成功，合计：".$money;
  }
  ?>
<div class="topToolbar"> <span class="title"><?php echo $titlename;?></span></span>
<a href="javascript:location.reload();" class="reload"><?php echo $cfg_reload; ?></a></div>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="center" class="head">
		<td width="5%" height="36" class="firstCol">ID</td>
		<td width="10%">提现账号</td>
		<td width="10%">提现姓名</td>
    <td width="10%">账号类型</td>
		<td width="10%">提现金额</td>
		<td width="25%">提现提示</td>
    <td width="15%">操作时间</td>
	</tr>
	<?php

  //将提现的记录显示出来

  $sql = "SELECT * FROM `#@__tixian` where state=$state and uid=$uid and type='$type'";

	$dopage->GetPage($sql,$cfg_pagenum,'DESC');
	while($row = $dosql->GetArray())
	{
    $type = $row['type'];
    $uid = $row['uid'];
    $id = $row['id'];
    $money = $row['money'];

    if($type == "agency"){
     $tbname = "pmw_agency";
     $types = "旅行社";
    }elseif($type == "guide"){
     $tbname = "pmw_guide";
     $types = "导游";
    }

	$r = $dosql->GetOne("SELECT name,account FROM $tbname WHERE id=$uid");
  if(is_array($r)){
    $name = $r['name'];
    $account = $r['account'];
  }else{
    $name ="账号已被删除";
    $account ='账号已被删除';
  }

	?>

	<tr align="center" class="dataTr">
		<td height="36" class="firstCol"><?php echo $row['id']; ?></td>
		<td><?php echo $account; ?></td>
		<td><?php echo $name; ?></td>
    <td><?php echo $types; ?></td>
		<td class="num" style="color:red;"><?php echo $money; ?></td>
		<td><?php echo $row['reason']; ?></td>
    <td class="number"><?php echo GetDateTime($row['applytime']); ?></td>
	</tr>
	<?php
	}
	?>
</table>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>

<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php
//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea">
        <span class="pageSmall"><?php echo $dopage->GetList(); ?></span>
        </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>
