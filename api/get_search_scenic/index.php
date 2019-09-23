<?php
    /**
	   * 链接地址：get_search_scenic  搜索景点门票  关键字模糊搜索
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
     * @提供返回参数账号  keyword=>    title
     * live_province （百分之百传值过来）
     * live_city     （百分之百传值过来）
     *
     *keyword(有字段的时候有搜索的关键字，如果没有的话 则不存在这个字段） 有可能关键字是搜索景点的地理位置
     *openid  （有这个字段，有可能为空）
     *  搜索的同时，将用户的搜索记录保存在历史表里面去

     *  返回值   ：   list                 搜索结果（统计景点所有的搜索结果和当月的购买结果）
     *                                   （景点名称、定位）
     *               searchlist          历史搜索记录
     *               recommand          （不管搜索结果如何，默认显示四条推荐的记录）
     */
require_once("../../include/config.inc.php");
header("Content-type:application/json;charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

    $default_images =  Common::default_images();  //默认景点的图片

    if(isset($keyword)){
    //查询当前景点的关键字或者城市的定位关键字）
    $dosql->Execute("SELECT id,names,label,solds,types,flag,lowmoney,remarks,level,picarr,province,city,live_province,live_city FROM pmw_ticket where (names like '%$keyword%' or live_province like '%$keyword%' or live_city like '%$keyword%') and  checkinfo=1  order by id desc ");
    $num=$dosql->GetTotalRow();//获取数据条数
    }else{
    $num=0;
    }

      if($num > 0){  //搜索结果

        for($i=0;$i<$num;$i++){
         $row = $dosql->GetArray();
         $Data['list'][$i]=$row;

         $picarr=stripslashes($row['picarr']);

         if($picarr==""){
         $picarrTmp=array("0"=>$cfg_weburl."/".$default_images);
         $picarr = json_encode($picarrTmp);
         }else{
         $picarr=Common::GetPic($picarr, $cfg_weburl);
         }

        $Data['list'][$i]['picarr']=$picarr;
        //$month_solds = Common::get_ticket_num($row['id']);
        $Data['list'][$i]['month_solds'] = 0;
        $tid = $row['id'];  //行程id
        $three=3;
        $dosql->Execute("SELECT b.name,b.images FROM pmw_guide_confirm a inner join pmw_guide b  on a.gid=b.id where a.tid= $tid and a.checkinfo=1",$three);
        $nums=$dosql->GetTotalRow($three);//获取数据条数
        if($nums>0){
        for($j=0;$j<$nums;$j++){
        $show=$dosql->GetArray($three);
        $Data['list'][$i]['guide'][$j]=$show;
        }
        }else{
        $Data['list'][$i]['guide']= array();
        }
        }

        //如果有搜索的内容的时候，则将数据保存到搜索历史表里面
         if(isset($openid) && $openid!=""){

           //判断搜索历史表里面是否有相同的搜索关键字
           $r=$dosql->GetOne("SELECT keyword FROM pmw_searchlist where openid='$openid' and type=1 and keyword='$keyword'");

           if(!is_array($r)){
             //往搜索历史记录表里面添加搜索记录
             $posttime = time();
             $sql="INSERT INTO  `#@__searchlist` (keyword,openid,posttime,type) values ('$keyword','$openid',$posttime,1)";
              $dosql->ExecNoneQuery($sql);
           }
           //查询历史搜索记录（显示最新的五条搜索记录）

           $two=2;
           $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid'  and type=1 order by id desc limit  5",$two);
           for($i=0;$i<$dosql->GetTotalRow($two);$i++){
            $show=$dosql->GetArray($two);
            $Data['searchlist'][$i]=$show;
           }
         }else{
            $Data['searchlist']=array();
         }

           //默认推荐四条景点的数据(当传过来的值为underfined的时候的时候)
          if($live_city=="undefined" && $live_province=="undefined"){

            $four=4;
            $dosql->Execute("SELECT * FROM pmw_ticket where checkinfo=1  order by rand() limit 4",$four);
            for($i=0;$i<$dosql->GetTotalRow($four);$i++){
             $row = $dosql->GetArray($four);
             $Data['recommand'][$i]=$row;

             $picarr=stripslashes($row['picarr']);
             if($picarr==""){
               $picarrTmp=array("0"=>$cfg_weburl."/".$default_images);
               $picarr = json_encode($picarrTmp);
             }else{
               $picarr=Common::GetPic($picarr, $cfg_weburl);
             }
             $Data['recommand'][$i]['picarr']=$picarr;
             $Data['recommand'][$i]['month_solds'] = Common::get_ticket_num($row['id']);
             $tid = $row['id'];
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

          }else{
         //默认推荐四条景点的数据，当有具体的城市的定位的时候
           $four=4;
           $dosql->Execute("SELECT * FROM pmw_ticket where checkinfo=1 and live_province='$live_province' and live_city='$live_city' order by rand() limit 4",$four);
           for($i=0;$i<$dosql->GetTotalRow($four);$i++){
            $row = $dosql->GetArray($four);
            $Data['recommand'][$i]=$row;

            $picarr=stripslashes($row['picarr']);
            if($picarr==""){
              $picarrTmp=array("0"=>$cfg_weburl."/".$default_images);
              $picarr = json_encode($picarrTmp);
            }else{
              $picarr=Common::GetPic($picarr, $cfg_weburl);
            }
            $Data['recommand'][$i]['picarr']=$picarr;
            $Data['recommand'][$i]['month_solds'] = Common::get_ticket_num($row['id']);
            $tid = $row['id'];
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
     //没有搜索的内容，则将搜索记录为空
     $Data['list'] = array();
    //没有搜索到内容，则将搜索的历史记录显示出来
     if(isset($openid) && $openid!=""){
       //查询历史搜索记录（显示最新的五条搜索记录）
       $two=2;
       $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid'  and type=1 order by id desc limit  5",$two);
       $numbers = $dosql->GetTotalRow($two);
       for($i=0;$i<$numbers;$i++){
        $show=$dosql->GetArray($two);
        $Data['searchlist'][$i]=$show;
       }
     }else{
       $Data['searchlist']= array();
     }


     //默认推荐四条景点的数据(当传过来的值为underfined的时候的时候)
    if($live_city=="undefined" && $live_province=="undefined"){

        $four=4;
        $dosql->Execute("SELECT * FROM pmw_ticket where checkinfo=1  order by rand() limit 4",$four);
        for($i=0;$i<$dosql->GetTotalRow($four);$i++){
         $row = $dosql->GetArray($four);
         $Data['recommand'][$i]=$row;

         $picarr=stripslashes($row['picarr']);
         if($picarr==""){
           $picarrTmp=array("0"=>$cfg_weburl."/".$default_images);
           $picarr = json_encode($picarrTmp);
         }else{
           $picarr=Common::GetPic($picarr, $cfg_weburl);
         }
         $Data['recommand'][$i]['picarr']=$picarr;
         $Data['recommand'][$i]['month_solds'] = Common::get_ticket_num($row['id']);
         $tid = $row['id'];
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

    }else{
      //默认推荐四条景点的数据，当有具体的城市的定位的时候
       $four=4;
       $dosql->Execute("SELECT * FROM pmw_ticket where checkinfo=1 and live_province='$live_province' and live_city='$live_city' order by rand() limit 4",$four);
       for($i=0;$i<$dosql->GetTotalRow($four);$i++){
        $row = $dosql->GetArray($four);
        $Data['recommand'][$i]=$row;

        $picarr=stripslashes($row['picarr']);
        if($picarr==""){
          $picarrTmp=array("0"=>$cfg_weburl."/".$default_images);
          $picarr = json_encode($picarrTmp);
        }else{
          $picarr=Common::GetPic($picarr, $cfg_weburl);
        }
        $Data['recommand'][$i]['picarr']=$picarr;
        $Data['recommand'][$i]['month_solds'] = Common::get_ticket_num($row['id']);
        $tid = $row['id'];
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
        $State = 0;
        $Descriptor = '搜索数据为空！';
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
