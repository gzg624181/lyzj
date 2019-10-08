<?php require_once(dirname(__FILE__).'/inc/config.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>行程列表管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/listajax.js"></script>
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script type="text/javascript" src="templates/js/ajax.js"></script>
<script type="text/javascript" src="templates/js/getarea.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script>

//标题搜索
   function GetSearchs(){
	 var keyword= document.getElementById("keyword").value;
	if($("#keyword").val() == "")
	{
		alert("请输入搜索内容！");
		$("#keyword").focus();
		return false;
	}
  window.location.href='travel_list.php?keyword='+keyword;
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
 window.location.href='travel_list.php?keyword=postion&province='+live_prov+'&city='+live_city;
}
 }

//审核，未审，功能
  function checkinfo(key){
     var v= key;
	// alert(v)
	window.location.href='travel_list.php?check='+v;
	}

function checkguide(gid,tid){
  layer.open({
  type: 2,
  title: '查看导游个人信息：',
  maxmin: true,
  shadeClose: true, //点击遮罩关闭	层
  area : ['60%' , '80%'],
  content: 'check_guide.php?id='+gid+'&tid='+tid,
  });
	}
</script>
</head>
<body>

<?php
//初始化参数
$action  = isset($action)  ? $action  : 'travel_save.php';
$keyword = isset($keyword) ? $keyword : '';
$check = isset($check) ? $check : '';
$adminlevel=$_SESSION['adminlevel'];  //用户级别
//将新的注册记录清空掉
$update = new Agency();
$update->update_agency_travel('travel');
?>
<div class="topToolbar"> <span class="title">发布行程列表管理</span>
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
<a href="javascript:location.reload();" class="reload"><i class="fa fa-refresh fa-spin fa-fw"></i></a>

</div>
<div class="toolbarTab">
	<ul>
 <li class="<?php if($check==""){echo "on";}?>"><a href="travel_list.php">全部</a></li> <li class="line">-</li>
 <li class="<?php if($check=="appointment"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('appointment')">待预约&nbsp;&nbsp;<i class='fa  fa-circle-o-notch' aria-hidden='true' style="color:#30B534"></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="confirm"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('confirm')">待确认&nbsp;&nbsp;<i class='fa fa-unlock' aria-hidden='true' style="color:#F90"></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="complete"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('complete')">已完成&nbsp;&nbsp;<i class='fa fa-unlock-alt' aria-hidden='true' style="color:#509ee1"></i></a></li>
 <li class="line">-</li>
 <li class="<?php if($check=="concel"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('concel')">已取消&nbsp;&nbsp;<i class='fa fa-chain-broken' aria-hidden='true' style="color:#F00"></i></a></li>
<li class="line">-</li>
<li class="<?php if($check=="comment"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('comment')">已评论&nbsp;&nbsp;<i class='fa fa-paper-plane-o' aria-hidden='true' style="color:#F1700E"></i></a></li>
  <li class="line">-</li>
  <li class="<?php if($check=="invalid"){echo "on";}?>"><a href="javascript:;" onclick="checkinfo('invalid')">已失效&nbsp;&nbsp;<i class='fa fa-stop-circle-o' aria-hidden='true' style="color:#ccc"></i></a></li>

	</ul>
	<div id="search" class="search"> <span class="s">
<input name="keyword" id="keyword" type="text" class="number" placeholder="请输入旅行社名称或者导游名字进行搜索" title="请输入旅行社名称或者导游名字进行搜索" />
		</span> <span class="b"><a href="javascript:;" onclick="GetSearchs();"></a></span></div>
	<div class="cl"></div>
</div>
<form name="form" id="form" method="post" action="<?php echo $action;?>">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="1%" height="36" align="center"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="10%" align="left">旅行社名称</td>
			<td width="6%" align="center">开始时间</td>
			<td width="7%" align="center">截止时间</td>
			<td width="4%" align="center">时长(天)</td>
			<td width="6%" align="center">导游费用</td>
			<td width="10%" align="center">行程标题</td>
			<td width="4%" align="center">团队人数</td>
			<td width="7%" align="center">客源地</td>
			<td width="14%" align="center">备注</td>
			<td width="10%" align="center">发布时间</td>
			<td width="7%" align="center">导游接单</td>
			<td width="5%" align="center">结算价格</td>
			<td width="3%" align="center">评论</td>
			<td width="6%" align="center">操作</td>
		</tr>
		<?php
		$username=$_SESSION['admin'];
		$adminlevel=$_SESSION['adminlevel'];
		$tbname='pmw_travel';

		 if($check=="appointment"){ //待预约
		$dopage->GetPage("SELECT * FROM $tbname where state=0",10);
		  }elseif($check=="confirm"){ //待确认
		$dopage->GetPage("SELECT * FROM $tbname where state=1",10);
		  }elseif($check=="complete"){ //已完成
		$dopage->GetPage("SELECT * FROM $tbname where state=2",10);
		  }elseif($check=="concel"){ //已取消
		$dopage->GetPage("SELECT * FROM $tbname where state=3 or state=5",10);
     }elseif($check=="invalid"){ //已取消
		$dopage->GetPage("SELECT * FROM $tbname where state=4",10);
		  }elseif($check=="agency"){ //旅行社发布的行程
		$dopage->GetPage("SELECT * FROM $tbname where aid=$id",10);
		  }elseif($check=="guide"){ //旅行社发布的行程
		$dopage->GetPage("SELECT * FROM $tbname where gid=$id",10);
		  }elseif($check=="comment"){
		$dopage->GetPage("SELECT * FROM $tbname where state=2 and comment_state=1",10);
		  }elseif($check=="month"){
		$dopage->GetPage("SELECT * FROM $tbname where state=2 and aid=$id and complete_ym='$m'",10);
		  }elseif($check=="search"){
		$dopage->GetPage("SELECT * FROM $tbname where complete_ym='$m'",10);
		  }
		  elseif($keyword!=""){
        if($keyword =="postion"){
           if($city == -1){ //只搜索省份
             $dopage->GetPage("SELECT * FROM $tbname where province=$province ",15);
           }else{
             $dopage->GetPage("SELECT * FROM $tbname where province=$province and  city=$city",15);
           }
        }else{
		   $dopage->GetPage("SELECT * FROM $tbname where company like '%$keyword%' OR name like '%$keyword%' ",10);
       }
		  }else{
		$dopage->GetPage("SELECT * FROM $tbname",10);
		  }
		while($row = $dosql->GetArray())
		{


			switch($row['state']){

				case 0: //待预约
					$state= "<i class='fa  fa-circle-o-notch' aria-hidden='true' style='color:#30B534'></i>";
          $color="#30B534";
					break;
				case 1://待确认
					$state= "<i class='fa fa-unlock' aria-hidden='true' style='color:#F90'></i>";
          $color="#F90";
					break;
				case 2://已确认
					$state= "<i class='fa fa-unlock-alt' aria-hidden='true'  style='color:#509ee1'></i>";
          $color="#509ee1";
					break;
				case 3://已取消
					$state= "<i class='fa fa-chain-broken' aria-hidden='true' style='color:#F00'></i>";
          $color="#F00";
					break;
        case 4://已失效
  					$state= "<i class='fa fa-stop-circle-o' aria-hidden='true' style='color:#ccc'></i>";
            $color="#ccc";
  					break;
        case 5://已取消
    					$state= "<i class='fa fa-chain-broken' aria-hidden='true' style='color:#F00'></i>";
              $color="#F00";
    					break;
				default:
               $state = '暂无分类';

				}
			$gid=$row['gid'];
      $tid=$row['id'];
			 if($gid==""){
				 $gname = "<a title='点击查看已经预约的导游信息' href='javascript:void(0);' onclick=\"checkguide('','$tid')\"><i class='fa fa-minus-circle' aria-hidden='true'></a></i>";
			 }else{
			  $r=$dosql->GetOne("SELECT name FROM pmw_guide where id=$gid");
        if(is_array($r)){
				 $gname ="<a title='点击查看已确认的导游信息' href='javascript:void(0);' onclick=\"checkguide('$gid','$tid')\">
				 {$r['name']}</a>";
       }else{
         $gname = "导游已删除";
       }
			 }
			$xingcheng=$row['days'];

			switch($row['comment_state']){

				case 0:
				$pinglun= "<i title='未评论'  class='fa fa-minus-circle' aria-hidden='true'></i>";
				break;

				case 1:
				$pinglun= "<i title='已评论' class='fa fa-paper-plane-o' aria-hidden='true'></i>";
				break;

				}
		?>
		<tr align="left" class="dataTr">
			<td height="50" align="center"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td align="center"><?php
			$aid= $row['aid'];
			$j=$dosql->GetOne("SELECT company FROM pmw_agency where id=$aid");
      if(is_array($j)){
			echo $j['company'];
      }
			 ?></td>
			<td align="center"><?php echo date("Y-m-d",$row['starttime']); ?></td>
			<td align="center"><?php echo date("Y-m-d",$row['endtime']); ?></td>
			<td align="center" class="num"><?php echo $xingcheng;?></td>
			<td align="center" class="num" style="color:#06F"><?php echo $row['money'];?></td>
			<td align="center"><?php echo $row['title']; ?></td>
			<td align="center"><?php echo $row['num']; ?></td>
			<td align="center"><?php echo $row['origin'];?></td>
			<td align="center"><?php echo $row['other'];?></td>
			<td align="center"><span class="number"><?php echo date("Y-m-d H:i:s",$row['posttime']); ?></span></td>
			<td align="center"><?php echo $gname; ?>
            </td>
			<td align="center" class="num" style="color:red"><?php echo $row['jiesuanmoney'];?></td>
			<td align="center"><?php echo $pinglun;?></td>

			<td align="center"><span><?php echo $state; ?></span> &nbsp;&nbsp;
			<span><a title="编辑" href="travel_update.php?id=<?php echo $row['id']; ?>">
			<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></span> &nbsp;
      <?php if($adminlevel==1){ ?>
			<span class="nb"><a title="删除发布的行程信息" href="<?php echo $action;?>?action=del2&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0);"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span>
      <?php }else{ ?>
      <span class="nb"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
      <?php  }?>

    </td>
		</tr>
		<?php
		}
		?>
	</table>
</form>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('<?php echo $action;?>');" onclick="return ConfDelAll(0);">删除</a> - <a style="cursor:pointer;" onclick="return history.go(-1);">返回</a></span><a href="travel_add.php" class="dataBtn">发布新的行程</a> </div>
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
