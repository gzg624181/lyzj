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

#转json格式数据
function phpvers($result){
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

 # 旅行社添加行程

  function add_travel($getarray){
    //判断传了几条行程
		$num=(count($getarray) - 9) / 3;
		if($num == 1){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
                 )
			);
	  }elseif($num == 2){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" => strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
				 				 )
			);
		}elseif($num == 3){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" =>  strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
					      ),
				"2"=>array(
					"jinName" =>  $getarray['jinName3'],
				"starttime" =>  strtotime($getarray['starttime3']),
						 "days" =>  $getarray['days3']
				 		 )
			);
		}elseif($num == 4){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" =>  strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
					      ),
				"2"=>array(
					"jinName" =>  $getarray['jinName3'],
				"starttime" =>  strtotime($getarray['starttime3']),
						 "days" =>  $getarray['days3']
					 ),
				"3"=>array(
					"jinName" =>  $getarray['jinName4'],
				"starttime" => strtotime($getarray['starttime4']),
						 "days" =>  $getarray['days4']
								 )
			);
		}elseif($num==5){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" =>  strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
					      ),
				"2"=>array(
					"jinName" =>  $getarray['jinName3'],
				"starttime" =>  strtotime($getarray['starttime3']),
						 "days" =>  $getarray['days3']
					 ),
				"3"=>array(
					"jinName" =>  $getarray['jinName4'],
				"starttime" =>  strtotime($getarray['starttime4']),
						 "days" =>  $getarray['days4']
					 ),
				"4"=>array(
					"jinName" =>  $getarray['jinName5'],
				"starttime" =>  strtotime($getarray['starttime5']),
						 "days" =>  $getarray['days5']
				         )
			);
		}elseif($num==6){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" =>  strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
					      ),
				"2"=>array(
					"jinName" =>  $getarray['jinName3'],
				"starttime" =>  strtotime($getarray['starttime3']),
						 "days" =>  $getarray['days3']
					 ),
				"3"=>array(
					"jinName" =>  $getarray['jinName4'],
				"starttime" =>  strtotime($getarray['starttime4']),
						 "days" =>  $getarray['days4']
					 ),
				"4"=>array(
					"jinName" =>  $getarray['jinName5'],
				"starttime" =>  strtotime($getarray['starttime5']),
						 "days" =>  $getarray['days5']
					 ),
				"5"=>array(
					"jinName" =>  $getarray['jinName6'],
				"starttime" =>  strtotime($getarray['starttime6']),
						 "days" =>  $getarray['days6']
				)
			);
		}elseif($num==7){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" => strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" =>  strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
					      ),
				"2"=>array(
					"jinName" =>  $getarray['jinName3'],
				"starttime" =>  strtotime($getarray['starttime3']),
						 "days" =>  $getarray['days3']
					 ),
				"3"=>array(
					"jinName" =>  $getarray['jinName4'],
				"starttime" =>  strtotime($getarray['starttime4']),
						 "days" =>  $getarray['days4']
					 ),
				"4"=>array(
					"jinName" =>  $getarray['jinName5'],
				"starttime" =>  strtotime($getarray['starttime5']),
						 "days" =>  $getarray['days5']
					 ),
				"5"=>array(
					"jinName" =>  $getarray['jinName6'],
				"starttime" =>  strtotime($getarray['starttime6']),
						 "days" =>  $getarray['days6']
				),
				"6"=>array(
					"jinName" =>  $getarray['jinName7'],
				"starttime" =>  strtotime($getarray['starttime7']),
						 "days" =>  $getarray['days7']
				       )
			);
		}elseif($num==8){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" => strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
					      ),
				"2"=>array(
					"jinName" =>  $getarray['jinName3'],
				"starttime" =>  strtotime($getarray['starttime3']),
						 "days" =>  $getarray['days3']
					 ),
				"3"=>array(
					"jinName" =>  $getarray['jinName4'],
				"starttime" =>  strtotime($getarray['starttime4']),
						 "days" =>  $getarray['days4']
					 ),
				"4"=>array(
					"jinName" =>  $getarray['jinName5'],
				"starttime" =>  strtotime($getarray['starttime5']),
						 "days" =>  $getarray['days5']
					 ),
				"5"=>array(
					"jinName" =>  $getarray['jinName6'],
				"starttime" =>  strtotime($getarray['starttime6']),
						 "days" =>  $getarray['days6']
				),
				"6"=>array(
					"jinName" =>  $getarray['jinName7'],
				"starttime" =>  strtotime($getarray['starttime7']),
						 "days" =>  $getarray['days7']
					 ),
				 "7"=>array(
					 "jinName" =>  $getarray['jinName8'],
 				"starttime" =>  strtotime($getarray['starttime8']),
 						 "days" =>  $getarray['days8']
			 				     )
			);
		}elseif($num==9){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" =>  strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
					      ),
				"2"=>array(
					"jinName" =>  $getarray['jinName3'],
				"starttime" =>  strtotime($getarray['starttime3']),
						 "days" =>  $getarray['days3']
					 ),
				"3"=>array(
					"jinName" =>  $getarray['jinName4'],
				"starttime" => strtotime($getarray['starttime4']),
						 "days" =>  $getarray['days4']
					 ),
				"4"=>array(
					"jinName" =>  $getarray['jinName5'],
				"starttime" =>  strtotime($getarray['starttime5']),
						 "days" =>  $getarray['days5']
					 ),
				"5"=>array(
					"jinName" =>  $getarray['jinName6'],
				"starttime" =>  strtotime($getarray['starttime6']),
						 "days" =>  $getarray['days6']
				),
				"6"=>array(
					"jinName" =>  $getarray['jinName7'],
				"starttime" =>  strtotime($getarray['starttime7']),
						 "days" =>  $getarray['days7']
					 ),
				 "7"=>array(
					 "jinName" =>  $getarray['jinName8'],
 				"starttime" =>   strtotime($getarray['starttime8']),
 						 "days" =>   $getarray['days8']
					 ),
				"8"=>array(
					"jinName" =>  $getarray['jinName9'],
			 "starttime" =>   strtotime($getarray['starttime9']),
						"days" =>   $getarray['days9']
										)
			);
		}elseif($num==10){
			$content=array(
				"0"=>array(
					"jinName" =>  $getarray['jinName1'],
				"starttime" =>  strtotime($getarray['starttime1']),
						 "days" =>  $getarray['days1']
								 ),
				"1"=>array(
					"jinName" =>  $getarray['jinName2'],
				"starttime" =>  strtotime($getarray['starttime2']),
						 "days" =>  $getarray['days2']
								),
				"2"=>array(
					"jinName" =>  $getarray['jinName3'],
				"starttime" =>  strtotime($getarray['starttime3']),
						 "days" =>  $getarray['days3']
					 ),
				"3"=>array(
					"jinName" =>  $getarray['jinName4'],
				"starttime" => strtotime($getarray['starttime4']),
						 "days" =>  $getarray['days4']
					 ),
				"4"=>array(
					"jinName" =>  $getarray['jinName5'],
				"starttime" =>  strtotime($getarray['starttime5']),
						 "days" =>  $getarray['days5']
					 ),
				"5"=>array(
					"jinName" =>  $getarray['jinName6'],
				"starttime" =>  strtotime($getarray['starttime6']),
						 "days" =>  $getarray['days6']
				),
				"6"=>array(
					"jinName" =>  $getarray['jinName7'],
				"starttime" =>  strtotime($getarray['starttime7']),
						 "days" =>  $getarray['days7']
					 ),
				 "7"=>array(
					 "jinName" =>  $getarray['jinName8'],
				"starttime" =>   strtotime($getarray['starttime8']),
						 "days" =>   $getarray['days8']
					 ),
				"8"=>array(
					"jinName" =>  $getarray['jinName9'],
			 "starttime" =>   strtotime($getarray['starttime9']),
						"days" =>   $getarray['days9']
					),
				"9"=>array(
					"jinName" =>  $getarray['jinName10'],
			 "starttime" =>   strtotime($getarray['starttime10']),
						"days" =>   $getarray['days10']
					      	)
			);
		}
    //转为json格式的数据
		$json=phpvers($content);
		return $json;
	}



























?>
