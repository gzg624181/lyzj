<?php
require_once('../include/config.inc.php');

//每天凌晨自动操作功能（默认将还未接的行程，自动改为已失效）

$complete_y = date("Y");

$complete_ym = date("Y-m");

$starttime_ymd=date("Y-m-d");

$complete_time= time();

// 有导游预约了此行程，但是旅行社没有确认是哪一个导游接了行程，所以在行程出发的前一天，系统自动将行程状态改为 state=2 ，
$sql="UPDATE pmw_travel SET state=2,complete_y='$complete_y',complete_ym='$complete_ym',complete_time='$complete_time' where state=1 and starttime_ymd='$starttime_ymd'";

$dosql->ExecNoneQuery($sql);

//将所有旅行社已经发布行程，但是还未有导游接单的行程，弄成已失效

$sql="UPDATE pmw_travel SET state=4 where state=0 and yuyue_num=0 and starttime <= '$complete_time'";

$dosql->ExecNoneQuery($sql);

?>
