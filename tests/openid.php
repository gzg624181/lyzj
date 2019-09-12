<?php

require_once("../include/config.inc.php");

$code = "061HBX611bZClX1t6i9110tN611HBX6s";
$openid = Common::openid($code,$cfg_appid,$cfg_appsecret);

echo $openid;

$id=282;
$dosql->ExecNoneQuery("UPDATE `#@__travel` set yuyue_num = yuyue_num +1 where id=$id");
$get_travel_arr = Guide::get_travel($id);
$yuyue_num = $get_travel_arr['yuyue_num'];


echo $yuyue_num;



 ?>
