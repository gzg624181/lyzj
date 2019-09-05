<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');





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

































 ?>
