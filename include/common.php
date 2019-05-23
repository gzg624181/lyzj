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


 //发送验证码（如果需要发送不同的验证码，只需要更改短信模板id）
function sendcode($cfg_message_id,$cfg_message_pwd,$cfg_message_signid,$cfg_message_regid,$telephone){

  $content=rand(100000,999999);                  //发送的验证码
  $data['Account'] = $cfg_message_id;            //短信接口ID
  $data['Pwd'] 	 = $cfg_message_pwd;             //短信接口密码
  $data['Content'] = $content;                   //发送的短信内容
  $data['SignId']	 = $cfg_message_signid;         //签名Id
 //发送不同的验证码，只需要更改短信模板
  $data['TemplateId']	 = $cfg_message_regid;      //短信模板ID
  $data['Mobile']	 = $telephone;                  //接收短信的号码
  $url="http://api.feige.ee/SmsService/Template";

  $res=post($url,$data);
  $datas= json_decode($res,true);
  $Code= $datas["Code"];
  $Message= $datas["Message"];
  if($Code == 0 && $Message=="OK"){
  //判断验证码发送成功的时候、
  return $content;
  }else{
  return 0;
  }
}

//添加账户明细  提成使用方法   根据下线会员来判断是否有上线会员 ，如果有上线会员则充值的时候返水给上线会员的返水金额
// mid  当前下线会员的id  bcode 上线会员的ucode    ssid上线会员的id

	function ticheng($money,$mid,$bcode,$gid,$ucode){
		global $dosql;
		$tbnames="pmw_record";
		$tbname="pmw_members";
		//下线用户给上线用户提成为一定的比率

     //判断推荐码是否书写正确
		$s=$dosql->GetOne("SELECT * from `#@__members` where ucode=$bcode");  //推荐人的ucode
		if(is_array($s)){
		//一级代理的提成比例
		$k=$dosql->GetOne("SELECT zsticheng,ejticheng from `#@__game` where id=$gid");  //直属代理的提成比例
		$zs=$k['zsticheng'];
		$zsmoney="+".sprintf('%.2f',$money * $zs /100);
		$ej=$k['ejticheng'];
		$ejmoney="+".sprintf('%.2f',$money * $ej /100); //二级代理的提成（推荐人的推荐人）
		$dosql->ExecNoneQuery("UPDATE `$tbname` SET `money`=`money` + '$zsmoney' WHERE `ucode`= $bcode");
		$ssid=$s['id'];   //推荐人的id
		$time_list=time();
		$randnumber=rand(100000,999999);
		$chargeorder=date("YmdHis").$randnumber;
		$sql = "INSERT INTO `$tbnames` (money_list,types,mid,time_list,chargeorder,xid,gid,leibie,xcode,content,money) VALUES ('$zsmoney','ticheng',$ssid,$time_list,'$chargeorder','$mid',$gid,'直属代理','$ucode','提成','$money')";
		$dosql->ExecNoneQuery($sql);

		//二级代理的提成
		$r=$dosql->GetOne("SELECT * from `#@__daili` where zscode=$bcode and ejcode=$ucode");  //查找二级代理
		if(is_array($r)){  //如果有二级代理人的时候

		$ucodes=$r['ucode'];
		$dosql->ExecNoneQuery("UPDATE `$tbname` SET `money`=`money` + '$ejmoney' WHERE `ucode`= $ucodes");
		$ejid=$r['uid'];   //推荐人的id
		$time_list=time();
		$randnumber=rand(100000,999999);
		$chargeorder=date("YmdHis").$randnumber;
		$sql = "INSERT INTO `$tbnames` (money_list,types,mid,time_list,chargeorder,xid,gid,leibie,xcode,content,money) VALUES ('$ejmoney','ticheng',$ejid,$time_list,'$chargeorder','$mid',$gid,'二级代理','$ucode','提成','$money')";
		$dosql->ExecNoneQuery($sql);
	  }
	  }
	}

//发送忘记密码验证码（如果需要发送不同的验证码，只需要更改短信模板id）
function forgetpassword_sendcode($cfg_message_id,$cfg_message_pwd,$cfg_message_signid,$cfg_forgetpassword,$telephone){

 $content=rand(100000,999999);                  //发送的验证码
 $data['Account'] = $cfg_message_id;            //短信接口ID
 $data['Pwd'] 	 = $cfg_message_pwd;             //短信接口密码
 $data['Content'] = $content;                   //发送的短信内容
 $data['SignId']	 = $cfg_message_signid;         //签名Id
//发送不同的验证码，只需要更改短信模板
 $data['TemplateId']	 = $cfg_forgetpassword;      //短信模板ID
 $data['Mobile']	 = $telephone;                  //接收短信的号码
 $url="http://api.feige.ee/SmsService/Template";

 $res=post($url,$data);
 $datas= json_decode($res,true);
 $Code= $datas["Code"];
 $Message= $datas["Message"];
 if($Code == 0 && $Message=="OK"){
 //判断验证码发送成功的时候、
 return $content;
 }else{
 return 0;
 }
}

//获取验证码
function getcode(){
  $content=rand(10000,99999);
  return $content;
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


  //添加账户明细   （充值 recharge   提现 take_money    返水  back_money   订单号chargeorder）

   function records($money_list,$types,$mid,$chargeorder){
		global $dosql;
		$tbnames="pmw_record";
		$time_list=time();
		$sql = "INSERT INTO `$tbnames` (money_list,types,mid,time_list,chargeorder) VALUES ('$money_list','$types',$mid,$time_list,'$chargeorder')";
    $dosql->ExecNoneQuery($sql);
	 }

//给下线转账
  /*  $id转账会员 id
	    $transfer_money： 转账金额
			$transfer_bid：   转账到下线会员的uid
			$transfer_uid：   转账会员uid
	*/
  function transfer($id,$transfer_bid,$transfer_uid,$transfer_money){

  #将会员的金额转账到下线里面去
  global $dosql;
	$tbname = "pmw_members";
	$dosql->ExecNoneQuery("UPDATE $tbname SET money = money - $transfer_money where id=$id");

  #更新下线会员的账户金额
	$dosql->ExecNoneQuery("UPDATE $tbname SET money = money + $transfer_money where ucode=$transfer_bid");

	#将转账记录数据保存到转账表的数据库中
  $randnumber=rand(100000,999999);
  $transfer_order=date("YmdHis").$randnumber;
  $tbnames='pmw_transfer';
  $transfer_time=time();
  $sql = "INSERT INTO `$tbnames` (mid, transfer_money, transfer_uid, transfer_bid, transfer_time, transfer_order) VALUES ($id, '$transfer_money', '$transfer_uid', '$transfer_bid' ,$transfer_time,'$transfer_order')";
  $dosql->ExecNoneQuery($sql);
	}


	//获取当前ip的城市
	function get_city($ip){
			$url = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
	    $ret =  https_request($url);
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

//游戏玩法      $code  开奖号码  $sum 开奖号码求和   $type 选择下注的类型
// 加拿大28,   1.单注【大小单双】   2.组合【小单，小双，大单，大双】  3.极小值(0-5)，极大值(22-27)。
//            4.28个号码定位   5.豹子，对子，顺子   6.龙，虎，豹)

  function canada28($code,$sum,$type){

		if($type == 0){
				if($sum>=14){
					$result=  "大/";
				}elseif($sum<=13){
          $result= "小/";
				}
				if($sum % 2==1){
					if(isset($result)){
 				   $result .= "单/";
 			   }else{
 				   $result = "单/";
 			     }
				}elseif($sum % 2==0){
					if(isset($result)){
 				   $result .= "双/";
 			   }else{
 				   $result = "双/";
 			     }
				}
				if($sum>=14 && $sum % 2 ==0){
					if(isset($result)){
 					$result .= "大双/";
 				}else{
 					$result = "大双/";
 					}
 			 }elseif($sum>=14 && $sum % 2 ==1){
				 if(isset($result)){
				 $result .= "大单/";
			 }else{
				 $result = "大单/";
				 }
 			 }elseif($sum<=13 && $sum % 2 ==0){
				 if(isset($result)){
				 $result .= "小双/";
			 }else{
				 $result = "小双/";
				 }
 			 }elseif($sum<=13 && $sum % 2 ==1){
				 if(isset($result)){
				 $result .= "小单/";
			 }else{
				 $result = "小单/";
				 }
 			 }
			 if($sum<=5){
				 if(isset($result)){
				 $result .= "极小值/";
			 }else{
				 $result = "极小值/";
				 }
			 }elseif($sum>=22 && $sum<=27){
				 if(isset($result)){
				 $result .= "极大值/";
			 }else{
				 $result = "极大值/";
				 }
 			}
			  return $result;
		 }elseif($type == 1){
			  switch($sum){
					case 0:
					$sum='0';
					break;
					case 1:
					$sum='a';
					break;
					case 2:
					$sum='b';
					break;
					case 3:
					$sum='c';
					break;
					case 4:
					$sum='d';
					break;
					case 5:
					$sum='e';
					break;
					case 6:
					$sum='f';
					break;
					case 7:
					$sum='g';
					break;
					case 8:
					$sum='h';
					break;
					case 9:
					$sum='i';
					break;
					case 10:
					$sum='j';
					break;
					case 11:
					$sum='k';
					break;
					case 12:
					$sum='l';
					break;
					case 13:
					$sum='m';
					break;
					case 14:
					$sum='n';
					break;
					case 15:
					$sum='o';
					break;
					case 16:
					$sum='p';
					break;
					case 17:
					$sum='q';
					break;
					case 18:
					$sum='r';
					break;
					case 19:
					$sum='s';
					break;
					case 20:
					$sum='t';
					break;
					case 21:
					$sum='u';
					break;
					case 22:
					$sum='v';
					break;
					case 23:
					$sum='w';
					break;
					case 24:
					$sum='x';
					break;
					case 25:
					$sum='y';
					break;
					case 26:
					$sum='z';
					break;
					case 27:
					$sum='@';
					break;
				}
        return $sum."/";
		 }elseif($type ==2){
			 $arr= str_split($code);
			 $newarr = bubbleSort($arr);  //开奖号码从小到大排列

			 if($arr[0]==$arr[1] && $arr[1]==$arr[2]){
				 if(isset($result)){
				 $result .= "豹子/";
			   }else{
				 $result = "豹子/";
			   }
			 }

			 if(($newarr[2]-$newarr[1] == $newarr[1]-$newarr[0] && $newarr[2]-$newarr[1]==1) ||
          ($newarr[0]==0 && $newarr[1]==1 && $newarr[1]==9) ||
					($newarr[0]==0 && $newarr[1]==8 && $newarr[1]==9) ){
				 if(isset($result)){
 				$result .= "顺子/";
 				}else{
 				$result = "顺子/";
 				}
 			}

 			if(($newarr[0]==$newarr[1] && $newarr[1]!=$newarr[2]) ||  ($newarr[2]==$newarr[1] && $newarr[1]!=$newarr[0])){
				if(isset($result)){
			   $result .= "对子/";
			 }else{
			   $result = "对子/";
			 }
  		   }

			if($sum % 3==0){
				if(isset($result)){
				 $result .= "龙/";
			 }else{
				 $result = "龙/";
			   }
	 		}elseif($sum % 3==1){
				if(isset($result)){
				 $result .= "虎/";
			 }else{
				 $result = "虎/";
				 }
	 		}elseif($sum % 3==2){
				if(isset($result)){
				 $result .= "豹/";
			 }else{
				 $result = "豹/";
				 }
	 		}
			return $result;
		 }

	}
	//当选取定点号码的时候
	 function tochange($ml,$lb){
		 if($ml==1){
		 switch($lb){
			 case '0':
			 $sum= 0;
			 break;
			 case 'a':
			 $sum=1;
			 break;
			 case 'b':
			 $sum=2;
			 break;
			 case 'c':
			 $sum=3;
			 break;
			 case 'd':
			 $sum=4;
			 break;
			 case 'e':
			 $sum=5;
			 break;
			 case 'f':
			 $sum=6;
			 break;
			 case 'g':
			 $sum=7;
			 break;
			 case 'h':
			 $sum=8;
			 break;
			 case 'i':
			 $sum=9;
			 break;
			 case 'j':
			 $sum=10;
			 break;
			 case 'k':
			 $sum=11;
			 break;
			 case 'l':
			 $sum=12;
			 break;
			 case 'm':
			 $sum=13;
			 break;
			 case 'n':
			 $sum=14;
			 break;
			 case 'o':
			 $sum=15;
			 break;
			 case 'p':
			 $sum=16;
			 break;
			 case 'q':
			 $sum=17;
			 break;
			 case 'r':
			 $sum=18;
			 break;
			 case 's':
			 $sum= 19;
			 break;
			 case 't':
			 $sum=20;
			 break;
			 case 'u':
			 $sum=21;
			 break;
			 case 'v':
			 $sum=22;
			 break;
			 case 'w':
			 $sum=23;
			 break;
			 case 'x':
			 $sum=24;
			 break;
			 case 'y':
			 $sum=25;
			 break;
			 case 'z':
			 $sum=26;
			 break;
			 case '@':
			 $sum=27;
			 break;
		 }
		 return $sum;
	 }
	 }

	 //当选取定点转换为字母
		function getzimus($lb){
			 if($lb=='0'){
				 return '0';
			 }elseif($lb=='1'){
				 return 'a';
			 }elseif($lb=='2'){
				 return 'b';
			 }elseif($lb=='3'){
				 return 'c';
			 }elseif($lb=='4'){
				 return 'd';
			 }elseif($lb=='5'){
				 return 'e';
			 }elseif($lb=='6'){
				 return 'f';
			 }elseif($lb=='7'){
				 return 'g';
			 }elseif($lb=='8'){
				 return 'h';
			 }elseif($lb=='9'){
				 return 'i';
			 }elseif($lb=='10'){
				 return 'j';
			 }elseif($lb=='11'){
				 return 'k';
			 }elseif($lb=='12'){
				 return 'l';
			 }elseif($lb=='13'){
				 return 'm';
			 }elseif($lb=='14'){
				 return 'n';
			 }elseif($lb=='15'){
				 return 'o';
			 }elseif($lb=='16'){
				 return 'p';
			 }elseif($lb=='17'){
				 return 'q';
			 }elseif($lb=='18'){
				 return 'r';
			 }elseif($lb=='19'){
				 return 's';
			 }elseif($lb=='20'){
				 return 't';
			 }elseif($lb=='21'){
				 return 'u';
			 }elseif($lb=='22'){
				 return 'v';
			 }elseif($lb=='23'){
				 return 'w';
			 }elseif($lb=='24'){
				 return 'x';
			 }elseif($lb=='25'){
				 return 'y';
			 }elseif($lb=='26'){
				 return 'z';
			 }elseif($lb=='27'){
				 return '@';
			 }
	}

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


// 冒泡排序
		function bubbleSort($arr)
		{
		  $len=count($arr);
		  //该层循环控制 需要冒泡的轮数
		  for($i=1;$i<$len;$i++)
		  { //该层循环用来控制每轮 冒出一个数 需要比较的次数
		    for($k=0;$k<$len-$i;$k++)
		    {
		       if($arr[$k]>$arr[$k+1])
		        {
		            $tmp=$arr[$k+1];
		            $arr[$k+1]=$arr[$k];
		            $arr[$k]=$tmp;
		        }
		    }
		  }
		  return $arr;
		}

		//生成开奖号码的结果 【小单，小双，大单，大双】

		function results($sum){
			if($sum>=14 && $sum % 2 ==0){
			 return "(大双)";
		 }elseif($sum>=14 && $sum % 2 ==1){
			 return "(大单)";
		 }elseif($sum<=13 && $sum % 2 ==0){
				return "(小双)";
		 }elseif($sum<=13 && $sum % 2 ==1){
			 return "(小单)";
		 }
		}


		function jieguo($sum,$code){

					if($sum>=14){
						$result=  "大/";
					}elseif($sum<=13){
						$result= "小/";
					}
					if($sum % 2==1){
						if(isset($result)){
						 $result .= "单/";
					 }else{
						 $result = "单/";
						 }
					}elseif($sum % 2==0){
						if(isset($result)){
						 $result .= "双/";
					 }else{
						 $result = "双/";
						 }
					}
					if($sum>=14 && $sum % 2 ==0){
						if(isset($result)){
						$result .= "大双/";
					}else{
						$result = "大双/";
						}
					}elseif($sum>=14 && $sum % 2 ==1){
					 if(isset($result)){
					 $result .= "大单/";
				 }else{
					 $result = "大单/";
					 }
				 }elseif($sum<=13 && $sum % 2 ==0){
					 if(isset($result)){
					 $result .= "小双/";
				 }else{
					 $result = "小双/";
					 }
				 }elseif($sum<=13 && $sum % 2 ==1){
					 if(isset($result)){
					 $result .= "小单/";
				 }else{
					 $result = "小单/";
					 }
				 }
				 if($sum<=5){
					 if(isset($result)){
					 $result .= "极小值/";
				 }else{
					 $result .= "极小值/";
					 }
				 }elseif($sum>=22 && $sum<=27){
					 if(isset($result)){
					 $result .= "极大值/";
				 }else{
					 $result .= "极大值/";
					 }
				}

				 $arrs= str_split($code);
				 $newarr = bubbleSort($arrs);

				 if($newarr[0]==$newarr[1] && $newarr[1]==$newarr[2]){
					 if(isset($result)){
					 $result .= "豹子/";
					 }else{
					 $result = "豹子/";
					 }
				 }

				 if(($newarr[2]-$newarr[1] == $newarr[1]-$newarr[0] && $newarr[2]-$newarr[1]==1) ||
	          ($newarr[0]==0 && $newarr[1]==1 && $newarr[1]==9) ||
						($newarr[0]==0 && $newarr[1]==8 && $newarr[1]==9) ){
					 if(isset($result)){
					$result .= "顺子/";
					}else{
					$result = "顺子/";
					}
				}

				if(($newarr[0]==$newarr[1] && $newarr[1]!=$newarr[2]) ||  ($newarr[2]==$newarr[1] && $newarr[1]!=$newarr[0])){
					if(isset($result)){
					 $result .= "对子/";
				 }else{
					 $result = "对子/";
				 }
					 }

				if($sum % 3==0){
					if(isset($result)){
					 $result .= "龙/";
				 }else{
					 $result = "龙/";
					 }
				}elseif($sum % 3==1){
					if(isset($result)){
					 $result .= "虎/";
				 }else{
					 $result = "虎/";
					 }
				}elseif($sum % 3==2){
					if(isset($result)){
					 $result .= "豹/";
				 }else{
					 $result = "豹/";
					 }
				 }
				return $result;


		}

		function hidtel($phone){
    $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone); //固定电话
    if($IsWhat == 1){
        return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
    }else{
        return  preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
    }
   }

	 function check_teshu($code){
		 $arr= str_split($code);
		 $newarr = bubbleSort($arr);  //开奖号码从小到大排列

    //豹子
		 if($arr[0]==$arr[1] && $arr[1]==$arr[2]){
			return 1;
		 }
    //顺子
		 elseif(($newarr[2]-$newarr[1] == $newarr[1]-$newarr[0] && $newarr[2]-$newarr[1]==1) ||
				($newarr[0]==0 && $newarr[1]==1 && $newarr[1]==9) ||
				($newarr[0]==0 && $newarr[1]==8 && $newarr[1]==9) ){
			return  2;
		}
    //对子
		elseif(($newarr[0]==$newarr[1] && $newarr[1]!=$newarr[2]) ||
		   ($newarr[2]==$newarr[1] && $newarr[1]!=$newarr[0]) ){
			  echo 3;
			}else{
				return 4;
			}
	 }

	 function get_beilv($type,$mulu,$gameid){ //
    global $dosql;
    $typename=gettypename($type);

    $r=$dosql->GetOne("SELECT  $typename  FROM pmw_gameplay where gid=$gameid and typename_name=$mulu");
    $beilv= $r[$typename];
    return $beilv;
   }

   function get_gamebl($type,$mulu,$gameid){ //
    global $dosql;
    $typename=get_bl($type);

    $r=$dosql->GetOne("SELECT  $typename  FROM pmw_gameplay where gid=$gameid and typename_name=$mulu");
    $beilv= $r[$typename];
    return $beilv;
   }

   function getkuaitou($name){
     global $dosql;
     $s=$dosql->GetOne("SELECT  content  FROM pmw_kuaitou where name='$name'");
		 if(is_array($s)){
     $content = $s['content'];
     return $content;
		 }else{
			 return " ";
		 }
   }

  function getmulu($type){
       if($type=="大" || $type=="小" || $type=="单" || $type=="双" || $type=="极大" || $type=="极小" || $type=="大单" || $type=="大双" || $type=="小单" || $type=="小双"){
         return 0;
       }elseif($type=="龙" || $type=="虎" || $type=="豹" || $type=="豹子" || $type=="顺子" || $type=="对子"){
         return 2;
       }
  }
  function get_bl($name){
    switch($name){
    case '大':
    $typename = "da";
    break;
    case '小':
    $typename = "xiao";
    break;
    case '单':
    $typename = "dan";
    break;
    case '双':
    $typename = "shuang";
    break;
    case '极大':
    $typename = "jida";
    break;
    case '大单':
    $typename = "dadan";
    break;
    case '小单':
    $typename = "xiaodan";
    break;
    case '大双':
    $typename = "dashuang";
    break;
    case '小双':
    $typename = "xiaoshuang";
    break;
    case '极小':
    $typename = "jixiao";
    break;
    case '豹子':
    $typename = "baozi";
    break;
    case '顺子':
    $typename = "shunzi";
    break;
    case '对子':
    $typename = "duizi";
    break;
    case '龙':
    $typename = "drgon";
    break;
    case '虎':
    $typename = "hu";
    break;
    case '豹':
    $typename = "bao";
    break;
  }
    return $typename;
 }
   function gettypename($type){
     switch($type){
       case 0:
       $typename = "zero";
       break;
       case 1:
       $typename = "one";
       break;
       case 2:
       $typename = "two";
       break;
       case 3:
       $typename = "three";
       break;
       case 4:
       $typename = "four";
       break;
       case 5:
       $typename = "five";
       break;
       case 6:
       $typename = "six";
       break;
       case 7:
       $typename = "seven";
       break;
       case 8:
       $typename = "eight";
       break;
       case 9:
       $typename = "night";
       break;
       case 10:
       $typename = "ten";
       break;
       case 11:
       $typename = "one_one";
       break;
       case 12:
       $typename = "one_two";
       break;
       case 13:
       $typename = "one_three";
       break;
       case 14:
       $typename = "one_four";
       break;
       case 15:
       $typename = "one_five";
       break;
       case 16:
       $typename = "one_six";
       break;
       case 17:
       $typename = "one_seven";
       break;
       case 18:
       $typename = "one_eight";
       break;
       case 19:
       $typename = "one_night";
       break;
       case 20:
       $typename = "two_zero";
       break;
       case 21:
       $typename = "two_one";
       break;
       case 22:
       $typename = "two_two";
       break;
       case 23:
       $typename = "two_three";
       break;
       case 24:
       $typename = "two_four";
       break;
       case 25:
       $typename = "two_five";
       break;
       case 26:
       $typename = "two_six";
       break;
       case 27:
       $typename = "two_seven";
       break;
     }
     return $typename;
   }
?>
