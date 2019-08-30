<?php
    /**
	   * 链接地址：get_search_scenic  搜索景点 关键字模糊搜索
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
     */
require_once("../../include/config.inc.php");
header("Content-type:application/json;charset:utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
    $r=$dosql->GetOne("SELECT imagesurl FROM pmw_share  where id=3");
    $cfg_default = $r['imagesurl'];

    if(isset($keyword)){

    $dosql->Execute("SELECT id,names,label,solds,types,flag,lowmoney,remarks,level,picarr,province,city FROM pmw_ticket where names like '%$keyword%' and  checkinfo=1 order by id desc ");

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
         $picarrTmp=array("0"=>$cfg_weburl."/".$cfg_default);
         $picarr = json_encode($picarrTmp);
         }else{
         $picarr=Common::GetPic($picarr, $cfg_weburl);
         }
         $Data['list'][$i]['picarr']=$picarr;
        }

        //如果有搜索的内容的时候，则将数据保存到搜索历史表里面
         if(isset($openid)){
           //判断搜索历史表里面是否有相同的搜索关键字

           $r=$dosql->GetOne("SELECT keyword FROM pmw_searchlist where openid='$openid' and type=1");

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
         }

         //默认推荐四条景点的数据
         $four=4;
         $dosql->Execute("SELECT * FROM pmw_ticket where checkinfo=1 and province='$province' and city='$city' order by rand() limit 4",$four);
         for($i=0;$i<$dosql->GetTotalRow($four);$i++){
          $row = $dosql->GetArray($four);
          $Data['recommand'][$i]=$row;

          $picarr=stripslashes($row['picarr']);
          if($picarr==""){
          $picarrTmp=array("0"=>$cfg_weburl."/".$cfg_default);
          $picarr = json_encode($picarrTmp);
          }else{
          $picarr=Common::GetPic($picarr, $cfg_weburl);
          }
          $Data['recommand'][$i]['picarr']=$picarr;

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

    //  当搜索数据内容为空的时候，随机选出4个

    $six=6;
    $dosql->Execute("SELECT * FROM pmw_ticket where checkinfo=1  and province='$province' and city='$city' order by rand() limit 4",$six);
    for($i=0;$i<$dosql->GetTotalRow($six);$i++){
     $row = $dosql->GetArray($six);
     $Data['recommand'][$i]=$row;

     $picarr=stripslashes($row['picarr']);
     if($picarr==""){
     $picarrTmp=array("0"=>$cfg_weburl."/".$cfg_default);
     $picarr = json_encode($picarrTmp);
     }else{
     $picarr=Common::GetPic($picarr, $cfg_weburl);
     }
     $Data['recommand'][$i]['picarr']=$picarr;

    }
    if(isset($openid)){
     $five=5;
     $dosql->Execute("SELECT * FROM `#@__searchlist` where openid='$openid' and type=1 order by id desc limit 5",$five);
     for($i=0;$i<$dosql->GetTotalRow($five);$i++){
     $go=$dosql->GetArray($five);
     $Data['searchlist'][$i]=$go;
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
