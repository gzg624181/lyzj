<?php

   require_once("../include/config.inc.php");
   header("content-type:application/json; charset=utf-8");

   if($redis->exists('json')){
     echo 1;

     $arr = $redis->get('json');

     print_r(json_decode($arr,true));
   }
   else{
     echo 0;

   $one=1;
   $two=2;
   $dosql->Execute("SELECT dataname,datavalue FROM `#@__cascadedata` where  datagroup='area' AND level=0 order by orderid asc",$one);
   $num=$dosql->GetTotalRow($one);
   for($i=0;$i<$num;$i++){
   $row=$dosql->GetArray($one);
   $Data[$i]['provice']= $row;
   $province = $row['datavalue'];

   $dosql->Execute("SELECT dataname,datavalue FROM `#@__cascadedata` WHERE `datagroup`='area' AND level=1 AND datavalue > $province AND datavalue< $province + 500  ORDER BY orderid ASC, datavalue ASC",$two);
   for($j=0;$j<$dosql->GetTotalRow($two);$j++){
     $show=$dosql->GetArray($two);
     $Data[$i]['city'][$j]=$show;
   }
   }

   $redis->set('json',json_encode($Data));

   $arr = $redis->get('json');


   print_r(json_decode($arr,true));
}



?>
