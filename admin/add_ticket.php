<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admanage'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加景区</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<script type="text/javascript" src="templates/js/getuploadify.js"></script>
<script type="text/javascript" src="layui/layui.js"></script>
<link href="layui/css/layui.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
//初始化参数
$action  = isset($action)  ? $action  : 'ticket_save.php';
$tbname='pmw_ticketclass';
?>
<div class="formHeader"> <span class="title" style="margin-left: 13px;">添加景区</span> <a href="javascript:location.reload();" class="reload"><i class="fa fa-refresh fa-spin fa-fw"></i></a> </div>
<form name="form" id="form" method="post" action="<?php echo $action;?>">
	<table id="table1"  width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable" >
         <tr>
			<td width="25%" height="40" align="right">景区名称：</td>
			<td width="75%">
      <input type="text" class="input" id="name" name="name" required="required">
      <span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span>
      </td>
		</tr>

		<tr>
		 <td height="40" align="right">属　性：</td>
		 <td class="attrArea"><?php
		 $dosql->Execute("SELECT * FROM `#@__infoflag` ORDER BY orderid ASC");
		 while($row = $dosql->GetArray())
		 {
			 echo '<span><input type="checkbox" name="flag[]" id="flag[]" value="'.$row['flag'].'" />'.$row['flagname'].'['.$row['flag'].']</span>';
		 }
		 ?></td>
	 </tr>

    <tr>
    <td width="25%" height="40" align="right">景区分类：</td>
    <td>
    <select name="types" id="types" class="input" style="width:508px;">
               <?php
      $dosql->Execute("SELECT * FROM pmw_ticketclass order by id asc");
      while($row=$dosql->GetArray()){
      ?>
    <option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
    <?php }?>
    </select>
    </td>
  </tr>

      <tr>
        <td width="25%" height="40" align="right">景区级别：</td>
        <td>
         <select class="input" name="level" id="level" style="width:508px;">
           <option value="1">1星</option>
           <option value="2">2星</option>
           <option value="3">3星</option>
           <option value="4">4星</option>
           <option value="5">5星</option>
         </select>
        </td>
      </tr>

      <tr>
      <td width="25%" height="40" align="right">景区标签：</td>
      <td width="75%">
      <input type="text" class="input" id="label" name="label" required="required">
      <span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span>
      </td>
      </tr>

      <tr>
      			<td height="124" align="right">景区图片：</td>
      			<td colspan="11"><fieldset class="picarr" style="width:80%">
      					<legend>列表</legend>
      					<div>最多可以上传<strong>50</strong>张图片<span onclick="GetUploadify('uploadify2','组图上传','image','image',50,<?php echo $cfg_max_file_size; ?>,'picarr','picarr')">开始上传</span></div>
      					<ul id="picarr">
      					</ul>
      				</fieldset></td>
      		</tr>


        <tr>
        <td height="40" align="right">备注信息：</td>
        <td><textarea  name="remarks" id="remarks" class="input" style="height:90px; width:51%;"></textarea>
        </td>
        </tr>

        <tr>
        <td width="25%" height="40" align="right">已售设置：</td>
        <td width="75%">
        <input type="text" class="input" id="solds" name="solds" required="required">
        <span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span>
        </td>
        </tr>

		<tr>
			<td height="40" align="right">添加时间：</td>
			<td><input type="text" name="posttime" id="posttime" class="input" value="<?php echo GetDateTime(time()); ?>" />
				<script type="text/javascript">
				Calendar.setup({
					inputField     :    "posttime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="add_ticket" />
  </div>
</form>

</body>
</html>
