<?php
    /**
	   * 链接地址：get_invite_num  获取用户邀请注册的人数
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
     * @提供返回参数账号     用户的openid   用户的uid  用户的类别classes
     *                    选择的类别type:
     *                                       审核中：shenhe
     *                                       成功：  success
     *                                       审核失败：failed
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

     //计算用户的佣余额
     if($classes =="agency"){
       $tbname = "pmw_agency";
     }else{
        $tbname =  "pmw_guide";
     }
     $k = $dosql->GetOne("SELECT money from $tbname where id=$uid");
     $money = $k['money'];
      // 计算推荐了多少会员注册
      $one =1;
      $two =2;
      $three =3;
      $four =4;
      $five =5;
      $six  =6;
      $seven =7;
      $eight =8;

      #所有邀请推荐注册的会员
      $dosql->Execute("SELECT id FROM pmw_guide where recommender_openid='$openid' and recommender_type='$classes' and uid='$uid'",$one);
      $nums_guide = $dosql->GetTotalRow($one);

      $dosql->Execute("SELECT id FROM pmw_agency where recommender_openid='$openid' and recommender_type='$classes' and uid='$uid'",$two);
      $nums_agency = $dosql->GetTotalRow($two);

      $nums = $nums_guide + $nums_agency;    //合计推荐注册的会员

      #审核中
      if($type == 'shenhe'){
      $dosql->Execute("SELECT name,regtime FROM pmw_guide where recommender_openid='$openid' and recommender_type='$classes' and uid='$uid' and checkinfo=0",$three);
      $nums_guide_shenhe = $dosql->GetTotalRow($three);
      $list = array();
      if($nums_guide_shenhe==0){
        $list1= array();
      }else{
        for($i=0;$i<$nums_guide_shenhe;$i++){
          $row1 = $dosql->GetArray($three);
          $list1[]= $row1;
          $list1[$i]['type'] = 'guide';
          $list1[$i]['state'] = 'shenhe';
        }
      }

      $dosql->Execute("SELECT name,regtime FROM pmw_agency where recommender_openid='$openid'  and recommender_type='$classes' and uid='$uid' and checkinfo=0",$four);
      $nums_agency_shenhe = $dosql->GetTotalRow($four);
      $list2 = array();
      if($nums_agency_shenhe==0){
        $list2= array();
      }else{
        for($i=0;$i<$nums_agency_shenhe;$i++){
          $row2 = $dosql->GetArray($four);
          $list2[]= $row2;
          $list2[$i]['type'] = 'agency';
          $list2[$i]['state'] = 'shenhe';
        }
      }

      $nums_shenhe = $nums_guide_shenhe + $nums_agency_shenhe;   //合计推荐注册正在审核的会员
      $list = array();
      $list = array_merge($list1,$list2);

      $Data = array(

           "nums" => $nums_shenhe,
           "money"=> $money,
           "list" => $list
      );

    }elseif($type=="success"){


      #注册成功的会员
      $dosql->Execute("SELECT name,regtime FROM pmw_guide where recommender_openid='$openid'  and recommender_type='$classes' and uid='$uid' and checkinfo=1",$five);
      $nums_guide_success = $dosql->GetTotalRow($five);

      if($nums_guide_success==0){
        $list3= array();
      }else{
        for($i=0;$i<$nums_guide_success;$i++){
          $row3 = $dosql->GetArray($five);
          $list3[]= $row3;
          $list3[$i]['type'] = 'guide';
          $list3[$i]['state'] = 'success';
        }
      }

      $dosql->Execute("SELECT name,regtime FROM pmw_agency where recommender_openid='$openid'  and recommender_type='$classes' and uid='$uid' and checkinfo=1",$six);
      $nums_agency_success = $dosql->GetTotalRow($six);

      if($nums_agency_success==0){
        $list4= array();
      }else{
        for($i=0;$i<$nums_agency_success;$i++){
          $row4 = $dosql->GetArray($six);
          $list4[]= $row4;
          $list4[$i]['type'] = 'agency';
          $list4[$i]['state'] = 'success';
        }
      }

      $nums_success = $nums_guide_success + $nums_agency_success;   //合计推荐注册成功的会员

      $list = array_merge($list3,$list4);

      $Data = array(

           "nums" => $nums_success,
           "money"=> $money,
           "list" => $list
      );

    }elseif($type=="failed"){
      #审核未通过的会员
      $dosql->Execute("SELECT name,regtime,account FROM pmw_un_guide where recommender_openid='$openid'  and recommender_type='$classes' and uid='$uid' and checkinfo=2",$seven);
      $nums_guide_failed = $dosql->GetTotalRow($seven);

      if($nums_guide_failed==0){
        $list5= array();
      }else{
        for($i=0;$i<$nums_guide_failed;$i++){
          $row5 = $dosql->GetArray($seven);
          $list5[]= $row5;
          $account = $row5['account'];
          //审核失败的原因
          $r = $dosql->GetOne("SELECT content  from pmw_agency_message where tel='$account' and tp='导游'");
          $list5[$i]['type'] = 'guide';
          $list5[$i]['reason'] = $r['content'];
          $list5[$i]['state'] = 'failed';
        }
      }

      $dosql->Execute("SELECT name,regtime,account FROM pmw_un_agency where recommender_openid='$openid' and recommender_type='$classes' and uid='$uid'  and checkinfo=2",$eight);
      $nums_agency_failed = $dosql->GetTotalRow($eight);

      if($nums_agency_failed==0){
        $list6= array();
      }else{
        for($i=0;$i<$nums_agency_failed;$i++){
          $row6 = $dosql->GetArray($eight);
          $list6[]= $row6;
          //审核失败的原因
          $account = $row6['account'];
          $r = $dosql->GetOne("SELECT content  from pmw_agency_message where tel='$account' and tp='旅行社'");
          $list6[$i]['type'] = 'agency';
          $list6[$i]['reason'] = $r['content'];
          $list6[$i]['state'] = 'failed';
        }
      }

      $nums_failed = $nums_guide_failed + $nums_agency_failed;   //合计推荐注册审核失败的会员

      $list = array_merge($list5,$list6);

      $Data = array(

           "nums" => $nums_failed,
           "money"=> $money,
           "list" => $list
      );

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
