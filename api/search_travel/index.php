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
     * (有字段的时候有搜索的关键字，如果没有的话 则不存在这个字段）
     *  用户的openid    （有这个字段，有可能为空）
     * 当前用户的定位    live_province  live_city
     * 有可能不存在，当存在的时候（有可能两个值都是 undefined也有可能传了定位的信息过来）
     *     搜索结果     list
     *     搜索历史记录  searchlist
     *     推荐记录     recommand
     */
require_once("../../include/config.inc.php");
header("Content-type:applicaton/json; charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){


    if(isset($keyword) && $keyword!=""){

      if(strpos($keyword,"-")){
            $starttime_ymd = $keyword; // 搜索行程开始时间
      }elseif(is_numeric($keyword)){
            $days = $keyword;          //搜索行程天数
      }else{
            $title = $keyword;        // 搜索行程的标题或者 行程的定位
      }

        //当没有定位的时候查询搜索的关键字
      if($live_city=="undefined" && $live_province=="undefined"){

        if(isset($title)){

        $dosql->Execute("SELECT * FROM pmw_travel where (title like '%$title%' or live_province like '%$title%' or live_city like '%$title%')   and  (state=0 or state=1) and yuyue_num<3   order by id desc ");

        }elseif(isset($starttime_ymd)){

        $dosql->Execute("SELECT * FROM pmw_travel where starttime_ymd ='$starttime_ymd' and (state=0 or state=1) and yuyue_num<3   order by id desc ");

        }elseif(isset($days)){

        $dosql->Execute("SELECT * FROM pmw_travel where days=$days  and (state=0 or state=1) and yuyue_num<3  order by id desc ");

        }
      //当有定位的信息的时候，则调用定位信息（进行搜索查询）
      }else{

      if(isset($title)){

      $dosql->Execute("SELECT * FROM pmw_travel where (title like '%$title%' or live_province like '%$title%' or live_city like '%$title%')  and  (state=0 or state=1) and yuyue_num<3   order by id desc ");

      }elseif(isset($starttime_ymd)){

      $dosql->Execute("SELECT * FROM pmw_travel where starttime_ymd ='$starttime_ymd' and live_province='$live_province' and live_city='$live_city' and (state=0 or state=1) and yuyue_num<3  order by id desc ");

      }elseif(isset($days)){

      $dosql->Execute("SELECT * FROM pmw_travel where days=$days and live_province='$live_province' and live_city='$live_city' and (state=0 or state=1) and yuyue_num<3 order by id desc ");

      }
      }

      $num=$dosql->GetTotalRow();//获取数据条数

      }else{
      //当没有关键字搜索的时候
       $num=0;
      }


      if($num>0){

        #list        搜索内容
        for($i=0;$i<$num;$i++){
           $row=$dosql->GetArray();
           $Data['list'][$i]=$row;
           $tid = $row['id'];  //行程id
           $three=3;
           $dosql->Execute("SELECT b.name,b.images FROM pmw_guide_confirm a inner join pmw_guide b  on a.gid=b.id where a.tid= $tid and a.checkinfo=1",$three);
           $nums=$dosql->GetTotalRow($three);//获取数据条数
           if($nums>0){
           for($j=0;$j<$nums;$j++){
           $show=$dosql->GetArray($three);
           $Data['list'][$i]['guide'][$j]=$show;
           $images = $show['images'];
           if(!check_str($images,"https")){
             $Data['list'][$i]['guide'][$j]['images']=$cfg_weburl."/".$images;
           }
           }
           }else{
           $Data['list'][$i]['guide']= array();
           }
        }

        //如果有搜索的内容的时候，则将数据保存到搜索历史表里面,如果有用户的openid则保存，没有则不保存
         if(isset($openid) && $openid!=""){

           //判断搜索历史表里面是否有相同的搜索关键字
           $r=$dosql->GetOne("SELECT keyword FROM pmw_searchlist where openid='$openid' and type=0 and keyword='$keyword'");

           if(!is_array($r)){
             //往搜索历史记录表里面添加搜索记录
             $posttime = time();
             $sql="INSERT INTO  `#@__searchlist` (keyword,openid,posttime,type) values ('$keyword','$openid',$posttime,0)";
              $dosql->ExecNoneQuery($sql);
           }

           //查询历史搜索记录（显示最新的五条搜索记录）
           # searchlist  搜索历史
           $two=2;
           $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid'  and type=0 order by id desc limit  5",$two);
           for($i=0;$i<$dosql->GetTotalRow($two);$i++){
            $show=$dosql->GetArray($two);
            $Data['searchlist'][$i]=$show;
           }
         }else{
            $Data['searchlist']=array();
         }

        //默认推荐四条数据
        # recommand

        //默认推荐四条景点的数据(当传过来的值为underfined的时候的时候)
       if($live_city=="undefined" && $live_province=="undefined"){

        $four=4;
        $dosql->Execute("SELECT * from pmw_travel where (state=0 or state=1) and yuyue_num<3  order by rand() limit 4",$four);
        $num = $dosql->GetTotalRow($four);
        if($num>0){
          for($i=0;$i<$num;$i++){
            $sow = $dosql->GetArray($four);
            $Data['recommand'][$i]= $sow;
            $tid = $sow['id'];
            //推荐
            $thr = 33;
            $dosql->Execute("SELECT b.name,b.images FROM pmw_guide_confirm a inner join pmw_guide b  on a.gid=b.id where a.tid= $tid and a.checkinfo=1",$thr);
            $nums=$dosql->GetTotalRow($thr);//获取数据条数
            if($nums>0){
            for($j=0;$j<$nums;$j++){
            $show=$dosql->GetArray($thr);
            $Data['recommand'][$i]['guide'][$j]=$show;
            $images = $show['images'];
            if(!check_str($images,"https")){
              $Data['recommand'][$i]['guide'][$j]['images']=$cfg_weburl."/".$images;
            }
            }
          }else{
            $Data['recommand'][$i]['guide']= array();
          }
          }
        }
     //当有定位的时候，则根据定位的来推荐
      }else{
        $four=4;
        $dosql->Execute("SELECT * from pmw_travel where live_province='$live_province' and live_city='$live_city' and  (state=0 or state=1) and yuyue_num<3  order by rand() limit 4",$four);
        $num = $dosql->GetTotalRow($four);
        if($num>0){
          for($i=0;$i<$num;$i++){
            $sow = $dosql->GetArray($four);
            $Data['recommand'][$i]= $sow;
            $tid = $sow['id'];
            //推荐
            $thr = 33;
            $dosql->Execute("SELECT b.name,b.images FROM pmw_guide_confirm a inner join pmw_guide b  on a.gid=b.id where a.tid= $tid and a.checkinfo=1",$thr);
            $nums=$dosql->GetTotalRow($thr);//获取数据条数
            if($nums>0){
            for($j=0;$j<$nums;$j++){
            $show=$dosql->GetArray($thr);
            $Data['recommand'][$i]['guide'][$j]=$show;
            $images = $show['images'];
            if(!check_str($images,"https")){
              $Data['recommand'][$i]['guide'][$j]['images']=$cfg_weburl."/".$images;
            }
            }
          }else{
            $Data['recommand'][$i]['guide']= array();
          }
          }
        }
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
      //当搜索不到行程的时候，则只需要将搜索历史记录和推荐弄出来
         $Data['list'] = array();  //搜索结果为空
         //如果有搜索的内容的时候，则将数据保存到搜索历史表里面,如果有用户的openid则保存，没有则不保存
          if(isset($openid) && $openid!=""){
            # searchlist  搜索历史
            $two=2;
            $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid'  and type=0 order by id desc limit  5",$two);
            for($i=0;$i<$dosql->GetTotalRow($two);$i++){
             $show=$dosql->GetArray($two);
             $Data['searchlist'][$i]=$show;
            }
          }else{
             $Data['searchlist']=array();
          }

      // 搜索内容为空，将推荐几条数据到前端
      //默认推荐四条景点的数据(当传过来的值为underfined的时候的时候)
     if($live_city=="undefined" && $live_province=="undefined"){

      $four=4;
      $dosql->Execute("SELECT * from pmw_travel where (state=0 or state=1) and yuyue_num<3  order by rand() limit 4",$four);
      $num = $dosql->GetTotalRow();
      if($num>0){
        for($i=0;$i<$num;$i++){
          $sow = $dosql->GetArray($four);
          $Data['recommand'][$i]= $sow;
          $tid = $sow['id'];
          //推荐
          $thr = 33;
          $dosql->Execute("SELECT b.name,b.images FROM pmw_guide_confirm a inner join pmw_guide b  on a.gid=b.id where a.tid= $tid and a.checkinfo=1",$thr);
          $nums=$dosql->GetTotalRow($thr);//获取数据条数
          if($nums>0){
          for($j=0;$j<$nums;$j++){
          $show=$dosql->GetArray($thr);
          $Data['recommand'][$i]['guide'][$j]=$show;
          $images = $show['images'];
          if(!check_str($images,"https")){
            $Data['recommand'][$i]['guide'][$j]['images']=$cfg_weburl."/".$images;
          }
          }
        }else{
          $Data['recommand'][$i]['guide']= array();
        }
        }
      }
   //当有定位的时候，则根据定位的来推荐
    }else{
      $four=4;
      $dosql->Execute("SELECT * from pmw_travel where live_province='$live_province' and live_city='$live_city' and  (state=0 or state=1) and yuyue_num<3  order by rand() limit 4",$four);
      $num = $dosql->GetTotalRow($four);
      if($num>0){
        for($i=0;$i<$num;$i++){
          $sow = $dosql->GetArray($four);
          $Data['recommand'][$i]= $sow;
          $tid = $sow['id'];
          //推荐
          $thr = 33;
          $dosql->Execute("SELECT b.name,b.images FROM pmw_guide_confirm a inner join pmw_guide b  on a.gid=b.id where a.tid= $tid and a.checkinfo=1",$thr);
          $nums=$dosql->GetTotalRow($thr);//获取数据条数
          if($nums>0){
          for($j=0;$j<$nums;$j++){
          $show=$dosql->GetArray($thr);
          $Data['recommand'][$i]['guide'][$j]=$show;
          $images = $show['images'];
          if(!check_str($images,"https")){
            $Data['recommand'][$i]['guide'][$j]['images']=$cfg_weburl."/".$images;
          }
          }
        }else{
          $Data['recommand'][$i]['guide']= array();
        }
        }
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
