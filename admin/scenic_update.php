<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admanage'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改景区</title>
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
<script type="text/javascript" src="templates/js/ajax.js"></script>
<script type="text/javascript" src="templates/js/getarea.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
//初始化参数
$action  = isset($action)  ? $action  : 'ticket_save.php';
$tbname='pmw_ticket';
$r=$dosql->GetOne("SELECT * FROM $tbname where id=$id");
?>
<div class="formHeader"> <span class="title" style="margin-left: 13px;">修改景区</span> <a href="javascript:location.reload();" class="reload"><i class="fa fa-refresh fa-spin fa-fw"></i></a> </div>
<form name="form" id="form" method="post" action="<?php echo $action;?>">
	<table id="table1"  width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable" >
         <tr>
			<td width="25%" height="40" align="right">景区名称：</td>
			<td width="75%">
      <input type="text" class="input" id="names" name="names" value="<?php echo $r['names'];?>" required="required">
      <span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span>
      </td>
		</tr>

		<tr>
		 <td height="40" align="right">属　性：</td>
		 <td class="attrArea">
			 <?php
		 $dosql->Execute("SELECT * FROM `#@__infoflag` ORDER BY orderid ASC");
		 while($row = $dosql->GetArray()){
		 ?>

         <span>

		  <!-- check_str  判断函数匹配 ，（前面的字符串字符集   需要匹配的字符）    -->
		<input type="checkbox" <?php if(check_str($r['flag'],$row['flag'])){echo "checked";}?>  name="flag[]" id="flag[]" value="<?php echo $row['flag'];?>" /><?php echo $row['flagname'];?>
	      </span>

          <?php }?>
		 </td>
	 </tr>

    <tr>
    <td width="25%" height="40" align="right">景区分类：</td>
    <td>
			<?php
	 $dosql->Execute("SELECT * FROM `#@__ticketclass`");
	 while($row = $dosql->GetArray()){
	 ?>

				<span>
				<input type="checkbox" <?php if(check_str($r['types'],$row['id'])){echo "checked";}?>  name="types[]" id="types[]" value="<?php echo $row['id'];?>" />
				<?php echo $row['title'];?></span>
	<?php }?>

    </td>
  </tr>

      <tr>
        <td width="25%" height="40" align="right">景区级别：</td>
        <td>
         <select class="input" name="level" id="level" style="width:508px;">
					 <option <?php if($r['level']=="NULL"){ echo "selected='selected'";}?> value="NULL">无</option>
           <option <?php if($r['level']==1){ echo "selected='selected'";}?> value="1">1星</option>
           <option <?php if($r['level']==2){ echo "selected='selected'";}?> value="2">2星</option>
           <option <?php if($r['level']==3){ echo "selected='selected'";}?> value="3">3星</option>
           <option <?php if($r['level']==4){ echo "selected='selected'";}?> value="4">4星</option>
           <option <?php if($r['level']==5){ echo "selected='selected'";}?> value="5">5星</option>
         </select>
        </td>
      </tr>

      <tr>
      <td width="25%" height="40" align="right">景区标签：</td>
      <td width="75%">
      <input type="text" class="input" id="label" name="label"  value="<?php echo $r['label'];?>">
      <span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span>
      </td>
      </tr>
			<tr>
			<td width="25%" height="40" align="right">景区备注：</td>
			<td width="75%">
			<input type="text" class="input" id="remarks" name="remarks" required="required" value="<?php echo $r['remarks']; ?>">
			</td>
			</tr>
			<tr>
	     <td height="45" align="right">景区位置：</td>
	     <td><select name="live_prov" id="live_prov" style="width:100px;" class="input" onchange="SelProv(this.value,'live');">
						<option value="-1">请选择</option>
						<?php
						$sow=$dosql->GetOne("SELECT * FROM $tbname where id=$id");
						$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
						while($row2 = $dosql->GetArray())
						{
							if($sow['live_province'] === $row2['dataname'])
								$selected = 'selected="selected"';
							else
								$selected = '';

							echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
						}
						?>
					</select> &nbsp;&nbsp;
	        <select style="width:100px;" class="input" name="live_city" id="live_city"  onchange="SelCity(this.value,'live');">
	        		 <option value="-1">--</option>
	        					<?php
	        					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$row['province']." AND datavalue<".($row['province'] + 500)." ORDER BY orderid ASC, datavalue ASC");
	        					while($row2 = $dosql->GetArray())
	        					{
	        						if($sow['live_city'] === $row2['dataname'])
	        							$selected = 'selected="selected"';
	        						else
	        							$selected = '';

	        						echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
	        					}
	        					?>
	        	      </select>
	      </td>
	   </tr>
      <tr>
      			<td height="124" align="right">景区图片：</td>
      			<td colspan="11"><fieldset class="picarr" style="width:78%">
      					<legend>列表</legend>
      					<div>最多可以上传<strong>50</strong>张图片<span onclick="GetUploadify('uploadify2','组图上传','image','image',50,<?php echo $cfg_max_file_size; ?>,'picarr','picarr')">开始上传</span></div>
      					<ul id="picarr">
						<?php

					if($r['picarr'] != '')
					{
						$picarr = json_decode($r['picarr']);
						foreach($picarr as $v)
						{
							$v = explode(',', $v);
							echo '<li rel="'.$v[0].'"><input type="hidden" name="picarr[]" value="'.$v[0].'"><img src="'.$cfg_weburl."/".$v[0].'" width="100" height="120" ><a href="javascript:void(0);" onclick="ClearPicArr(\''.$v[0].'\')">删除</a></li>';
						}
					}
					?>
					</ul>
      				</fieldset></td>
      		</tr>


					<tr>
	        <td height="40" align="right">景区须知：</td>
	        <td><textarea name="xuzhi" id="xuzhi" class="kindeditor"><?php echo $r['xuzhi']; ?></textarea>
					<script>
					var editor;
					KindEditor.ready(function(G) {
						editor = G.create('textarea[name="xuzhi"]', {
							allowFileManager : true,
							width:'80%',
							height:'280px',
							extraFileUploadParams : {
								sessionid :  '<?php echo session_id(); ?>'
							}
						});
					});
					</script></textarea>
	        </td>
	        </tr>

         <tr>
        <td height="40" align="right">景区介绍：</td>
        <td><textarea name="content" id="content" class="kindeditor"><?php echo $r['content'];?></textarea>
				<script>
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="content"]', {
						allowFileManager : true,
						width:'80%',
						height:'250px',
						extraFileUploadParams : {
							sessionid :  '<?php echo session_id(); ?>'
						}
					});
				});
				</script></textarea>
        </td>
        </tr>

        <tr>
        <td width="25%" height="40" align="right">最低价格：</td>
        <td width="75%">
        <input type="text" class="input" id="lowmoney" name="lowmoney" required="required" value="<?php echo $r['lowmoney'];?>">
        <span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span>
        </td>
        </tr>

        <tr>
        <td width="25%" height="40" align="right">已售设置：</td>
        <td width="75%">
        <input type="text" class="input" id="solds" name="solds" required="required" value="<?php echo $r['solds'];?>">
        <span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span>
        </td>
        </tr>

        <tr>
        <td width="25%" height="40" align="right">排序设置：</td>
        <td width="75%">
        <input type="text" class="input" id="orderid" name="orderid" required="required" value="<?php echo $r['orderid'];?>">
        <span class="maroon">*</span><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span>
        </td>
        </tr>

		<tr>
			<td height="40" align="right">添加时间：</td>
			<td><input type="text" name="posttime" id="posttime" class="input" value="<?php echo GetDateTime($r['posttime']); ?>" />
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
		<input type="hidden" name="action" id="action" value="update_ticket" />
        <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
  </div>
</form>

</body>
</html>
