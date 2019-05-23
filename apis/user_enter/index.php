<?php
    /**
	   * 链接地址：用户进入房间文本消息  user_enter
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
    *"token": "wFu1lIfZcfhWf3IX",
    *"uid": 47,
    *"timestamp": 1556230200,
    *"gameid":5,
    *"type":"user_enter"
*}
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
$type = $json['type'];  //投注


if(isset($token) && $token==$cfg_auth_key){
  $now=time();
  $Data=array();
  $k=$dosql->GetOne("SELECT telephone,nickname,money from pmw_members where  id = $uid");
  $nickname = $k['nickname'];


  $Data['type'] = $type;
  $Data['nickname'] = $nickname;
  $Data['uid'] = $uid;
  $Data['gameid'] = intval($gameid);
  $Data['content'] = "欢迎".$nickname."进入房间";


   $content=phpver($Data);
   $sql = "INSERT INTO `#@__message` (type,content,timestamp) VALUES ('user_enter','$content',$timestamp)";
   $dosql->ExecNoneQuery($sql);


    $State = 1;
    $Descriptor = '用户进入房间成功！';
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
