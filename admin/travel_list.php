<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>行程列表管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/listajax.js"></script>
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script>
function fn(id){
      if(layer.confirm("您确定要上线这个游戏吗?")){
        var ajax_url='product_save.php?action=getup&id='+id;
  //alert(ajax_url);
	$.ajax({
    url:ajax_url,
    type:'get',
	  data: "data" ,
	dataType:'html',
    success:function(data){
   document.getElementById("sj_"+id).innerHTML = data;
   window.location.reload()
    } ,
	error:function(){
       alert('error');
    }
	});
      }
}
function fd(id){
      if(layer.confirm("您确定要下线这个游戏吗?")){
     var ajax_url='product_save.php?action=getdown&id='+id;

  // alert(ajax_url);
	$.ajax({
    url:ajax_url,
    type:'get',
	data: "data" ,
	dataType:'html',
    success:function(data){
     document.getElementById("sj_"+id).innerHTML = data;
	 window.location.reload()
    } ,
	error:function(){
       alert('error');
    }
	});
      }
}
//审核，未审，功能
  function checkinfo(key){
     var v= key;
	// alert(v)
	window.location.href='product.php?check='+v;
	}
//标题搜索
   function GetSearchs(){
	 var keyword= document.getElementById("keyword").value;
	if($("#keyword").val() == "")
	{
		alert("请输入搜索内容！");
		$("#keyword").focus();
		return false;
	}
  window.location.href='product.php?keyword='+keyword;
}
function message(Id){
 //  alert(Id);
   layer.ready(function(){ //为了layer.ext.js加载完毕再执行
   layer.photos({
    photos: '#layer-photos-demo_'+Id,
	//area:['500px','300px'],  //图片的宽度和高度
    shift: 0 ,//0-6的选择，指定弹出图片动画类型，默认随机
	closeBtn:1,
	offset:'40px',  //离上方的距离
	shadeClose:true
  });
});
}
function checkcontent(id){
	 var ajax_url='game_save.php?action=checkcontent&id='+id;
  // alert(ajax_url);
	$.ajax({
    url:ajax_url,
    type:'get',
	data: "data" ,
	dataType:'html',
    success:function(data){
        layer.open({
        type: 1
        ,title: false //不显示标题栏
        ,closeBtn: false
        ,area: '800px;'
        ,shade: 0.8
        ,id: 'LAY_layuipros' //设定一个id，防止重复弹出
        ,btn: ['点击关闭']
        ,btnAlign: 'c'
        ,moveType: 1 //拖拽模式，0或者1
        ,content: "<div style='widht:750px;padding:25px; height:400px;line-height: 22px;'>"+ data+"</div>"
        ,
      });
    } ,
	error:function(){
       alert('error');
    }
	});
	}
</script>
</head>
<body>

<?php
//初始化参数
$action  = isset($action)  ? $action  : 'travel_save.php';
$keyword = isset($keyword) ? $keyword : '';
$check = isset($check) ? $check : '';

?>
<div class="topToolbar"> <span class="title">行程列表管理</span>
<a href="javascript:location.reload();" class="reload">刷新</a>

</div>
<div class="toolbarTab">
	<ul>
 <li class="<?php if($check==""){echo "on";}?>"><a href="travel_list.php">全部</a></li> <li class="line">-</li>
 <li class="<?php if($check=="appointment"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('appointment')">待预约</a></li> 
 <li class="line">-</li>
 <li class="<?php if($check=="confirm"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('confirm')">待确认</a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="complete"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('complete')">已完成</a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="concel"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('concel')">已取消</a></li>
	</ul>
	<div id="search" class="search"> <span class="s">
<input name="keyword" id="keyword" type="text" class="number" placeholder="请输入旅行社名称或者导游名字进行搜索" title="请输入旅行社名称或者导游名字进行搜索" />
		</span> <span class="b"><a href="javascript:;" onclick="GetSearchs();"></a></span></div>
	<div class="cl"></div>
</div>
<form name="form" id="form" method="post" action="<?php echo $action;?>">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="2%" height="36" align="center"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="7%" align="left">旅行社名称</td>
			<td width="10%" align="center">开始时间</td>
			<td width="15%" align="center">截止时间</td>
			<td width="9%" align="center">行程标题</td>
			<td width="9%" align="center">团队人数</td>
			<td width="17%" align="center">客源地</td>
			<td width="13%" align="center">行程安排</td>
			<td width="5%" align="center">备注</td>
			<td width="10%" align="center">发布时间</td>
			<td width="3%" align="center">操作</td>
		</tr>
		<?php
		$username=$_SESSION['admin'];
		$adminlevel=$_SESSION['adminlevel'];
		$tbname='pmw_travel';
		 if($check=="appointment"){ //待预约
		$dopage->GetPage("SELECT * FROM $tbname where state=0",10);
		  }elseif($check=="confirm"){ //待确认
		$dopage->GetPage("SELECT * FROM $tbname where state=1",10);
		  }elseif($check=="complete"){ //已完成
		$dopage->GetPage("SELECT * FROM $tbname where state=2",10);
		  }elseif($check=="concel"){ //已取消
		$dopage->GetPage("SELECT * FROM $tbname where state=3",10);
		  }elseif($keyword!=""){
		$dopage->GetPage("SELECT * FROM `pmw_game` where gametypes like '%$keyword%' OR gamename like '%$keyword%' ",10);
		  }else{
		$dopage->GetPage("SELECT * FROM `pmw_game`",10);
		  }
		while($row = $dosql->GetArray())
		{


			switch($row['gameonline']){

				case 0:
					$gameonline= "<i class='fa fa-times' aria-hidden='true'></i>";
					break;
				case 1:
					$gameonline= "<i class='fa fa-check' aria-hidden='true'></i>";
					break;
				default:
        $gameonline = '暂无分类';

				}
		?>
		<tr align="left" class="dataTr">
			<td height="50" align="center"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td align="center"><?php echo $row['game']; ?></td>
			<td align="center" class="num"><?php echo $row['gamename']; ?></td>
			<td align="center"><?php echo $row['gametypes']; ?></td>
			<td align="center" class="num" style="color:#529ee0"><?php echo $row['zsticheng']; ?></td>
			<td align="center" class="num" style="color:#529ee0"><?php echo $row['ejticheng']; ?></td>
			<td align="center"><a style="cursor:pointer;" onclick="checkcontent('<?php echo $row['id'];?>');">点击查看游戏赔率说明</a></td>
			<td align="center"><?php echo $row['gamenumber']; ?></td>
			<td align="center" id="sj_<?php echo $row['id'];?>"><?php
			if($row['gameonline']==1){
			echo "<font color='#339933'><B>"."<i class='fa fa-check' aria-hidden='true'></i>"."</b></font>";
			}else{
			echo "<font color='#FF0000'><B>"."<i class='fa fa-times' aria-hidden='true'></i>"."</b></font>";
			}
			?></td>
			<td align="center"><span class="number"><?php echo date("Y-m-d H:i:s",$row['gametime']); ?></span></td>
			<td align="center"><?php echo $checkinfo; ?></td>
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
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('product_save.php');" onclick="return ConfDelAll(0);">删除</a></span> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php
//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea">
        <a href="product_add.php" class="dataBtn">新增游戏</a> <span class="pageSmall"><?php echo $dopage->GetList(); ?></span>
        </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>
