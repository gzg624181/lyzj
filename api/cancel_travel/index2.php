<?php
    /**
	   * 链接地址：cancel_travel   旅行社取消行程
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
     * id          此条行程的id
     * formid      旅行社的formid
     * openid      旅行社的openid
     * reason      旅行社取消的原因
     * aid         旅行社id
     * gid         导游id
     * reason      取消原因

     * 如果有导游预约的话，则单个点击，取消行程，给导游发送取消行程的模板消息
     *
     */
require_once("../../include/config.inc.php");
header("Content-type:application/json; charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //备注 ：取消行程的时候分为两种状态
  // 1.待预约状态的时候，直接取消（也就是没有人去预约的情况下）
  // 2.待确认状态下的时候，给已经预约过的导游发送模板消息

  $get_travel_arr = Guide::get_travel($id);
  $yuyue_num = $get_travel_arr['yuyue_num'];  //此条行程已经预约的人数
  $state = $get_travel_arr['state'];          //行程的状态

  if($yuyue_num==0  && $state==0){  //行程状态为待预约，且预约的导游人数为0，没人预约的时候
    $sql = "UPDATE `#@__travel` set state=5 WHERE id=$id";
    if($dosql->ExecNoneQuery($sql)){
    $s=$dosql->GetOne("SELECT state FROM pmw_travel where id=$id");
    $State = 1;
    $Descriptor = '行程取消成功!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $s
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '行程取消失败!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }
}elseif($state==1){  // 待确认的状态，已经有人去预约了

    $arr =explode(".",$reason);

    //将行程的状态改为已取消
    $dosql->ExecNoneQuery("UPDATE `#@__travel` set state=3 WHERE id=$id");

    //预约的导游的发送取消行程的模板消息
    $g=$dosql->GetOne("SELECT * FROM pmw_guide_confirm where gid=$gid and tid=$id");
    //必备参数数组
    $a=$dosql->GetOne("SELECT * FROM pmw_agency where id=$aid");
    $x=$dosql->GetOne("SELECT * FROM pmw_travel where id=$id");

    $info = [

         "openid_guide"=>$g['openid'],         //预约此条行程的导游的openid
         "title" => $x['title'],               //旅行社发布的行程标题
         "time" =>date("Y-m-d",$x['starttime'])."--".date("Y-m-d",$x['endtime']),  //行程时间段
         "reason" =>$arr[1],                   //取消原因
         "tishi" =>"您发布的此条行程已取消，可进入小程序再次发布行程，欢迎您再次使用。",  //取消提示
         "page" => "pages/about/confirm/confirm?id=".$id."&gid=".$gid."&tem=tem",
         "faxtime" => time(),
         "name" =>  $a['company'],
         "tel" => $a['tel'],
         "tishi_guide"=>"您预约的此条行程已取消，可进入小程序再次预约行程，欢迎您再次使用。",
         "page_guide"=>"pages/about/guideConfirm/index?id=".$id."&gid=".$gid."&aid=".$aid."&tem=tem",

    ];

    //发送双向模板消息

   # ①.给旅行社发布模板消息

   //  $agency =new Agency($openid,$formid);
   //
   //  $agency->Send_Concel_Agency($info);
   //
   // //将旅行社撤销行程的模板消息保存到历史消息记录里面去
   //  $agency->concel_Agency_Message($info,$aid);

   #②. 给导游发送模板消息

    $guide = new Guide($g['openid'],Common::get_new_formid($g['openid']));

    $guide->Send_Concel_Guide($info);

    //将导游撤销行程的模板消息保存到历史消息记录里面去
    $guide->concel_Guide_Message($info,$gid);

    $s=$dosql->GetOne("SELECT state FROM pmw_travel where id=$id");

    $states =$s['state'];

    if($states==3){
    $State = 1;
    $Descriptor = '行程取消成功!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $s
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '行程取消失败!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }

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
