<?php
    /**
	   * 链接地址：cancel_guide   旅行社取消更改导游
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
     * aid         旅行社id
     * gid         导游id
     * reason      取消原因

     * 如果有导游预约的话，对单个导游进行修改更换，则对导游发送 模板消息
     * 减去此条行程已经被预约的人数，此条行程的状态为待确认
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

  if($state==1 && $yuyue_num >0){  // 待确认的状态，已经有人去预约了

    $arr =explode(".",$reason);

    //将选中的导游的信息改为预约失败 ，同时更改已经预约的人数减去1
    $dosql->ExecNoneQuery("UPDATE `#@__guide_confirm` set checkinfo=0 WHERE tid=$id and gid=$gid");
    $dosql->ExecNoneQuery("UPDATE `#@__travel` set yuyue_num = yuyue_num -1 where id=$id");
    //预约的导游的发送取消行程的模板消息
    $g=$dosql->GetOne("SELECT * FROM pmw_guide_confirm where gid=$gid and tid=$id");
    $a=$dosql->GetOne("SELECT * FROM pmw_agency where id=$aid");
    $x=$dosql->GetOne("SELECT * FROM pmw_travel where id=$id");

    //必备参数数组
    $info = [

         "openid_guide"=>$g['openid'],         //预约此条行程的导游的openid
         "title" => $x['title'],               //旅行社发布的行程标题
         "time" =>date("Y-m-d",$x['starttime'])."--".date("Y-m-d",$x['endtime']),  //行程时间段
         "reason" =>$arr[1],                   //取消原因
         "faxtime" => time(),
         "name" =>  $a['company'],
         "tel" => $a['tel'],
         "tishi_guide"=>"您预约的此条行程已取消，可进入小程序再次预约行程，欢迎您再次使用。",
         "page_guide"=>"pages/about/guideConfirm/index?id=".$id."&gid=".$gid."&aid=".$aid."&tem=tem",

    ];

   #给导游发送模板消息

    $guide = new Guide($g['openid'],Common::get_new_formid($g['openid']));

    $guide->Send_Concel_Guide($info);

    //将旅行社更改导游的信息保存下来
    $guide->concel_Guide_Message($info,$gid);

    $State = 1;
    $Descriptor = '导游信息更改成功!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '没有可以更改的导游信息!';
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
