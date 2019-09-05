<?php
    /**
	   * 链接地址：reg_guide  导游注册接口
	   *
     * 下面直接来连接操作数据库进而得到json串
     *
     * 按json方式输出通信数据
     *
     * @param unknown $State 状态码
     *
     * @param string $Descriptor  提示信息
     *
	   * @param string $Version  操作时间
     *
     * @param array $Data 数据
     *
     * @return string
     *
     * @导游注册接口 提供返回参数账号，
     * name         导游姓名(varchar)
     * sex          导游性别(int)
     * card         导游证(varchar)
     * cardnumber   导游证号(varchar)
     * tel          导游电话(varchar
     * images       导游头像(varchar)默认第一次拉取微信头像
     * account      账号(varchar)
     * password     密码(varchar)
     * content      简介(text)
     * pics         导游相册 (选填)(text)
     * regtime      注册时间 (选填)(int)
     * openid        用户关注小程序唯一的openid
     * formid        前端发送formid
     * cardidnumber   导游的身份证号码
     * cardid_picarr  身份证正面图片
     * cardid_back    身份证反面图片
     * uid            推荐人的id
     * recommender_type  推荐人的类别
     * province      默认登录省份数字代码   live_province
     * city          默认登录的城市数字代码 live_city

     *  备注：   1.手机号码唯一，同时一个手机号码只能注册导游账号和旅行社账号
     *          2.导游的证件号码唯一
     *          3.身份证号码填写正确（与导游的信息一致）

     *  json返回状态码：  0：此电话号码已经被注册，请重新注册！
     *                  1:导游注册信息已提交成功！
     *                  2:导游注册信息已提交失败！
     *                  3:此电话号码正在审核中，请等待管理员审核
     *                  2:此电话号码已注册过旅行社账号！
     *                  5：此导游证号码已经被注册！
     *                  6：身份证号码填写错误！
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$body = file_get_contents('php://input');
$json = json_decode($body,true);
//通过post格式传递过来

$name=$json['name'];
$sex=$json['sex'];
$card=$json['card'];
$cardnumber=$json['cardnumber'];
$tel=$json['tel'];
$account=$json['account'];    // 注册的账号（保证注册的手机号码唯一性）
$password=$json['password'];
$content=$json['content'];
$pics=$json['pics'];
$token=$json['token'];
$images=$json['images'];
$formid=$json['formid'];
$openid =$json['openid'];
$cardidnumber = $json['cardidnumber'];
$cardid_picarr = $json['cardid_picarr'];
$experience = $json['experience'];

// 推荐信息
$uid = $json['uid'];
$recommender_type = $json['recommender_type'];

// 导游定位信息
$live_province = $json['live_province'];
$live_city = $json['live_city'];


$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

//判断旅行社表里面是否已经注册了这个手机号码

$s = $dosql->GetOne("SELECT id from `#@__agency` where account='$account'");

//旅行社表里面没有这个电话号码执行以下操作
if(!is_array($s)){
//判断当前注册的手机账号是否已经被注册过
$r=$dosql->GetOne("SELECT * FROM `#@__guide` WHERE account='$account'");
//如果导游表里面已经有了账号，则判断此账号的状态（0：正在审核中 1：已经审核通过，此电话号码已经被注册过
if(is_array($r)){
  if($r['checkinfo']==0){
    $State = 2;
    $Descriptor = '此电话号码正在审核中，请等待管理员审核！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }elseif($r['checkinfo']==1){
  $State = 2;
  $Descriptor = '此电话号码已经被注册，请重新注册！';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);
  }
}else{
  //判断导游表里面 导游证号的唯一性 ，且身份证号码唯一性
  $k = $dosql->GetOne("SELECT id from `#@__guide` where cardnumber='$cardnumber' or cardidnumber='$cardidnumber'");
  //如果导游表里面没有这个导游的导游证号码
  if(!is_array($k)){
   //判断导游的身份证号码是否正确
  $idc=Common::idcard($cardidnumber);
  if($idc){
  $regtime=time();
  $regip=GetIP();
  $getcity=Common::get_city($regip);
  $ymdtime=date("Y-m-d");
  $password=md5(md5($password));
  //这个是自定义函数，将Base64图片转换为本地图片并保存
  $savepath= "../../uploads/image/";
  $card = Common::base64_image_content($card,$savepath);
  $card=str_replace("../../",'',$card);
  //将相册里面的图片进行处理
  $pic="";
  $arr=explode("|",$pics);
  for($i=0;$i<count($arr);$i++){
    $pics  = Common::base64_image_content($arr[$i],$savepath);
    if($i==count($arr)-1){
      $thispic = str_replace("../../",'',$pics);
    }else{
      $thispic = str_replace("../../",'',$pics)."|";
    }
    $pic .= $thispic;
  }

  $card_picarr="";
  $arr=explode("|",$cardid_picarr);
  for($i=0;$i<count($arr);$i++){
    $pics  = Common::base64_image_content($arr[$i],$savepath);
    if($i==count($arr)-1){
      $thispic = str_replace("../../",'',$pics);
    }else{
      $thispic = str_replace("../../",'',$pics)."|";
    }
    $card_picarr .= $thispic;
  }

  //判断是否有这个推荐人的信息
  if($uid!=""){
    // 添加推荐统计人数，如果是推荐人推荐注册过来的话，则记录推荐的人数
    Common::add_recommender_nums();
    $arr = Common::get_recommender_array($recommender_type,$uid);
    $recommender_type   = $arr['recommender_type'];
    $uid = $arr['uid'];
  }

  //将新生成的formid的信息保存到formid表里面去
  Common::add_formid($openid,$formid);

  //获取省份数字代码
 $row = $dosql->GetOne("SELECT * FROM `pmw_cascadedata`WHERE `dataname` like  '%$live_province%'");
 $province=$row['datavalue'];  //省份数字代码
 $live_province = $row['dataname']; //省份中文

//获取城市数字代码
 $row = $dosql->GetOne("SELECT * FROM `pmw_cascadedata`WHERE `dataname` like '%$live_city%'");
 $city=$row['datavalue'];   //城市数字代码
 $live_city = $row['dataname'];


  $sql = "INSERT INTO `#@__guide` (name,sex,card,cardnumber,tel,account,password,content,pics,regtime,regip,ymdtime,images,getcity,openid,cardidnumber,cardid_picarr,experience,uid,recommender_type,live_province,live_city,province,city) VALUES ('$name',$sex,'$card','$cardnumber','$tel','$account','$password','$content','$pic',$regtime,'$regip','$ymdtime','$images','$getcity','$openid','$cardidnumber','$card_picarr','$experience','$uid','$recommender_type','$live_province','$live_city',$province,$city)";

if($dosql->ExecNoneQuery($sql)){

  //注册成功自动在数据里面加上1
  Common::update_message('guide');

  $State = 1;
  $Descriptor = '导游注册信息已提交成功！';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);
}else{
  $State = 0;
  $Descriptor = '导游注册信息已提交失败！';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);
}
}else{
  // 导游证件号码已经被注册过
  $State = 2;
  $Descriptor = '身份证号码填写错误！';
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                   'Version' => $Version,
                   'Data' => $Data
                   );
  echo phpver($result);
}
}else{
  // 导游证件号码已经被注册过
  $State = 2;
  $Descriptor = '此导游证号码或身份证号码已经被注册！';
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                   'Version' => $Version,
                   'Data' => $Data
                   );
  echo phpver($result);
}
}
}else{
$State = 2;
$Descriptor = '此电话号码已注册过旅行社账号！';
$result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                 'Version' => $Version,
                 'Data' => $Data
                 );
echo phpver($result);
}
}else{
  $State = 520;
  $Descriptor = 'token验证失败！';
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
  				         'Version' => $Version,
                   'Data' => $Data,
                   );
  echo phpver($result);
}

?>
