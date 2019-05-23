<?php
    /**
	   * 链接地址：system_kj  系统定时器判断当前的状态是出于开奖
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

     * @param array $Data 返回数据
     *
     * @return string
     *
     */
   	require_once(dirname(__FILE__).'/include/config.inc.php');
   $now=time();
   $tbname="pmw_lotterynumber";
   //执行开奖的同时，第一步先获取开奖号码
   $arr = file_get_contents(stripslashes("http://api.dabai28.com/?service=Get.jnd28"));  //去除对象里面的斜杠
   $srr = json_decode($arr,true);
   $state=$srr['ret'];
   if($state==200){
     $array=$srr['data'][0];
     $issue=$array['issue'];  //当前最新的开奖期数
     $kj_number=$array['c1'].$array['c2'].$array['c3'];
     $kj_he=$array['c4'];
     $kj_varchar=$array['c1']."+".$array['c2']."+".$array['c3']."=".$kj_he.results($kj_he);
     $addtime=date("Y-m-d H:i:s");
     $kj_maketime=date("Y-m-d");
     $kj_mdhi=date("m-d H:i:s",$now);
     //更新最新的开奖结果到数据库里面来
     $sql = "UPDATE `$tbname` SET kj_number='$kj_number', kj_varchar='$kj_varchar', kj_he=$kj_he, kj_maketime='$kj_maketime', addtime='$addtime', addtimestamp=$now, kj_endtime_sjc=$now, kj_endtime='$addtime',kj_mdhi='$kj_mdhi', kj_state=1, state='kj' WHERE kj_times=$issue";
   	 $dosql->ExecNoneQuery($sql);
     //生成一条新的即将开奖的信息
     $kj_endtime_sjc=$now+210; //开奖完毕之后，大概的下一期开奖时间为210s之后
     $kj_mdhi=date("m-d H:i:s",$kj_endtime_sjc);
     $kj_endtime=date("Y-m-d H:i:s",$kj_endtime_sjc);
     $state='xz'; //当前处于下注状态
     $kj_times=$issue + 1;
     $sql = "INSERT INTO `pmw_lotterynumber` (kj_times,kj_endtime,kj_endtime_sjc,kj_mdhi,state) VALUES ($kj_times, '$kj_endtime','$kj_endtime_sjc', '$kj_mdhi', '$state')";
     $dosql->ExecNoneQuery($sql);
  }


   $r=$dosql->GetOne("SELECT addtime,kj_varchar,kj_number,kj_he,addtimestamp FROM pmw_lotterynumber where kj_times=$issue");
   $kj_endtime = substr($r['addtime'],5,11);
   $kj_varchar = $r['kj_varchar'];
   $kj_code = $r['kj_number'];
   $kj_he = $r['kj_he'];
   $tswf= check_teshus($kj_code);  //豹子1  顺子2  对子3

   $content=array(
     "serial" => $issue, //期数
     "time"   => $kj_endtime, //时间
     "value"  => $kj_varchar, //开奖结果
     "code"   => $kj_code,
     "timestamp" => intval($now)
   );
    $actiontime=date("Y-m-d H:i:s",$now);
    $content=phpver($content);
    $one=1;
    $dosql->Execute("SELECT  id from pmw_game where game='Canada28'",$one);
    for($i=0;$i<$dosql->GetTotalRow($one);$i++)
    {
     $show=$dosql->GetArray($one);
     $gameid=$show['id'];
    $sql = "INSERT INTO `#@__message` (type,content,timestamp,kjtime,gid) VALUES ('system_kj','$content',$now,'$actiontime',$gameid)";
    $dosql->ExecNoneQuery($sql);
    }

    //开奖完毕之后，通过开奖期数进行开奖计算,目前仅仅只是针对canada28加拿大28的游戏，后续再来添加
    $one=1;
    $two=2;
    $dosql->Execute("SELECT xiazhu_orderid,uid,gameid FROM pmw_xiazhuorder where xiazhu_qishu='$issue'",$one);
    while($row=$dosql->GetArray($one)){
         if(is_array($row)){
           $xiazhu_orderid=$row['xiazhu_orderid'];
           $gameid=$row['gameid'];
           $uid=$row['uid'];
           $dosql->Execute("SELECT xiazhu_type,xiazhu_money,xiazhu_beilv,id,xiazhu_mulu FROM pmw_xiazhucontent where xiazhu_times='$issue' and xiazhu_orderid='$xiazhu_orderid'",$two);
           $b=0;
           while($show = $dosql->GetArray($two)){
               if(is_array($show)){
               $xiazhu_money = intval($show['xiazhu_money']); //下注金额
               $ml=$show['xiazhu_mulu'];    //一级目录
               $lbs=$show['xiazhu_type'];    //下注的类别
                   if($ml==1){   //特码的情况
                    $lb=getzimus($lbs);
                   }else{
                    $lb=$lbs;
                   }
               $bl=$show['xiazhu_beilv'];   //开奖的倍率
               $id=$show['id'];   //开奖的倍率
              $str=canada28($kj_code,$kj_he,$ml); //本期开奖的所有类别


              if($gameid==4){ //加拿大28  2.0开奖结果算法
                $tbname="pmw_xiazhucontent";
               if(check_str($str,$lb."/")){
                   //买小单，开奖结果为13：回本
                   if(check_str($str,"小单/") && $kj_he==13){
                    $b  += $xiazhu_money * 1;
                    $kj_content= $xiazhu_money * 1;
                    $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                   //买大双，开奖结果为14：回本
                  }elseif(check_str($str,"大双/") && $kj_he==14){
                    $b  += $xiazhu_money * 1;
                    $kj_content= $xiazhu_money * 1;
                    $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                  //买小或单，开奖结果为13， :1.6倍（含本金）
                  }elseif((check_str($str,"小/") && $kj_he==13) ||
                          (check_str($str,"单/") && $kj_he==13) ){
                    $b  += $xiazhu_money * 1.6;
                    $kj_content= $xiazhu_money * 1.6;
                    $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1.6' WHERE id=$id");
                  //买大或双，开奖结果为14，：1.6倍（含本金）
                }elseif((check_str($str,"大/") && $kj_he==14) ||
                        (check_str($str,"双/") && $kj_he==14) ){
                    $b  += $xiazhu_money * 1.6;
                    $kj_content= $xiazhu_money * 1.6;
                    $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1.6' WHERE id=$id");
                  //下注小单/大双 开13/14中奖回本
                }elseif((check_str($str,"小单/") && $kj_he==13) ||
                        (check_str($str,"大双/") && $kj_he==14) ){
                     $b  += $xiazhu_money * 1;
                     $kj_content= $xiazhu_money * 1;
                     $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                }elseif((check_str($str,"对子/") && $kj_he==13) ||
                        (check_str($str,"对子/") && $kj_he==14) ){
                     $b  += $xiazhu_money * 1;
                     $kj_content= $xiazhu_money * 1;
                     $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                }else{
                    $b  += $xiazhu_money * $bl;
                    $kj_content= $xiazhu_money * $bl;
                    $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl' WHERE id=$id");
                }
            }else{
                   $b  +=0;
                   $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='0',new_beilv='0' WHERE id=$id");
            }
         }elseif($gameid==5){  //加拿大28  2.5开奖结果算法
           $tbname="pmw_xiazhucontent";
                  if(check_str($str,$lb."/")){
                       if($ml==0){ //大小单双大单，大双，小单，小双，极大，极小
                         // 大/小/单/双下注小于1000开奖13/14，中奖正常计算
                         if((check_str($str,"大/") || check_str($str,"小/") || check_str($str,"单/") || check_str($str,"双/")) && ($kj_he==14 || $kj_he==13) && $xiazhu_money <= 1000){
                             $b  += $xiazhu_money * $bl;
                             $kj_content= $xiazhu_money * $bl;
                             $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl',tswf='$tswf' WHERE id=$id");
                          // 大/小/单/双下注大于1000开奖13/14，回本
                        }elseif((check_str($str,"大/") || check_str($str,"小/") || check_str($str,"单/") || check_str($str,"双/")) && ($kj_he==14 || $kj_he==13) && $xiazhu_money > 1000){
                                   $b  += $xiazhu_money * 1;
                                   $kj_content= $xiazhu_money * 1;
                                   $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                           //下注大/小/单/双开对子/顺子/豹子,中奖回本
                         }elseif((check_str($str,"大/") || check_str($str,"小/") || check_str($str,"单/") || check_str($str,"双/")) &&
                                 ($tswf==1 || $tswf==2 || $tswf==3))
                                 {
                                   $b  += $xiazhu_money * 1;
                                   $kj_content= $xiazhu_money * 1;
                                   $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1',tswf='$tswf' WHERE id=$id");
                          //下注小单/大双/小双/大单开奖13/14/对子/顺子/豹子/中奖回本
                        }elseif((check_str($str,"小单/") || check_str($str,"大双/") || check_str($str,"小双/") || check_str($str,"大单/")) && ($kj_he==13 || $kj_he==14 || $tswf==3 || $tswf==2 || $tswf==1))
                                {
                                    $b  += $xiazhu_money * 1;
                                    $kj_content= $xiazhu_money * 1;
                                    $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1',tswf='$tswf' WHERE id=$id");
                          //  开13/14如果是3+3+8按对子处理回本
                         }elseif(($kj_he==13 || $kj_he==14) && $tswf==3){
                                     $b  += $xiazhu_money * 1;
                                     $kj_content= $xiazhu_money * 1;
                                     $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1',tswf='$tswf' WHERE id=$id");
                         }else{
                                     $b  += $xiazhu_money * $bl;
                                     $kj_content= $xiazhu_money * $bl;
                                     $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl',tswf='$tswf' WHERE id=$id");
                         }
                       }elseif($ml==1){  //特码
                                     $b  += $xiazhu_money * $bl;
                                     $kj_content= $xiazhu_money * $bl;
                                     $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl',tswf='$tswf' WHERE id=$id");
                       }elseif($ml==2){//豹子，顺子，对子
                                     $b  += $xiazhu_money * $bl;
                                     $kj_content= $xiazhu_money * $bl;
                                     $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl',tswf='$tswf' WHERE id=$id");
                       }
                  }else{
                    $b  +=0;
                    $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='0',new_beilv='0' WHERE id=$id");
                   }
               }elseif($gameid==6){ //加拿大28-2.8 开奖结果算法
                 $tbname="pmw_xiazhucontent";
                 if(check_str($str,$lb."/")){
                      if($ml==1){   //特码
                        $b  += $xiazhu_money * $bl;
                        $kj_content= ($xiazhu_money * $bl);
                        $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl',tswf='$lb' WHERE id=$id");
                      }elseif($ml==0){
                        //开13/14/对子/顺子/豹子/ 中奖单注组合回本
                          if($kj_he==13 || $kj_he==14 || $tswf==3 || $tswf==2 || $tswf==1){
                          $b  += $xiazhu_money * 1;
                          $kj_content= ($xiazhu_money * 1);
                          $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1',tswf='$tswf' WHERE id=$id");
                        }elseif($lb=="极大" || $lb="极小"){//如果下注的是极大值，极小值
                          $b  += $xiazhu_money * $bl;
                          $kj_content= ($xiazhu_money * $bl);
                          $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl',tswf='$lb'  WHERE id=$id");
                        }else{
                          $b  += $xiazhu_money * $bl;
                          $kj_content= ($xiazhu_money * $bl);
                          $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl',tswf='$lb'  WHERE id=$id");
                        }
                      }elseif($ml==2){ //豹子，顺子，对子
                        $b  += $xiazhu_money * $bl;
                        $kj_content= ($xiazhu_money * $bl);
                        $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl',tswf='$lb'  WHERE id=$id");
                      }
                 }else{
                   $b  +=0;
                   $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='0',new_beilv='0',tswf='$lb' WHERE id=$id");
                 }
               }elseif($gameid==7){ //加拿大28-1.88 开奖结果算法
                 $tbname="pmw_xiazhucontent";
                 if(check_str($str,$lb."/")){
                   //买小/单，开奖13且总下注大于1000：1.88倍（含本金）
                   if((check_str($str,"小/") && $kj_he==13 && $xiazhu_money>1000) ||
                      (check_str($str,"单/") && $kj_he==13 && $xiazhu_money>1000)
                   ){
                     $b  += $xiazhu_money * 1.88;
                     $kj_content= $xiazhu_money * 1.88;
                     $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1.88' WHERE id=$id");
                     //买小/单，开奖13且总下注小于1000：2倍（含本金）
                   }elseif((check_str($str,"小/") && $kj_he==13 && $xiazhu_money<1000) ||
                      (check_str($str,"单/") && $kj_he==13 && $xiazhu_money<1000)
                   ){
                      $b  += $xiazhu_money * 2;
                      $kj_content= $xiazhu_money * 2;
                      $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='2' WHERE id=$id");
                    //买大/双，开奖14且总下注大于1000：1.88倍（含本金）
                  }elseif((check_str($str,"大/") && $kj_he==14 && $xiazhu_money>1000) ||
                      (check_str($str,"双/") && $kj_he==14 && $xiazhu_money>1000)
                   ){
                      $b  += $xiazhu_money * 1.88;
                      $kj_content= $xiazhu_money * 1.88;
                      $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1.88' WHERE id=$id");
                      //买大/双，开奖14且总下注小于1000： 2倍（含本金）
                   }elseif((check_str($str,"大/") && $kj_he==14 && $xiazhu_money<1000) ||
                       (check_str($str,"双/") && $kj_he==14 && $xiazhu_money<1000)
                    ){
                       $b  += $xiazhu_money * 2;
                       $kj_content= $xiazhu_money * 2;
                       $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='2' WHERE id=$id");
                       //买组合开奖结果为13/14回本
                    }elseif($kj_he==13 || $kj_he==14){
                    $b  += $xiazhu_money * 1;
                    $kj_content= $xiazhu_money * 1;
                    $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                    }else{
                        $b  += $xiazhu_money * $bl;
                        $kj_content= $xiazhu_money * $bl;
                        $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl' WHERE id=$id");
                    }
                 }else{
                   $b  +=0;
                   $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='0',new_beilv='0' WHERE id=$id");
                 }
               }elseif($gameid==8){//加拿大28-3.2 开奖结果算法
                 $tbname="pmw_xiazhucontent";
                 if(check_str($str,$lb."/")){
                 //前三位数字含0或9视为回本，中单注/组合：回本 如：0+7+8=15 (回本)， 9+4+5=18 (回本)
                   if(check_str($kj_code,0) || check_str($kj_code,9)){
                     $b  += $xiazhu_money * 1;
                     $kj_content= $xiazhu_money * 1;
                     $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                     //小/单/小单开奖结果为13，回本
                   }elseif((check_str($str,"小/") && $kj_he==13) ||
                          (check_str($str,"单/") && $kj_he==13) ||
                          (check_str($str,"小单/") && $kj_he==13)
                   ){
                   $b  += $xiazhu_money * 1;
                   $kj_content= $xiazhu_money * 1;
                   $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                   //大/双/大双开奖结果为14，回本
                 }elseif((check_str($str,"大/") && $kj_he==14) ||
                         (check_str($str,"双/") && $kj_he==14) ||
                         (check_str($str,"大双/") && $kj_he==14)
                   ){
                   $b  += $xiazhu_money * 1;
                   $kj_content= $xiazhu_money * 1;
                   $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                   }elseif(check_str($str,"对子/") && $kj_he==13){
                       $b  += $xiazhu_money * 1;
                       $kj_content= $xiazhu_money * 1;
                       $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                    //中对子，开奖结果为14.回本
                   }elseif(check_str($str,"对子/") && $kj_he==14){
                           $b  += $xiazhu_money * 1;
                           $kj_content= $xiazhu_money * 1;
                           $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='1' WHERE id=$id");
                   }else{
                       $b  += $xiazhu_money * $bl;
                       $kj_content= $xiazhu_money * $bl;
                       $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='$kj_content',new_beilv='$bl' WHERE id=$id");
                   }
                 }else{
                   $b  +=0;
                   $dosql->ExecNoneQuery("UPDATE `$tbname` SET kj_jieguo=1, kj_content='0',new_beilv='0' WHERE id=$id");
                 }
               }
             }
           }
    }
    //更改用户的下注订单状态，
    $sql = "UPDATE `#@__xiazhuorder` SET xiazhu_kjstate=1,xiazhu_jiangjin='$b' where xiazhu_orderid='$xiazhu_orderid'";
    $dosql->ExecNoneQuery($sql);

    //同时向用户的账户里面添加中奖金额
    if($b!=0){
    $b=intval($b);
    $sql = "UPDATE `#@__members` SET money=money + $b where id=$uid";
    $dosql->ExecNoneQuery($sql);
    }
    }

//获取开奖是否是豹子，顺子，对子
    function check_teshus($code){
      $arr= str_split($code);
      $newarr = bubbleSort($arr);  //开奖号码从小到大排列

     //豹子
      if($arr[0]==$arr[1] && $arr[1]==$arr[2]){
       return 1;
      }
     //顺子
      elseif(($newarr[2]-$newarr[1] == $newarr[1]-$newarr[0] && $newarr[2]-$newarr[1]==1) ||
         ($newarr[0]==0 && $newarr[1]==1 && $newarr[1]==9) ||
         ($newarr[0]==0 && $newarr[1]==8 && $newarr[1]==9) ){
       return  2;
     }
     //对子
     elseif(($newarr[0]==$newarr[1] && $newarr[1]!=$newarr[2]) ||
        ($newarr[2]==$newarr[1] && $newarr[1]!=$newarr[0]) ){
         return 3;
       }else{
         return 4;
       }
    }


?>
