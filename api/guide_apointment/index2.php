<?php
    /**
	   * 链接地址：guide_apointment  导游预约旅行社发布的行程
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
     * @导游预约旅行社发布的行程   提供返回参数账号，一个行程可以同时最多有三个导游同时预约
     * gid             导游id
     * name            导游姓名
     * id              发布的行程id
     * aid             旅行社的id
     * formid          导游的formid
     * openid          导游的openid
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //当同时多个人预约此行程的时候，则判断当前行程是否已经有三个导游已经预约了


  # 备注 ： 更改行程为待确认
  #        双向发送模板消息
  #        判断当前的行程是否已经被预约
  #        判断当前预约的行程是否和前面已经预约的行程相冲突

  $get_travel_arr = Guide::get_travel($id);
  $yuyue_num = $get_travel_arr['yuyue_num'];
  //此条行程最多只能被三个导游预约
  if($yuyue_num < 3){

    //判断当前的行程的起始时间
    $starttime = $get_travel_arr['starttime'];  //本次行程的开始时间

    $endtime = $get_travel_arr['endtime'];     //本次行程的截至时间

    //计算出当前导游已经预约过的行程的所有的开始时间

    $one=1;

    $num =0;
    $dosql->Execute("SELECT * FROM pmw_travel where (state=1 or state=2) and gid=$gid",$one);

    while($sow=$dosql->GetArray($one)){

     $f=$sow['starttime'];

     $e=$sow['endtime'];

     if($starttime < $e && $e < $endtime){

        $num=1;

        break;

     }elseif($f< $endtime && $endtime< $e){

       $num=2;

       break;

     }elseif($starttime <= $f && $e <= $endtime){

       $num=3;

       break;

     }elseif($f< $starttime && $endtime< $e){

       $num=4;

       break;
     }

    }

    if($num==0){
    //判断预约的这个导游(实际是根据这个openid来判断)是否已经预约过此行程了

     $r = $dosql->GetOne("SELECT id from pmw_guide_confirm where tid=$id and openid='$openid' and chekinfo=1 where openid = '$openid'");
     if(!is_array($r)){
    $g=$dosql->GetOne("SELECT * FROM pmw_guide where id=$gid");   //导游信息
    $a=$dosql->GetOne("SELECT * FROM pmw_agency where id=$aid");  //旅行社信息
    $x=$dosql->GetOne("SELECT * FROM pmw_travel where id=$id");   //具体的行程信息

    $user_info = array(
        //构造模板消息的字段
       "company" => $a['company'],   //旅行社名称
       "names"   => $a['name'],      //旅行社联系人姓名
       "guide_name" => $name,        //预约的导游的姓名
       "tel"     => $a['tel'],       //旅行社的联系电话
       "title"   => $x['title'],     //旅行社发布的行程标题
       "time"    =>date("Y-m-d",$x['starttime'])."--".date("Y-m-d",$x['endtime']),  //行程时间
       "tishi"   => "亲爱的".$g['name']."您好，您预约的行程已提交成功，请尽快与旅行社核实行程信息并查看详情确认此行程。",
       "page"    => "pages/about/guideConfirm/index?id=".$id."&gid=".$gid."&aid=".$aid."&tem=tem",
       "guide_tel"=>$g['tel'],    //预约的导游的联系人电话
       "datetime" =>date("Y-m-d H:i:s"),  //预约时间
       "page_agency"=> "pages/about/confirm/confirm?id=".$id."&gid=".$gid."&tem=tem",
       "openid_agency" =>$x['openid'],    //发布此条行程的旅行社openid
       "formid_agency" => Common::get_new_formid($x['openid']),   //获取还未使用过的旅行社formid
       "posttime" => time(),

    );


     //实例化导游类
     $send_guide_message = new Guide($openid,$formid);
     //执行给导游发送模板消息方法，返回模板消息状态码，更改行程状态为1(待确认)
     $errcode_guide = $send_guide_message->SendGuide($aid,$gid,$id,$user_info);
     //将导游预约的行程保存到消息表里面去
     $send_guide_message->Insert_Guide_Message($user_info,$gid);


     //实例化旅行社类
     $send_agency_message = new Agency($user_info['openid_agency'],$user_info['formid_agency']);
     //执行给旅行社发送模板消息方法，返回模板消息状态码
     $errcode_agency = $send_agency_message->SendAgency($user_info);
     //将旅行社发布的此条行程被预约的消息保存到消息表里面去
     $send_agency_message->insert_Agency_Message($user_info,$aid);



  if($errcode_guide==0 && $errcode_agency==0){

      $State = 1;
      $Descriptor = '导游预约行程成功!，模板消息发送成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                  'Data' => $Data
                   );
      echo phpver($result);
    }else{
      $State = 0;
      $Descriptor = '导游预约行程成功,模板消息发送失败!';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
                  'Data' => $Data
                   );
      echo phpver($result);
    }
  }else{
    $State = 4;
    $Descriptor = '您已经预约过此行程，请及时和旅行社联系！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }
  }else{
    $State = 3;
    $Descriptor = '您已有此时间段内行程，请合理安排出行时间!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }

}else{
  $State = 5;
  $Descriptor = '行程已经被预约，请重新预约新的行程!';
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
