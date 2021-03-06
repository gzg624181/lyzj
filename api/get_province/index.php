<?php
    /**
	   * 链接地址：get_province  获取所有的省份
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
     * @提供返回参数账号 id
     */
     require_once("../../include/config.inc.php");
     header("content-type:application/json; charset=utf-8");
     $Data = array();
     $Version=date("Y-m-d H:i:s");
     if(isset($token) && $token==$cfg_auth_key){

           if($redis->exists('get_province')){

           $Data = json_decode(stripslashes($redis->get('get_province')),true);

           }else{
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
            //将数组保存到redis中去
            update_redis('get_province',phpver($Data));
          }

          $State = 1;
          $Descriptor = '数据获取成功！';
          $result = array (
                      'State' => $State,
                      'Descriptor' => $Descriptor,
                      'Version' => $Version,
                      'Data' => $Data
                       );
          echo phpver($result);


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
