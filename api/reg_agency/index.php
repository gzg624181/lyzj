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
     * @旅行社注册接口    提供返回参数账号，
     * cardpic          营业执照(varchar)
     * address          公司地址(varchar)
     * name             联系人姓名(varchar)
     * tel              联系电话(varchar)
     * images          旅行社头像(varchar)默认第一次拉取微信头像
     * account         账号(varchar)
     * password        密码(varchar)
     * openid          关注小程序的openid
     * formid          关注小程序的formid
     * cardpicnumber   营业执照号码
     * cardidnumber    身份证号码
     * cardid_picarr   身份证正反面图片
     * uid              推荐人的id   （可以为空）
     * recommender_type 推荐人的类别 （可以为空）
     * province          定位省份代码   中文 live_province
     * city              定位城市代码   中文 live_city

     *  备注：   1.手机号码唯一，同时一个手机号码只能注册旅行社账号和旅行社账号  （需要判断）
     *          2.旅行社营业执照证件号码唯一     （需要判断）
     *          3.身份证号码填写正确（与旅行社的信息一致） （需要判断身份证号码填写是否正确）
     *          4.身份证号码唯一     （需要判断）
     *
     *  推荐：    如果是推荐注册的话，则统计推荐的人数，如果有推荐注册的话，则先来判断是否有这个推荐人，
     *           如果有将推荐人的信息保存下来，如果没有，则推荐的信息为空
     *
     *          1.推荐人的id：  uid
     *          2.推荐人的类型： type
     *
     *  传输方式：post -> 采用json格式传输数据

     *  json返回状态码：  2：此电话号码已经被注册，请重新注册！
     *                  1:旅行社注册信息已提交成功！
     *                  0:旅行社注册信息已提交失败！
     *                  2:此电话号码正在审核中，请等待管理员审核
     *                  2:此电话号码已注册过旅行社账号！
     *                  2：此旅行社证号码或身份证号码已经被注册！
     *                  2：身份证号码填写错误！
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$body = file_get_contents('php://input');
$json = json_decode($body,true);

print_r($json);


// 旅行社注册基本信息
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
$cardpicnumber = $json['cardpicnumber'];
$cardidnumber = $json['cardidnumber'];
$cardid_picarr = $json['cardid_picarr'];

// 推荐信息
$uid = $json['uid'];
$recommender_type = $json['recommender_type'];

// 旅行社定位信息

$live_province = $json['live_province'];
$province = $json['province'];
$live_city = $json['live_city'];
$city = $json['city'];


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
  //判断旅行社表里面 旅行社证号的唯一性，或者 旅行社注册的身份证的唯一性

  // $k = $dosql->GetOne("SELECT id from `#@__agency` where cardpicnumber='$cardpicnumber' or cardidnumber='$cardidnumber'");

  //暂时不判断旅行社证号的唯一性
  $k = $dosql->GetOne("SELECT id from `#@__agency` where  cardidnumber='$cardidnumber'");

  //如果旅行社表里面没有这个旅行社的旅行社证号码,或者身份证号码
      if(!is_array($k)){

       //判断旅行社的身份证号码是否正确
      $idc= Common::idcard($cardidnumber);
            if($idc){
            $regtime=time();
            $regip=GetIP();
            $getcity=Common::get_city($regip);
            $ymdtime=date("Y-m-d");
            $password=md5(md5($password));

            //这个是自定义函数，将Base64图片转换为本地图片并保存
            $savepath= "../../uploads/image/";
            $cardpic = Common::base64_image_content($cardpic,$savepath);
            $cardpic = str_replace("../../",'',$cardpic);


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

          //判断是否有这个推荐人的信息，如果推荐人被删除的情况下，则不需要添加推荐人信息
          if($uid!=""){
            // 添加推荐统计人数，如果是推荐人推荐注册过来的话，则记录推荐的人数
            Common::add_recommender_nums();
            $arr = Common::get_recommender_array($recommender_type,$uid);
            $recommender_type   = $arr['recommender_type'];
            $uid = $arr['uid'];
          }

          //将新生成的formid的信息保存到formid表里面去
          Common::add_formid($openid,$formid);

          $sql = "INSERT INTO `#@__agency` (cardpic,address,name,tel,account,password,regtime,regip,ymdtime,images,getcity,openid,company,cardpicnumber,cardidnumber,cardid_picarr,uid,recommender_type,live_province,live_city,province,city) VALUES ('$cardpic','$address','$name','$tel','$account','$password',$regtime,'$regip','$ymdtime','$images','$getcity','$openid','$company','$cardpicnumber','$cardidnumber','$card_picarr','$uid','$recommender_type','$live_province','$live_city',$province,$city)";


              if($dosql->ExecNoneQuery($sql)){

                //注册成功自动刷新后台左侧栏目
                Common::update_message('agency');

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
                $State = 0;
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
      $Descriptor = '此旅行社营业执照号码，或身份证号码已经被注册！';
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
$Descriptor = '此电话号码已注册过导游账号！';
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
