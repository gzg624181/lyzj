<?php
    /**
	   * 链接地址：消息 getmessage 接口
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
     *  params: uid, 用户id
     *          gameid, 游戏id
     *          gamename, 游戏名称
     *          timestamp 当前时间
     *          mid: 聊天记录id， 为空时返回当前时间timestamp往前的20条消息
     *
     */
header('Content-Type: application/json; charset=utf-8');
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  $now=time();
  $Data=array();
  $k=$dosql->GetOne("select money,nickname from pmw_members where  id = $uid");
  $money = sprintf("%.2f",$k['money']);
  $uname = $k['nickname'];

  $r=$dosql->GetOne("select kj_endtime_sjc,kj_times,kj_endtime,id from pmw_lotterynumber where  kj_endtime_sjc >= $now order by id asc");
  $kj_endtime_sjc = $r['kj_endtime_sjc'];
  $kj_times = $r['kj_times'];
  $kj_endtime = $r['kj_endtime'];
  $id=$r['id'];
  if($now + 60 > $r['kj_endtime_sjc']){

      $Data['current']['process'] = 2;    //封盘开奖中
      $Data['current']['countdown'] = $kj_endtime_sjc- $now ;
      $Data['current']['kjtime'] = intval($kj_endtime_sjc);   //下次开奖时间，封盘中则为空
      $Data['current']['fptime'] = 0;
      $Data['current']['serial'] = $kj_times;
      $Data['current']['people'] = rand(100,1000);
      $Data['current']['money'] =   $money;
      $Data['current']['money_double'] =floatval($money);
  }else{

      $Data['current']['process'] = 1;   //倒计时
      $Data['current']['countdown'] = $kj_endtime_sjc - 60 -$now;
      $Data['current']['kjtime'] = intval($kj_endtime_sjc) ;   //下次开奖时间，封盘中则为空
      $Data['current']['fptime'] = intval($kj_endtime_sjc-60) ;
      $Data['current']['serial'] = $kj_times;
      $Data['current']['people'] = rand(100,1000);
      $Data['current']['money'] =   $money;
      $Data['current']['money_double'] = floatval($money);

  }

  $one=1;
  $two=2;
  $dosql->Execute("select kj_times as serial, kj_mdhi as time, kj_varchar as value from pmw_lotterynumber  where  kj_endtime_sjc < $now order by id desc limit 0,10",$two);
  for($i=0;$i<$dosql->GetTotalRow($two);$i++)
  {
      $row = $dosql->GetArray($two);
      $Data['current']['history'][$i]=$row;
  }
  $history=array();
  $history=array_reverse($Data['current']['history']);
  $Data['current']['history']=$history;
   $dosql->Execute("SELECT * FROM `pmw_message` order by id desc limit 0,20",$one);
   for($i=0;$i<$dosql->GetTotalRow($one);$i++)
   {
       $row1 = $dosql->GetArray($one);
       $content=array();
       $content=json_decode($row1['content'],true);

       if($row1['type']=="system_xz"){
         $xiazu_arr=array();
         $xiazhu_arr= $content['items'];
         foreach($xiazhu_arr as $key=> $val){
             foreach($val as $k=> $t){
               $type_arr=explode("+",$val['type']);
               $ml=$type_arr[0];
               $lb=$type_arr[1];
               if($ml==1){
               $types =  tochange($ml,$lb);
                }else{
               $types= $lb;
                }
               $xiazhu_arr[$key]['type']= $types;
            }
        }
       }


       $Data['message'][$i]['content']=$content;
      $Data['message'][$i]['type']=$row1['type'];
   }

   $message=array();
   $message=array_reverse($Data['message']);

   $Data['message']=$message;


  if($dosql->GetTotalRow($one)>0){
    $State = 1;
    $Descriptor = '数据获取成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data,
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '数据获取失败';
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
