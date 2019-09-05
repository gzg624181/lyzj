<?php

require_once("../../include/config.inc.php");
header("Content-type:application/json; charset:utf-8");

$starttime = "1568217600";
$title ="测试空闲时间数据";
$province = "9000";
$city = "9001";

  Guide::Send_Remind($starttime,$title,$province,$city);

 ?>
