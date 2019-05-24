<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2018-2019 phpMyWind.com
update: 2019-03-03 16:55:36
person: Gang
**************************
*/

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





























?>
