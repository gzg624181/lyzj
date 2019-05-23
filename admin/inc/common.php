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


//添加账户明细   （充值 recharge   提现 take_money 使用这个方法   订单号chargeorder）

   function records($money_list,$types,$mid,$chargeorder){
		global $dosql;
		$tbnames="pmw_record";
		$time_list=time();
		$sql = "INSERT INTO `$tbnames` (money_list,types,mid,time_list,chargeorder,leibie,content) VALUES ('$money_list','$types',$mid,$time_list,'$chargeorder','账户充值','充值')";
    $dosql->ExecNoneQuery($sql);
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
		 			 $newarr = bubbleSort($arr);

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


























?>
