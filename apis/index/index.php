<?php
    /**
	   * 链接地址：index  获取首页大厅数据
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
     * @return string   null
     *
     */
header('Content-Type: application/json; charset=utf-8');
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $one=1;
  $two=2;
  $three=3;
  $four=4;
  $dosql->Execute("SELECT * FROM `#@__banner`",$one);
  for($i=0;$i<$dosql->GetTotalRow($one);$i++)
  {
      $row1 = $dosql->GetArray($one);
      $Data['banner'][$i]=$row1;
      $Data['banner'][$i]['url']=$cfg_weburl."/banner_show.php?id=".$row1['id'];
  }
  $dosql->Execute("SELECT *  from  `#@__important` WHERE id=1",$two);
  for($k=0;$k<$dosql->GetTotalRow($two);$k++)
      {
          $row2 = $dosql->GetArray($two);
          $Data['importantnotice'][$k]=$row2;
      }
  $dosql->Execute("SELECT gamename,gamepic,gamenumber,gameonline,game FROM `#@__game` group by gamename order by id asc",$three);
  for($j=0;$j<$dosql->GetTotalRow($three);$j++)
      {
          $row3 = $dosql->GetArray($three);
          $Data['games'][$j]=$row3;
        }

  $dosql->Execute("SELECT id,active_name,active_description,active_statues,active_onimages,active_offimages FROM `#@__active`  order by id desc",$four);
            for($m=0;$m<$dosql->GetTotalRow($four);$m++)
                {
                    $row4 = $dosql->GetArray($four);
                    $Data['active'][$m]=$row4;
                  if($row4['active_statues']==1){
                    $Data['active'][$m]['images']=$row4['active_onimages'];
                  }else{
                    $Data['active'][$m]['images']=$row4['active_offimages'];
                  }

                  if($row4['active_statues']==1){

                  if($row4['id']==4){
                    $Data['active'][$m]['active_url']=$cfg_weburl."/luckyzp_show.php";
                  }elseif($row4['id']==3){
                    $Data['active'][$m]['active_url']="#";
                  }elseif($row4['id']==2){
                    $Data['active'][$m]['active_url']=$cfg_weburl."guessnum.php";
                  }elseif($row4['id']==1){
                    $Data['active'][$m]['active_url']="#";
                  }
                }else{
                  if($row4['id']==4){
                    $Data['active'][$m]['active_url']="";
                  }elseif($row4['id']==3){
                    $Data['active'][$m]['active_url']="";
                  }elseif($row4['id']==2){
                    $Data['active'][$m]['active_url']="";
                  }elseif($row4['id']==1){
                    $Data['active'][$m]['active_url']="";
                  }
                }
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
