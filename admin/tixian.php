<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admin'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提现管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script>
function checkinfo(key){
   var v= key;
// alert(v)
window.location.href='tixian.php?check='+v;
}

function confirm_money(uid,money,type,id)

{

layer.open({
  type: 2,
  title: '',
  maxmin: true,
  shadeClose: true, //点击遮罩关闭	层
  area : ['480px' , '420px'],
  content: 'get_tixian.php?uid='+uid+'&money='+money+'&type='+type+'&id='+id,
  });
}

function tixian_list(uid,type,id,state,money)

{

layer.open({
  type: 2,
  title: '',
  maxmin: true,
  shadeClose: true, //点击遮罩关闭	层
  area : ['80%' , '80%'],
  content: 'tixian_list.php?uid='+uid+'&type='+type+'&id='+id+'&state='+state+'&money='+money,
  });
}



function failed_money(uid,type,money,id)

{
layer.open({
  type: 2,
  title: '',
  maxmin: true,
  shadeClose: true, //点击遮罩关闭	层
  area : ['50%' , '50%'],
  content: 'get_tixian_failed.php?uid='+uid+'&type='+type+'&id='+id+'&money='+money,
  });
}
</script>
</head>
<body>
  <?php
  //初始化参数
  $action  = isset($action)  ? $action  : '';
  $check = isset($check) ? $check : 'tixian';
  ?>
<div class="topToolbar"> <span class="title">提现管理</span>
<a href="javascript:location.reload();" class="reload"><?php echo $cfg_reload; ?></a></div>
<div class="toolbarTab" style="margin-bottom:5px;">
<ul>

 <li class="<?php if($check=="tixian"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('tixian')">待提现&nbsp;&nbsp;<i style='color:#509ee1; cursor:pointer;' title='待提现' class='fa fa-exclamation-triangle' aria-hidden='true'></i></a></li>
 <li class="line">-</li>

 <li class="<?php if($check=="success"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('success')">提现成功&nbsp;&nbsp;<i style='color:#509ee1;cursor:pointer;'  title='提现成功' class='fa fa-check' aria-hidden='true'></i></a></li>
 <li class="line">-</li>

 <li class="<?php if($check=="failed"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('failed ')">提现失败&nbsp;&nbsp;<i style='color:red; cursor:pointer;' title='提现失败' class='fa fa-times' aria-hidden='true'></i></a></li>

</ul>
	<div class="cl"></div>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
	<tr align="center" class="head">
		<td width="5%" height="36" class="firstCol">ID</td>
		<td width="10%">提现账号</td>
		<td width="10%">提现姓名</td>
    <td width="10%">账号类型</td>
		<td width="10%">提现金额</td>
		<td width="25%">提现提示</td>
    <td width="15%">申请时间</td>
		<td width="15%">操作</td>
	</tr>
	<?php
	$username=$_SESSION['admin'];
	$adminlevel=$_SESSION['adminlevel'];

  //将提现的记录显示出来

  if($check=="tixian"){  //待提现
	$sql = "SELECT *,sum(money) as money FROM `#@__tixian` where state=0 group by type,uid";
  }elseif($check=="success"){
  $sql = "SELECT *,sum(money) as money FROM `#@__tixian` where state=2 group by uid";
  }elseif($check=="failed"){
  $sql = "SELECT *,sum(money) as money FROM `#@__tixian` where state=1 group by uid";
  }

	$dopage->GetPage($sql,$cfg_pagenum,'DESC');
	while($row = $dosql->GetArray())
	{
    $type = $row['type'];
    $uid = $row['uid'];
    $id = $row['id'];
    $money = $row['money'];
    $zt = $row['state'];

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

		switch($row['state'])
		{
			case  0:
				$state_success = "<font color='#339933'><B>"."<i  title='点击进行打款提现操作' class='fa fa-check' aria-hidden='true'></i>"."</b></font>";

        $state_faield = "<font color='#d90aa5'><B>"."<i title='打款失败' class='fa fa-times' aria-hidden='true'></i>"."</b></font>";

        $state = '<a style="cursor:pointer" onclick=" confirm_money('."'$uid'".','."'$money'".','."'$type'".','."'$id'".');" >'.$state_success.'</a>';
        $state .= "&nbsp;&nbsp;&nbsp;";
        $state .= '<a style="cursor:pointer" onclick="return failed_money('."'$uid'".','."'$type'".','."'$money'".','."'$id'".');" >'.$state_faield.'</a>';
				break;
			case 1:
				$state = "<font color='red'><B>"."<i title='提现失败' class='fa fa-times' aria-hidden='true'></i>"."</b></font>";
				break;
      case 2:
  			$state = "<font color='#509ee1'><B>"."<i title='提现成功' class='fa fa-check' aria-hidden='true'></i>"."</b></font>";
  				break;
			default:
				$state = '没有获取到参数';
		}
    $delstr = '<a href="tixian_save.php?action=del&id='.$row['id'].'" onclick="return ConfDel(0);"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';

    $tixian_list = "<font color='#339933'><B>"."<i  title='点击查看提现记录' class='fa fa-eye' aria-hidden='true'></i>"."</b></font>";

    $state_str = '<a style="cursor:pointer" onclick=" tixian_list('."'$uid'".','."'$type'".','."'$id'".','."'$zt'".','."'$money'".');" >'.$tixian_list.'</a>';
	?>

	<tr align="center" class="dataTr">
		<td height="36" class="firstCol"><?php echo $row['id']; ?></td>
		<td><?php echo $account; ?></td>
		<td><?php echo $name; ?></td>
    <td><?php echo $types; ?></td>
		<td class="num" style="color:red;"><?php echo $money; ?></td>
		<td><?php echo $row['reason']; ?></td>
    <td class="number"><?php echo GetDateTime($row['applytime']); ?></td>
		<td>
		<span><?php echo $state; ?></span> &nbsp;&nbsp;
    <?php echo $state_str; ?>
		</td>
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
