<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>手动后台充值</title>
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
<script>
function tuijian(){
     if($("#money").val() == "")
	{
		layer.alert("请输入打款金额！",{icon:2});
		$("#money").focus();
		return false;
	}
	}
</script>
</head>
<body>
<?php
//初始化参数
$adminlevel=$_SESSION['adminlevel'];
?>
<input type="hidden" name="adminlevel" id="adminlevel" value="<?php echo $adminlevel;?>" />
<div class="topToolbar"> <span class="title" style="text-align:center;">确认提现申请</span> <a title="刷新" href="javascript:location.reload();" class="reload" style="float:right; margin-right:35px;"><i class="fa fa-refresh" aria-hidden="true"></i></a></div>
<?php
if($type == "agency"){
 $tbname = "pmw_agency";
 $types = "旅行社";
}elseif($type == "guide"){
 $tbname = "pmw_guide";
 $types = "导游";
}
$s=$dosql->GetOne("SELECT * from  $tbname where id=$uid");
$r=$dosql->GetOne("SELECT * from  pmw_bank where uid=$uid and type='$type'");
if(is_array($r)){
  $cardname= $r['cardname'];
  $cardnumber= $r['cardnumber'];
}else{
  $cardname= "";
  $cardnumber= "";
}
?>
<form name="form" id="form" method="post" action="tixian_save.php" onsubmit="return tuijian();">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
		  <td height="40" align="right">提现申请账号：</td>
		  <td width="78%"><input type="text" name="accout" id="accout" value="<?php echo $s['account'];?>" readonly="readonly" class="input"/></td>
    </tr>
	<tr>
			<td width="22%" height="40" align="right">提现申请姓名：</td>
			<td><input type="text" name="name" id="name" value="<?php echo $s['name'];?>" readonly="readonly" class="input"/></td>
	</tr>
  <tr>
			<td width="22%" height="40" align="right">提现账号类别：</td>
			<td><input type="text" name="types" id="types" value="<?php echo $types;?>" readonly="readonly" class="input"/></td>
	</tr>
  <tr>
  <td height="40" align="right">打款银行：</td>
  <td><input type="text" name="cardname" id="cardname" value="<?php echo $cardname;?>" readonly="readonly" class="input"/></td>
  </tr>
		<tr>
		  <td height="40" align="right">打款账号：</td>
		  <td><input type="text" name="cardnumber" id="cardnumber" readonly  value="<?php echo $cardnumber; ?>" class="input"/></td>
    </tr>
    <tr>
		  <td height="40" align="right">打款金额：</td>
		  <td><input type="text" name="money" id="money" placeholder="请输入充值金额" value="<?php echo $money; ?>" class="input"/></td>
    </tr>
		<tr>
		  <td height="40" align="right">打款时间：</td>
		  <td><input type="text" name="chargetime" id="chargetime" class="inputms" value="<?php echo GetDateTime(time()); ?>" readonly="readonly" />
			<script type="text/javascript" src="plugin/calendar/calendar.js"></script>
		  <script type="text/javascript">
				Calendar.setup({
					inputField     :    "chargetime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
    </tr>

  </table>
	<div class="formSubBtn" style="float:left; margin-left:95px;margin-top: 15px;">
         <input type="submit" class="submit" value="提交" />
    		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
    		<input type="hidden" name="action" id="action" value="pick_money" />
        <input type="hidden" name="uid" id="uid" value="<?php echo $uid; ?>" />
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="type" id="type" value="<?php echo $type; ?>" />
  </div>
</form>
</body>
</html>
