<?php
    /**
	   * 链接地址：getindex  获取单个游戏列表
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
     * @return string   游戏简称 game
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
$one=1;
$two=2;
if(isset($token) && $token==$cfg_auth_key){
  $dosql->Execute("SELECT id,gamename,game,gamepic,gamenumber,remark,gametypes,gamewho,gamedescription FROM `#@__game` WHERE game='$game' order by id asc",$one);
  for($i=0;$i<$dosql->GetTotalRow($one);$i++)
  {
      $row = $dosql->GetArray($one);
      $Data[$i]=$row;
      $arr= array("Circley:小单，大双，小双：50","气旋:单，大单：30","聚财：单：63","残风:小双，小单：20","不思:小双，豹子：20","比赢:小100","小妖:单：500","爱你么么哒:单20小双10","BUDEENG:顺子：10","赢:顺子:20","互相进步:15大10大双","发达了:大单,小双：20","梅子:极大:50","blue奔放:小:100","无敌OBA:大350小350","卡王：大500小200数字14","soul：11大11大双","goodluck:小双:300","玩着爽:顺子：100");
      $randkey=array_rand($arr);
      $Data[$i]['gamewho']=$arr[$randkey];
      $Data[$i]['gamedescription']=$cfg_weburl."/peilv_show.php?id=".$row['id'];
      // $gid=$row['id'];
      // $dosql->Execute("SELECT * FROM `#@__gameplay` where gid=$gid",$two);
      // for($j=0;$j<$dosql->GetTotalRow($two);$j++)
      // {
      //     $show = $dosql->GetArray($two);
      //     $Data[$i]['typeName'][$j]=$show['typename'];
      //
      //     $Data[$i]['betRate'][$j][0]['key']=$show['typename_name']."+".$show['da_name']."+".$show['da'];
      //     $Data[$i]['betRate'][$j][0]['keyword']=$show['da_name'];
      //     $Data[$i]['betRate'][$j][0]['value']=$show['da'];
      //     $Data[$i]['betRate'][$j][0]['valueword']=$show['da'];
      //
      //     $Data[$i]['betRate'][$j][1]['key']=$show['typename_name']."+".$show['xiao_name']."+".$show['xiao'];
      //     $Data[$i]['betRate'][$j][1]['keyword']=$show['xiao_name'];
      //     $Data[$i]['betRate'][$j][1]['value']=$show['xiao'];
      //     $Data[$i]['betRate'][$j][1]['valueword']=$show['xiao'];
      //
      // }

  }
  if($dosql->GetTotalRow($one)>0){
    $State = 1;
    $Descriptor = '数据获取成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data,
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '数据获取失败';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
        );
    echo phpver($result);
  }
}else{
  $State = 520;
  $Descriptor = 'token验证失败！';
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
  				         'Version' => $Version,
                   'Data' => $Data,
                   );
  echo phpver($result);
}

?>
