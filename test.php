<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
/*
$arr = file_get_contents(stripslashes("http://api.dabai28.com/?service=Get.jnd28"));  //去除对象里面的斜杠
$srr = json_decode($arr,true);
$state=$srr['ret'];
$r=$dosql->GetOne("SELECT kj_times,kj_endtime_sjc FROM pmw_lotterynumber order by id desc limit 0,1");
$kj_times=$r['kj_times'];
if(date("H:i:s")=="19:03:30"){
  $kj_endtime_sjc=$r['kj_endtime_sjc']+3450;
}else{
$kj_endtime_sjc=$r['kj_endtime_sjc']+210;
  }
$kj_endtime=date("Y-m-d H:i:s",$kj_endtime_sjc); //开奖年月日，时分秒
$kj_mdhis =date("m-d H:i:s",$kj_endtime_sjc);
if($state==200){
  $array=$srr['data'][0];
  $issue=$array['issue'];
  $kj_number=$array['c1'].$array['c2'].$array['c3'];
  $kj_he=$array['c4'];
  $kj_varchar=$array['kj'].results($kj_he);
  $kj_maketime=date("Y-m-d");
  if($kj_times!=$issue){  //自动获取最新的开奖号码
  $sql = "INSERT INTO `pmw_lotterynumber` (kj_times, kj_number,kj_maketime,kj_varchar,kj_he,kj_mdhi,kj_endtime,kj_endtime_sjc) VALUES ($issue, '$kj_number', '$kj_maketime','$kj_varchar' , $kj_he,'$kj_mdhis','$kj_endtime',$kj_endtime_sjc)";
  $dosql->ExecNoneQuery($sql);
  }else{
  include("test.php");
  }
}

//0-24点钟的开奖时间和日期 每3分钟半钟开奖一次，每两分半钟之后封盘 先生成200期 ，其实期数2418198  起始开奖时间 2019-04-24 20:53:30
$w=date('w');
if($w==1){  //星期一
  $firsttimes = GetKj_times();
  for($i=0;$i<=326;$i++){
  //  $data="2019-04-29 00:02:30---2019-04-30 19:03:30";
    $date=date("Y-m-d");
    $startzero=strtotime($date)+150;
    $next_times=$startzero+210 * $i;
    $kj_times=$firsttimes + $i;
    $kj_endtime=date("Y-m-d H:i:s",$next_times);
    $kj_endtime_sjc=strtotime($kj_endtime);
    $kj_mdhi=date("m-d H:i:s",$next_times);
    $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
    $dosql->ExecNoneQuery($sql);
  }

  //星期一
  $secondttimes = GetKj_times();
  for($i=0;$i<=50;$i++){
  //  $data="2019-04-29 21:04:00--2019-04-29-23:59:00";
    $date=date("Y-m-d");
    $startzero=strtotime($date)+75840;
    $next_times=$startzero+210 * $i;
    $kj_times=$secondttimes + $i;
    $kj_endtime=date("Y-m-d H:i:s",$next_times);
    $kj_endtime_sjc=strtotime($kj_endtime);
    $kj_mdhi=date("m-d H:i:s",$next_times);
    $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
    $dosql->ExecNoneQuery($sql);
  }
}else{
  $firsttimes = GetKj_times();
  for($i=0;$i<=326;$i++){
  //  $data="2019-04-30 00:02:30---2019-04-30 19:03:30";
    $date=date("Y-m-d");
    $startzero=strtotime($date)+150;
    $next_times=$startzero+210 * $i;
    $kj_times=$firsttimes + $i;
    $kj_endtime=date("Y-m-d H:i:s",$next_times);
    $kj_endtime_sjc=strtotime($kj_endtime);
    $kj_mdhi=date("m-d H:i:s",$next_times);
    $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
    $dosql->ExecNoneQuery($sql);
  }
  $secondttimes = GetKj_times();
  for($i=0;$i<=67;$i++){
  //  $data="2019-04-30 20:04:30--2019-04-30-23:59:00";
    $date=date("Y-m-d");
    $startzero=strtotime($date)+72270;
    $next_times=$startzero+210 * $i;
    $kj_times=$secondttimes + $i;
    $kj_endtime=date("Y-m-d H:i:s",$next_times);
    $kj_endtime_sjc=strtotime($kj_endtime);
    $kj_mdhi=date("m-d H:i:s",$next_times);
    $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
    $dosql->ExecNoneQuery($sql);
  }
}
/*
for($i=1;$i<201;$i++){
  $data="2019-04-28 21:14:30";
  $firsttimes="2419777";
  $startzero=strtotime($data);
  $next_times=$startzero+210 * $i;
  $kj_times=$firsttimes + $i;
  $kj_endtime=date("Y-m-d H:i:s",$next_times);
  $kj_endtime_sjc=strtotime($kj_endtime);
  $kj_mdhi=date("m-d H:i:s",$next_times);
  $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi')";
  $dosql->ExecNoneQuery($sql);
}
*/

// List<BetItemModel> tab0List = [
//   new BetItemModel('0+大+2', '1:2', '大', '1:2', false),
//   new BetItemModel('0+小+2', '1:2', '小', '1.2', false),
//   new BetItemModel('0+单+2', '1:2', '单', '1:2', false),
//   new BetItemModel('0+双+2', '1:2', '双', '1:2', false),
//   new BetItemModel('0+极大+10', '1:10', '极大', '1:10', false),
//   new BetItemModel('0+大单+4.2', '1:4.2', '大单', '1:4.2', false),
//   new BetItemModel('0+小单+4.6', '1:4.6', '小单', '1:4.6', false),
//   new BetItemModel('0+大双+4.6', '1:4.6', '大双', '1:4.6', false),
//   new BetItemModel('0+小双+4.2', '1:4.2', '小双', '1:4.2', false),
//   new BetItemModel('0+极小+10', '1:10', '极小', '1:10', false),
// ];

// $str='{
//     "token": "wFu1lIfZcfhWf3IX",
//     "uid": 1,
//     "timestamp": 1555522504,
//     "gameid":5,
//     "xz": [
//                 {
//                     "type": "0+双+12",
//                     "money": 10
//                 },
//
//                  {
//                   "type": "1+c+20",
//                   "money": 10
//                 }
//             ]
// }';
// //$body = file_get_contents($str);
// $json = json_decode($str,true);
// $xiazhu_arr=array();
// $xiazhu_arr=$json['xz'];
// foreach($xiazhu_arr as $key=> $val){
//     foreach($val as $k=> $t){
//       $type_arr=explode("+",$val['type']);
//       $ml=$type_arr[0];
//       $lb=$type_arr[1];
//       if($ml==1){
//       $type =  tochange($ml,$lb);
//        }else{
//       $type= $ml;
//        }
//       $xiazhu_arr[$key]['type']= $type;
//    }
// }
// print_r($xiazhu_arr);

// $kj_code="424";
// $tswf= check_teshus($kj_code);
// //echo $tswf;
// if($tswf==3 || $tswf==2 || $tswf==1){
//
// $kj_content= "昨天".$tswf;
// echo $kj_content;
// }else{
// echo $tswf;
// }


//print_r($arr);

  $str="14草100shuang20 大25 jida50";
  $arrs = explode(" ",$str);
  //print_r($arrs);
  $gameid=5;
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
$json='{"serial":"2427544","time":"05-18 21:04","uid":"47","uname":"九头蛇","total":200,"items":[{"type":"豹子","money":100,"beilv":"50","mulu":"2","id":"1861"},{"type":"对子","money":100,"beilv":"3","mulu":"2","id":"1862"},{"type":"15","money":900,"beilv":"3","mulu":"2","id":"1862"}],"timestamp":1558184625264}';
$arrs=json_decode($json, true);
$arr=$arrs['items'];
print_r($arr);

$xiazhu_str="";
for($i=0;$i<count($arr);$i++){
  $type=$arr[$i]['type'];
  $money=$arr[$i]['money'];
  if(is_numeric($type)){
  $xiazhu_str .=$type."点".$money." ";
  }else{
  $xiazhu_str .=$type.$money." ";
  }
}

 echo $xiazhu_str;
?>
