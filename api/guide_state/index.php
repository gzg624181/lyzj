<?php
    /**
	   * 链接地址：guide_state   导游接到行程的状态
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
     *
     * @param array $Data 数据
     *
     * @return string
     *
     * @提供返回参数账号 id    导游的id
     *  （1，待确认，3，已取消  2，已完成   待出发 ：导游接到行程，还未有出发之前）
     */
require_once("../../include/config.inc.php");
header("Content-type:application/json; charset:utf-8");
//error_reporting(E_ALL ^ E_NOTICE);
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){


      #计算已经发布的形成
      $we='we';
      $me='me';
      $one=1;
      $two=2;
      $three=3;
      $four=4;
      $five=5;
      #待出发(已经确认了是哪个导游才能计算待出发的行程)
      $now=time();
      $dosql->Execute("SELECT * FROM `#@__travel` WHERE gid=$id and state=2 and starttime  >= $now order by id desc",$we);
      for($i=0;$i<$dosql->GetTotalRow($we);$i++){
        $row = $dosql->GetArray($we);
        $Data['daichufa'][$i]=$row;
        $Data['daichufa'][$i]['posttime']=date("Y-m-d",$row['posttime']);
      }


      #待确认（还没有确认是哪个导游接单的所有行程）
      $dosql->Execute("SELECT a.* FROM `#@__travel` a inner join pmw_guide_confirm b on a.id=b.tid WHERE b.gid=$id and a.state=1 and b.checkinfo=1 order by b.id desc",$me);
      for($j=0;$j<$dosql->GetTotalRow($me);$j++){
        $show = $dosql->GetArray($me);
        $Data['daiqueren'][$j]=$show;
        $Data['daiqueren'][$j]['posttime']=date("Y-m-d",$show['posttime']);
      }


      #已取消 （旅行社取消了这个导游，或者确认了其中一个导游，取消了其他的导游）
      $dosql->Execute("SELECT a.* FROM `#@__travel` a inner join pmw_guide_confirm b on a.id=b.tid WHERE b.gid=$id and b.checkinfo=0 order by b.id desc",$one);
      for($i=0;$i<$dosql->GetTotalRow($one);$i++){
        $row1 = $dosql->GetArray($one);
        $Data['yiquxiao'][$i]=$row1;
        $Data['yiquxiao'][$i]['posttime']=date("Y-m-d",$row1['posttime']);

      }
      #已完成 （已经接单的导游，旅行社确认了行程）
      $dosql->Execute("SELECT * FROM `#@__travel` WHERE gid=$id and state=2 and starttime  < $now order by id desc",$five);
      for($j=0;$j<$dosql->GetTotalRow($five);$j++){
        $show1 = $dosql->GetArray($five);
        $Data['yiwancheng'][$j]=$show1;
        $Data['yiwancheng'][$j]['posttime']=date("Y-m-d",$show1['posttime']);
      }

      #全部行程 （所有的行程）
      $dosql->Execute("SELECT a.* FROM `#@__travel` a inner join pmw_guide_confirm b on a.id=b.tid WHERE b.gid=$id order by b.id desc",$two);
      for($j=0;$j<$dosql->GetTotalRow($two);$j++){
        $show2 = $dosql->GetArray($two);
        $Data['all'][$j]=$show2;
        $Data['all'][$j]['posttime']=date("Y-m-d",$show2['posttime']);
      }

      $State = 1;
      $Descriptor = '行程列表查询成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                  'Data' => $Data
                   );
      echo phpver($result);


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
