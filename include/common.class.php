<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2018-2019 phpMyWind.com
update: 2019-08-08 16:55:36
person: Gang
project: 所有前台公用类库  common.class.php
**************************
*/



  class Common{

      //前端注册页面，判断是否有这个推荐人的信息，如果推荐人被删除的情况下，则不需要添加推荐人信息

      // 判断通过推荐码推荐过来的推荐人是否还存在

      public  static	function get_recommender_array($recommender_type,$uid){

    		global $dosql;

    		if($recommender_type=="agency"){

    			$r=$dosql->GetOne("SELECT id from pmw_agency where id=$uid");

    			if(is_array($r)){

            //说明存在这个推荐人
    				$array = array(

    									"recommender_type"   => $recommender_type,

    									"uid"                => $uid

    				);
    			}else{

    			//说明推荐人的个人信息已经被删除
    			$array = array(

    								"recommender_type"   => "",

    								"uid"                => ""

    			);
    			}
    		}elseif($recommender_type=="guide"){

    			$r=$dosql->GetOne("SELECT id from pmw_guide where id=$uid");

    			if(is_array($r)){

            //说明存在这个推荐人
    				$array = array(

    									"recommender_type"   => $recommender_type,

    									"uid"                => $uid

    				);
    			}else{

    			//说明推荐人的个人信息已经被删除
    			$array = array(

    								"recommender_type"   => "",

    								"uid"                => ""

    			);
    			}
    		}

    		return $array;
    	}


      // 统计推荐人推荐的人数

    	#当推荐的人数达到系统指定的人数的时候，则将营销活动的总开关关闭掉

    	#在总开关开启的情况下，执行统计人数

  public static  function add_recommender_nums()
    	{

    		global $dosql;

    		global $cfg_allmoney;  //获取营销的总金额

    		global $cfg_money;     //获取推荐注册了之后，给推荐人的账户添加佣金

    		global $cfg_task;      //获取营销的总金额

    		if($cfg_task=="Y"){

    		$sumnums = $cfg_allmoney / $cfg_money;

    		$dosql->ExecNoneQuery("UPDATE pmw_count set nums = nums +1  where id=1");

        //更新营销金额
    		$r = $dosql->GetOne("SELECT nums from pmw_count where id=1");
    		$nowmoney = $r['nums'] * $cfg_money;
    		$lastmoney = $cfg_allmoney - $nowmoney;
    		$dosql->ExecNoneQuery("UPDATE pmw_webconfig set varvalue='$lastmoney' where orderid=130");
    		//将统计人数更新掉
    		$k = $dosql->GetOne("SELECT nums from pmw_count where id=1");
    		$lastnums = $k['nums'];
    		$dosql->ExecNoneQuery("UPDATE pmw_webconfig set varvalue='$lastnums' where orderid=131");
    		//当统计的人数达到指定的人数的时候，则关闭总开关

    		if($lastnums >= $sumnums){  // 当推广的数量大于等于设置的人数的时候，则将总开关关闭

        $dosql->ExecNoneQuery("UPDATE pmw_webconfig set varvalue='N' where varname='cfg_task'");

    		}

    	  }

    	}

      //保存用户的最新的formid

    public static  function add_formid($openid,$formid)
      {
      	// 将所有的用户的最新的formid保存到数据库中来

        global $dosql;

        $guoqi_time = strtotime("+7 days");  //设置7天过期时间

      	$dosql->ExecNoneQuery("INSERT INTO `#@__formid` (formid,openid,guoqi_time) VALUES ('$formid','$openid',$guoqi_time)");

      }


      // 更新左侧新注册数字提醒

    public static  function update_message($type){

        global $dosql;

        if($type == "agency" || $type=="travel"){
        # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1
        if($type == "agency"){
          $id = 82;
        }elseif($type=="travel"){
          $id = 6;
        }

        $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=$id");

       # 2.同时更新旅行社栏目,或者导游的总的数量

        $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=5");


      }elseif($type == "guide" || $type=="freetime"){

       if($type == "guide"){
         $id = 21;
       }elseif($type=="freetime"){
         $id = 22;
       }
       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=$id");

       # 2.同时更新旅行社栏目的总的数量

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=8");

     }elseif($type=="order"){

       if($type == "order"){
         $id = 48;
       }

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=$id");

      # 2.同时更新旅行社栏目的总的数量

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=47");

     }elseif($type == "comment" || $type=="fankui"){

       if($type == "comment"){
         $id = 43;
       }elseif($type=="fankui"){
         $id=  93;
       }

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=$id");

       # 2.同时更新旅行社栏目的总的数量

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=42");
     }
  }

  //base64图片转码

public static  function base64_image_content($base64_image_content,$path){
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

   //base64图片转码

   public static  function base64($base64_image_content,$path){
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


   /********************php验证身份证号码是否正确函数*********************/
  public static  function idcard( $id )
   {
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if(!preg_match($regx, $id))
    {
   	 return FALSE;
    }
    if(15==strlen($id)) //检查15位
    {
   	 $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

   	 @preg_match($regx, $id, $arr_split);
   	 //检查生日日期是否正确
   	 $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
   	 if(!strtotime($dtm_birth))
   	 {
   		 return FALSE;
   	 } else {
   		 return TRUE;
   	 }
    }
    else      //检查18位
    {
   	 $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
   	 @preg_match($regx, $id, $arr_split);
   	 $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
   	 if(!strtotime($dtm_birth)) //检查生日日期是否正确
   	 {
   		 return FALSE;
   	 }
   	 else
   	 {
   		 //检验18位身份证的校验码是否正确。
   		 //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
   		 $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
   		 $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
   		 $sign = 0;
   		 for ( $i = 0; $i < 17; $i++ )
   		 {
   			 $b = (int) $id{$i};
   			 $w = $arr_int[$i];
   			 $sign += $b * $w;
   		 }
   		 $n = $sign % 11;
   		 $val_num = $arr_ch[$n];
   		 if ($val_num != substr($id,17, 1))
   		 {
   			 return FALSE;
   		 } //phpfensi.com
   		 else
   		 {
   			 return TRUE;
   		 }
   	 }
    }

   }

   //获取当前ip的城市
  public static    function get_city($ip){
   		$url = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
       $ret = self::https_request($url);
       $jsonAddress = json_decode($ret,true);
   		if($jsonAddress['code']==0){
         return $jsonAddress['data']['country']."-".$jsonAddress['data']['region']."-".$jsonAddress['data']['city'];
       }else{
         return "地址未知";
       }
   }

   //POST请求函数
 	public static function https_request($url,$data = null){
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


   //替换编辑器里面的图片链接 ，前面自动加上域名
   /**
    * 替换fckedit中的图片 添加域名
    * @param  string $content 要替换的内容
    * @param  string $strUrl 内容中图片要加的域名
    * @return string
    * @eg
    */
  public static  function GetPic($content = null, $strUrl = null) {
    		if ($strUrl) {
    		     $img=json_decode($content,TRUE);
    					if (!empty($img)) {
    								$patterns= array();
    								$replacements = array();
    								foreach($img as $imgItem){
    									if(!filter_var($imgItem, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
    										$final_imgUrl = $strUrl."/".$imgItem;
    									}else{
    										$final_imgUrl = $imgItem;
    									}
    										$replacements[] = $final_imgUrl;
    										$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
    										$patterns[] = $img_new;
    								}

    								//让数组按照key来排序
    								ksort($patterns);
    								ksort($replacements);

    								//替换内容
    								$vote_content = preg_replace($patterns, $replacements, $content);

    								return $vote_content;


    				}else {
    						return $content;
    				}
    		} else {
    				return $content;
    		}
    }


    //替换相册里面的图片，加上域名、

  public static  function GetPics($content = null, $strUrl = null) {
       if ($strUrl) {
            $img=explode("|",$content);
             if (!empty($img)) {
                   $patterns= array();
                   $replacements = array();
                   foreach($img as $imgItem){
                     if(!filter_var($imgItem, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
                       $final_imgUrl = $strUrl."/".$imgItem;
                     }else{
                       $final_imgUrl = $imgItem;
                     }
                       $replacements[] = $final_imgUrl;
                       $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
                       $patterns[] = $img_new;
                   }

                   //让数组按照key来排序
                   ksort($patterns);
                   ksort($replacements);

                   //替换内容
                   $vote_content = preg_replace($patterns, $replacements, $content);

                   return $vote_content;


           }else {
               return $content;
           }
       } else {
           return $content;
       }
    }


    //获取微信小程序openid
  public static  function openid($code,$appid,$appsecret){
      $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code=' . $code . '&grant_type=authorization_code';
      $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl
      $json = json_decode($info);//对json数据解码
      $arr = get_object_vars($json);

    	if(isset($arr['openid'])){
      $openid = $arr['openid'];
    	}else{
    	$openid ="";
    	}
      return $openid;
    }


    //当用户使用formid的时候，找出最新的fromid提供给用户
     # 1.判断当前用户的所有的fromid是否已经过期
     # 2.将最后一条没有过期的formid拿出来

  public static  function get_new_formid($openid)
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

      //判断formid表里面是否还有这个用户的formid

      if($k['id']!=NULL){

    	$ids=$k['id'];

    	$r=$dosql->GetOne("SELECT formid FROM `#@__formid` where id=$ids");

    	$formid=$r['formid'];

    	}else{

    	$formid="";  //formid为空，则不发送消息

    	}

    	return $formid;

     }


     //用户使用完毕formid之后，删除已经已经使用过的formid

    public static  function del_formid($formid,$openid)
     {

    	global $dosql;

    	$dosql->ExecNoneQuery("DELETE FROM `#@__formid` where formid='$formid' and openid='$openid'");

     }

     //图片替换
  public static  function rePic($content = null, $strUrl = null) {
     		if ($strUrl) {
     				//提取图片路径的src的正则表达式 并把结果存入$matches中
     				preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/U",$content,$matches);
     				$img = "";
     				if(!empty($matches)) {
     				//注意，上面的正则表达式说明src的值是放在数组的第三个中
     				$img = $matches[2];
     				}else {
     				$img = "";
     				}

     					if (!empty($img)) {
     								$patterns= array();
     								$replacements = array();
     								foreach($img as $imgItem){
     									if(!filter_var($imgItem, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
     										$final_imgUrl = $strUrl.$imgItem;
     									}else{
     										$final_imgUrl = $imgItem;
     									}
     										$replacements[] = $final_imgUrl;
     										$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
     										$patterns[] = $img_new;
     								}

     								//让数组按照key来排序
     								ksort($patterns);
     								ksort($replacements);

     								//替换内容
     								$vote_content = preg_replace($patterns, $replacements, $content);

     								return $vote_content;


     				}else {
     						return $content;
     				}
     		} else {
     				return $content;
     		}
     }


     //获取旅行社已经发布成功的次数和带团人数
  public static  function get_agency_num($id){

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

public static function  send_payer_message($info){

     global $cfg_paysuccess,$cfg_appid,$cfg_appsecret;

     self::paysuccess($info['openid'],$cfg_paysuccess,$info['page'],$info['formid'],$info['jingquname'],$info['typename'],$info['nums'],$info['totalamount'],$info['pay_time'],$info['tishi'],$cfg_appid,$cfg_appsecret);

     //删除已经用过的formid
     self::del_formid($info['formid'],$info['openid']);

   }


   //获取当前购票成功的id
  public static function get_orderid($did,$posttime)
   {
   	// code...
     global $dosql;

   	$r=$dosql->GetOne("SELECT id FROM pmw_order where did=$did and posttime=$posttime");

   	$id=$r['id'];

   	return $id;

   }

   //用户购票成功之后，发送购票成功的模板消息

      public static function paysuccess($openid,$cfg_paysuccess,$page,$form_id,$jingquname,$typename,$nums,$totalamount,$posttime,$tishi,$cfg_appid,$cfg_appsecret)
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

   	$ACCESS_TOKEN = get_access_token($cfg_appid,$cfg_appsecret);//ACCESS_TOKEN

   	//模板消息请求URL
   	$url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

   	$data = json_encode($data);//转化成json数组让微信可以接收
   	$data = self::https_request($url, urldecode($data));//请求开始
   	$data = json_decode($data, true);
   	// $errcode=$data['errcode'];  //判断模板消息发送是否成功
   	// return $errcode;
   }

   //获取购票管理员的openid和formid

  public static function get_openid_formid()
   {
     global $dosql;
   	 $r=$dosql->GetOne("SELECT openid FROM pmw_members where sets=1");
   	 return $r;
   }


   #向管理员发送购票成功订单的模板消息
   public static function send_leader_message($info)
    {
   global $cfg_ticketsuccess,$cfg_appid,$cfg_appsecret;
   self::ticketsuccess($info['openid_leader'],$cfg_ticketsuccess,$info['page_leader'],$info['formid_leader'],$info['jingquname'],$info['typename'],$info['usetime'],$info['nums'],$info['type'],$info['totalamount'],$info['contactname'],$info['contacttel'],
   $info['paytypes'],$info['pay_time'],$cfg_appid,$cfg_appsecret);

   self::del_formid($info['formid_leader'],$info['openid_leader']);

   }

   //用户购票成功之后，向购票管理员发送模板消息
    public static function  ticketsuccess($openid,$cfg_ticketsuccess,$page,$form_id,$jingquname,$typename,$usetime,$nums,$type,$totalamount,$contactname,$contacttel,$paytype,$posttime,$cfg_appid,$cfg_appsecret)
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

   	$ACCESS_TOKEN = get_access_token($cfg_appid,$cfg_appsecret);//ACCESS_TOKEN

   	//模板消息请求URL
   	$url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$ACCESS_TOKEN;

   	$data = json_encode($data);//转化成json数组让微信可以接收
   	$data = self::https_request($url, urldecode($data));//请求开始
   	$data = json_decode($data, true);
   	// $errcode=$data['errcode'];  //判断模板消息发送是否成功
   	// return $errcode;
   }


   //获取旅行社发布的所有已完成的行程的月份

  public static function get_months_success($id,$y){

   global $dosql;

   $dosql->Execute("SELECT complete_ym as time FROM pmw_travel where aid=$id and complete_y='$y' group by complete_ym");

   $num=$dosql->GetTotalRow();
   $arr =array();
   if($num==0){
   while($show=$dosql->GetArray()){
   	$arr[]=$show;
   }
   }else{
   	$arr =array();
   }
   return $arr;

   }

   //获取旅行社已经发布成功的行程的状态
  public static function get_agency_state($id,$y,$m){

   global $dosql;

   $r = $dosql->GetOne("SELECT SUM(jiesuanmoney) AS money,SUM(num) as teamnumber,SUM(days) as days,Settlement  FROM pmw_travel  where aid=$id and state=2 and complete_y='$y' and complete_ym='$m'");

   $return =$r;

   return $return;

   }




  }
