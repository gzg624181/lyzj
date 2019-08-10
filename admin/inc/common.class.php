<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2018-2019 phpMyWind.com
update: 2019-08-08 16:55:36
person: Gang
**************************
*/
   class Agency {

     //点击之后，则指定将新的注册的新信息清空


     function update_agency_travel($type){

       global $dosql;

       # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1

       if($type == "agency"){
         $id = 82;
       }elseif($type=="travel"){
         $id = 6;
       }

       $r= $dosql->GetOne("SELECT newmessage from pmw_infoclass_left where id=$id");

       //计算当前的栏目总的注册数量
       $nums = $r['newmessage'];

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = 0 where id=$id");

      # 2.同时更新旅行社栏目的总的数量

       $r= $dosql->GetOne("SELECT newmessage from pmw_infoclass_left where id=5");

       $newmessage = $r['newmessage'];

       if($newmessage!=0){

         $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage -$nums where id=5");

       }

     }
   }


   class Guide {

     //点击之后，则指定将新的注册的新信息清空


     function update_guide_freetime($type){

       global $dosql;

       # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1

       if($type == "guide"){
         $id = 21;
       }elseif($type=="freetime"){
         $id = 22;
       }

       $r= $dosql->GetOne("SELECT newmessage from pmw_infoclass_left where id=$id");

       //计算当前的栏目总的注册数量
       $nums = $r['newmessage'];

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = 0 where id=$id");

      # 2.同时更新旅行社栏目的总的数量

       $r= $dosql->GetOne("SELECT newmessage from pmw_infoclass_left where id=8");

       $newmessage = $r['newmessage'];

       if($newmessage!=0){

         $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage -$nums where id=8");

       }

     }
   }

   class Order {

     //点击之后，则指定将新的注册的新信息清空

     function update_order($type){

       global $dosql;

       # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1

       if($type == "order"){
         $id = 48;
       }

       $r= $dosql->GetOne("SELECT newmessage from pmw_infoclass_left where id=$id");

       //计算当前的栏目总的注册数量
       $nums = $r['newmessage'];

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = 0 where id=$id");

      # 2.同时更新旅行社栏目的总的数量

       $r= $dosql->GetOne("SELECT newmessage from pmw_infoclass_left where id=47");

       $newmessage = $r['newmessage'];

       if($newmessage!=0){

         $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage -$nums where id=47");

       }

     }
   }


   class Comment {

     //点击之后，则指定将新的注册的新信息清空

     function update_comment_fankui($type){

       global $dosql;

       # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1

       if($type == "comment"){
         $id = 43;
       }elseif($type=="fankui"){
         $id = 93;
       }

       $r= $dosql->GetOne("SELECT newmessage from pmw_infoclass_left where id=$id");

       //计算当前的栏目总的注册数量
       $nums = $r['newmessage'];

       $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = 0 where id=$id");

      # 2.同时更新旅行社栏目的总的数量

       $r= $dosql->GetOne("SELECT newmessage from pmw_infoclass_left where id=42");

       $newmessage = $r['newmessage'];

       if($newmessage!=0){

         $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage -$nums where id=42");

       }

     }
   }
