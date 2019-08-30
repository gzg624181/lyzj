<?php
require_once('../include/config.inc.php');

// 更改所有用户的状态 checkinfo 为0

$sql="UPDATE pmw_member SET checkinfo=0";

$dosql->ExecNoneQuery($sql);


?>
