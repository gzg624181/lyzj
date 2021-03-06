<?php require_once(dirname(__FILE__).'/inc/config.inc.php');
$username=$_SESSION['admin'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>注册导游信息管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="templates/js/ajax.js"></script>
<script type="text/javascript" src="templates/js/getarea.js"></script>
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

function userinfo_guide(id) {
  layer.open({
     type:2,
     title:'导游注册详情',
     maxmin:true,
     area:['800px','550px'],
     content: 'userinfo_guide.php?id='+id,
  });
}

function checkguide(id,type){
	 var ajax_url='guide_save.php?action=checkguide&id='+id+'&type='+type;
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
        ,closeBtn: 1  //关闭按钮是否显示 1显示0不显
        ,area: '50%;'
        ,shade: 0.5
        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
        ,btn: ['点击关闭']
        ,btnAlign: 'c'
        ,moveType: 0 //拖拽模式，0或者1
        ,content: "<div style='widht:50%; height:90%;padding:25px; line-height: 22px;text-align:center'>"+ data+"</div>"
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
if(confirm("是否确认拒绝导游注册审核？"))
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
//审核，未审，功能
  function checkinfo(key){
     var v= key;
	// alert(v)
	window.location.href='guide.php?check='+v;
	}

  //更改用户的权限  forbiden 1，允许 0，禁止
  function changeforbiden(id){
    layer.confirm('是否更改用户的权限？',function(index){

      window.location.href="guide_save.php?action=changeforbiden&id="+id;
    })
  }


function GetSearchs(){
var keyword= document.getElementById("keyword").value;
if($("#keyword").val() == "")
{
 layer.alert("请输入搜索内容！",{icon:0});
 $("#keyword").focus();
 return false;
}
window.location.href='guide.php?keyword='+keyword;
}

function GetPostion(){

if($("#live_prov").val() == -1)
{
 layer.alert("请输入搜索省份！",{icon:0});
 $("#live_prov").focus();
 return false;
}else{
 var live_prov = $("#live_prov").val();
 var live_city = $("#live_city").val();
 window.location.href='guide.php?keyword=postion&province='+live_prov+'&city='+live_city;
}
 }
function member_update(Id){
 var adminlevel=document.getElementById("adminlevel").value;
  if(adminlevel==1){
	  window.location("member_update.php?id="+Id);
    }else{
	  alert("亲，您还没有操作本模块的权限，请联系超级管理员！");
		}
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
$check = isset($check) ? $check : '';
$username=$_SESSION['admin'];
$adminlevel=$_SESSION['adminlevel'];
$r=$dosql->GetOne("select * from pmw_admin where username='$username'");
//将新的注册记录清空掉
$update = new Guide();
$update->update_guide_freetime('guide');
?>
</head>
<body>
<?php
$tbname="pmw_guide";
$action="guide_save.php";
$one=1;
$dosql->Execute("SELECT * FROM $tbname",$one);
$num=$dosql->GetTotalRow($one);
?>
<input type="hidden" name="adminlevel" id="adminlevel" value="<?php echo $adminlevel;?>" />
<div class="topToolbar">
<span class="title">导游合计：
<span class="num" style="color:red;"><?php echo $num;?></span>
&nbsp;&nbsp;&nbsp;
请选择地理位置进行搜索：
<?php
if(isset($province) && isset($city)){
 ?>
 <select name="live_prov" id="live_prov" style="width:100px; height:28px;" class="input" onchange="SelProv(this.value,'live');">
    <option value="-1">请选择</option>
    <?php
    $dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
    while($row2 = $dosql->GetArray())
    {
      if($province === $row2['datavalue'])
        $selected = 'selected="selected"';
      else
        $selected = '';

      echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
    }
    ?>
  </select> &nbsp;&nbsp;
  <select style="width:100px; height:28px;" class="input" name="live_city" id="live_city"  onchange="SelCity(this.value,'live');">
       <option value="-1">--</option>
            <?php
            $dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue>".$province." AND datavalue<".($province + 500)." ORDER BY orderid ASC, datavalue ASC");
            while($row2 = $dosql->GetArray())
            {
              if($city === $row2['datavalue'])
                $selected = 'selected="selected"';
              else
                $selected = '';

              echo '<option value="'.$row2['datavalue'].'" '.$selected.'>'.$row2['dataname'].'</option>';
            }
            ?>
          </select>
<?php }else{ ?>
<select name="live_prov" style="width:100px; height:28px;" class="input" id="live_prov" onchange="SelProv(this.value,'live');">
<option value="-1">请选择省份</option>
<?php
  $dosql->Execute("SELECT * FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=0 ORDER BY orderid ASC, datavalue ASC");
  while($row = $dosql->GetArray())
  {
    echo '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
  }
  ?>
</select> &nbsp;&nbsp;
<select style="width:100px; height:28px;" class="input" name="live_city" id="live_city" onchange="SelCity(this.value,'live');">
  <option value="-1">--</option>
</select>
<?php } ?>
   &nbsp;&nbsp;
  <a href="javascript:;" onclick="GetPostion();"><i class="fa fa-search-minus" aria-hidden="true"></i></a>
</span> <a href="javascript:location.reload();" class="reload"><?php echo $cfg_reload;?></a>
</div>
<div class="toolbarTab" style="margin-bottom:5px;">
<ul>
 <li class="<?php if($check==""){echo "on";}?>"><a href="guide.php">全部</a></li> <li class="line">-</li>
 <li class="<?php if($check=="success"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('success')">已通过&nbsp;&nbsp;<i style='color:#509ee1; cursor:pointer;' title='审核已通过' class='fa fa-dot-circle-o' aria-hidden='true'></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="failed"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('failed')">未通过&nbsp;&nbsp;<i style='color:red;cursor:pointer;'  title='审核不通过' class='fa fa-dot-circle-o' aria-hidden='true'></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="reviewed"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('reviewed ')">待审核&nbsp;&nbsp;<i style='color:#509ee1; cursor:pointer;' title='待审核' class='fa fa-circle-o' aria-hidden='true'></i></a></a></li>
</ul>
	<div id="search" class="search"> <span class="s">
<input name="keyword" id="keyword" type="text" class="number" style="font-size:11px;" placeholder="请输入用户账号或者导游姓名" title="请输入用户账号或者导游姓名" />
		</span> <span class="b"><a href="javascript:;" onclick="GetSearchs();"></a></span></div>
	<div class="cl"></div>
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
                <td width="1%" height="36" align="center"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);" /></td>
                <td width="6%" align="center">用户账号</td>
                <td width="6%" align="center">头像</td>
                <td width="6%" align="center">导游姓名</td>
                <td width="3%" align="center">性别</td>
                <td width="4%" align="center">证件</td>
                <td width="9%" align="center">导游证号</td>
                <td width="8%" align="center">导游电话</td>
                <td width="6%" align="center">导游简介</td>
                <td width="7%" align="center">导游相册</td>
                <td width="4%" align="center">剩余推广佣金</td>
                <td width="4%" align="center">推荐人</td>
                <td width="9%" align="center">注册时间</td>
                <td width="4%" align="center">已接行程</td>
                <td width="4%" align="center">已购票</td>
                <td width="4%" align="center">推荐</td>
                <td width="10%" align="center">操作</td>
                </tr>
              <?php
		if($check=="today"){
		$time=date("Y-m-d"); //今天注册
		$dopage->GetPage("select * from $tbname where ymdtime = '$time'",15);
	    }elseif($check=="tomorrow"){ //昨天注册
		$time=date("Y-m-d",strtotime("-1 day"));
		$dopage->GetPage("select * from $tbname where ymdtime = '$time'",15);
	    }elseif($check=="success"){ //已通过
		$dopage->GetPage("select * from $tbname where checkinfo = 1",15);
	    }elseif($check=="failed"){ //未通过
		$dopage->GetPage("SELECT * from pmw_un_guide",15);
	    }elseif($check=="reviewed"){ //待审核
		$dopage->GetPage("select * from $tbname where checkinfo = 0",15);
	    }elseif($check=="user"){ //搜索单个用户
		$dopage->GetPage("select * from $tbname where id = $id",15);
	    }
		elseif($keyword!=""){ //关键字搜索
      if($keyword =="postion"){
         if($city == -1){ //只搜索省份
           $dopage->GetPage("SELECT * FROM $tbname where province=$province ",15);
         }else{
           $dopage->GetPage("SELECT * FROM $tbname where province=$province and  city=$city",15);
         }
      }else{
	    $dopage->GetPage("SELECT * FROM $tbname where account like '%$keyword%' or name  like '%$keyword%' ",15);
     }
		}else{
		$dopage->GetPage("SELECT * FROM $tbname",15);
		}

		while($row = $dosql->GetArray())
		{
			$id=$row['id'];
      $type = "guide";
			switch($row['sex'])
			{
				case 1:
					$sex = "<i title='男' style='font-size:16px;color: #509ee1;' class='fa fa-venus' aria-hidden='true'></i>";
					break;
				case 0:
					$sex = "<i title='女' style='font-size:16px;color: red;' class='fa fa-mercury' aria-hidden='true'></i>";
					break;
			}
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
        $recommender_name = '<i title="无推荐人" class="fa fa-minus-circle" aria-hidden="true"></i>';
      }
      }else{
        $recommender_name = '<i title="无推荐人" class="fa fa-minus-circle" aria-hidden="true"></i>';
      }

      if($row['images']==""){
      $images="../templates/default/images/noimage.jpg";
      }elseif(check_str($row['images'],"https")){
       $images=$row['images'];   //用户头像
      }else{
        $images=$cfg_weburl."/".$row['images'];
        }
					if($row['checkinfo']==0){

				 $checkinfo = "<a href='agency_save.php?action=checkinfo&info=guide&id={$id}'><i onclick='return ConfCheck(0);' style='color:#509ee1; cursor:pointer;' title='审核通过' class='fa fa-circle-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;";

				 $checkinfo .="<a href='javascript:void(0);' onclick=\"SendCheck('$id','guide')\"><i style='color:red;cursor:pointer;'  title='审核不通过' class='fa fa-circle-o' aria-hidden='true'></i></a>";

			}elseif($row['checkinfo']==1){

			 $checkinfo = "<i onClick='userinfo_guide({$id})' style='color:#509ee1; cursor:pointer;' title='审核已通过,点击查看导游详情' class='fa fa-dot-circle-o' aria-hidden='true'></i>";

       if($row['forbiden']==0){
       $checkinfo .= "&nbsp;&nbsp;&nbsp;"."<i onclick='changeforbiden({$id})' style='color:red; cursor:pointer;' title='账户权限已被禁止，点击更改用户权限' class='fa fa-toggle-on' aria-hidden='true'></i>";
       }elseif($row['forbiden']==1){
        $checkinfo .= "&nbsp;&nbsp;&nbsp;"."<i onclick='changeforbiden({$id})' style='color:#509ee1; cursor:pointer;' title='账户权限已被允许，点击禁止用户权限' class='fa fa-toggle-off' aria-hidden='true'></i>";
       }


			}elseif($row['checkinfo']==2){

			 $checkinfo = "<i style='color:red; cursor:pointer;' title='审核未通过' class='fa fa-dot-circle-o' aria-hidden='true'></i>";

			}
			$id=$row['id'];
			$five=5;
			$dosql->Execute("SELECT id from pmw_travel where gid=$id",$five);
			$guide_num=$dosql->GetTotalRow($five);
		?>
              <tr class="dataTr" align="left">
                <td height="110" align="center"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
                <td align="center"><?php echo $row['account']; ?></td>
                <td align="center"><div id="layer-photos-demo_<?php  echo $row['id'];?>" class="layer-photos-demo"> <img  width="100px;" layer-src="<?php echo $images;?>" style="cursor:pointer" onclick="message('<?php echo $row['id']; ?>');"  src="<?php echo $images;?>" alt="<?php echo $row['name']; ?>" /></div></td>
                <td align="center"><?php echo $row['name']; ?></td>
                <td align="center"><?php echo $sex; ?></td>
                <td align="center"><a style="cursor:pointer;" onclick="checkguide('<?php echo $row['id'];?>','card');"><i title="查看" class="fa fa-eye" aria-hidden="true"></i></a></td>
                <td align="center"><?php echo $row['cardnumber']; ?></td>
                <td align="center"><?php echo $row['tel']; ?></td>

                <td align="center">
                  <?php if($row['content']==""){ ?>
                  <i title="无个人简介" class="fa fa-minus-circle" aria-hidden="true"></i>
                <?php }else{ ?>
                <a style="cursor:pointer;" onclick="checkguide('<?php echo $row['id'];?>','content');">查看</a>
                 <?php } ?>
               </td>

                <td align="center">
                  <?php if($row['pics']==""){ ?>
                  <i title="无个人相册" class="fa fa-minus-circle" aria-hidden="true"></i>
                <?php }else{ ?>
                  <a style="cursor:pointer;" onclick="checkguide('<?php echo $row['id'];?>','pics');">查看</a>
                 <?php } ?>
                </td>
                <td align="center" class="num"><?php echo sprintf("%.2f",$row['money']);?></td>
                <td align="center" class="num"><?php echo $recommender_name;?></td>
                <td align="center"><?php echo date("Y-m-d H:i:s",$row['regtime']);?></td>
                <td align="center" class="num"><a title="点击查看详情"  style="color:red;font-weight:bold;" href="travel_list.php?check=guide&id=<?php echo $row['id'];?>"><?php echo $guide_num;?></a></td>
                <td align="center" class="num"><a title="点击查看详情"  style="color:#4a34ea;font-weight:bold;" href="allorder.php?id=<?php echo $row['id'];?>&type=guide&check=guides"><?php echo get_ticket_sum($row['id'],'guide');?></a></td>
                <td align="center" class="num"><a title="点击查看推荐注册的会员列表" href="recommender.php?uid=<?php echo $id; ?>&type=guide" ><?php echo get_recommender($type,$id); ?></a></td>
                <td align="center">  <span><?php echo $checkinfo; ?></span> &nbsp;
      <?php if($row['checkinfo']!=2){?>
			<span><a title="编辑" href="guide_update.php?id=<?php echo $row['id']; ?>">
			<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></span> &nbsp;
    <?php }?>
      <?php  if($adminlevel==1){ ?>
      <?php  if($check=="failed"){ ?>
			<span class="nb"><a title="删除未通过的导游信息" href="<?php echo $action;?>?action=del22&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0);"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span>
      <?php }else{ ?>
	    <span class="nb"><a title="删除导游信息" href="<?php echo $action;?>?action=del2&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0);"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span>
      <?php } ?>
      <?php }else{?>
      <span class="nb"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
    <?php  }?>
     </td>
                <?php //}?>
              </tr>
              <?php
		}
		?>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  </table>
</form>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('<?php echo $action;?>');" onclick="return ConfDelAll(0);">删除</a></span></div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php
//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea">
        <span class="pageSmall"><?php echo $dopage->GetList(); ?></span>
        </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>
