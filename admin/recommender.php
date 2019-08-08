<?php require_once(dirname(__FILE__).'/inc/config.inc.php');
$username=$_SESSION['admin'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>推荐列表</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="layer/layer.js"></script>
<script>
function message(Id){
  // alert(Id);
   layer.ready(function(){ //为了layer.ext.js加载完毕再执行
   layer.photos({
   photos: '#layer-photos-demo_'+Id,
	 area:['300px','270px'],  //图片的宽度和高度
   shift: 0 ,//0-6的选择，指定弹出图片动画类型，默认随机
   closeBtn:1,
   offset:'40px',  //离上方的距离
   shadeClose:false
  });
});
}

function checkagency(id,type){
	 var ajax_url='agency_save.php?action=checkagency&id='+id+'&type='+type;
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
        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
        ,btn: ['点击关闭']
        ,btnAlign: 'c'
        ,moveType: 1 //拖拽模式，0或者1
        ,content: "<div style='widht:750px;padding:25px; height:600px;line-height: 22px;text-align:center; '>"+ data+"</div>"
        ,
      });
    } ,
	error:function(){
       alert('error');
    }
	});
	}

function SendCheck(id,type)
{
if(confirm("是否确认拒绝此旅行社注册审核？"))
 {

layer.open({
  type: 2,
  title: '审核未通过模板消息：',
  maxmin: true,
  shadeClose: true, //点击遮罩关闭	层
  area : ['800px' , '545px'],
  content: 'check.php?id='+id+'&type='+type,
  });
  }
}

 function userinfo_agency(id) {
   layer.open({
      type:2,
      title:'旅行社注册详情',
      maxmin:true,
      area:['800px','550px'],
      content: 'userinfo_agency.php?id='+id,
   });
 }
//更改用户的权限  forbiden 1，允许 0，禁止
function changeforbiden(id){
  layer.confirm('是否更改用户的权限？',function(index){

    window.location.href="agency_save.php?action=changeforbiden&id="+id;
  })
}
//审核，未审，功能
  function checkinfo(key){
     var v= key;
     var classes= $("#classes").val();
     var openid= $("#openid").val();
     var uid= $("#uid").val();
     var nums= $("#nums").val();
	// alert(v)
	window.location.href='recommender.php?check='+v+'&type='+classes+'&openid='+openid+'&uid='+uid+'&nums='+nums;
	}



function del_member(){
 var adminlevel=document.getElementById("adminlevel").value;
  if(adminlevel!=1){
	  alert("亲，您还没有操作本模块的权限，请联系超级管理员！");
  }
	}
</script>


<?php
//初始化参数
$action  = isset($action)  ? $action  : '';
$keyword = isset($keyword) ? $keyword : '';
$check = isset($check) ? $check : 'success';
$username=$_SESSION['admin'];
$adminlevel=$_SESSION['adminlevel'];
$r=$dosql->GetOne("select * from pmw_admin where username='$username'");

//前端传过来的值

#uid    会员id
#type   会员类型 (guide,)
#check  注册的状态

#注册成功的会员
if($check=="success"){
  $checkinfo=1;
  $tishiname = "成功";
}elseif($check=="failed"){
  $checkinfo=2;
  $tishiname = "失败";
}elseif($check=="reviewed"){
  $checkinfo=0;
  $tishiname = "审核中";
}
// 计算推荐注册的会员是导游的情况
$five = 5;
if($check=="failed"){
  $tbname = "pmw_un_guide";
}else{
  $tbname = "pmw_guide";
}

$dosql->Execute("SELECT name,regtime,account FROM $tbname where  recommender_type='$type' and uid='$uid' and checkinfo=$checkinfo",$five);
$nums_guide_success = $dosql->GetTotalRow($five);

if($nums_guide_success==0){
  $list3= array();
}else{
  for($i=0;$i<$nums_guide_success;$i++){
    $row3 = $dosql->GetArray($five);
    $list3[]= $row3;
    $list3[$i]['type'] = '导游';
    $list3[$i]['state'] = 'success';
}
}

//计算推荐注册的会员是旅行社的情况下
$six=6;
if($check=="failed"){
  $tbnames = "pmw_un_agency";
}else{
  $tbnames = "pmw_agency";
}
$dosql->Execute("SELECT name,regtime,account FROM $tbnames where recommender_type='$type' and uid='$uid' and checkinfo=$checkinfo",$six);
$nums_agency_success = $dosql->GetTotalRow($six);

if($nums_agency_success==0){
  $list4= array();
}else{
  for($i=0;$i<$nums_agency_success;$i++){
    $row4 = $dosql->GetArray($six);
    $list4[]= $row4;
    $list4[$i]['type'] = '旅行社';
    $list4[$i]['state'] = 'success';
}
}

$nums_success = $nums_guide_success + $nums_agency_success;   //合计推荐注册成功的会员

$list = array_merge($list3,$list4);

?>
</head>
<body>
<input type="hidden" name="adminlevel" id="adminlevel" value="<?php echo $adminlevel;?>" />
<input type="hidden" name="uid" id="uid" value="<?php echo $uid;?>" />
<input type="hidden" name="classes" id="classes" value="<?php echo $type;?>" />
<div class="topToolbar">
<span class="title">推荐<?php echo $tishiname;?></span>总数量：<span class="num" style="color:red;"><?php echo $nums_success;?></span>
</span> <a href="javascript:location.reload();" class="reload"><?php echo $cfg_reload;?></a>
</div>
<div class="toolbarTab" style="margin-bottom:5px;">
<ul>
 <li class="<?php if($check==""){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('success')">已通过&nbsp;&nbsp;<i style='color:#509ee1; cursor:pointer;' title='审核已通过' class='fa fa-dot-circle-o' aria-hidden='true'></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="failed"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('failed')">未通过&nbsp;&nbsp;<i style='color:red;cursor:pointer;'  title='审核不通过' class='fa fa-dot-circle-o' aria-hidden='true'></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="reviewed"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('reviewed')">待审核&nbsp;&nbsp;<i style='color:#509ee1; cursor:pointer;' title='待审核' class='fa fa-circle-o' aria-hidden='true'></i></a></li>
</ul>

</div>
<form name="form" id="form" method="post" action="<?php echo $action;?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
  <tr align="left" class="head">
    <td width="3%" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
      <tr align="left" class="head">
        <td width="3%" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
          <tr align="left" class="head">
            <td width="3%" height="165" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
              <tr align="left" class="head" style="font-weight:bold;">
                <td width="4%" height="36" align="center"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);" /></td>
                <td width="20%" align="center">账号</td>
                <td width="16%" align="center">姓名</td>
                <td width="20%" align="center">账号类型</td>
                <td width="20%" align="center">账号状态</td>
                <td width="20%" align="center">注册时间</td>
                </tr>
              <?php
    				for($i=0;$i<count($list);$i++){
		?>
              <tr class="dataTr" align="left">
                <td height="110" align="center"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $i; ?>" /></td>
                <td align="center"><?php echo $list[$i]['account']; ?></td>
                <td align="center"><?php echo $list[$i]['name']; ?></td>
                <td align="center"><?php echo $list[$i]['type']; ?></td>
                <td align="center"><?php echo $tishiname; ?></td>
                <td align="center" class="num"><?php echo date("Y-m-d H:i:s",$list[$i]['regtime']); ?>
                </td>
          </tr><?php }?>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  </table>
</form>

</body>
</html>
