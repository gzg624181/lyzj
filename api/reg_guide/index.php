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
     * openid       前端发送formid
     * cardidnumber 导游的身份证号码
     * cardid_picarr  身份证正面图片
     * cardid_back  身份证反面图片
     * recommender_openid  推荐人的openid
     * uid         推荐人的id
     * recommender_type  推荐人的类别

     *  备注：   1.手机号码唯一，同时一个手机号码只能注册导游账号和旅行社账号
     *          2.导游的证件号码唯一
     *          3.身份证号码填写正确（与导游的信息一致）

     *  json返回状态码：  0：此电话号码已经被注册，请重新注册！
     *                  1:导游注册信息已提交成功！
     *                  2:导游注册信息已提交失败！
     *                  3:此电话号码正在审核中，请等待管理员审核
     *                  4:此电话号码已注册过旅行社账号！
     *                  5：此导游证号码已经被注册！
     *                  6：身份证号码填写错误！
     */
require_once("../../include/config.inc.php");
require_once("../../admin/sendmessage.php");
$body = file_get_contents('php://input');
$json = json_decode($body,true);
header('Content-Type: application/json; charset=utf-8');
//通过post格式传递过来

$name=$json['name'];
$sex=$json['sex'];
$card=$json['card'];
$cardnumber=$json['cardnumber'];
$tel=$json['tel'];
$account=$json['account'];  // 注册的账号（保证注册的手机号码唯一性）
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
$recommender_openid = $json['recommender_openid'];
$uid   =  $json['uid'];
$recommender_type = $json['recommender_type'];


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
    $State = 3;
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
  //判断导游表里面 导游证号的唯一性
  $k = $dosql->GetOne("SELECT id from `#@__guide` where cardnumber='$cardnumber'");
  //如果导游表里面没有这个导游的导游证号码
  if(!is_array($k)){
   //判断导游的身份证号码是否正确
   $idc=is_idcard($cardidnumber);
   if($idc){
  $appid=$cfg_appid;
  $appsecret=$cfg_appsecret;
  $regtime=time();
  $regip=GetIP();
  $getcity=get_city($regip);
  $ymdtime=date("Y-m-d");
  $password=md5(md5($password));
  //这个是自定义函数，将Base64图片转换为本地图片并保存
  $savepath= "../../uploads/image/";
  $card = base64_image_content($card,$savepath);
  $card=str_replace("../../",'',$card);
  //将相册里面的图片进行处理
  $pic="";
  $arr=explode("|",$pics);
  for($i=0;$i<count($arr);$i++){
    $pics  = base64_image_content($arr[$i],$savepath);
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
    $pics  = base64_image_content($arr[$i],$savepath);
    if($i==count($arr)-1){
      $thispic = str_replace("../../",'',$pics);
    }else{
      $thispic = str_replace("../../",'',$pics)."|";
    }
    $card_picarr .= $thispic;
  }

  //判断是否有这个推荐人的信息
  if($uid!=""){
    $arr = get_recommender_array($recommender_type,$uid);
    $recommender_type   = $arr['recommender_type'];
    $uid = $arr['uid'];
  }

  $sql = "INSERT INTO `#@__guide` (name,sex,card,cardnumber,tel,account,password,content,pics,regtime,regip,ymdtime,images,getcity,openid,formid,cardidnumber,cardid_picarr,experience,uid,recommender_type) VALUES ('$name',$sex,'$card','$cardnumber','$tel','$account','$password','$content','$pic',$regtime,'$regip','$ymdtime','$images','$getcity','$openid','$formid','$cardidnumber','$card_picarr','$experience','$uid','$recommender_type')";

  // 添加推荐统计人数，如果是推荐人推荐注册过来的话，则记录推荐的人数
  if($uid!=""){
    add_recommender_nums();
  }

  add_formid($openid,$formid);

if($dosql->ExecNoneQuery($sql)){
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
  $State = 2;
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
  $Descriptor = '此导游证号码已经被注册！';
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
