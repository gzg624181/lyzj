<?php
    /**
	   * 链接地址：success_travel 获取旅行社已经完成的形成列表
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
     * @提供返回参数账号 id    旅行社的id 返回旅行社的导游形成  （0，待预约，2.已完成,1,待确认 3.已经取消）
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

      $r=$dosql->GetOne("SELECT id FROM `#@__travel` WHERE aid=$id");
      if(!is_array($r)){  //如果传递过来的账号不存在，则没有这一列
        $State = 0;
        $Descriptor = '暂无发布的形成列表，请先发布行程！';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' => $Data
                     );
        echo phpver($result);
      }else{
      #计算已经发布的形成
      $we='we';
      $me='me';
      $one=1;
      $two=2;
      #已取消
      $dosql->Execute("SELECT * FROM `#@__travel` WHERE aid=$id and state=2",$we);
      for($i=0;$i<$dosql->GetTotalRow($we);$i++){
        $row = $dosql->GetArray($we);
        $Data['complete'][$i]=$row;
      }
      #待预约
      $dosql->Execute("SELECT * FROM `#@__travel` WHERE aid=$id and state=0",$me);
      for($j=0;$j<$dosql->GetTotalRow($me);$j++){
        $show = $dosql->GetArray($me);
        $Data['appointment'][$j]=$show;
      }
      #待确认
      $dosql->Execute("SELECT * FROM `#@__travel` WHERE aid=$id and state=1",$one);
      for($i=0;$i<$dosql->GetTotalRow($one);$i++){
        $row1 = $dosql->GetArray($one);
        $Data['confirm'][$i]=$row1;
      }
      #已取消
      $dosql->Execute("SELECT * FROM `#@__travel` WHERE aid=$id and state=3",$two);
      for($j=0;$j<$dosql->GetTotalRow($two);$j++){
        $show1 = $dosql->GetArray($two);
        $Data['concel'][$j]=$show1;
      }
      $State = 1;
      $Descriptor = '形成列表查询成功！';
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
