<?php
    /**
	   * 链接地址：openkj  获取开奖状态
	   *
     * 下面直接来连接操作数据库进而得到json串
     *
     * 按json方式输出通信数据
     *
     * @param unknown $State 状态码
     *
     * @param string $Descriptor  提示信息
     *
	   * @param string $Version  操作时间

     * @param array $Data 返回数据
     *
     *  params: uid, 用户id
     *          gameid, 游戏id
     *          gamename, 游戏名称
     *          timestamp 当前时间
     *          mid: 聊天记录id， 为空时返回当前时间timestamp往前的20条消息
     *
     */
header('Content-Type: application/json; charset=utf-8');
require_once("../../include/config.inc.php");

$kj_times="2189";
$one=1;
$two=2;
$summary=array();
$dosql->Execute("select a.xiazhu_qishu,b.telephone,b.nickname,a.xiazhu_sum,a.xiazhu_orderid from pmw_xiazhuorder a inner join pmw_members b on a.uid=b.id where a.xiazhu_qishu='$kj_times'",$one);
for($i=0;$i<$dosql->GetTotalRow($one);$i++)
{
  $row1 = $dosql->GetArray($one);
  //$summary[$i]=$row1;
  $summary[$i]['username']=$row1['nickname'];
  $summary[$i]['telephone']=substr($row1['telephone'],-4);
  $summary[$i]['sum']=$row1['xiazhu_sum'];
  $xiazhu_orderid=$row1['xiazhu_orderid'];
  $dosql->Execute("select xiazhu_type,xiazhu_money from pmw_xiazhucontent where xiazhu_orderid='$xiazhu_orderid'",$two);
  for($j=0;$j<$dosql->GetTotalRow($two);$j++){
      $row2 = $dosql->GetArray($two);
      $xiazhu_type =  $row2['xiazhu_type'];
	    $arr=explode("+",$xiazhu_type);
      $ml=$arr[0];   //一级目录
      $lb=$arr[1];   //下注的类别
      $bl=$arr[2];   //开奖的倍率
      if($ml==1){
		  $type= tochange($ml,$lb);
	     }else{
	    $type= $lb;
	     }
      $summary[$i]['content'][$j]['type']=$type;
      $summary[$i]['content'][$j]['money']=$row2['xiazhu_money'];
  }

}


//print_r($summary);
echo phpver($summary);

?>
