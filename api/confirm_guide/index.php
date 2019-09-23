<?php
    /**
	   * 链接地址：confirm_guide   旅行社确认导游，同时给确认的人发送模板消息，给未确认的导游发送行程预约失败的消息
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
     * @旅行社发布旅游行程   提供返回参数账号，
     * id            此条行程的id
     * aid           旅行社id
     * gid           导游id（被选中的导游的id）
     * openid        旅行社的openid
     * formid        旅行社的formid


     * 旅行社确认其中的一个导游的信息,同时给其他的导游发送预约失败的消息
     *
     */
require_once("../../include/config.inc.php");
header("Content-type:application/json; charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //备注 ：旅行社更改导游
  // 1.取消选中的导游，更改导游，取消导游之后，给导游发送模板消息

  $get_travel_arr = Guide::get_travel($id);
  $yuyue_num = $get_travel_arr['yuyue_num'];  //此条行程已经预约的人数
  $state = $get_travel_arr['state'];          //行程的状态

  //保存旅行社发过来的最新的formid
  Common::add_formid($openid,$formid);

  if($state==1 && $yuyue_num >0){  // 待确认的状态，已经有人去预约了

    //将选中的导游的信息添加到此条行程里面去
    $g=$dosql->GetOne("SELECT * FROM pmw_guide_confirm where gid=$gid and tid=$id");
    //预约的导游的发送取消行程的模板消息
    $a=$dosql->GetOne("SELECT * FROM pmw_agency where id=$aid");  //旅行社信息
    $x=$dosql->GetOne("SELECT * FROM pmw_travel where id=$id");
    $gu=$dosql->GetOne("SELECT * FROM pmw_guide where id=$gid");   //导游信息

    // 被选中的导游的信息
    $name_guide =  $gu['name'];  //导游的姓名
    $openid_guide = $g['openid'];  //被选中的导游的openid
    $formid_guide = Common::get_new_formid($openid_guide);

    //必备参数数组
    $info = array(

         "title"   => $x['title'],               //旅行社发布的行程标题
         "time"    =>date("Y-m-d",$x['starttime'])."--".date("Y-m-d",$x['endtime']),  //行程时间段
         "company" => $a['company'],           //旅行社名称
         "names"   => $a['name'],              //旅行社联系人姓名
         "faxtime" => time(),                  //发布时间
         "reason"  => "旅行社已经取消了此行程",    //给未选中的导游发送模板消息的原因
         "tel"     => $a['tel'],               //旅行社联系人电话号码
         "tishi_guide" =>"您预约的此条行程已取消，可进入小程序再次预约行程，欢迎您再次使用。",
         "page_guide"  =>"pages/about/guideConfirm/index?id=".$id."&gid=".$gid."&aid=".$aid."&tem=tem",
         "tishi"       => "亲爱的".$name_guide."您好，您预约的行程已被旅行社确认成功，请提前做好行程准备！",
         "page"        => "pages/about/guideConfirm/index?id=".$id."&gid=".$gid."&aid=".$aid."&tem=tem",
         "openid_guide"=> $openid_guide,
         "formid_guide"=> $formid_guide,
    );

   # 给已经确定的导游发送确定的模板消息
   $send_confirm_guide_message = new Guide($openid_guide,$formid_guide);

   $errcode_guide = $send_confirm_guide_message->Confirm_Gide($aid,$gid,$id,$info);

   //将旅行社确认成功的的导游保存到消息表里面去
   $send_confirm_guide_message->Insert_Confirm_Guide_Message($info,$gid);


   # 给另外没有被选中的导游发送模板消息
    $dosql->Execute("SELECT openid,gid from pmw_guide_confirm where tid=$id and checkinfo=1");
    $nums = $dosql->GetTotalRow();
    if($nums > 0){ //如果还有其他的导游预约了此条行程，则执行发送模板消息的操作，如果没有其他的导游，则不执行
    while($row = $dosql->GetArray()){

    $guide = new Guide($row['openid'],Common::get_new_formid($row['openid']));
    $gids = $row['gid'];
    $guide->Send_Concel_Guide($info,$gids,$id);

    //将另外的导游没有确认的导游的信息保存下来
    $guide->concel_Guide_Message($info,$gids);

    }
    }

    $State = 1;
    $Descriptor = '导游信息确认成功!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '没有预约的导游信息!';
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
