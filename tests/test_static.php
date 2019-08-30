<?php

require_once("../include/config.inc.php");



$r=$dosql->GetOne("SELECT * FROM `#@__guide` WHERE id=91");

//print_r($r);

$agreement=stripslashes($r['agreement']);
$agreement=Common::GetPic($agreement, $cfg_weburl);
$pics=stripslashes($r['pics']);
$pics=Common::GetPics($pics, $cfg_weburl);


//echo $pics;
$cardidnumber="421127198805080011";

$idc=Common::is_idcard($cardidnumber);

if($idc){

  echo "真";
}else{
  echo "假";
}
?>
