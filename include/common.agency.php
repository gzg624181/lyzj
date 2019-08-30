<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2018-2019 phpMyWind.com
update: 2019-08-24 09:46:36
person: Gang
program: agency的所有功能代码
**************************
*/


class  Agency {

    private $openid;

    private $formid;

   function __construnction($openid,$formid){

     $this->openid = $openid;

     $this->formid = $formid;

   }

   // 导游预约旅行社的行程，向旅行社发送模板消息

  public  function SendAgency($user_info){

    global $dosql;

    global $cfg_appid;

    global $cfg_appsecret;

    global $cfg_agency_remind;  //旅行社发布模板消息提醒

   $ACCESS_TOKEN = get_access_token($cfg_appid,$cfg_appsecret);//ACCESS_TOKEN

   //模板消息请求URL
   $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

   $data=$this->send_agency_message($user_info['openid_agency'],$user_info['title'],$user_info['guide_tel'],$user_info['guide_name'],$user_info['time'],$user_info['datetime'],$cfg_agency_remind,$user_info['page_agency'],$user_info['formid_agency']);

   $json = json_encode($data);//转化成json数组让微信可以接收
   $res = https_request($url, urldecode($json));//请求开始
   $res_agency = json_decode($res, true);
   $errcode_agency=$res_agency['errcode'];

   //删除已经用过的formid
   Common::del_formid($this->formid,$this->openid);

   //返回模板消息的状态码
    return $errcode_agency;

  }


  //旅行社（行程提醒）,给旅行社发送模板消息
  public function send_agency_message($openid,$title,$tel,$name,$time,$timestamp,$cfg_agency_remind,$page,$form_id)
  {
      $data = array(
          'touser' => $openid,                   //要发送给旅行社的openid
     'template_id' => $cfg_agency_remind,        //改成自己的模板id，在微信后台模板消息里查看
            'page' => $page,                     //点击模板消息详情之后跳转连接
  		   'form_id' => $form_id,                   //form_id
            'data' => array(
               'keyword1' => array(
                  'value' => $title,            //行程名称
                  'color' => "#3d3d3d"
              ),
              'keyword2' => array(
                  'value' => $tel,               //领队电话（导游电话）
                  'color' => "#3d3d3d"
              ),
              'keyword3' => array(
                  'value' => $name,                //领队姓名（导游姓名）
                  'color' => "#3d3d3d"
              ),
              'keyword4' => array(
                  'value' => $time,              //行程时间（行程的时间段）
                  'color' => "#3d3d3d"
              ),
  			'keyword5' => array(
                  'value' => $timestamp,               //预约时间（当前时间）
                  'color' => "#173177"
              )
          ),
      );
      return $data;
  }


public function insert_Agency_Message($user_info,$aid)
{
  //行程被导游预约，将向旅行社发布的消息表里面去
  $type = 'agency';
  $messagetype='template';
  $templatetype='appointment';  //预约行程的模板消息类型
  $tent = "恭喜你，你发布的行程已被预约成功：|";
  $tent .= "行程名称：".$user_info['title']."|";
  $tent .= "导游电话：".$user_info['guide_tel']."|";
  $tent .= "导游姓名：".$user_info['guide_name']."|";
  $tent .= "行程时间：".$user_info['time']."|";
  $tent .= "预约时间：".$user_info['datetime'];
  $stitle="预约成功通知";
  $faxtime = time();
  $biaoti="你发布的".$user_info['time'].$user_info['title']."行程已被导游成功预约，请尽快与导游联系";

  $banames = 'pmw_message';
  $sql = "INSERT INTO `$tbnames` (type, messagetype, templatetype, content,stitle, title, mid, faxtime) VALUES ('$type', '$messagetype', '$templatetype', '$tent', '$stitle', '$biaoti', $aid, $faxtime)";
  $dosql->ExecNoneQuery($sql);

}


   //旅行社取消行程，向旅行社自己发送模板消息

 public function Send_Concel_Agency($info)
 {
   global $dosql,$cfg_concel_agency,$cfg_appid,$cfg_appid;

   //更新旅行社的formid列表
  Common::add_formid($this->openid,$this->formid);

  $new_formid=Common::get_new_formid($this->openid); //从formid表里面查询还没有用过的formid，最新的给存起来

  $data = $this->CancelAgency($info['title'],$info['time'],$info['reason'],$info['tishi'],$this->openid,$cfg_concel_agency,$info['page'],$this->formid);

  $ACCESS_TOKEN = get_access_token($cfg_appid,$cfg_appid);//ACCESS_TOKEN

  //模板消息请求URL
  $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

  $json_data_agency = json_encode($data);//转化成json数组让微信可以接收
  $res_agency = https_request($url, urldecode($json_data_agency));//请求开始
  $res_agency = json_decode($res_agency, true);
  $errcode_agency=$res_agency['errcode'];
  //删除已经用过的formid
   Common::del_formid($this->formid,$this->openid);

   return $errcode_agency;
 }


 # 旅行社取消发布的行程，给旅行社发布行程提醒

 public function CancelAgency($title,$time,$reason,$tishi,$openid,$cfg_cancel_guide,$page,$form_id){

 	$data = array(
 			'touser' => $openid,                   //要发送给旅行社的openid
 	'template_id' =>$cfg_cancel_guide,       //改成自己的模板id，在微信后台模板消息里查看
 				'page' => $page,                     //点击模板消息详情之后跳转连接
 		 'form_id' => $form_id,                   //form_id
 				'data' => array(
 					'keyword1' => array(
 							'value' => $title,             //出发行程
 							'color' => "#3d3d3d"
 					),
 					'keyword2' => array(
 							'value' => $time,               //行程时间
 							'color' => "#3d3d3d"
 					),
 					'keyword3' => array(
 							'value' => $reason,             //取消原因
 							'color' => "#3d3d3d"
 					),
 					'keyword4' => array(
 							'value' => $tishi,              //温馨提示
 							'color' => "#3d3d3d"
 					)
 			),
 	);
 	return $data;

 }

  //将旅行社注撤销行程的模板消息保存起来
public function concel_Agency_Message($info,$aid)
{
  global $dosql;
  $type = 'agency';
  $messagetype='template';
  $templatetype='cancel';  //取消行程的模板消息类型
  $tent = "行程已取消成功：|";
  $tent .= "出发行程：".$info['title']."|";
  $tent .= "行程时间：".$info['time']."|";
  $tent .= "取消原因：".$info['reason']."|";
  $tent .= "温馨提示：".$info['tishi'];
  $stitle="行程取消通知";
  $biaoti="你好，你发布的".$info['time']."行程已取消";
  $faxtime = $info['faxtime'];

  $tbnames = 'pmw_message';
  $sql = "INSERT INTO `$tbnames` (type, messagetype, templatetype, content,stitle, title, mid, faxtime) VALUES ('$type', '$messagetype', '$templatetype', '$tent', '$stitle', '$biaoti', $aid, $faxtime)";
  $dosql->ExecNoneQuery($sql);
}




}
