<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

function phpver($result){
	if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    $json = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function ($matches) {
        return iconv('UCS-2BE', 'UTF-8', pack('H4', $matches[1]));
    }, json_encode($result));
	   return $json;
} else {
    $json = json_encode($result, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    return $json;
}
}

//base64图片转码

function base64_image_content($base64_image_content,$path){
   //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/',
    $base64_image_content, $result)){ //后缀
    $type = $result[2]; //创建文件夹，以年月日
    $new_file = $path.date('Ymd',time())."/";
    if(!file_exists($new_file)){ //检查是否有该文件夹，如果没有就创建，并给予最高权限
    mkdir($new_file, 0700);
    }
    $new_file = $new_file.time().rand(111,999).".{$type}"; //图片名以时间命名
    //保存为文件
    if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
    //返回这个图片的路径
    return $new_file;
   }else{
  return false;
  }}else{ return false; }
 }


//post传值
	function post($url, $data, $proxy = null, $timeout = 20) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //在HTTP请求中包含一个"User-Agent: "头的字符串。
	curl_setopt($curl, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。
	curl_setopt($curl, CURLOPT_POST, true); //发送一个常规的Post请求
	curl_setopt($curl,  CURLOPT_POSTFIELDS, $data);//Post提交的数据包
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); //启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //文件流形式
	curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); //设置cURL允许执行的最长秒数。
	$content = curl_exec($curl);
	curl_close($curl);
	unset($curl);
	return $content;
	}

	//POST请求函数
	function https_request($url,$data = null){
			$curl = curl_init();

			curl_setopt($curl,CURLOPT_URL,$url);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);

			if(!empty($data)){//如果有数据传入数据
					curl_setopt($curl,CURLOPT_POST,1);//CURLOPT_POST 模拟post请求
					curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//传入数据
			}

			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			$output = curl_exec($curl);
			curl_close($curl);

			return $output;
	}

//判断登陆的设备是安卓还是苹果
function get_device_type(){
 $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
 $type = 'other';
 if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
  $type = "1";
 }
 if(strpos($agent, 'android')){
  $type = "0";
 }
 return $type;
}
//获取当前ip的城市
function get_city($ip){
		$url = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
    $ret = https_request($url);
    $jsonAddress = json_decode($ret,true);
		if($jsonAddress['code']==0){
      return $jsonAddress['data']['country']."-".$jsonAddress['data']['region']."-".$jsonAddress['data']['city'];
    }else{
      return "地址未知";
    }
}





//导游预约成功提醒,给导游发送模板消息
function SendGuide($openid,$company,$name,$tel,$title,$time,$tishi,$cfg_guide_appointment,$page,$form_id)
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


//旅行社（行程提醒）,给旅行社发送模板消息
function SendAgency($openid,$title,$tel,$name,$time,$timestamp,$cfg_agency_remind,$page,$form_id)
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


# 旅行社取消发布的行程，给旅行社发布行程提醒

function CancelAgency($title,$time,$reason,$tishi,$openid,$cfg_cancel_guide,$page,$form_id){

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

# 旅行社取消发布的行程，给导游发送模板消息提醒

function CancelGuide($title,$time,$nickname,$tel,$reason,$tishi,$openid,$cfg_cancel_guide,$page,$form_id){

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

//匹配测试
function check_str($str, $substr)
{
 $nums=substr_count($str,$substr);
 if ($nums>=1)
 {
	return true;
 }
 else
 {
	return false;
 }
}

//获取旅行社发布的所有已完成的行程的月份

function get_months_success($id,$y){

global $dosql;

$dosql->Execute("SELECT complete_ym as time FROM pmw_travel where aid=$id and complete_y='$y' group by complete_ym");
while($show=$dosql->GetArray()){
	$return[]=$show;
}

return $return;

}


//获取旅行社已经发布成功的行程的状态
function get_agency_state($id,$y,$m){

global $dosql;

$r = $dosql->GetOne("SELECT SUM(jiesuanmoney) AS money,SUM(num) as teamnumber,SUM(days) as days,Settlement  FROM pmw_travel  where aid=$id and state=2 and complete_y='$y' and complete_ym='$m'");

$return =$r;

return $return;
}


//获取导游已经带团成功的次数和带团人数


function get_guide_num($id){

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


//获取旅行社已经发布成功的次数和带团人数


function get_agency_num($id){

 global $dosql;

 $arr=array();

 $dosql->Execute("SELECT  id FROM pmw_travel where state=2 and aid=$id");

 $team_num = $dosql->GetTotalRow();

 $r=$dosql->GetOne("SELECT SUM(num) as num FROM pmw_travel where state=2 and aid=$id");

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

//获取关注的微信小程序的openid

//获取微信小程序openid
function Openid($code,$appid,$appsecret){
  $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code=' . $code . '&grant_type=authorization_code';
  $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl
  $json = json_decode($info);//对json数据解码
  $arr = get_object_vars($json);
  $openid = $arr['openid'];
  return $openid;
}


//用户购票成功之后，发送购票成功的模板消息

 function paysuccess($openid,$cfg_paysuccess,$page,$form_id,$jingquname,$typename,$nums,$totalamount,$posttime,$tishi,$cfg_appid,$cfg_appsecret)
{
	// code...
	$data = array(
			'touser' => $openid,                     //要发送给购票用户的openid
	'template_id' => $cfg_paysuccess,            //改成自己的模板id，在微信后台模板消息里查看
				'page' => $page,                      //点击模板消息详情之后跳转连接
		 'form_id' => $form_id,                   //购票用户的formid
				'data' => array(
					'keyword1' => array(
							'value' => $jingquname,          //景区名称
							'color' => "#3d3d3d"
					),
					'keyword2' => array(
							'value' => $typename,            //票务类型
							'color' => "#3d3d3d"
					),
					'keyword3' => array(
							'value' => $nums,               //购买张数
							'color' => "#3d3d3d"
					),
					'keyword4' => array(
							'value' => $totalamount,        //购票总金额
							'color' => "#3d3d3d"
					),
					'keyword5' => array(
							'value' => $posttime,        //购买时间
							'color' => "#3d3d3d"
					),
					'keyword6' => array(
							'value' => $tishi,        //温馨提示
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


//用户购票成功之后，向购票管理员发送模板消息

 function ticketsuccess($openid,$cfg_ticketsuccess,$page,$form_id,$jingquname,$typename,$usetime,$nums,$type,$totalamount,$contactname,$contacttel,$paytype,$posttime,$cfg_appid,$cfg_appsecret)
{
	// code...
	$data = array(
			'touser' => $openid,                     //要发送给购票管理员的openid
	'template_id' => $cfg_ticketsuccess,         //改成自己的模板id，在微信后台模板消息里查看
				'page' => $page,                      //点击模板消息详情之后跳转连接
		 'form_id' => $form_id,                   //购票用户的formid
				'data' => array(
					'keyword1' => array(
							'value' => $jingquname,          //景区名称
							'color' => "#3d3d3d"
					),
					'keyword2' => array(
							'value' => $typename,            //票务类型
							'color' => "#3d3d3d"
					),
					'keyword3' => array(
							'value' => $usetime,               //出发日期
							'color' => "#3d3d3d"
					),
					'keyword4' => array(
							'value' => $nums,                 //订票数量
							'color' => "#3d3d3d"
					),
					'keyword5' => array(
							'value' => $type,               //订单类型（用户的类型）
							'color' => "#3d3d3d"
					),
					'keyword6' => array(
							'value' => $totalamount,          //订单总金额
							'color' => "#3d3d3d"
					),
					'keyword7' => array(
							'value' => $contactname,          //联系人姓名
							'color' => "#3d3d3d"
					),
					'keyword8' => array(
							'value' => $contacttel,          //联系人手机
							'color' => "#3d3d3d"
					),
					'keyword9' => array(
							'value' => $paytype,              //支付方式
							'color' => "#3d3d3d"
					),
					'keyword10' => array(
							'value' => $posttime,             //支付时间
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

//获取微信小程序 access_token
function token($appid,$appsecret){
  $arr = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);  //去除对象里面的斜杠
  $result = json_decode($arr, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
  //logs('log.txt',$result);
  $access_token = $result['access_token'];
  return $access_token;
}

//获取导游或者旅行社的信息
function get_information($id,$type)
{
	// code...
	global $dosql;
	$data=array();
	if($type=="agency"){
		$r=$dosql->GetOne("SELECT * FROM pmw_agency where id=$id");
		if(is_array($r)){
			$data=$r;
		}
	}elseif($type=="guide"){
		$r=$dosql->GetOne("SELECT * FROM pmw_guide where id=$id");
		if(is_array($r)){
			$data=$r;
		}
	}
  return $data;
}

//获取当前购票成功的id
 function get_orderid($did,$posttime)
{
	// code...
  global $dosql;

	$r=$dosql->GetOne("SELECT id FROM pmw_order where did=$did and posttime=$posttime");

	$id=$r['id'];

	return $id;
}

//获取购票管理员的openid和formid

 function get_openid_formid()
{
	// code...
  global $dosql;
	$r=$dosql->GetOne("SELECT openid FROM pmw_members where sets=1");
	return $r;
}

//保存用户的formid，同时将过期的formid删除掉

function add_formid($openid,$formid)
{
	// code...  将所有的用户的最新的formid保存到数据库中来

  global $dosql;

  $guoqi_time = strtotime("+7 days");  //设置7天过期时间

	$dosql->ExecNoneQuery("INSERT INTO `#@__formid` (formid,openid,guoqi_time) VALUES ('$formid','$openid',$guoqi_time)");

}

//当用户使用formid的时候，找出最新的fromid提供给用户
 # 1.判断当前用户的所有的fromid是否已经过期
 # 2.将最后一条没有过期的formid拿出来

 function get_new_formid($openid)
 {
 	// code...
  global $dosql;

	$formid="";

	$now=time();  //当前的时间戳

	//删除formid表里面 openid是空的数据 ，同时删除 formid为本机测试产生的数据 the formId is a mock one
	 $dosql->ExecNoneQuery("DELETE FROM `#@__formid` where openid ='' or formid='the formId is a mock one'");

	//删除七天的过期的formid
	$dosql->ExecNoneQuery("DELETE FROM `#@__formid` where guoqi_time <= $now and openid='$openid'");

	$k=$dosql->GetOne("SELECT MIN(id) as id	FROM `#@__formid` where openid='$openid'");

	$ids=$k['id'];

	$r=$dosql->GetOne("SELECT formid FROM `#@__formid` where id=$ids");


  if(is_array($r)){

	$formid=$r['formid'];

  }

	return $formid;
 }

 //用户使用完毕formid之后，删除已经已经使用过的formid

  function del_formid($formid,$openid)
 {
 	// code... 删除使用完毕的formid

	global $dosql;

	$dosql->ExecNoneQuery("DELETE FROM `#@__formid` where formid='$formid' and openid='$openid'");

 }

 //
 //生成小程序二维码
function save_erweima($access_token,$xiaochengxu_path,$save_path,$url,$id,$time,$poster) {
		$post_url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$access_token";
		$width = '430';
		$scene=$id."&".$time."&".$poster;
		$post_data='{"page":"'.$xiaochengxu_path.'","width":'.$width.',"scene":"'.$scene.'"}';
		$opts = array('http' =>
				array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/json',
						'content' => $post_data
				)
		);
		$context = stream_context_create($opts);
		$result = file_get_contents($post_url, false, $context);
		$file_path = $save_path;
		$bytes = file_put_contents($file_path, $result);
		return $url;
}
?>
