<?php
    /**
	   * 链接地址：get_province_city  获取所有的省份和城市
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

           if($redis->exists('get_province_city')){

           $Data = $redis->get('get_province_city');

           }else{

           $file = "province_city.txt";

           $Data = file_get_contents($file);
            //将数组保存到redis中去
           update_redis('get_province_city',$Data);

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
