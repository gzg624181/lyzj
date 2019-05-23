<?php
    /**
	   * 链接地址：allbill  账户明细所有记录
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
     * @return string   用户会员id uid
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$list = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){
  $starttime = empty($starttime) ?  strtotime(date("Y-m-d",time())) : $starttime;
  $endtime = empty($endtime) ?  strtotime(date("Y-m-d",time()+24*3600)) : $endtime;
  if($type=="all"){
  $dosql->Execute("SELECT money_list,time_list,types FROM `#@__record` where mid=$uid and time_list >= $starttime and time_list < $endtime order  by id desc limit 30");
  }else{
  $dosql->Execute("SELECT money_list,time_list,types FROM `#@__record` where mid=$uid and types='$type' and time_list >= $starttime and time_list < $endtime order  by id desc limit 30");
  }
  $num=$dosql->GetTotalRow();
  if($num>0){
  for($i=0;$i<$dosql->GetTotalRow();$i++)
  {
    $row = $dosql->GetArray();
    $summoney[]=$row['money_list'];
    $types=$row['types'];
    $list[$i]['time_list']=date("Y-m-d H:i",$row['time_list']);
    if($types=="recharge"){
    $list[$i]['title']="账户充值";
    }elseif($types=="take_money"){
    $list[$i]['title']="账户提现";
    }elseif($types=="fanshui"){
    $list[$i]['title']="账户返水";
    }elseif($types=="ticheng"){
    $list[$i]['title']="账户提成";
    }elseif($types=="fenhong"){
    $list[$i]['title']="账户分红";
    }elseif($types=="jieshao"){
    $list[$i]['title']="介绍";
    }elseif($types=="yewu"){
    $list[$i]['title']="业务";
    }elseif($types=="active"){
    $list[$i]['title']="活动";
    }
    $list[$i]['money_list']=$row['money_list'];
  }
  if($type!="all"){
    $Data['summoney']=array_sum($summoney);
  }
  if($type=="all"){
    $Data['name']="账户明细";
  }elseif($type=="recharge"){
    $Data['name']="充值记录";
  }elseif($type=="take_money"){
    $Data['name']="提现记录";
  }elseif($type=="fanshui"){
    $Data['name']="返水记录";
  }elseif($type=="ticheng"){
    $Data['name']="提成记录";
  }elseif($type=="fenhong"){
    $Data['name']="分红记录";
  }elseif($type=="jieshao"){
    $Data['name']="介绍记录";
  }elseif($type=="yewu"){
    $Data['name']="业务记录";
  }elseif($type=="active"){
    $Data['name']="活动记录";
  }
    $Data['list']=$list;
    $State = 1;
    $Descriptor = '数据获取成功！';
    $result = array (
                'State' => $State,
                'Descriptor' => $Descriptor,
                'Version' => $Version,
                'Data' => $Data,
                 );
    echo phpver($result);
  }else{
    $list=(object)null;
    $Data['summoney']=0;
    if($type=="all"){
      $Data['name']="账户明细";
    }elseif($type=="recharge"){
      $Data['name']="充值记录";
    }elseif($type=="take_money"){
      $Data['name']="提现记录";
    }elseif($type=="fanshui"){
      $Data['name']="返水记录";
    }elseif($type=="ticheng"){
      $Data['name']="提成记录";
    }elseif($type=="fenhong"){
      $Data['name']="分红记录";
    }elseif($type=="jieshao"){
      $Data['name']="介绍记录";
    }elseif($type=="yewu"){
      $Data['name']="业务记录";
    }elseif($type=="active"){
      $Data['name']="活动记录";
    }
    $Data['list']=$list;
    $State = 0;
    $Descriptor = '账户明细数据为空';
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
