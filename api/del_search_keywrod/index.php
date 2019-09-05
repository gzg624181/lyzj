<?php
    /**
	   * 链接地址： del_search_keyword    删除搜索的关键字
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
     * @
     * id  关键字的id
     *
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //备注 ：添加行程的时候content 内容以json字符串的形式保存在数据库中去

  $sql = "DELETE FROM pmw_searchlist where id=$id and openid='$openid'";

  if($dosql->ExecNoneQuery($sql)){

    $State = 1;
    $Descriptor = '删除成功!';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '删除失败!';
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
