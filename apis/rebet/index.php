<?php
    /**
	   * 链接地址：delaccount  撤销下注订单
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
     * @return string   单注下单的id（假如删除了全部下注，则同时在订单表里面删除）
     *
     * 取消所有下注 ：用户uid 游戏gameid  当前下注期数 xiazhu_times   下注的总金额money
     */
require_once("../../include/config.inc.php");
$Data = (object)null;
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $r=$dosql->GetOne("SELECT state from pmw_lotterynumber where kj_state=0");
  $state=$r['state'];
  if($state=="xz"){  //只有在下注的情况下才能撤销订单，封盘过程中撤销不了

    if(isset($id) && $id!=""){
    $row = $dosql->GetOne("SELECT xiazhu_orderid,xiazhu_money FROM `#@__xiazhucontent` WHERE id=$id");
    if(is_array($row)){
    $xiazhu_money=$row['xiazhu_money']; //下注金额，返回给用户的账号里面去
    $xiazhu_orderid=$row['xiazhu_orderid'];
    $dosql->Execute("SELECT id FROM `#@__xiazhucontent` where xiazhu_orderid='$xiazhu_orderid'");
    $num=$dosql->GetTotalRow();  //判断当前数据库里面有下注的几条数据
    if($num==1){
         $dosql->QueryNone("DELETE FROM `#@__xiazhucontent` WHERE id=$id"); //删除已经下注的单个下注
         $r = $dosql->GetOne("SELECT uid FROM `#@__xiazhuorder` WHERE xiazhu_orderid='$xiazhu_orderid'");//获取用户的uid
         $uid=$r['uid'];
         $dosql->QueryNone("UPDATE `#@__members` SET money=money + $xiazhu_money where id=$uid"); //将用户下注的钱返回到用户的账户里面去
         $dosql->QueryNone("DELETE FROM `#@__xiazhuorder` WHERE xiazhu_orderid='$xiazhu_orderid'"); //删除已经下注的订单
    }else{
          $dosql->QueryNone("DELETE FROM `#@__xiazhucontent` WHERE id=$id"); //删除已经下注的单个下注
          $dosql->QueryNone("UPDATE `#@__xiazhuorder` SET xiazhu_sum=xiazhu_sum - $xiazhu_money where xiazhu_orderid='$xiazhu_orderid'"); //将用户下注的总金额减去撤销的下单金额
          $r = $dosql->GetOne("SELECT uid FROM `#@__xiazhuorder` WHERE xiazhu_orderid='$xiazhu_orderid'");//获取用户的uid
          $uid=$r['uid'];
          $dosql->QueryNone("UPDATE `#@__members` SET money=money + $xiazhu_money where id=$uid"); //将用户下注的钱返回到用户的账户里面去
    }

    $State = 1;
    $Descriptor = '下注订单撤销成功！';
    //$Data[]=$row;
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
                 );
    echo phpver($result);
  }else{
    $State = 0;
    $Descriptor = '下注订单撤销失败！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data
        );
    echo phpver($result);
  }
}elseif(isset($xiazhu_times) && $xiazhu_times!=""){
  $dosql->QueryNone("DELETE FROM `#@__xiazhucontent` WHERE userid =$uid  and xiazhu_times='$xiazhu_times' and gameid=$gameid"); //删除已经下注的所有下注
  $dosql->QueryNone("UPDATE `#@__members` SET money=money + $money where id=$uid"); //将用户下注的钱返回到用户的账户里面去
  $dosql->QueryNone("DELETE FROM `#@__xiazhuorder` WHERE  xiazhu_qishu='$xiazhu_times' and uid=$uid and gameid=$gameid"); //删除已经下注的所有下注
  $State = 3;
  $Descriptor = '所有订单撤销成功！';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);
  }

  }else{
    $State = 2;
    $Descriptor = '封盘阶段，不能撤单！';
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
