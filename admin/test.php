<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('admanage');


$var= "345";
$my_arr = str_split($var);
$var =$my_arr[0]."+".$my_arr[1]."+".$my_arr[2]."=".array_sum($my_arr);
echo $var;










?>
