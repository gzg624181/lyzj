<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>评论管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script>
function ord(OrderId)
{
  layer.open({
  type: 2,
  title: '订单详情：',
  maxmin: true,
  shadeClose: true, //点击遮罩关闭	层
  area : ['900px' , '600px'],
  content: 'ordershow.php?OrderId='+OrderId,
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
window.location.href='comment.php?keyword='+keyword;
}
function changestatus(status,id){
	var ajax_url='comment_ajax.php?id='+id+'&status='+status;
   //alert(ajax_url);
	$.ajax({
    url:ajax_url,
    type:'get',
	data: "data" ,
	dataType:'html',
    success:function(data){
     document.getElementById("changestatus"+id).innerHTML = data;
    } ,
	error:function(){
       alert('error');
    }
	});
	}
  function showimg(orderid,Id){
    var ajax_url='ajax_imgs.php?orderid='+orderid;
  // alert(ajax_url);
	$.ajax({
    url:ajax_url,
    type:'get',
	data: "data" ,
	dataType:'html',
    success:function(data){
	var div1 = document.getElementById("checkimages"+Id);
       div1.style.display="";
     document.getElementById("checkimages"+Id).innerHTML = data;
    } ,
	error:function(){
       alert('error');
    }
	});
  }
  function outimg(orderid,Id){

	var div1 = document.getElementById("checkimages"+Id);
     div1.style.display="none";

  }
function reply(id){
	var recomment=document.getElementById('recomment').value;
	window.location.href="comment_save.php?id="+id+"&action=reply"+"&recomment="+recomment;
	}
</script>
</head>
<body>
<?php
$adminlevel=$_SESSION['adminlevel'];
$keyword = isset($keyword) ? $keyword : '';
//将新的注册记录清空掉
$update = new Comment();
$update->update_comment_fankui('comment');
?>
<div class="topToolbar"> <span class="title">所有评论</span> <a href="javascript:location.reload();" class="reload"><?php  echo $cfg_reload; ?></a></div>
<div class="toolbarTab" style="margin-bottom:5px;">
  <div id="search" class="search"> <span class="s">
<input name="keyword" id="keyword" type="text" class="number" style="font-size:11px;" placeholder="请输入旅行社名称或者导游姓名" title="请输入旅行社名称或者导游姓名" />
		</span> <span class="b"><a href="javascript:;" onclick="GetSearchs();"></a></span></div>
	<div class="cl"></div>
</div>
<form name="form" id="form" method="post" action="comment_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="1%" height="31" align="center"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%" align="center">旅行社头像</td>
			<td width="9%" align="center">旅行社名称</td>
			<td width="34%">评论内容</td>
			<td width="9%">评价几星</td>
			<td width="15%">导游姓名</td>
			<td width="15%">评论时间</td>
			<td width="3%" align="left">操作</td>
		</tr>
		<?php
    if($keyword!=""){
    $dopage->GetPage("SELECT b.images,b.company,a.content,a.star,a.addtime,a.id,a.gid,c.name FROM `pmw_comment` a left join pmw_agency b on b.id=a.aid left join  pmw_guide c on a.gid=c.id where b.company like '%$keyword%' or c.name like '%$keyword%'",15);
    }else{
		$dopage->GetPage("SELECT b.images,b.company,a.content,a.star,a.addtime,a.id,a.gid,c.name FROM `pmw_comment` a left join pmw_agency b on b.id=a.aid left join  pmw_guide c on a.gid=c.id",15);
    }
		while($row = $dosql->GetArray())
		{

      if(check_str($row['images'],"https")){
        $images = $row['images'];
      }else{
        $images =$cfg_weburl.$row['images'];
      }

		?>

		<tr align="left" class="dataTr">
			<td height="42" align="center"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php  echo $row['Id']; ?>" /></td>
			<td align="center" valign="middle"><img style="height: 50px;padding: 5px;" src="<?php  echo $images;?>" /></td>
			<td align="center" valign="middle"><?php  echo $row['company'];?></td>
      <td align="center" ><?php  echo $row['content'];?></td>
			<td align="center" ><?php  echo $row['star'];?> </td>
			<td align="center" ><?php echo $row['name'];?></td></td>
			<td align="center" ><?php  echo date("Y-m-d H:i:s",$row['addtime']);?></td>
			<td align="center">
            <!-- <div id="jsddm"><a title="回复评论内容" href="javascript:vod(0)" onclick="reply('<?php echo $row['id'];?>');"><i class="fa fa-reply-all" aria-hidden="true"></i></a></div> -->

            <div id="jsddm"><a title="删除" href="comment_save.php?action=del2&id=<?php  echo $row['id']; ?>" onclick="return ConfDel(0);"><i class="fa fa-trash fa-lg fa-fw" aria-hidden="true"></i></a></div>

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
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('promotion_save.php');" onclick="return ConfDelAll(0);">删除</a></span></div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
</body>
</html>
