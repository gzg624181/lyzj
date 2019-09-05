<?php
    /**
	   * 链接地址：add_travel  旅行社发布旅游行程
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
     * title           行程标题
     * aid             旅行社id
     * starttime8       开始时间，年月日
     * endtime8         结束时间，年月日
     * num             团队人数
     * content         添加行程
     * money           导游费用
     * other           其他备注
     * openid          用户的formid  发布此条行程的旅行社openid
     * formid          当前的formid

     * live_province    省份  中文
     * live_city        城市  中文
     */
require_once("../../include/config.inc.php");
header("Content-type:application/json; charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //备注 ：添加行程的时候content 内容以json字符串的形式保存在数据库中去

  $posttime=time();  //添加时间
  $fabu_y=date("Y");
  $fabu_ym=date("Y-m");

  $starttime = strtotime($starttime8);
  $endtime = strtotime($endtime8);

  $days=($endtime-$starttime) / (60 * 60 * 24) +1;  //行程的天数
  $jiesuanmoney = $cfg_jiesuan * $days;

  $r=$dosql->GetOne("SELECT company from pmw_agency where id=$aid");
  $company=$r['company'];

  $starttime_ymd=date("Y-m-d",$starttime);

  //获取省份数字代码
  $row = $dosql->GetOne("SELECT * FROM `pmw_cascadedata` WHERE `dataname` = '$live_province'");
  $province=$row['datavalue'];  //省份数字代码

  //获取城市数字代码
  $row = $dosql->GetOne("SELECT * FROM `pmw_cascadedata` WHERE `dataname` = '$live_city'");
  $city=$row['datavalue'];   //城市数字代码

  $sql = "INSERT INTO `#@__travel` (title,starttime,starttime_ymd,endtime,num,content,money,other,posttime,fabu_y, fabu_ym,aid,jiesuanmoney,company,days,openid,province,city,live_province,live_city) VALUES ('$title',$starttime,'$starttime_ymd',$endtime,$num,'$content',$money,'$other',$posttime,'$fabu_y','$fabu_ym',$aid,'$jiesuanmoney','$company',$days,'$openid',$province,$city,'$live_province','$live_city')";
  if($dosql->ExecNoneQuery($sql)){

    if($cfg_free_time_message=="Y"){
  //匹配用户的空闲时间，旅行社发布的空闲时间如果匹配的话 ，则向导游发送空闲时间的模板消息，每个导游一天最多发送一条消息

    Guide::Send_Remind($starttime,$title,$province,$city);
    }

    //将旅行社的formid添加进去
      Common::add_formid($openid,$formid);

    //将行程的记录添加进去
     Common::update_message('travel');

  $State = 1;
  $Descriptor = '行程发布成功！!';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);

}else{
  $State = 0;
  $Descriptor = '行程发布失败!';
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
