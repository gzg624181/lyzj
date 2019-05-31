<?php
    /**
	   * 链接地址：cancel_travel  旅行社取消行程
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
     * id        此条行程的id
     * formid    旅行社的formid
     * reason     旅行社取消的原因
     * aid        旅行社id
     * gid       导游id
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //备注 ：取消行程的时候分为两种状态
  # 1.待预约状态的时候，直接取消
  # 2.待确认状态下的时候，发送双向模板消息
  $r=$dsosql->GetOne("SELECT state FROM pmw_travel where id=$id");
  if($r['state']==0){
    $sql = "UPDATE `#@__travel` set state=3 WHERE id=$id";
    if($dosql->ExecNoneQuery($sql)){
    $State = 1;
    $Descriptor = '行程取消成功!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
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
  }elseif($r['state']==1){

    $sql = "UPDATE `#@__travel` set state=3 WHERE id=$id";
    $dosql->ExecNoneQuery($sql);
    //发送双向模板消息

    # 给旅行社发布模板消息 （旅行社的formid通过参数获取）

    $g=$dosql->GetOne("SELECT * FROM pmw_guide where id=$gid");
    $a=$dosql->GetOne("SELECT * FROM pmw_agency where id=$aid");
    $x=$dosql->GetOne("SELECT * FROM pmw_travel where id=$id");

    $openid_agency=$a['openid'];    //旅行社联系人openid

    $openid_guide=$g['openid'];    //导游openid


    $title=$x['title'];           //旅行社发布的行程标题

    $time=date("Y-m-d",$x['starttime'])."--".date("Y-m-d",$x['endtime']); //旅行社发布的行程时间

    $reason="世界这么大，我想自己单独出去走走";

    $tishi="您发布的此条行程已取消，可进入小程序再次发布行程，欢迎您再次使用。";

    $page="pages/about/enter/enter";

    $data_agency=CancelAgency($title,$time,$reason,$tishi,$openid_agency,$cfg_concel_agency,$page,$formid);

    $ACCESS_TOKEN = get_access_token($cfg_appid,$cfg_appsecret);//ACCESS_TOKEN

    //模板消息请求URL
    $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

    $json_data_agency = json_encode($data_agency);//转化成json数组让微信可以接收
    $res_agency = https_request($url, urldecode($json_data_agency));//请求开始
    $res_agency = json_decode($res_agency, true);
    $errcode_agency=$res_agency['errcode'];

    #向导游发送取消行程的模板消息

    $data_guide=CancelGuide($title,$time,$nickname,$tel,$reason,$tishi,$openid,$cfg_cancel_guide,$page,$form_id);

    $ACCESS_TOKEN = get_access_token($cfg_appid,$cfg_appsecret);//ACCESS_TOKEN

    //模板消息请求URL
    $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

    $json_data_guide = json_encode($data_guide);//转化成json数组让微信可以接收
    $res_guide = https_request($url, urldecode($json_data_agency));//请求开始
    $res_guide = json_decode($res_guide, true);
    $errcode_guide=$res_guide['errcode'];

    if($errcode_guide==0 && $errcode_agency==0){
    $State = 1;
    $Descriptor = '行程取消成功!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
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
