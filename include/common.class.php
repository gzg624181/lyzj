<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2018-2019 phpMyWind.com
update: 2019-08-08 16:55:36
person: Gang
**************************
*/


class  Agency {

    // 计算新注册旅行社的数量

    function get_agency_travel($type){

      global $dosql;

      # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1
      if($type == "agency"){
        $id = 82;
      }elseif($type=="travel"){
        $id = 6;
      }
      $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=$id");

     # 2.同时更新旅行社栏目的总的数量

      $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=5");

     }
}


class  Guide {

    // 计算新注册导游的数量

    function get_guide_freetime($type){

      global $dosql;

      # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1
      if($type == "guide"){
        $id = 21;
      }elseif($type=="freetime"){
        $id = 22;
      }
      $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=$id");

     # 2.同时更新旅行社栏目的总的数量

      $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=8");

     }


}

class  Order {

    // 计算新注册导游的数量

    function get_order($type){

      global $dosql;

      # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1
      if($type == "order"){
        $id = 48;
      }

      $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=$id");

     # 2.同时更新旅行社栏目的总的数量

      $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=47");

     }
}


class  Comment {

    // 计算新注册导游的数量

    function get_comment($type){

      global $dosql;

      # 1.当有新的用户注册的时候，则自动将旅行社表里面的数据+1
      if($type == "comment"){
        $id = 43;
      }elseif($type=="fankui"){
        $id=  93;
      }

      $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=$id");

     # 2.同时更新旅行社栏目的总的数量

      $dosql->ExecNoneQuery("UPDATE pmw_infoclass_left SET newmessage = newmessage + 1 where id=42");

     }
}
