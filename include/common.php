<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

function phpver($result){
	if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    $json = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function ($matches) {
        return iconv('UCS-2BE', 'UTF-8', pack('H4', $matches[1]));
    }, json_encode($result));
	   return $json;
} else {
    $json = json_encode($result, JSON_UNESCAPED_UNICODE);
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
    $new_file = $new_file.time().".{$type}"; //图片名以时间命名
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


?>
