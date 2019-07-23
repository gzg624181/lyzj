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
     * @提供返回参数账号  keyword=>   行程标题 title   行程起始时间 starttime_ymd   行程时间 days
     *                   用户的openid
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

    if(isset($keyword)){

    if(strpos($keyword,"-")){
          $starttime_ymd = $keyword;
    }elseif(is_numeric($keyword)){
          $days = $keyword;
    }else{
          $title = $keyword;
    }

    if(isset($title)){

      preg_match_all("/./u", $title, $arr);

      $arr=$arr[0];
      $sql1="";
      for($i=0;$i<count($arr);$i++){
      if($i==count($arr)-1){
        $sql1 .= " title like "."'%".$arr[$i]."%'";
      }else{
        $sql1 .= " title like "."'%".$arr[$i]."%'". " or ";
       }
      }

      $sql = "SELECT * FROM pmw_travel where state=0  and ";

      $sql .= "(".$sql1.")"." order by id desc";


      $dosql->Execute($sql);

    }elseif(isset($starttime_ymd)){

    $dosql->Execute("SELECT * FROM pmw_travel where starttime_ymd ='$starttime_ymd' and state=0 order by id desc ");

    }elseif(isset($days)){

    $dosql->Execute("SELECT * FROM pmw_travel where days=$days and state=0 order by id desc ");

    }

    $num=$dosql->GetTotalRow();//获取数据条数

   }else{

   //当没有关键字搜索的时候
   $num=0;

   }

    //searchlist  搜索历史
    //list        搜索内容
    //recommand   推荐

    if($num>0){

    //如果搜索的有数据的时候，则将搜索记录保存到数据库中去
    if(isset($openid)){

     while($row=$dosql->GetArray()){
        $Data['list'][]=$row;
      }

    $two=2;
    $posttime=time();
    //判断历史搜索记录表里面是否有相同的字段

    $r=$dosql->GetOne("SELECT keyword FROM pmw_searchlist where keyword='$keyword' and openid='$openid' and type=0");

    //假如搜索历史记录里面没有这条记录
    if(!is_array($r)){

   //判断这个用户的历史搜索记录里面一起有多少条数据
   $dosql->Execute("SELECT id FROM pmw_searchlist where openid='$openid' and type=0");
   $nums = $dosql->GetTotalRow();

    //每个用户最多只能保存五条数据
      if($nums==5){
        //自动将保存的五条数据的最后一条数据删除掉，同时往搜索历史表里面插入新的数据
        $k=$dosql->GetOne("SELECT id from pmw_searchlist where openid='$openid' and type=1 order by id asc");
        $last_id = $k['id'];
        //删除倒数排序最后一条数据
        $dosql->ExecNoneQuery("DELETE from pmw_searchlist where id=$last_id");
        //往搜索历史记录表里面添加搜索记录
        $posttime = time();
        $sql="INSERT INTO  `#@__searchlist` (keyword,openid,posttime,type) values ('$keyword','$openid',$posttime,0)";
         $dosql->ExecNoneQuery($sql);
      }else{
        //往搜索历史记录表里面添加搜索记录
        $posttime = time();
        $sql="INSERT INTO  `#@__searchlist` (keyword,openid,posttime,type) values ('$keyword','$openid',$posttime,0)";
         $dosql->ExecNoneQuery($sql);
      }
    //假如搜索记录里面有这个关键字的记录
     }else{
       //判断这个用户的历史搜索记录有几条数据
       $dosql->Execute("SELECT id from pmw_searchlist where openid='$openid' and type=0 order by id desc");
       $nums = $dosql->GetTotalRow();
       //每个用户最多只能保存五条数据
       if($nums<=5){
         //删除已经存在的这条关键字的记录
         $dosql->ExecNoneQuery("DELETE from pmw_searchlist where openid='$openid' and type=0 and keyword='$keyword'");
         //保存最新的搜索关键字
         //往搜索历史记录表里面添加搜索记录
         $posttime = time();
         $sql="INSERT INTO  `#@__searchlist` (keyword,openid,posttime,type) values ('$keyword','$openid',$posttime,0)";
          $dosql->ExecNoneQuery($sql);
       }
     }

     //查询历史搜索记录（显示最新的五条搜索记录）
     $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid' and type=0 order by id desc limit  5",$two);
     while($show=$dosql->GetArray($two)){
      $Data['searchlist'][]=$show;
     }

   }


      //默认推荐四条数据
      $four=4;
      $dosql->Execute("SELECT * from pmw_travel where state=0 order by rand() limit 4",$four);
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
      $six=6;
      $dosql->Execute("SELECT * FROM pmw_travel where state=0 order by rand() limit 4",$six);
      while($row=$dosql->GetArray($six)){
      $Data['recommand'][]=$row;
      }
      if(isset($openid)){
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
