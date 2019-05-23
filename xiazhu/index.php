<?php
    /**
	   * 链接地址：下注接口  xiazhu
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

     * @param array $Data 返回数据
     *
     *  {
    *      "token": "wFu1lIfZcfhWf3IX",
    *        "uid": 1,
    *  "timestamp": 2467152632563,
    *     "gameid":5,
    *         "xz": [
    *                 {
    *                     "type": "dan",
    *                     "money": "900"
    *                 },
    *                 {
    *                   "type": "shuang",
    *                   "money": "50"
    *                 },
    *                 {
    *                   "type": "baozi",
    *                   "money": "60"
    *                 }
    *             ]
    *  }
     */
     //通过输入端来获取数据
$body = file_get_contents('php://input');
$json = json_decode($body,true);
header('Content-Type: application/json; charset=utf-8');
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
$uid=$json['uid'];
$gameid=$json['gameid'];
$token=$json['token'];
$timestamp=$json['timestamp'];
$arr=array();

if(isset($json['xz'])){
$arr=$json['xz'];

foreach($arr as $key=> $val){
    foreach($val as $k=> $t){
      $type_arr=explode("+",$val['type']);
      $ml=$type_arr[0];
      $lb=$type_arr[1];
      $bl=$type_arr[2];
      if($ml==1){
      $type =  "数字".tochange($ml,$lb);
       }else{
      $type= $lb;
       }
      $arr[$key]['type']= $type;
      $arr[$key]['beilv']= $bl;
      $arr[$key]['mulu']= $ml;
   }
}
}elseif(isset($json['wzxz'])){
  $str= $json['wzxz'];
  $arrs = explode(" ",$str);
  //print_r($arrs);
  if(is_array($arrs)){
  foreach($arrs as $key=> $val){
    $array = preg_split("/([0-9]+)/", $val, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $num=count($array);
    if($num==3){//选择特码
       if($array[0]>=0 && $array[1]<=27){
           $arr[$key]['type']= "数字".$array[0];
           $arr[$key]['money']= $array[2];
           $arr[$key]['beilv']= get_beilv($array[0],1,$gameid);
           $arr[$key]['mulu']=  1;
       }elseif($array[2]>=0 && $array[2]<=27){
           $arr[$key]['type']= "数字".$array[2];
           $arr[$key]['money']= $array[1];
           $arr[$key]['beilv']= get_beilv($array[2],1,$gameid);
           $arr[$key]['mulu']=  1;
       }
    }elseif($num==2){
        if(is_numeric($array[0])){  //如果第一个字段是数字，则表示下注的金额
          $arr[$key]['money']= $array[0];  //下注的金额
          $arr[$key]['type']= getkuaitou($array[1]);  //下注的类型(大小单双，大单，小单，大双，小双)
          $kuaitou=getkuaitou($array[1]);   //下注的类型
          $arr[$key]['beilv']= get_gamebl($kuaitou,getmulu($kuaitou),$gameid);
          $arr[$key]['mulu']=  getmulu($kuaitou);
        }else{
          $arr[$key]['money']= $array[1];  //下注的金额
          $arr[$key]['type']= getkuaitou($array[0]);  //下注的类型
          $kuaitou=getkuaitou($array[0]);   //下注的类型
          $arr[$key]['beilv']= get_gamebl($kuaitou,getmulu($kuaitou),$gameid);
          $arr[$key]['mulu']=  getmulu($kuaitou);
        }
    }
  }
}
}


if(isset($token) && $token==$cfg_auth_key){
  $now=time();
  $Data=array();
  $k=$dosql->GetOne("select money,nickname from pmw_members where  id = $uid");
  $money = sprintf("%.2f",$k['money']);
  $uname = $k['nickname'];

  //开奖时间戳
  $r=$dosql->GetOne("SELECT kj_endtime_sjc,kj_times,kj_endtime,id,kj_times,state from pmw_lotterynumber where kj_state=0");
  $state=$r['state'];

  $kj_endtime_sjc = $r['kj_endtime_sjc'];
  $kj_times = $r['kj_times']; //开奖期数
  $kj_endtime = $r['kj_endtime'];
  $kj_times= $r['kj_times'];
  $id=$r['id'];
  if($state=='fp'){

      $Data['current']['process'] = 2;    //封盘开奖中
      $Data['current']['countdown'] = $kj_endtime_sjc - $now ;
      $Data['current']['kjtime'] = intval($kj_endtime_sjc);   //开奖时间
      $Data['current']['fptime'] = 0;
      $Data['current']['serial'] = strval($kj_times);
      $Data['current']['people'] = rand(100,1000);
      $Data['current']['money'] =   $money;
      $Data['current']['money_double'] =floatval($money);
  }elseif($state=='xz'){
      $ahead_kjtimes=$kj_times-1;
      $s=$dosql->GetOne("SELECT kj_endtime_sjc from pmw_lotterynumber where kj_times=$ahead_kjtimes");
      $Data['current']['process'] = 1;   //倒计时下注时间
      $Data['current']['countdown'] = $s['kj_endtime_sjc'] + 180 -$now;
      $Data['current']['kjtime'] = intval($kj_endtime_sjc);   //下次开奖时间，封盘中则为空
      $Data['current']['fptime'] = intval($kj_endtime_sjc+180) ;
      $Data['current']['serial'] = strval($kj_times);
      $Data['current']['people'] = rand(100,1000);
      $Data['current']['money'] =   $money;
      $Data['current']['money_double'] = floatval($money);

  }

  $one=1;
  $two=2;
  $dosql->Execute("SELECT kj_times as serial, kj_mdhi as time, kj_varchar as value from pmw_lotterynumber  where  kj_state =1 order by id desc limit 0,10",$two);
  for($i=0;$i<$dosql->GetTotalRow($two);$i++)
  {
      $row = $dosql->GetArray($two);
      $Data['current']['history'][$i]=$row;
  }


  if($state=='fp'){
    $State =2;
    $Descriptor = '已经封盘下注失败!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data,
                 );
    echo phpver($result);
  }elseif($state=='xz'){

   //将下注的数字传入到表中去
   $randnumber=rand(100000,999999);

   $xiazhu_orderid=date("YmdHis").$randnumber;
   $sum=0;
   $xiazhu_timestamp=time();
   for($i=0;$i<count($arr);$i++){
     $sum += $arr[$i]['money'];
   }
   //判断用户账户的金额是否大于下注的金额
   if($money >= $sum){
   //下注成功之后，减去用户账号里面的金额
   $sql = "UPDATE `#@__members` SET money=money - $sum WHERE id=$uid";
   $dosql->ExecNoneQuery($sql);

   //下注完成之后，同时将下注记录保存到数据库中
   $xiazhu_ymd=date("Y-m-d");
   $sql = "INSERT INTO `#@__xiazhuorder` (uid,xiazhu_qishu,xiazhu_orderid,xiazhu_kjtime,xiazhu_timestamp,timestamp,gameid,xiazhu_sum,xiazhu_ymd) VALUES ($uid,$kj_times,'$xiazhu_orderid', $kj_endtime_sjc,$xiazhu_timestamp,$timestamp,$gameid,'$sum','$xiazhu_ymd')";
   $dosql->ExecNoneQuery($sql);


   for($i=0;$i<count($arr);$i++){
     $xiazhu_type=str_replace('数字','',$arr[$i]['type']);
     $xiazhu_money=$arr[$i]['money'];
     $xiazhu_beilv=$arr[$i]['beilv'];
     $xiazhu_mulu=$arr[$i]['mulu'];
     $sum += $arr[$i]['money'];
     $randkey =rand(111111111,99999999);
     $sql = "INSERT INTO `#@__xiazhucontent` (userid,gameid,xiazhu_type,xiazhu_money,xiazhu_beilv,xiazhu_mulu,xiazhu_orderid,xiazhu_times,randkey) VALUES ($uid,$gameid,'$xiazhu_type','$xiazhu_money','$xiazhu_beilv','$xiazhu_mulu','$xiazhu_orderid','$kj_times',$randkey)";
     $dosql->ExecNoneQuery($sql);
     $k=$dosql->GetOne("SELECT id FROM `#@__xiazhucontent` WHERE randkey=$randkey");
     $id=$k['id'];
     $arr[$i]['id']=$id;
   }



   //下注消息保存到数据库中
   $content=array(
     "serial" => strval($kj_times), //期数
     "time" => substr($kj_endtime,5,11), //时间
     "uid" =>  strval($uid), //用户id
     "uname" => $uname, //用户名
     "total" => $sum/2, //合计
     "items" => $arr,
     "timestamp" =>$timestamp
   );

   $content=phpver($content);
   $sql = "INSERT INTO `#@__message` (type,content,timestamp,gid) VALUES ('system_xz','$content',$timestamp,$gameid)";
   $dosql->ExecNoneQuery($sql);


    $State = 1;
    $Descriptor = '下注成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
        );
    echo phpver($result);
  }else{
    $State = 3;
    $Descriptor = '账户余额不足!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
        );
    echo phpver($result);
  }
  }else{
    $State = 0;
    $Descriptor = '系统错误!';
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
