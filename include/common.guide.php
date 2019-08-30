<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2018-2019 phpMyWind.com
update: 2019-08-23 16:19:36
person: Gang
program: guide的所有功能代码
**************************
*/


class  Guide {

    private $openid;

    private $formid;

   function __construnction($openid,$formid){

     $this->openid = $openid;

     $this->formid = $formid;

   }

   // 导游预约旅行社的行程，向导游发送模板消息

  public  function SendGuide($aid,$gid,$id,$user_info){

    global $dosql;

    global $cfg_appid;

    global $cfg_appsecret;

    global $cfg_guide_appointment;  //导游行程模板

    //更新导游最新的formid库

   Common::add_formid($this->openid,$this->formid);

   $new_formid=Common::get_new_formid($this->openid); //从formid表里面查询还没有用过的formid，最新的给存起来


   # 更改此条行程为待确认
   # 发布状态（0，待预约，1:待确认 2:已确认（已完成），如果导游没有确认,则系统默认在出发的前一天进行确认处理
   # 3:已取消，在已完成里面不能取消）4已失效  5，未预约的时候取消行程
   $name = $user_info['guide_name'];
   $dosql->ExecNoneQuery("UPDATE `#@__travel` set state=1,gid=$gid,name='$name',openid_guide='$this->openid' where id=$id");

   $ACCESS_TOKEN = get_access_token($cfg_appid,$cfg_appsecret);//ACCESS_TOKEN

   //模板消息请求URL
   $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

   $data=$this->send_guide_message($this->openid,$user_info['company'],$user_info['names'],$user_info['tel'],$user_info['title'],$user_info['time'],$user_info['tishi'],$cfg_guide_appointment,$user_info['page'],$new_formid);

   $json = json_encode($data);//转化成json数组让微信可以接收
   $res = https_request($url, urldecode($json));//请求开始
   $res_guide = json_decode($res, true);
   $errcode_guide=$res_guide['errcode'];

   //删除已经用过的formid
   Common::del_formid($this->formid,$this->openid);

   //返回模板消息的状态码
    return $errcode_guide;

  }


    //导游预约成功提醒,给导游发送模板消息
    public function send_guide_message ($openid,$company,$name,$tel,$title,$time,$tishi,$cfg_guide_appointment,$page,$form_id)
    {
        $data = array(
            'touser' => $openid,                   //要发送给导游的openid
       'template_id' => $cfg_guide_appointment,    //改成自己的模板id，在微信后台模板消息里查看
              'page' => $page,                     //点击模板消息详情之后跳转连接
    		   'form_id' => $form_id,                   //form_id
              'data' => array(
                'keyword1' => array(
                    'value' => $company,            //旅行社公司名称
                    'color' => "#3d3d3d"
                ),
                'keyword2' => array(
                    'value' => $name,               //旅行社联系人姓名
                    'color' => "#3d3d3d"
                ),
                'keyword3' => array(
                    'value' => $tel,                //旅行社联系人电话
                    'color' => "#3d3d3d"
                ),
                'keyword4' => array(
                    'value' => $title,              //行程标题
                    'color' => "#3d3d3d"
                ),
    			'keyword5' => array(
                    'value' => $time,               //行程起始时间
                    'color' => "#173177"
                ),
    			'keyword6' => array(
                    'value' => $tishi,               //温馨提示
                    'color' => "#3d3d3d"
                )
            ),
        );
        return $data;
    }


   //将导游预约的行程保存到消息表里面去

   public function Insert_Guide_Message($user_info,$gid)
   {
     global $dosql;

     $tbnames = 'pmw_message';
     $type = 'guide';
     $messagetype='template';
     $templatetype='appointment';  //预约行程的模板消息类型
     $tent = "恭喜你，你的行程预约成功：|";
     $tent .= "旅行社名称：".$user_info['company']."|";
     $tent .= "旅行社联系人：".$user_info['names']."|";
     $tent .= "联系人电话：".$user_info['tel']."|";
     $tent .= "预约行程：".$user_info['title']."|";
     $tent .= "预约时间：".$user_info['time']."|";
     $tent .= "温馨提示：".$user_info['tishi'];
     $stitle="预约成功通知";
     $biaoti="你预约的".$user_info['time'].$user_info['title']."行程已预约成功，请尽快与旅行社联系";
     $faxtime=time();

     $banames = 'pmw_message';
     $sql = "INSERT INTO `$tbnames` (type, messagetype, templatetype, content,stitle, title, mid, faxtime) VALUES ('$type', '$messagetype', '$templatetype', '$tent', '$stitle', '$biaoti', $gid, $faxtime)";
     $dosql->ExecNoneQuery($sql);
   }


   //获取导游已经带团成功的次数和带团人数

public static  function get_guide_num($id){

    global $dosql;

    $arr=array();

    $dosql->Execute("SELECT  id FROM pmw_travel where state=2 and gid=$id");

    $team_num = $dosql->GetTotalRow();

    $r=$dosql->GetOne("SELECT SUM(num) as num FROM pmw_travel where state=2 and gid=$id");

   	if(is_array($r)){
   	 $people_num = $r['num'];
     }else{
   	 $people_num = 0;
   	}

    $arr =array(
   	       "team"=>$team_num,
   				 "people"=>intval($people_num)
    );

   return $arr;

   }

   // 空闲时间与行程匹配的话则发送模板消息提醒

  public static function Send_Remind($starttime,$title)
   {
   	// code...  计算所有导游发布的空闲时间,每天只能发送一次
     global $dosql,$cfg_free_time,$cfg_appid,$cfg_appsecret;

    $todaytime=strtotime(date("Y-m-d"));

   	$dosql->Execute("SELECT * FROM pmw_freetime where usetime <> $todaytime");

   	while($row=$dosql->GetArray()){

   		$content1= $row['content'];  //导游发布的所有的空闲时间

   		if(check_str($content1,$starttime)){  //进行匹配操作

       $gid= $row['gid'];  //导游的id

   		 $id= $row['id'];  //当前用户发布的空闲时间id

   		 $array=self::Get_Guide_Infromation($gid);

      $openids=$array['openid']; //导游的openid

   		$name=$array['name'];      //导游的姓名

   		$formids=Common::get_new_formid($openids);

   		$travel_date=date("Y-m-d",$starttime);

   		$travel_bak="亲爱的".$name."你好，与您空闲时间匹配的行程已经出现，请点击进入我的小程序查看详情";

     	$page="pages/searchDetail/index?data=".$travel_date."&search=true";

   		$travel_date=date("Y-m-d",$starttime)."开始出发";

   		self::Send_Freetime_Message($openids,$cfg_free_time,$page,$formids,$title,$travel_date,$travel_bak,$cfg_appid,$cfg_appsecret);

   	  Common::del_formid($formids,$openids);

   		//将用户今天的空闲时间更改为一次，每天只能有一次发送模板消息的机会

   		$dosql->ExecNoneQuery("UPDATE pmw_freetime SET usetime=$todaytime where id=$id");

   		}
   	}

   }


   //获取导游的信息
 public static function Get_Guide_Infromation($id)
   {
   	// code...
   	global $dosql;

   	$r=$dosql->GetOne("SELECT * from pmw_guide where id=$id");

   	return $r;
   }


   // 发送空闲时间模板消息
  public static function Send_Freetime_Message($openid,$cfg_free_time,$page,$formid,$travel_name,$travel_date,$travel_bak,$cfg_appid,$cfg_appsecret)
   {
    // code...
    $data = array(
   		 'touser' => $openid,                     //要发送给导游的openid
    'template_id' => $cfg_free_time,         //改成自己的模板id，在微信后台模板消息里查看
   			 'page' => $page,                      //点击模板消息详情之后跳转连接
   		'form_id' => $formid,                   //导游的formid
   			 'data' => array(
   				 'keyword1' => array(
   						 'value' => $travel_name,          //行程名称
   						 'color' => "#3d3d3d"
   				 ),
   				 'keyword2' => array(
   						 'value' => $travel_date,            //行程日期
   						 'color' => "#3d3d3d"
   				 ),
   				 'keyword3' => array(
   						 'value' => $travel_bak,               //行程备注
   						 'color' => "#3d3d3d"
   				 )
   		 ),
    );

    $ACCESS_TOKEN = token($cfg_appid,$cfg_appsecret);//ACCESS_TOKEN

    //模板消息请求URL
    $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

    $data = json_encode($data);//转化成json数组让微信可以接收
    $data = https_request($url, urldecode($data));//请求开始
    $data = json_decode($data, true);
    // $errcode=$data['errcode'];  //判断模板消息发送是否成功
    // return $errcode;
   }

  public function  Send_Concel_Agency($info){

  global $dosql,$cfg_cancel_guide,$cfg_appid,$cfg_appid;

   $data=self::CancelGuide($info['title'],$info['time'],$info['name'],$info['tel'],$info['reason'],$info['tishi_guide'],$this->openid,$cfg_cancel_guide,$info['page_guide'],$this->fromid);

   $ACCESS_TOKEN = get_access_token($cfg_appid,$cfg_appsecret);//ACCESS_TOKEN

   //模板消息请求URL
   $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

   $json_data_guide = json_encode($data);//转化成json数组让微信可以接收
   $res_guide = https_request($url, urldecode($json_data_guide));//请求开始
   $res_guide = json_decode($res_guide, true);
   $errcode_guide=$res_guide['errcode'];
   Common::del_formid($this->formid,$this->openid);
   return $errcode_guide;
  }


  # 旅行社取消发布的行程，给导游发送模板消息提醒
  public static function CancelGuide($title,$time,$nickname,$tel,$reason,$tishi,$openid,$cfg_cancel_guide,$page,$form_id){

  	$data = array(
  			'touser' => $openid,                   //要发送给旅行社的openid
  	'template_id' => $cfg_cancel_guide,       //改成自己的模板id，在微信后台模板消息里查看
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
  							'value' => $nickname,             //昵称（旅行社联系人的姓名）
  							'color' => "#3d3d3d"
  					),
  					'keyword4' => array(
  							'value' => $tel,              //手机号码(旅行社联系人的电话号码)
  							'color' => "#3d3d3d"
  					),
  					'keyword5' => array(
  							'value' => $reason,              //取消原因
  							'color' => "#3d3d3d"
  					),
  					'keyword6' => array(
  							'value' => $tishi,              //温馨提示
  							'color' => "#3d3d3d"
  					)
  			),
  	);
  	return $data;
  }

  //将导游接收到的撤销行程的模板消息保存起来
  public function concel_Guide_Message($info,$gid){
  global $dosql;
  $type = 'guide';
  $messagetype='template';
  $templatetype='cancel';  //取消行程的模板消息类型
  $tent = "行程已被取消：|";
  $tent .= "出发行程：".$info['title']."|";
  $tent .= "行程时间：".$info['time']."|";
  $tent .= "昵称：".$info['name']."|";
  $tent .= "取消原因：".$info['reason']."|";
  $tent .= "温馨提示：".$info['tishi_guide'];
  $stitle="行程取消通知";
  $biaoti="你好，你预约的".$info['time']."行程已被取消";
  $faxtime = $info['faxtime'];

  $banames = 'pmw_message';
  $sql = "INSERT INTO `$tbnames` (type, messagetype, templatetype, content,stitle, title, mid, faxtime) VALUES ('$type', '$messagetype', '$templatetype', '$tent', '$stitle', '$biaoti', $gid, $faxtime)";
  $dosql->ExecNoneQuery($sql);
 }



}
