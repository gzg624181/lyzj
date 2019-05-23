<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('message'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>下注详情</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<link href="templates/style/menu1.css" rel="stylesheet" type="text/css" />
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<style>
.input {
    width: 280px;
    height: 35px;
    border-radius: 3px;
}
.input1 {    width: 280px;
    height: 35px;
    border-radius: 3px;
}
</style>
</head>
<body>

<div class="topToolbar"> <span class="title" style="text-align:center;">下注详情</span> <a title="刷新" href="javascript:location.reload();" class="reload" style="float:right; margin-right:35px;"><i class="fa fa-refresh" aria-hidden="true"></i></a></div>
<?php
  $s=$dosql->GetOne("SELECT a.xiazhu_orderid,a.xiazhu_qishu,a.xiazhu_timestamp,a.xiazhu_sum,a.xiazhu_kjstate,a.gameid,b.kj_varchar,a.xiazhu_jiangjin,b.kj_number,b.kj_he FROM `pmw_xiazhuorder` a inner join `pmw_lotterynumber` b on a.xiazhu_qishu=b.kj_times where a.id=$gid");
  $orderid=$s['xiazhu_orderid'];
  $kj_code=$s['kj_number'];
  $kj_heji=$s['xiazhu_sum'];
  $gid=$s['gameid'];
			   switch($gid){
				   case 4:
				   $gameid="加拿大28-2.0";
				   break;
				   case 5:
				   $gameid="加拿大28-2.5";
				   break;
				   case 6:
				   $gameid="加拿大28-2.8";
				   break;
				   case 7:
				   $gameid="加拿大28-1.88";
				   break;
				   case 8:
				   $gameid="加拿大28-3.2";
				   break;				   
				   }
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr>
		  <td height="40" align="right">下注订单：</td>
		  <td colspan="4"><?php echo $s['xiazhu_orderid'];?></td>
    </tr>
	<tr>
		  <td width="22%" height="40" align="right">下注期数：</td>
		  <td colspan="4"><?php echo $s['xiazhu_qishu'];?></td>
    </tr>
  <tr>
  <td height="40" align="right">下注时间：</td>
  <td colspan="4"><?php echo date("Y-m-d H:i:s",$s['xiazhu_timestamp']);?></td>
  </tr>
		<tr>
		  <td height="40" align="right">下注金额：</td>
		  <td colspan="4" class="num" style="color:red;"><?php echo sprintf("%.2f",$s['xiazhu_sum']);?></td>
    </tr>
    <?php if($s['xiazhu_kjstate']==0){?>
    <tr>
      <td height="40" align="right">下注类别：</td>
      <td colspan="4"><?php echo $gameid;?></td>
    </tr>
    <tr>
      <td height="40" align="right">是否开奖：</td>
      <td colspan="4">未开奖</td>
    </tr>
    <tr>
        <td height="40" align="right">下注类别</td>
        <td colspan="2" align="center">下注倍率</td>
        <td colspan="2" align="center">下注金额</td>
  </tr>
   <?php
		$dosql->Execute("SELECT *  FROM `pmw_xiazhucontent` where xiazhu_orderid='$orderid'");

		while($r = $dosql->GetArray())
		{
			   $xiazhu_type =  $r['xiazhu_type'];
	?>
    <tr>
      <td height="40" align="right"><?php echo $xiazhu_type; ?></td>
      <td colspan="2" align="center" class="num"><?php echo  $r['xiazhu_beilv'];?></td>
      <td colspan="2" align="center" class="num" style="color:red"><?php echo  $r['xiazhu_money'];?></td>
    </tr>
    <?php }}elseif($s['xiazhu_kjstate']==1){?>
    <tr>
      <td height="40" align="right">中奖金额：</td>
      <td colspan="4" class="num" style="color:#529ee0;"><?php echo sprintf("%.2f",$s['xiazhu_jiangjin']);?></td>
    </tr>
        <tr>
      <td height="40" align="right">下注类别：</td>
      <td colspan="4"><?php echo $gameid;?></td>
    </tr>
    <tr>
      <td height="40" align="right">是否开奖：</td>
      <td colspan="4">已开奖</td>
    </tr>
    <tr>
      <td height="40" align="right">开奖号码：</td>
      <td colspan="4"><?php echo $s['kj_varchar'];?></td>
    </tr>
    <tr>
        <td height="40" align="right">下注类别</td>
        <td width="18%" align="center">下注倍率</td>
        <td width="19%" align="center">下注金额</td>
        <td width="17%" align="center">开奖倍率</td>
        <td width="24%" align="center">开奖结果</td>
  </tr>
   <?php
		$dosql->Execute("SELECT *  FROM `pmw_xiazhucontent` where xiazhu_orderid='$orderid'");

		while($r = $dosql->GetArray())
		{
			   $xiazhu_type =  $r['xiazhu_type'];
	?>
    <tr>
      <td height="40" align="right"><?php echo $xiazhu_type; ?></td>
      <td align="center" class="num"><?php echo  $r['xiazhu_beilv'];?></td>
      <td align="center" class="num" style="color:red"><span class="num" style="color:red"><?php echo  $r['xiazhu_money'];?></span></td>
      <td align="center" class="num"><?php echo  $r['new_beilv'];?></td>
      <td align="center" class="num" style="color:#509ee1"><?php echo  $r['kj_content'];?></td>
    </tr>
    <?php }}?>
  </table>
</body>
</html>
