<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改banner图片</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
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

</head>
<body>
<div class="formHeader"> <span class="title">修改banner图片</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<?php
$row = $dosql->GetOne("SELECT * FROM `pmw_banner` WHERE id=$id");
?>
<form name="form" id="form" method="post" action="banner_save.php" onsubmit="return quan();">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
		  <td height="40" align="right">标题：</td>
		  <td colspan="2"><input type="text" name="title" id="title" class="input" value="<?php echo $row['title'];?>"/></td>
    </tr>
		<tr>
			<td height="40" align="right">跳转链接：</td>
			<td colspan="2"><input type="text" name="linkurl" id="linkurl" class="input"  value="<?php echo $row['linkurl'];?>"/></td>
		</tr>

		<tr>
			<td width="9%" height="75" align="right">banner图片：</td>
			<td width="8%" align="center"><img  width="100" height="50" style="cursor:pointer; padding:5px;border-radius:3px;" layer-src="<?php echo $row['pic']; ?>"  src="<?php echo $row['pic']; ?>" alt="<?php echo $row['title']; ?>" /></td>
			<td width="83%"></div>
   <input style="margin-top:5px;" type="text" name="pic" id="pic" class="input" value="<?php echo $row['pic']; ?>" />
	 <span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','缩略图上传','image','image',1,20971520,'pic')">上 传</span></span></td>
		</tr>
        	<tr>
        	  <td height="40" align="right">简介：</td>
        	  <td colspan="2"> <textarea name="content" id="content" class="kindeditor"><?php echo $row['content'];?></textarea>
       	      <script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="content"]', {
						allowFileManager : true,
						width:'100%',
						height:'400px',
						extraFileUploadParams : {
							sessionid :  '<?php echo session_id(); ?>'
						}
					});
				});
				</script>	</td>
       	  </tr>
        	<tr>
        	  <td height="40" align="right">更新时间：</td>
        	  <td colspan="2"> <input type="text" name="pictime" id="pictime" class="inputms" value="<?php echo GetDateTime(time()); ?>" />
        	    <script type="text/javascript" src="plugin/calendar/calendar.js"></script>
       	      <script type="text/javascript">
				Calendar.setup({
					inputField     :    "pictime",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
				});
				</script></td>
       	  </tr>
          <tr>
        	  <td height="40" align="right"></td>
        	  <td colspan="2"> <div class="formSubBtn" style="float:left; margin-bottom:30px;">
         <input type="submit" class="submit" value="保存" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
        <input type="hidden" name="id" id="id" value="<?php echo $row['id'];?>" />
  </div></td>
       	  </tr>
  </table>

</form>
</body>
</html>
