<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>购票记录</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="layer/layer.js"></script>
<style>
.layui-layer-iframe .layui-layer-btn, .layui-layer-page .layui-layer-btn {
    padding-top: 10px;
    text-align: center;
}
</style>
<script>
function getticket(id,type){
layer.open({
  type: 2,
  title: '下票人详细信息：',
  maxmin: true,
  shadeClose: true, //点击遮罩关闭	层
  area : ['600px' , '345px'],
  content: 'changeticket.php?id='+id+'&type='+type,
  });
  }

function changenums(id,states){
  if(states==0){
  layer.confirm('您确定要处理订单状态吗？',{btn: ['确定', '取消']},function(){
  layer.open({
  type: 2,
  title: '实际取票数量：',
  maxmin: true,
  shadeClose: true, //点击遮罩关闭	层
  area : ['600px' , '305px'],
  content: 'changenums.php?id='+id+'&states='+states,
  });
  });
  }else{
    layer.open({
    type: 2,
    title: '订单处理详情：',
    maxmin: true,
    shadeClose: true, //点击遮罩关闭	层
    area : ['600px' , '305px'],
    content: 'changenums.php?id='+id+'&states='+states,
    });
  }
  }
   //审核，未审，功能
function checkinfo(key){
var v= key;
window.location.href='allorder.php?check='+v;
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
$tbname="pmw_order";
$check = isset($check) ? $check : '';
$keyword = isset($keyword) ? $keyword : '';
$adminlevel=$_SESSION['adminlevel'];
//将新的注册记录清空掉
$update = new Order();
$update->update_order('order');
?>
</head>
<body>
<div class="topToolbar">
<span class="title">购票记录</span>
<a href="javascript:location.reload();" class="reload"><?php echo $cfg_reload;?></a>
</div>
<div class="toolbarTab">
	<ul>
 <li class="<?php if($check==""){echo "on";}?>"><a href="allorder.php">全部</a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="onrent"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('onrent')">已处理&nbsp;&nbsp;<i class='fa  fa-toggle-on' aria-hidden='true' style="color:#30B534"></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="unrent"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('unrent')">未处理&nbsp;&nbsp;<i class='fa fa-toggle-off' aria-hidden='true' style="color:red"></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="wxpay"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('wxpay')">微信支付&nbsp;&nbsp;<i class='fa fa-weixin' aria-hidden='true' style="color:#7bcb2b"></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="outline"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('outline')">线下支付&nbsp;&nbsp;<i class='fa fa-universal-access' aria-hidden='true' style="color:#4bb1cf"></i></a></li>
<li class="line">-</li>
<li class="<?php if($check=="Adult"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('Adult')">成人票&nbsp;&nbsp;<i class='fa fa-user' aria-hidden='true' style="color:#F1700E"></i></a></li>
  <li class="line">-</li>
  <li class="<?php if($check=="Children"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('Children')">优惠票&nbsp;&nbsp;<i class='fa fa-child' aria-hidden='true' style="color:#2B44BE"></i></a></li>

	</ul>
	<div id="search" class="search"> <span class="s">
<input name="keyword" id="keyword" type="text" class="number" placeholder="请输入取票人姓名,取票人电话，景区名称，使用日期进行搜索" title="请输入取票人姓名,取票人电话，景区名称，使用日期进行搜索" />
		</span> <span class="b"><a href="javascript:;" onclick="GetSearchs();"></a></span></div>
	<div class="cl"></div>
</div>
<form name="form" id="form" method="post" action="allorder_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="2%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="6%">取票人姓名</td>
			<td width="6%">取票人电话</td>
			<td width="8%">景区名称</td>
			<td width="6%">使用日期</td>
			<td width="10%">票务类型</td>
			<td width="6%">票务价格</td>
			<td width="6%" align="center">数量</td>
			<td width="6%" align="center">支付总金额</td>
			<td width="6%" align="center">实际取票数量</td>
			<td width="5%" align="center">实际支付总金额</td>
			<td width="5%" align="center">支付类型</td>
      <td width="5%" align="center">支付状态</td>
			<td width="5%" align="center">下票人</td>
			<td width="7%" align="center">购买时间</td>
			<td width="3%" align="center">状态</td>
			<td colspan="5" align="center">操作</td>
		</tr>
		<?php

  if($check=="onrent"){ //已处理
   $dopage->GetPage("SELECT * FROM $tbname where states=1",10);
 }elseif($check=="unrent"){ //未处理
   $dopage->GetPage("SELECT * FROM $tbname where states=0",10);
 }elseif($check=="wxpay"){ //微信支付
   $dopage->GetPage("SELECT * FROM $tbname where paytype='wxpay'",10);
 }elseif($check=="outline"){ //线下支付
   $dopage->GetPage("SELECT * FROM $tbname where paytype='outline'",10);
 }elseif($check=="Adult"){ //成人票
   $dopage->GetPage("SELECT * FROM $tbname where typename='成人票'",10);
 }elseif($check=="Children"){ //儿童票
   $dopage->GetPage("SELECT * FROM $tbname where typename='优惠票'",10);
 }elseif($check=="numsid"){
   $dopage->GetPage("SELECT * FROM $tbname where tid=$id",10);
 }elseif($check=="agencys"){
   $dopage->GetPage("SELECT * FROM $tbname where did=$id and type='$type'",10);
 }elseif($check=="guides"){
   $dopage->GetPage("SELECT * FROM $tbname where did=$id and type='$type'",10);
 }elseif($check=="today"){
   $ymd=date("Y-m-d");
   $dopage->GetPage("SELECT * FROM $tbname where ymd='$ymd'",10);
 }elseif($check=="tomorrowdingdan"){
   $ymd=date("Y-m-d",strtotime("-1 day"));
   $dopage->GetPage("SELECT * FROM $tbname where ymd='$ymd'",10);
  }elseif($check=="today_zhiufu"){
   $ymd=date("Y-m-d");
   $dopage->GetPage("SELECT * FROM $tbname where ymd='$ymd'",10);
 }elseif($check=="tomorrow_zhifu"){
   $ymd=date("Y-m-d",strtotime("-1 day"));
   $dopage->GetPage("SELECT * FROM $tbname where ymd='$ymd'",10);
  }

 elseif($keyword!=""){
   $dopage->GetPage("SELECT * FROM $tbname where jingquname like '%$keyword%' OR contactname like '%$keyword%' OR contacttel like '%$keyword%'  OR usetime like '%$keyword%' ",10);
     }else{
		 $dopage->GetPage("SELECT * from pmw_order",10);
     }
		while($row = $dosql->GetArray())
		{
			switch($row['states'])
			{

				  case 1:
					$states = "<font color='#339933'><B>"."<i title='已处理' class='fa fa-toggle-on' aria-hidden='true'></i>"."</b></font>";
					break;
				    case 0:
					$states = "<font color='#FF0000'><B>"."<i title='未处理' class='fa fa-toggle-off' aria-hidden='true'></i>"."</b></font>";
					break;
				}

        switch($row['pay_state'])
        {

            case 1:
            $pay_state = "<font color='#339933'><B>"."<i title='已支付' class='fa fa-check' aria-hidden='true'></i>"."</b></font>";
            break;
              case 0:
            $pay_state = "<font color='#FF0000'><B>"."<i title='未支付' class='fa fa-times' aria-hidden='true'></i>"."</b></font>";
            break;
          }

				switch($row['paytype'])
			{

				  case "wxpay":
					$pay = "<font color='#339933'><B>"."<i title='微信支付' class='fa fa-weixin'></i>"."</b></font>";
					break;
				  case "outline":
					$pay = "<font color='#4bb1cf'><B>"."<i title='线下支付' class='fa fa-universal-access'></i>"."</b></font>";
					break;
				}

        $eye ="<font color='#666'><B>"."<i title='点击查看下票人信息' class='fa fa-eye' aria-hidden='true'></i>"."</b></font>"
		?>
		<tr align="center" class="dataTr">
			<td height="40" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php  echo $row['contactname']; ?></td>
			<td><?php  echo $row['contacttel'];?></td>
			<td><?php  echo $row['jingquname']; ?></td>
			<td><?php  echo $row['usetime']; ?></td>
			<td><?php  echo $row['typename']; ?></td>
			<td class="num"><?php echo sprintf("%.2f",$row['price']); ?></td>
			<td align="center" class="num"><?php  echo $row['nums']; ?></td>
			<td  align="center" class="num"><?php echo sprintf("%.2f",$row['totalamount']); ?></td>
			<td align="center" class="num" style="color:red">
            <?php
			if($row['infactnums']==""){
				echo "";
			}else{
				echo "<span style='color:#243ea8'>".$row['infactnums']."</span>";
			}
			?>
            </td>
			<td align="center" class="num" >
            <?php
			if($row['infacttotalamount']==""){
				echo "";
			}else{
				echo "<span style='color:#20a9bd'>".sprintf("%.2f",$row['infacttotalamount'])."</span>";
			}
			?>
            </td>
			<td align="center"><span style="cursor:pointer;"><?php echo $pay;?></span></td>
      <td align="center"><?php echo $pay_state; ?></td>
			<td align="center"><a style="cursor:pointer" onclick="getticket('<?php echo $row['did']; ?>','<?php echo $row['type'];?>')" title="点击查看下票人信息"><?php echo $eye; ?></a></td>
			<td align="center"><?php  echo date("Y-m-d",$row['posttime']);?></td>
			<td align="center"><?php echo $states; ?></td>
			<td width="2%">
      <!-- states 操作是否已经处理   -->
       <?php if($row['states']==0){   //还未处理
             if($row['pay_state']==1){  //已经支付 ?>
             <a style="cursor:pointer" onclick="changenums('<?php echo $row['id']; ?>','<?php echo $row['states'] ?>')" title="点击更改当前的状态,更新实际取票数量或退款金额">
             <i class="fa fa-gavel" aria-hidden="true"></i></a>
        <?php   }else{ ?>
            <a style="cursor:pointer"><i title="待支付" class="fa fa-minus-circle" aria-hidden="true"></i></a>
       <?php   }?>
       <?php }else{  //状态已经处理?>
         <a style="cursor:pointer" onclick="changenums('<?php echo $row['id']; ?>','<?php echo $row['states'] ?>')" title="实际取票数量已更新，点击请查看">
         <i class="fa fa-info-circle" aria-hidden="true"></i></a>
       <?php  }?>

      </td>
		  <td width="2%">
      <?php if($adminlevel==1){ ?>
     <a title="删除购票订单" href="allorder_save.php?id=<?php echo $row['id']; ?>&amp;action=del6" onclick="return ConfDel(0);"><i class="fa fa-trash fa-fw" aria-hidden="true"></i></a>
   <?php }else{?>
     <i class="fa fa-trash fa-fw" aria-hidden="true"></i>
   <?php } ?>
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
