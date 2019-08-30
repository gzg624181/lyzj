<?php
    /**
	   * 链接地址：get_order 获取单个用户的购票列表
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
     * @提供返回参数账号 id    用户类型  agency guide
     *  # 已支付
     *  # 已完成
     *
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

      $r=$dosql->GetOne("SELECT id FROM `#@__order` WHERE did=$id and type='$type'");
      if(!is_array($r)){  //如果传递过来的账号不存在，则没有这一列
        $State = 0;
        $Descriptor = '暂无购票列表！';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' => $Data
                     );
        echo phpver($result);
      }else{

      $we='we';
      $me='me';

      $now=time();
      #已经支付（线上和线下已经支付完成的订单）
      $r=$dosql->GetOne("SELECT imagesurl FROM pmw_share  where id=3");
      $cfg_default = $r['imagesurl'];

      $dosql->Execute("SELECT a.*,b.picarr FROM `#@__order` a inner join `#@__ticket` b  on a.tid=b.id  WHERE a.did=$id and a.type='$type'  and  a.pay_state =1 order by a.posttime desc",$we);
      for($i=0;$i<$dosql->GetTotalRow($we);$i++){
        $row = $dosql->GetArray($we);
        $Data['pay'][$i]=$row;
        $Data['pay'][$i]['posttime']=date("Y-m-d",$row['posttime']);
        $picarr=stripslashes($row['picarr']);
        if($picarr==""){
        $picarrTmp=array("0"=>$cfg_weburl."/".$cfg_default);
        $picarr = json_encode($picarrTmp);
        }else{
        $picarr=Common::GetPic($picarr, $cfg_weburl);
        }
        $Data['pay'][$i]['picarr']=$picarr;
      }
      #已完成
      $dosql->Execute("SELECT a.*,b.picarr FROM `#@__order` a inner join `#@__ticket` b  on a.tid=b.id  WHERE a.did=$id and a.type='$type'  and  a.pay_state =1 and a.states=1 order by a.posttime desc",$me);
      for($i=0;$i<$dosql->GetTotalRow($me);$i++){
        $row = $dosql->GetArray($me);
        $Data['finish'][$i]=$row;
        $Data['finish'][$i]['posttime']=date("Y-m-d",$row['posttime']);
        $picarr=stripslashes($row['picarr']);
        if($picarr==""){
        $picarrTmp=array("0"=>$cfg_weburl."/".$cfg_default);
        $picarr = json_encode($picarrTmp);
        }else{
        $picarr=Common::GetPic($picarr, $cfg_weburl);
        }
        $Data['finish'][$i]['picarr']=$picarr;
      }


      $State = 1;
      $Descriptor = '数据查询成功！';
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
