<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>下注记录</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="layer/layer.js"></script>
<style>
.layui-layer-iframe .layui-layer-btn, .layui-layer-page .layui-layer-btn {
    padding-top: 10px;
    text-align: center;
}
</style>
<script>
 function xiazhuorder(id){

	   layer.open({
		   type:2,
		   title:'',
		   maxmin:true,
		   shadeClose:true,
		   area : ['480px' , '480px'],
       id: 'LAY_layuipro', //设定一个id，防止重复弹出
       btn: ['点击关闭'],
       moveType: 1 ,//拖拽模式，0或者1
       content: 'xiazhuorder.php?gid='+id,
	   });

   }
  function GetSearchs(){
var keyword= document.getElementById("keyword").value;
if($("#keyword").val() == "")
{
 layer.alert("请输入搜索内容！",{icon:0});
 $("#keyword").focus();
 return false;
}
window.location.href='allorder.php?keyword='+keyword;
} 
</script>
<?php
//初始化参数
$check = isset($check) ? $check : '';
$keyword = isset($keyword) ? $keyword : '';
?>
</head>
<body>
<div class="topToolbar">
<span class="title">下注记录</span>
<a href="javascript:location.reload();" class="reload">刷新</a>
</div>

<form name="form" id="form" method="post" action="money_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="2%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="4%">下注昵称</td>
			<td width="7%">下注账号</td>
			<td width="7%">下注游戏</td>
			<td width="7%">下注期数</td>
			<td width="13%">下注订单号</td>
			<td width="9%">下注时间</td>
			<td width="9%" align="center">下注开奖时间</td>
			<td width="9%" align="center">下注总金额</td>
			<td width="9%" align="center">开奖号码</td>
			<td width="6%" align="center">中奖金额</td>
			<td width="6%" align="center">中奖盈亏</td>
			<td width="6%" align="center">开奖状态</td>
			<td colspan="2" align="center">操作</td>
		</tr>
		<?php

		$dopage->GetPage("SELECT a.id,b.telephone,c.gametypes,b.nickname,a.xiazhu_qishu,a.xiazhu_orderid,a.xiazhu_timestamp,a.xiazhu_kjtime,a.xiazhu_sum,a.xiazhu_jiangjin,a.xiazhu_kjstate,d.kj_varchar FROM `pmw_xiazhuorder` a inner join `pmw_members` b on a.uid=b.id inner join `pmw_game` c on a.gameid=c.id inner join `pmw_lotterynumber` d on a.xiazhu_qishu=d.kj_times",18);

		while($row = $dosql->GetArray())
		{
			switch($row['xiazhu_kjstate'])
			{

				    case 1:
					$xiazhu_kjstate = "<font color='#339933'><B>"."<i title='已开奖' class='fa fa-check' aria-hidden='true'></i>"."</b></font>";
                    $xiazhu_jiangjin=$row['xiazhu_jiangjin'];
					$kj_number=$row['kj_varchar'];
					break;
				    case 0:
					$xiazhu_kjstate = "<font color='#FF0000'><B>"."<i title='未开奖' class='fa fa-times' aria-hidden='true'></i>"."</b></font>";
                    $xiazhu_jiangjin="";
					$kj_number="";
					break;
				}
		?>
		<tr align="center" class="dataTr">
			<td height="40" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php  echo $row['nickname']; ?></td>
			<td><?php  echo $row['telephone'];?></td>
			<td><?php  echo $row['gametypes']; ?></td>
			<td><?php  echo $row['xiazhu_qishu']; ?></td>
			<td><a style="cursor:pointer" onclick="xiazhuorder('<?php echo $row['id']; ?>')" title="查看下注详情"><?php  echo $row['xiazhu_orderid']; ?></a></td>
			<td><?php  echo date("Y-m-d H:i:s",$row['xiazhu_timestamp']); ?></td>
			<td align="center"><?php  echo date("Y-m-d H:i:s",$row['xiazhu_kjtime']); ?></td>
			<td  align="center"><?php echo sprintf("%.2f",$row['xiazhu_sum']); ?></td>
			<td align="center"><?php echo $kj_number;?></td>
			<td align="center"><?php echo sprintf("%.2f",$xiazhu_jiangjin);?></td>
			<td align="center" class="num">
            <?php
			  if($row['xiazhu_kjstate']!=0){
				$yingkui= sprintf("%.2f",$xiazhu_jiangjin - $row['xiazhu_sum']);
				if($yingkui>=0){
				echo "<font color='#3399FF'>+".$yingkui."</font>";	
				}else{
				echo "<font color='#ff0a0a'>".$yingkui."</font>";	
				}
			  }
			?>
            </td>
			<td align="center"><?php echo $xiazhu_kjstate; ?></td>
			<td width="3%">
      <div id="jsddm" style="margin-top: 6px;margin-bottom: 8px;"><a style="cursor:pointer" onclick="xiazhuorder('<?php echo $row['id']; ?>')" title="查看下注详情"><i class="fa fa-eye" aria-hidden="true"></i></a></div></td>
		  <td width="3%">
      <div id="jsddm" style="margin-top: 6px;margin-bottom: 8px;"><a title="删除下注订单" href="allorder_save.php?id=<?php echo $row['id']; ?>&amp;action=del6" onclick="return ConfDel(0);"><i class="fa fa-trash fa-fw" aria-hidden="true"></i></a></div>
    </td>
		</tr>
		<?php
		}
		?>
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
<p>&nbsp;</p>

</body>
</html>
