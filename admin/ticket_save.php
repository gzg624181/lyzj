<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('member');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-30 17:16:14
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__ticket';
$gourl  = 'ticket.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加景区分类

if($action=="add_ticket_class"){

  $icon = $cfg_weburl."/".$icon;

  $posttime=strtotime($posttime);

  $sql="INSERT INTO pmw_ticketclass (title,icon,posttime) VALUES ('$title','$icon',$posttime)";

  if($dosql->ExecNoneQuery($sql))
	{
    $gourl= "ticket_class.php";
		header("location:$gourl");
		exit();
	}
}
//修改导游信息
else if($action == 'update')
{

  if(!isset($picarr))        $picarr = '';
//合同组图
  if(is_array($picarr))
  {
    $picarrNum = count($picarr);
    $picarrTmp = '';

    for($i=0;$i< $picarrNum;$i++)
    {
      $picarrTmp[] = $cfg_weburl."/".$picarr[$i];
    }

    $picarr = json_encode($picarrTmp);
  }

  $ymdtime=substr($regtime,0,10);
  $regtime=strtotime($regtime);

  if(!check_str($card,$cfg_weburl)){
    $card=$cfg_weburl."/".$card; //导游证件
  }

  if(!check_str($images,$cfg_weburl)){
    $images=$cfg_weburl."/".$images; //导游头像
  }

  if($password==""){ //密码不修改
    $sql = "UPDATE `$tbname` SET name='$name', agreement='$picarr', sex=$sex,card = '$card', cardnumber='$cardnumber', images='$images', content='$content',regtime=$regtime,ymdtime='$ymdtime' WHERE id=$id";
  }else{
    $password=md5(md5($password));
    $sql = "UPDATE `$tbname` SET name='$name',agreement='$picarr', sex=$sex,card = '$card', cardnumber='$cardnumber', images='$images', password='$password', content='$content',regtime=$regtime,ymdtime='$ymdtime' WHERE id=$id";
  }

	if($dosql->ExecNoneQuery($sql))
	{

		header("location:$gourl");
		exit();
	}
}
//ajax获取导游简介
else if($action == 'checkguide')
{
  if($type=="content"){
	$r=$dosql->GetOne("SELECT content FROM $tbname WHERE id=$id");
  $content = $r['content'];
  }elseif($type=="pics"){
  $r=$dosql->GetOne("SELECT pics,name FROM $tbname WHERE id=$id");
  $contents = $r['pics'];
  $content =  "<span style='font-size:14px;font-weight:bold;margin-bottom:10px;'>".$r['name']."--导游相册"."</span>";

  $arr=explode("|",$contents);
  for($i=0;$i<count($arr);$i++){
  $content .= "<img src='".$arr[$i]."' width=90% style='margin-top:17px;margin-bottom:8px;border-radius:3px;'><br>";
  }
  }elseif($type=="card"){
  $r=$dosql->GetOne("SELECT card,name FROM $tbname WHERE id=$id");
  $contents = $r['card'];
  $content =  "<span style='font-size:18px;font-weight:bold;margin-bottom:10px;'>".$r['name']."的导游证件"."</span>";
  $content .= "<img src='".$contents."' width=90% style='margin-top:17px;'>";
  }
	echo $content;
}

else if($action=="del5"){
//删除空闲时间
$dosql->ExecNoneQuery("DELETE FROM pmw_freetime WHERE id=$id");
$gourl="free_time.php";
header("location:$gourl");
exit();

}

//无条件返回
else
{
    header("location:$gourl");
	  exit();
}
?>