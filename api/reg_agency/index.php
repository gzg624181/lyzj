<?php
    /**
	   * 链接地址：reg_agency  旅行社注册接口
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
     * @旅行社注册接口 提供返回参数账号，
     *
     * @旅行社注册接口   提供返回参数账号，
     * cardpic         营业执照(varchar)
     * address         公司地址(varchar)
     * name            联系人姓名(varchar)
     * tel             联系电话(varchar)
     * images          旅行社头像(varchar)默认第一次拉取微信头像
     * account         账号(varchar)
     * password        密码(varchar)
     * openid          关注小程序的openid
     * formid          关注小程序的formid
     * cardpicnumber   营业执照号码
     * cardidnumber    身份证号码
     * cardid_picarr   身份证正反面图片
     * recommender_openid 推荐人openid
     * uid              推荐人的id
     * recommender_type  推荐人的类别

     *  备注：   1.手机号码唯一，同时一个手机号码只能注册旅行社账号和旅行社账号
     *          2.旅行社营业执照证件号码唯一
     *          3.身份证号码填写正确（与旅行社的信息一致）
     *
     *          如果是推荐注册的话，则统计推荐的人数

     *           如果有推荐注册的话，则先来判断是否有这个推荐人，如果有将推荐人的信息保存下来，如果没有
     *           则没有推荐的信息

     *  json返回状态码：  0：此电话号码已经被注册，请重新注册！
     *                  1:旅行社注册信息已提交成功！
     *                  2:旅行社注册信息已提交失败！
     *                  3:此电话号码正在审核中，请等待管理员审核
     *                  4:此电话号码已注册过旅行社账号！
     *                  5：此旅行社证号码已经被注册！
     *                  6：身份证号码填写错误！
     */
require_once("../../include/config.inc.php");
require_once("../../admin/sendmessage.php");
$body = file_get_contents('php://input');
$json = json_decode($body,true);
header('Content-Type: application/json; charset=utf-8');
//通过post格式传递过来

$cardpic=$json['cardpic'];
$address=$json['address'];
$name=$json['name'];
$tel=$json['tel'];
$account=$json['account'];
$password=$json['password'];
$token=$json['token'];
$images=$json['images'];
$formid=$json['formid'];
$company=$json['company'];
$openid = $json['openid'];
$formid = $json['formid'];
$cardpicnumber = $json['cardpicnumber'];
$cardidnumber = $json['cardidnumber'];
$cardid_picarr = $json['cardid_picarr'];

$recommender_openid = $json['recommender_openid'];  //可要可不要
$uid = $json['uid'];   //推荐人的id
$recommender_type = $json['recommender_type'];

$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

//判断导游表里面是否已经注册了这个手机号码

$s = $dosql->GetOne("SELECT id from `#@__guide` where account='$account'");

//旅行社表里面没有这个电话号码执行以下操作
if(!is_array($s)){
//判断当前注册的手机账号是否已经被注册过
$r=$dosql->GetOne("SELECT * FROM `#@__agency` WHERE account='$account'");
//如果旅行社表里面已经有了账号，则判断此账号的状态（0：正在审核中 1：已经审核通过，此电话号码已经被注册过
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
  //判断旅行社表里面 旅行社证号的唯一性
  $k = $dosql->GetOne("SELECT id from `#@__agency` where cardpicnumber='$cardpicnumber'");
  //如果旅行社表里面没有这个旅行社的旅行社证号码
  if(!is_array($k)){
   //判断旅行社的身份证号码是否正确
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
  $cardpic = base64_image_content($cardpic,$savepath);
  $cardpic = str_replace("../../",'',$cardpic);


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

//判断是否有这个推荐人的信息，如果推荐人被删除的情况下，则不需要添加推荐人信息
if($uid!=""){
  $arr = get_recommender_array($recommender_type,$uid);
  $recommender_type   = $arr['recommender_type'];
  $uid = $arr['uid'];
}

$sql = "INSERT INTO `#@__agency` (cardpic,address,name,tel,account,password,regtime,regip,ymdtime,images,getcity,openid,formid,company,cardpicnumber,cardidnumber,cardid_picarr,uid,recommender_type) VALUES ('$cardpic','$address','$name','$tel','$account','$password',$regtime,'$regip','$ymdtime','$images','$getcity','$openid','$formid','$company','$cardpicnumber','$cardidnumber','$card_picarr','$uid','$recommender_type')";


// 添加推荐统计人数，如果是推荐人推荐注册过来的话，则记录推荐的人数
if($uid!=""){
  add_recommender_nums();
}

add_formid($openid,$formid);

if($dosql->ExecNoneQuery($sql)){
  $State = 1;
  $Descriptor = '旅行社注册信息已提交成功！';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);
}else{
  $State = 2;
  $Descriptor = '旅行社注册信息已提交失败！';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);
}
}else{
  // 旅行社证件号码已经被注册过
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
  // 旅行社证件号码已经被注册过
  $State = 2;
  $Descriptor = '此旅行社营业执照号码已经被注册！';
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
