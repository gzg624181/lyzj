<?php
    /**
	   * 链接地址：appointment  获取所有待预约的行程安排
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
     *管沟  ceshi
     * @param array $Data 数据
     *
     * @return string
     *
     * @提供返回参数账号 page  默认为0 ,每页pagenumber条数据
     *
     * 根据登录的定位城市省份和城市，live_province  live_city
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
   $pagenumber=4;
   if(isset($page)){
    $first=$page * $pagenumber;
     $dosql->Execute("SELECT id,title,starttime,endtime,money,other FROM pmw_travel where state=0 or (state=1 and yuyue_num<3)  and live_province ='$live_province' and live_city='$live_city' order by id desc limit $first,$pagenumber");
     }else{
      $dosql->Execute("SELECT id,title,starttime,endtime,money,other FROM pmw_travel where state=0 or (state=1 and yuyue_num<3)   and live_province ='$live_province' and live_city='$live_city' order by id desc limit 0,$pagenumber");
     }
    $num=$dosql->GetTotalRow();//获取数据条数

    if($num>0){
      for($i=0;$i<$num;$i++){
      $row=$dosql->GetArray();
      $Data[$i]=$row;
      $tid = $row['id'];  //行程id
      $two=2;
       $dosql->Execute("SELECT b.name,b.images FROM pmw_guide_confirm a inner join pmw_guide b  on a.gid=b.id where a.tid= $tid and a.checkinfo=1",$two);
       $nums=$dosql->GetTotalRow($two);//获取数据条数
       if($nums>0){
       for($j=0;$j<$nums;$j++){
       $show=$dosql->GetArray($two);
       $Data[$i]['guide'][$j]=$show;
       }
     }else{
       $Data[$i]['guide']= array();
     }
    }
      $State = 1;
      $Descriptor = '数据获取成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                  'Data' => $Data
                   );
      echo phpver($result);
    }else{
      $State = 0;
      $Descriptor = '已经没有数据了';
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
