<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('member'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改会员</title>
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
<script type="text/javascript" src="layer/layer.js"></script>
<style>
img {
  border: 0;
  border-radius: 12px;
}
</style>
<script>

function message(Id){
  // alert(Id);
   layer.ready(function(){ //为了layer.ext.js加载完毕再执行
   layer.photos({
   photos: '#layer-photos-demo_'+Id,
	// area:['300px','270px'],  //图片的宽度和高度
   shift: 0 ,//0-6的选择，指定弹出图片动画类型，默认随机
   closeBtn:1,
   offset:'40px',  //离上方的距离
   shadeClose:false
  });
});
}

function messages(Id){
  // alert(Id);
   layer.ready(function(){ //为了layer.ext.js加载完毕再执行
   layer.photos({
   photos: '#layer-photos-demos_'+Id,
	// area:['300px','270px'],  //图片的宽度和高度
   shift: 0 ,//0-6的选择，指定弹出图片动画类型，默认随机
   closeBtn:1,
   offset:'40px',  //离上方的距离
   shadeClose:false
  });
});
}

</script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__agency` WHERE id=$id");
$adminlevel=$_SESSION['adminlevel'];
?>
<div class="formHeader"> <span class="title">修改旅行社信息</span> <a href="javascript:location.reload();" class="reload"><?php echo $cfg_reload; ?></a> </div>
<form name="form" id="form" method="post" action="agency_save.php">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="45" align="right">账　号：</td>
			<td width="75%"><strong><?php echo $row['account']; ?></strong></td>
		</tr>
        <tr>
			<td height="45" align="right"> 联系人姓名：</td>
			<td><input name="name" type="text" class="input" id="name" value="<?php echo $row['name']; ?>"  required="required" /></td>
		</tr>

		<tr>
		  <td height="45" align="right">旅行社名称：</td>
		  <td><input name="company" type="text" class="input" id="company" value="<?php echo $row['company']; ?>"  required="required" /></td>
	  </tr>

    <tr>
      <td height="45" align="right">旅行社地址：</td>
      <td><input name="address" type="text" class="input" id="address" value="<?php echo $row['address']; ?>"  required="required" /></td>
    </tr>

     <tr>
      <td height="45" align="right">用户账号余额：</td>
      <td><input name="money" type="text" class="input" id="money" value="<?php echo $row['money']; ?>"  required="required" readonly/>(禁止修改)</td>
    </tr>
    <tr>
     <td height="45" align="right">用户注册地理位置：</td>
     <td><select name="live_prov" id="live_prov" style="width:100px;" class="input" onchange="SelProv(this.value,'live');">
					<option value="-1">请选择</option>
					<?php
					$dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
					while($row2 = $dosql->GetArray())
					{
						if($row['live_province'] === $row2['dataname'])
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
        						if($row['live_city'] === $row2['dataname'])
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
     <td height="45" align="right">账号状态：</td>
     <td>
       <p>
         <label>
        <input type="radio" readonly name="checkinfo" value="0" <?php if($row['checkinfo']==0){echo "checked='checked'";} ?> id="checkinfo" />
        待审核</label>
         &nbsp;&nbsp;
           <label>
          <input type="radio" readonly name="checkinfo" value="1" <?php if($row['checkinfo']==1){echo "checked='checked'";} ?> id="checkinfo" />
          审核通过</label>
           &nbsp;&nbsp;
        <label>
          <input type="radio" readonly name="checkinfo" value="2" <?php if($row['checkinfo']==2){echo "checked='checked'";} ?> id="checkinfo" />
          审核失败</label>&nbsp;&nbsp;(禁止修改)<br />
        </p>

     </td>
    </tr>
    <tr>
     <td height="45" align="right">用户权限：</td>
     <td>
       <p>
           <label>
          <input type="radio" name="forbiden" value="1" <?php if($row['forbiden']==1){echo "checked='checked'";} ?> id="forbiden" />
          通过</label>
           &nbsp;&nbsp;
        <label>
          <input type="radio" name="forbiden" value="0" <?php if($row['forbiden']==0){echo "checked='checked'";} ?> id="forbiden" />
          禁止</label><span class="num" style="color:red">（权限禁止后，则用户不能进行任何操作，请谨慎操作！）</span><br />

        </p>

     </td>
    </tr>
    <tr>
     <td height="45" align="right">用户提现开关：</td>
     <td>
       <p>
           <label>
 		      <input type="radio" name="cashmoney" value="1" <?php if($row['cashmoney']==1){echo "checked='checked'";} ?> id="cashmoney" />
 		      开启</label>
           &nbsp;&nbsp;
 		    <label>
 		      <input type="radio" name="cashmoney" value="0" <?php if($row['cashmoney']==0){echo "checked='checked'";} ?> id="cashmoney" />
 		      关闭</label><br />
 	      </p>
     </td>
   </tr>


		<tr>
		  <td height="155" align="right">营业执照：</td>
		  	<td colspan="11" valign="middle">
           <div id="layer-photos-demo_<?php  echo $row['id'];?>" class="layer-photos-demo"> <img  width="100px;" layer-src="<?php echo $cfg_weburl."/".$row['cardpic'];?>" style="cursor:pointer; padding:8px;" onclick="message('<?php echo $row['id']; ?>');"
            src="<?php echo $cfg_weburl."/".$row['cardpic'];?>" alt="<?php echo $row['company']; ?>" /></div><br />
            <input style="margin-top:5px;" type="text" name="cardpic" id="cardpic" class="input" value="<?php echo $row['cardpic'];?>"  required="required"/>
				<span class="cnote">
                <span onclick="GetUploadify('uploadify','缩略图上传','image','image',1,<?php echo $cfg_max_file_size; ?>,'cardpic')">
                <i title="上传" style="cursor:pointer;color: #696b72;" class="fa fa-upload fa-2x"></i>
                </span> </span></td>
	  </tr>
		<tr>
			<td height="45" align="right">营业执照号码：</td>
			<td><input type="text" name="cardpicnumber" id="cardpicnumber" class="input" value="<?php echo $row['cardpicnumber']; ?>" /></td>
		</tr>
    <tr>
			<td height="45" align="right">身份证号码：</td>
			<td><input type="text" name="cardidnumber" id="cardidnumber" class="input" value="<?php echo $row['cardidnumber']; ?>" /></td>
		</tr>
    <tr>
		  <td height="155" align="right">身份证正反面图片：</td>
		  	<td colspan="11" valign="middle">

        <?php
        if($row['cardid_picarr']!=""){
        $arr =explode("|",$row['cardid_picarr']);
        for($i=0;$i<count($arr);$i++){
           ?>
        <div id="layer-photos-demos_<?php  echo $row['id'].$i;?>" class="layer-photos-demo">
        <img  width="192px;" height="123px" layer-src="<?php echo $cfg_weburl."/".$arr[$i];?>" style="cursor:pointer; float: left;padding:8px;" onclick="messages('<?php echo $row['id'].$i; ?>');"
        src="<?php echo $cfg_weburl."/".$arr[$i];?>" alt="<?php echo $row['name']; ?>" />
      </div>
    <?php }}else{ ?>
      <div id="layer-photos-demos_zhengmian" class="layer-photos-demo">
      <img style="border-radius:8px;float: left;"  width="254px;" height="159px" layer-src="templates/images/zhengmian.jpg" style="cursor:pointer; float: left;padding:8px;" onclick="messages('zhengmian');"
      src="templates/images/zhengmian.jpg" alt="<?php echo $row['name']; ?>" />
      </div>
      <div id="layer-photos-demos_fanmian" class="layer-photos-demo" >
      <img style="border-radius:8px;float: left; margin-left:12px;"  width="254px;" height="159px" layer-src="templates/images/fanmian.jpg" style="cursor:pointer; float: left;padding:8px;" onclick="messages('fanmian');"
      src="templates/images/fanmian.jpg" alt="<?php echo $row['name']; ?>" />
      </div>
    <?php } ?>
      </td>
	  </tr>
        <tr>
		  <td height="155" align="right">合同：</td>
		  	<td colspan="11" valign="middle">
           <fieldset class="picarr">
					<legend>列表</legend>
					<div>最多可以上传<strong>50</strong>张图片<span onclick="GetUploadify('uploadify2','组图上传','image','image',50,<?php echo $cfg_max_file_size; ?>,'picarr','picarr')">开始上传</span></div>
					<ul id="picarr">
						<?php
					if($row['agreement'] != '')
					{
						$picarr = json_decode($row['agreement']);
						foreach($picarr as $v)
						{
							$v = explode(',', $v);
							echo '<li rel="'.$v[0].'"><input type="hidden" name="picarr[]" value="'.$v[0].'"><img src="'.$cfg_weburl.'/'.$v[0].'" width="100" height="120" ><a href="javascript:void(0);" onclick="ClearPicArr(\''.$v[0].'\')">删除</a></li>';
						}
					}
					?>
					</ul>

				</fieldset>（长宽比例 2：1）</td>
	  </tr>
		<tr>
			<td height="45" align="right">联系人电话：</td>
			<td><?php echo $row['tel']; ?></td>
		</tr>
        <tr>
			<td height="45" align="right">密　码：</td>
			<td><input type="text"  name="password" id="password" class="input"  placeholder="如果密码不修改，则默认为空" /></td>
		</tr>
		<tr>
			<td height="163" align="right">头　像：</td>
			<td colspan="11" valign="middle">
    <img  width="100px;" src="
    <?php
    if(check_str($row['images'], "https")){
      echo $row['images'];
    }else{
      echo $cfg_weburl."/".$row['images'];
    }
     ?>
    " alt="<?php echo $row['name']; ?>" /><br />
   <input style="margin-top:5px;" type="text" name="images" id="images" class="input" value="<?php echo $row['images'];?>"  required="required"/>
				<span class="cnote"><span class="grayBtn" onclick="GetUploadify('uploadify','缩略图上传','image','image',1,20971520,'images')">上 传</span> </span></td>

          </td>
		</tr>
    <tr>
			<td height="45" align="right">推荐人：</td>
			<td><input readonly type="text" name="uid" id="uid" class="input"  value="<?php
      $recommender_id = $row['uid'];  //推荐人的id
      $recommender_type = $row['recommender_type']; //推荐人的类型
      if($recommender_type=="guide"){
        $tb = "pmw_guide";
        $tyname ="导游";
      }elseif($recommender_type=="agency"){
        $tb = "pmw_agency";
        $tyname ="旅行社";
      }

      if($recommender_id!=""){
        $k = $dosql->GetOne("SELECT name from $tb where id=$recommender_id");
        if(is_array($k)){
        $recommender_name = $k['name'];
        }else{
          $recommender_name = '';
        }
        }else{
          $recommender_name = '';
        }
        echo $recommender_name;
       ?>"  />(禁止修改)</td>
		</tr>

    <tr>
			<td height="45" align="right">推荐人类型：</td>
			<td><input readonly type="text" name="recommender_type" id="recommender_type" class="input"  value="<?php
      $recommender_type =$row['recommender_type'];
      if($recommender_type =="agency"){
      echo "旅行社";
      }elseif($recommender_type=="guide"){
      echo "导游";
      }else{
        echo "";
      }
       ?>"  />(禁止修改)</td>
		</tr>
    <tr>
			<td height="45" align="right">注册IP：</td>
			<td><input readonly type="text" name="regip" id="regip" class="input"  value="<?php echo $row['regip']; ?>"  />(禁止修改)</td>
		</tr>
    <tr>
			<td height="45" align="right">注册城市：</td>
			<td><input readonly type="text" name="getcity" id="getcity" class="input"  value="<?php echo $row['getcity']; ?>"  />(禁止修改)</td>
		</tr>
    <tr>
			<td height="45" align="right">注册时间：</td>
			<td><input type="text" name="regtime" id="regtime" class="input"  value="<?php echo date("Y-m-d H:i:s",$row['regtime']); ?>"  /></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
		<input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>" />
  </div>
</form>
</body>
</html>
