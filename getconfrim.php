<?php
require_once(dirname(__FILE__).'/include/config.inc.php');


//将所有导游已经接单，但是还未确认的系统自动确认
# 更改行程为确认
$complete_y = date("Y");

$complete_ym = date("Y-m");

$starttime_ymd=date("Y-m-d");

$sql="UPDATE pmw_travel SET state=2 where state=1 and starttime_ymd='$starttime_ymd'";

$dosql->ExecNoneQuery($sql);

?>
