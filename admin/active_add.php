<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加活动列表</title>
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
<script type="text/javascript" src="templates/js/getarea.js"></script>
<script>layer.ready(function(){ //为了layer.ext.js加载完毕再执行
   layer.photos({

    photos: '#layer-photos-demo',
	//area:['500px','300px'],  //图片的宽度和高度
    shift: 0 ,//0-6的选择，指定弹出图片动画类型，默认随机
	closeBtn:1,
	offset:'100px',  //离上方的距离
	shadeClose:true
  });
});

</script>
</head>
<body>
<div class="formHeader">添加活动<a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="active_save.php" onsubmit="return cfm_active();">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
		  <td width="12%" height="40" align="right">活动名称：</td>
		  <td colspan="4"><input type="text" name="active_name" id="active_name" class="input"/></td>
    </tr>
    <tr>
			<td height="40" align="right">活动开放头像：</td>
			<td colspan="4" valign="middle">
   <input style="margin-top:5px;" type="text" name="active_onimages" id="active_onimages" class="input" />
				<span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','缩略图上传','image','image',1,<?php echo $cfg_max_file_size; ?>,'active_onimages')"></span> <span class="rePicTxt">
			</span></td>
	  </tr>
    <tr>
      <td height="40" align="right">活动结束头像：</td>
      <td colspan="4" valign="middle">
   <input style="margin-top:5px;" type="text" name="active_offimages" id="active_offimages" class="input" />
        <span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','缩略图上传','image','image',1,<?php echo $cfg_max_file_size; ?>,'active_offimages')"></span> <span class="rePicTxt">
      </span></td>
    </tr>
      <tr>
	  <td height="40" align="right">是否在线：</td>
		  <td colspan="3"><p>
          <label>
		      <input type="radio" name="active_statues" value="1" checked='active_statues' id="active_online" />
		      在线</label>
          &nbsp;&nbsp;
		    <label>
		      <input name="active_statues" type="radio" id="active_statues" value="0" />
		      离线</label><br />
	      </p></td>
		  <td width="39%" align="right">&nbsp;</td>
    </tr>
      <tr>
		  <td height="272" align="right">活动说明：</td>
		  <td colspan="4"><textarea style="width:80%" name="active_description" id="active_description" class="kindeditor"></textarea>
				<script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="active_description"]', {
						allowFileManager : true,
						width:'80%',
						height:'365px',
						extraFileUploadParams : {
							sessionid :  '<?php echo session_id(); ?>'
						}
					});
				});
				</script>			<div id="fenlei" style="font-size:12px; color:#ffa8a8;display:inline;"></div></td>
	  </tr>
      <tr>
        <td height="40" align="right">排列排序：</td>
        <td width="11%"><input type="text" name="orderid" id="orderid" class="inputos" value="<?php echo GetOrderID('#@__active'); ?>" /></td>
      </tr>
      <tr>
      <td height="40" align="right">&nbsp;</td>
       <td><div class="formSubBtn" style="float:left; margin-top:5px;">
        <input type="submit" class="submit" value="保存" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="add" />
  </div></td>
    </tr>
	</table>

</form>
</body>
</html>
