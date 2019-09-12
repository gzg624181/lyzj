<?php
    /**
	   * 链接地址：search_travel  搜索行程
	   *
     * 下面直接来连接操作数据库进而得到json串
     *
     * 按json方式输出通信数据
     *
     * @param unknown $State 状态码
     *
     * @param string $Descriptor  提示信息
     *
	   * @param string $Version  操作时间
     *
     * @param array $Data 数据
     *
     * @return string
     *
     * @提供返回参数账号  keyword=>
     *                           行程标题 title
     *                           行程起始时间 starttime_ymd
     *                           行程时间 days
     *  用户的openid    判断是否存在
     * 当前用户的定位    live_province  live_city
     */
require_once("../../include/config.inc.php");
header("Content-type:applicaton/json; charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

   // 1.判断是否存在openid

      if(isset($openid) && $openid!=""){

      if(isset($keyword) && $keyword!=""){

      if(strpos($keyword,"-")){
            $starttime_ymd = $keyword;
      }elseif(is_numeric($keyword)){
            $days = $keyword;
      }else{
            $title = $keyword;
      }

      if($live_city=="undefined" && $live_province=="undefined"){

        if(isset($title)){

        $dosql->Execute("SELECT * FROM pmw_travel where title like '%$title%'  and  state=0  order by id desc ");

        }elseif(isset($starttime_ymd)){

        $dosql->Execute("SELECT * FROM pmw_travel where starttime_ymd ='$starttime_ymd' and state=0  order by id desc ");

        }elseif(isset($days)){

        $dosql->Execute("SELECT * FROM pmw_travel where days=$days  and state=0 order by id desc ");

        }

      }else{

      if(isset($title)){

      $dosql->Execute("SELECT * FROM pmw_travel where title like '%$title%' and  state=0 and live_province='$live_province' and live_city='$live_city' order by id desc ");

      }elseif(isset($starttime_ymd)){

      $dosql->Execute("SELECT * FROM pmw_travel where starttime_ymd ='$starttime_ymd' and live_province='$live_province' and live_city='$live_city' and state=0  order by id desc ");

      }elseif(isset($days)){

      $dosql->Execute("SELECT * FROM pmw_travel where days=$days and live_province='$live_province' and live_city='$live_city' and state=0 order by id desc ");

      }
      }

      $num=$dosql->GetTotalRow();//获取数据条数


      }else{
      //当没有关键字搜索的时候
       $num=0;
      }


      //searchlist  搜索历史
      //list        搜索内容
      //recommand   推荐,加上定位（live_province，live_city  ） 根据用户注册的地理位置来定位

      if($num>0){

      //如果搜索的有数据的时候，则将搜索记录保存到数据库中去
      $two=2;
      $posttime=time();
      $r=$dosql->GetOne("SELECT keyword FROM pmw_searchlist where keyword='$keyword' and openid='$openid'");
      if(!is_array($r)){
      $sql="INSERT INTO  `#@__searchlist` (keyword,openid,posttime) values ('$keyword','$openid',$posttime)";
       $dosql->ExecNoneQuery($sql);
      }

       $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid' and type=0 order by id desc limit  5",$two);
       while($show=$dosql->GetArray($two)){
        $Data['searchlist'][]=$show;
       }

      while($row=$dosql->GetArray()){
        $Data['list'][]=$row;
      }
        //默认推荐四条数据
        $four=4;
        $dosql->Execute("SELECT * from pmw_travel where state=0  order by rand() limit 4",$four);
        while($sow=$dosql->GetArray($four)){
          $Data['recommand'][]=$sow;
        }

        $State = 1;
        $Descriptor = '搜索数据查询成功！';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' => $Data
                     );
        echo phpver($result);
      }else{


          if(isset($keyword) && $keyword!=""){

         if($live_city=="undefined" && $live_province=="undefined"){

           $six=6;
           $dosql->Execute("SELECT * FROM pmw_travel where state=0   order by rand() limit 4",$six);
           while($row=$dosql->GetArray($six)){
           $Data['recommand'][]=$row;
           }

         }else{
           $six=6;
           $dosql->Execute("SELECT * FROM pmw_travel where state=0 and live_province='$live_province' and live_city='$live_city'  order by rand() limit 4",$six);
           while($row=$dosql->GetArray($six)){
           $Data['recommand'][]=$row;
           }
         }
         $five=5;
         $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid' and type=0 order by id desc limit 5",$five);

         while($go=$dosql->GetArray($five)){
         $Data['searchlist'][]=$go;
         }

       }else{

         if($live_city=="undefined" && $live_province=="undefined"){

           $six=6;
           $dosql->Execute("SELECT * FROM pmw_travel where state=0   order by rand() limit 4",$six);
           while($row=$dosql->GetArray($six)){
           $Data['recommand'][]=$row;
           }

         }else{
           $six=6;
           $dosql->Execute("SELECT * FROM pmw_travel where state=0 and live_province='$live_province' and live_city='$live_city'  order by rand() limit 4",$six);
           while($row=$dosql->GetArray($six)){
           $Data['recommand'][]=$row;
           }

         }

        $five=5;
        $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid' and type=0 order by id desc limit 5",$five);

        while($go=$dosql->GetArray($five)){
        $Data['searchlist'][]=$go;
        }
       }

        $State = 0;
        $Descriptor = '搜索数据为空，推荐数据获取成功！';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' => $Data
                     );
        echo phpver($result);
      }


    }else{

    //假如不存在openid的时候
    if($live_city=="undefined" && $live_province=="undefined"){

      $six=6;
      $dosql->Execute("SELECT * FROM pmw_travel where state=0   order by rand() limit 4",$six);
      while($row=$dosql->GetArray($six)){
      $Data['recommand'][]=$row;
      }

    }else{
      $six=6;
      $dosql->Execute("SELECT * FROM pmw_travel where state=0 and live_province='$live_province' and live_city='$live_city'  order by rand() limit 4",$six);
      while($row=$dosql->GetArray($six)){
      $Data['recommand'][]=$row;
      }

    }

   $State = 0;
   $Descriptor = '搜索数据为空，推荐数据获取成功！';
   $result = array (
               'State' => $State,
               'Descriptor' => $Descriptor,
               'Version' => $Version,
               'Data' => $Data
                );
   echo phpver($result);

    }

}else{
  $State = 520;
  $Descriptor = 'token验证失败！';
  $result = array (
                  'State' => $State,
                  'Descriptor' => $Descriptor,
  				         'Version' => $Version,
                   'Data' => $Data,
                   );
  echo phpver($result);
}

?>
