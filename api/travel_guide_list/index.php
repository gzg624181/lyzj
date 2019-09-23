<?php
    /**
	   * 链接地址：travel_guide_list       旅行社发布的一条行程对应的多个导游的详情
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
     * @return string  行程id
     *
     * @提供返回参数账号
     */
require_once("../../include/config.inc.php");
header("Content-type:application/json; charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
      $r=$dosql->GetOne("SELECT * FROM `#@__travel` where id=$id");
      if(is_array($r)){
      $Data=$r;
      $two=2;
      $dosql->Execute("SELECT b.name,b.sex,b.images,b.tel,b.cardnumber,b.id FROM pmw_guide_confirm a inner join pmw_guide b  on a.gid=b.id where a.tid= $id and a.checkinfo=1",$two);
      $nums=$dosql->GetTotalRow($two);//获取数据条数
      if($nums>0){
      for($j=0;$j<$nums;$j++){
      $show=$dosql->GetArray($two);
      $Data['guide'][$j]=$show;
      $images = $show['images'];
      if(!check_str($images,"https")){
        $Data['guide'][$j]['images']=$cfg_weburl."/".$images;
      }
      }
    }else{
      $Data['guide']= array();
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
      $Data[]=$r;
      $State = 0;
      $Descriptor = '没有此条行程！';
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
