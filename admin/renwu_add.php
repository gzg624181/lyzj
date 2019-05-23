<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加任务列表</title>
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
<script>

  function changemtype(){

	  var options=$("#mtype option:selected");
	  var typename=options.val();
	  var title = document.getElementById("mtitles");
	  var sum = document.getElementById("msums");
	  var money = document.getElementById("mmoneys");
	  if(typename == "xuzhi"){
	  title.style.display = "none";
	  sum.style.display = "none";
	  money.style.display = "none";
	  }else{
      title.style.display = "";
	  sum.style.display = "";
	  money.style.display = "";
		  }
  }
</script>
</head>
<body>
<div class="formHeader">添加任务<a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="active_save.php" onsubmit="return cfm_renwu();">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
         <tr>
              <td height="40" align="right">任务类型：</td>
                  <td colspan="4" valign="middle"><select name="mtype" id="mtype" class="input" onchange="return changemtype();">
                    <option value="-1">请选择任务类型</option>
                    <option value="list">任务列表</option>
                    <option value="complete">任务达成</option>
                    <option value="xuzhi">任务须知</option>
                  </select></td>
            </tr>
		<tr style="display:none" id="mtitles">
		  <td width="16%" height="40" align="right">任务标题：</td>
		  <td width="84%" colspan="4"><input type="text" name="mtitle" id="mtitle" class="input"/></td>
         </tr>
         <tr style="display:none"  id="msums">
          <td height="40" align="right">任务条件：</td>
          <td colspan="4" valign="middle"><input type="text" name="msum" id="msum" class="input"/></td>
    </tr>
        <tr style="display:none"  id="mmoneys">
      <td height="40" align="right">任务奖励：</td>
      <td colspan="4" valign="middle"><input type="text" name="mmoney" id="mmoney" class="input"/></td>
    </tr>
   
      <tr>
		  <td height="272" align="right">任务规则：</td>
		  <td colspan="4"><textarea style="width:80%" name="mrules" id="mrules" class="kindeditor"></textarea>
				<script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="mrules"]', {
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
      <td height="40" align="right">&nbsp;</td>
       <td><div class="formSubBtn" style="float:left; margin-top:5px;">
        <input type="submit" class="submit" value="保存" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="renwu_add" />
  </div></td>
    </tr>
	</table>

</form>
</body>
</html>
