<?php
    /**
	   * 链接地址：用户文本消息  user_text
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
    *     "gameid": 5,  游戏id
    *         "text": [
    *                 {
    *                   "type": "touzhu",  右侧栏旁边点击 投注数据             chat 聊天 下注
    *                   "content": "数据"       内容                                   下注内容
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
$type = $json['text'][0]['type'];
$arr=array();
$arr=$json['text'];

if(isset($token) && $token==$cfg_auth_key){
  $now=time();
  $Data=array();
  $k=$dosql->GetOne("select telephone,nickname,money from pmw_members where  id = $uid");
  $money = sprintf("%.2f",$k['money']);
  $nickname = $k['nickname'];
  $telephone = $k['telephone'];

  $r=$dosql->GetOne("select * from pmw_lotterynumber where  kj_endtime_sjc >= $now order by id asc");
  $kj_times = $r['kj_times'];

  $s=$dosql->GetOne("select xiazhu_orderid from pmw_xiazhuorder where  xiazhu_sum ='$kj_times'");

  if(!is_array($s)){  //用户暂未下注

      $Data['type'] = $type;
      $Data['nickname'] = $nickname;
      $Data['telephone'] = hidtel($telephone);
      $Data['money'] = intval($money);
      $Data['content'] = "本期下注：暂无下注"."余：".$money."元";
  }else{
      $xiazhu_orderid=$s['xiazhu_orderid'];
      $Data['type'] = $type;
      $Data['nickname'] = $nickname;
      $Data['telephone'] = hidtel($telephone);
      $Data['money'] = intval($money);
      $dosql->Execute("SELECT xiazhu_type,xiazhu_money from pmw_xiazhuorder where xiazhu_orderid='$kj_times'");
    for($i=0;$i<$dosql->GetTotalRow();$i++)
    {
        $row = $dosql->GetArray();
        $Data['current']['content'][$i]=$row;
    }
  }


   $content=phpver($Data);
   $sql = "INSERT INTO `#@__message` (type,content,timestamp) VALUES ('user_text','$content',$timestamp)";
   $dosql->ExecNoneQuery($sql);


    $State = 1;
    $Descriptor = '投注插入成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
        );
    echo phpver($result);

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
