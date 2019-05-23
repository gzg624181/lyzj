<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');

/*
**************************
(C)2010-2017 phpMyWind.com
update: 2014-5-30 17:22:45
person: Feng
**************************
*/


//初始化参数
$tbname = 'pmw_active';
$gourl  = 'active.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加活动列表
if($action == 'add')
{
	  $active_onimages = $cfg_weburl."/".$active_onimages;
		$active_offimages = $cfg_weburl."/".$active_offimages;
		$active_time=time();
		$sql = "INSERT INTO `$tbname` (active_name, active_onimages, active_offimages, active_description, orderid, active_time,active_statues) VALUES ('$active_name', '$active_onimages','$active_offimages', '$active_description', $orderid, $active_time, $active_statues)";
		if($dosql->ExecNoneQuery($sql))
		{
			header("location:$gourl");
			exit();
		}
}


//修改游戏简介
else if($action == 'update'){
  $active_time=time();
	if(strpos($active_onimages,$cfg_weburl)!==false){
  $active_onimagess=$active_onimages;
  }else{
	$active_onimagess=$cfg_weburl."/".$active_onimages;
	}
	if(strpos($active_offimages,$cfg_weburl)!==false){
	$active_offimagess=$active_offimages;
	}else{
	$active_offimagess=$cfg_weburl."/".$active_offimages;
	}
	$sql = "UPDATE `$tbname` SET active_name='$active_name',active_onimages='$active_onimagess',active_offimages='$active_offimagess',active_description='$active_description',
	active_statues=$active_statues,active_time=$active_time,orderid=$orderid WHERE id=$id";

	if($dosql->ExecNoneQuery($sql))
	{
		header("location:$gourl");
		exit();
	}
}

//上线游戏
elseif($action=="getup"){
	$sql = "UPDATE `pmw_game` SET gameonline=1 WHERE id=$id ";
	$dosql->ExecNoneQuery($sql);
echo "<font color='#339933'><B>"."<i class='fa fa-check' aria-hidden='true'></i>"."</b></font>";
}


  //上线游戏
elseif($action=="getdown"){
	$sql = "UPDATE `pmw_game` SET gameonline=0 WHERE id=$id ";
	$dosql->ExecNoneQuery($sql);
echo "<font color='#FF0000'><B>"."<i class='fa fa-times' aria-hidden='true'></i>"."</b></font>";
}

//删除游戏列表介绍
else if($action == 'del3'){
	$sql = "delete  from `$tbname` where id=$id";
	$dosql->ExecNoneQuery($sql);
	header("location:$gourl");
	exit();
}

//删除游戏玩法介绍
else if($action == 'del4'){
	$tbname= "pmw_gamedes";
	$gourl = "game_des";
	$sql = "delete  from `$tbname` where id=$id";
	$dosql->ExecNoneQuery($sql);
	header("location:$gourl");
	exit();
}


//ajax获取游戏玩法介绍
if($action == 'checkgamedes')
{
	$r=$dosql->GetOne("SELECT game_description FROM `pmw_gamedes` WHERE id=$id");
  $game_description = $r['game_description'];
	echo $game_description;
}

//修改游戏介绍
else if($action == 'update_gamedes'){
	$tbname= "pmw_gamedes";
	if(strpos($game_pic,$cfg_weburl)!==false){
  $game_pics=$game_pic;
	}else{
	$game_pics=$cfg_weburl."/".$game_pic;
	}
	$game_time = time();
	$sql = "UPDATE `$tbname` SET game_name='$game_name',game_pic='$game_pics',game_description='$game_description',game_time=$game_time WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		$gourls="game_des.php";
		//ShowMsg("更新成功",$gourls);
		header("location:$gourls");
		exit();
	}
}
//添加游戏介绍
elseif($action == 'add_gamedes')
{
	  $game_pic = $cfg_weburl."/".$game_pic;
		$tbname= "pmw_gamedes";
		$game_time=time();
		$sql = "INSERT INTO `$tbname` (game_name, game_pic, game_description, game_time) VALUES ('$game_name','$game_pic', '$game_description',$game_time)";
		if($dosql->ExecNoneQuery($sql))
		{
			$gourls="game_des.php";
			header("location:$gourls");
			exit();
		}
}

//添加活动列表
elseif($action == 'gonggao_add')
{
	  $tbname="pmw_gonggao";
		$issuetime=time();
		$sql = "INSERT INTO `$tbname` (title, type, content, issuetime) VALUES ('$title', '$type','$content', $issuetime)";
		if($dosql->ExecNoneQuery($sql))
		{
			$gourl="gonggao.php";
			header("location:$gourl");
			exit();
		}
}


//修改公告内容
else if($action == 'gonggao_update'){
	$tbname= "pmw_gonggao";

	$issuetime = time();
	$sql = "UPDATE `$tbname` SET title='$title',type='$type',content='$content',issuetime=$issuetime WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		$gourls="gonggao.php";
		header("location:$gourls");
		exit();
	}
}
//添加任务列表
elseif($action == 'renwu_add')
{
		$tbname= "pmw_renwu";
		$mtime=time();
		$sql = "INSERT INTO `$tbname` (mtitle, mtype, msum, mmoney, mrules, mtime) VALUES ('$mtitle','$mtype','$msum', '$mmoney','$mrules', $mtime)";
		if($dosql->ExecNoneQuery($sql))
		{
			$gourls="renwulist.php";
			header("location:$gourls");
			exit();
		}
}
//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
