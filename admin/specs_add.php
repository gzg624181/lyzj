<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加票务规格</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/getuploadify.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="templates/js/getjcrop.js"></script>
<script type="text/javascript" src="templates/js/getinfosrc.js"></script>
<script type="text/javascript" src="plugin/colorpicker/colorpicker.js"></script>
<script type="text/javascript" src="plugin/calendar/calendar.js"></script>
<script type="text/javascript" src="editor/kindeditor-min.js"></script>
<script type="text/javascript" src="editor/lang/zh_CN.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script>

function closes(){

  var index=parent.layer.getFrameIndex(window.name);

parent.layer.close(index);
}

</script>
<style>
.input {
    width: 325px;
    height: 35px;
    border-radius: 3px;
}
.input1 {    width: 280px;
    height: 35px;
    border-radius: 3px;
}
</style>
</head>
<body>
<?php
//初始化参数
$adminlevel=$_SESSION['adminlevel'];
?>
<input type="hidden" name="adminlevel" id="adminlevel" value="<?php echo $adminlevel;?>" />
<div class="topToolbar"> <span class="title" style="text-align:center;">添加票务规格</span> <a title="刷新" href="javascript:location.reload();" class="reload" style="float:right; margin-right:35px;"><?php echo $cfg_reload;?></a></div>
<?php
$s=$dosql->GetOne("select * from `#@__ticket` where id=$id");
?>
<form name="form" id="form" method="post" action="ticket_save.php">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">

		<tr>
		  <td width="22%" height="40" align="right">票务类型</td>
		  <td width="22%" align="center">正常价格</td>
		  <td width="25%" align="center">双休价格</td>
		  <td width="31%">操作</td>
    </tr>
<?php
   $dosql->Execute("SELECT * FROM pmw_specs where tid=$id");
   while($row=$dosql->GetArray()){
?>
  <tr>
  <td height="40" align="right"><?php echo $row['tickettype'];?></td>
  <td align="center"><?php echo $row['normalmoney'];?></td>
  <td align="center"><?php echo $row['resetmoney'];?></td>
  <td><span><a title="编辑" href="specs_update.php?id=<?php echo $row['id']; ?>">
  <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></span> &nbsp;&nbsp;
  <span class="nb"><a title="删除" href="ticket_save.php?action=del100&id=<?php echo $row['id']; ?>&tid=<?php echo $row['tid'];?>" onclick="return ConfDel(0);"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span></td>
  </tr>
<?php }?>
  </table>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">

		<tr>
		  <td height="40" align="right">景区名称：</td>
		  <td width="78%"><input type="text" name="names" id="names" class="input" readonly="readonly" value="<?php echo $s['names'];?>"/></td>
    </tr>

  <tr>
  <td height="40" align="right">票务类型：</td>
  <td><input type="text" name="tickettype" id="tickettype" placeholder="请输入票务类型" value="" required="required" class="input"/></td>
  </tr>
		<tr>
		  <td height="40" align="right">平常价格：</td>
		  <td><input type="text" name="normalmoney" id="normalmoney" placeholder="请输入金额" required="required" value="" class="input"/></td>
      </tr>
      <tr>
		  <td height="40" align="right">双休价格：</td>
		  <td><input type="text" name="resetmoney" id="resetmoney" placeholder="请输入金额" required="required" value="" class="input"/></td>
      </tr>

  </table>
	<div class="formSubBtn" style="float:left; margin-left:95px;margin-top: 15px;">
         <input type="submit" class="submit" value="提交" />
    	 <input type="button" class="back" value="关闭" onclick="closes()" />
    	 <input type="hidden" name="action" id="action" value="specs_add" />
         <input type="hidden" name="tid" id="tid" value="<?php echo $id;?>" />
  </div>
</form>
</body>
</html>
