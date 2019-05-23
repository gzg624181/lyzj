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
    *                   "type": "history",  右侧栏旁边点击 投注数据             chat 聊天 下注
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
   $contents="";
  $dosql->Execute("SELECT kj_he from pmw_lotterynumber where kj_number <> '' order by id desc limit 0,10");
    for($i=0;$i<$dosql->GetTotalRow();$i++)
    {
        $row = $dosql->GetArray();
        if($i==9){
          $contents .=$row['kj_he'];
        }else{
          $contents .=$row['kj_he'].",";
        }
    }
    $Data['content']=$contents;
    $Data['type']=$type;

   $content=phpver($Data);
   $sql = "INSERT INTO `#@__message` (type,content,timestamp) VALUES ('user_text','$content',$timestamp)";
   $dosql->ExecNoneQuery($sql);
    $State = 1;
    $Descriptor = '开奖历史查询成功！';
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
