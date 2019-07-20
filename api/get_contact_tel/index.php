<?php
    /**
<<<<<<< HEAD
<<<<<<< HEAD:api/get_contact_tel/index.php
	   * 链接地址：get_contact_tel  获取系统联系电话
=======
	   * 链接地址：get_agency_num  获取旅行社已经接团成功的
>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587:api/get_agency_num/index.php
=======
	   * 链接地址：get_contact_tel  获取系统联系电话
>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587
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
<<<<<<< HEAD
<<<<<<< HEAD:api/get_contact_tel/index.php
     * @提供返回参数账号
=======
     * @提供返回参数账号 旅行社id
>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587:api/get_agency_num/index.php
=======
     * @提供返回参数账号
>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

<<<<<<< HEAD
<<<<<<< HEAD:api/get_contact_tel/index.php


=======
      $arr=get_agency_num($id);
>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587:api/get_agency_num/index.php
=======


>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587
      $State = 1;
      $Descriptor = '数据获取成功！';
      $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
                  'Version' => $Version,
<<<<<<< HEAD
<<<<<<< HEAD:api/get_contact_tel/index.php
                  'Data' => $cfg_contact_tel
=======
                  'Data' => $arr
>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587:api/get_agency_num/index.php
=======
                  'Data' => $cfg_contact_tel
>>>>>>> fce197250f6cdcc1f69b07457834e5d555fdb587
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
