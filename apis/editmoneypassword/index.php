<?php
    /**
	   * 链接地址：editmoneypassword   修改资金密码
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
     * @param array $Data 返回数据
     *
     * @return string   旧密码 moneypassword  新密码 newmoneypassword      会员id
     *
     */
     require_once("../../include/config.inc.php");
     $Data = array();
     $Version=date("Y-m-d H:i:s");
     if(isset($token) && $token==$cfg_auth_key){
       $moneypassword=md5(md5($moneypassword));
       $row = $dosql->GetOne("SELECT telephone,nickname  from  `#@__members` WHERE id=$id and moneypassword='$moneypassword'");
       if(is_array($row)){
         $newmoneypassword=md5(md5($newmoneypassword));
         $r = $dosql->GetOne("SELECT password  from  `#@__members` WHERE id=$id");
         $password=$r['password'];
         if($password!=$newmoneypassword){
           $sql = "UPDATE `#@__members` SET moneypassword='$newmoneypassword' WHERE id=$id";
           $dosql->ExecNoneQuery($sql);
           $State = 1;
           $Descriptor = '密码修改成功！';
           $result = array (
                       'State' => $State,
                       'Descriptor' => $Descriptor,
                       'Version' => $Version,
                        );
           echo phpver($result);
         }else{
           $State = 2;
           $Descriptor = '新密码不能与登陆密码相同';
           $result = array (
                       'State' => $State,
                       'Descriptor' => $Descriptor,
                       'Version' => $Version,
                       'Data' => $Data
               );
           echo phpver($result);
         }
       }else{
         $State = 0;
         $Descriptor = '原始密码错误！';
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
