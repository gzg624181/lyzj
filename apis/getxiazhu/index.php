<?php
    /**
	   * 链接地址：getxiazhu，(获取当前用户的最新的下注消息)
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
     * @return string 提供用户的uid   提供游戏的gid
     *
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
  if(isset($token) && $token==$cfg_auth_key){
   $one=1;
   $two=2;
   $dosql->Execute("select xiazhu_orderid from  pmw_xiazhuorder where uid=$uid and gameid=$gid and xiazhu_kjstate=0  order by id desc limit 0,1",$one);
   $num=$dosql->GetTotalRow($one);
   if($num!=0){
   for($i=0;$i<$dosql->GetTotalRow($one);$i++)
   {
     $row1 = $dosql->GetArray($one);

      $xiazhu_orderid=$row1['xiazhu_orderid'];
      $dosql->Execute("select xiazhu_type,xiazhu_money,id from pmw_xiazhucontent where xiazhu_orderid='$xiazhu_orderid'",$two);
      for($j=0;$j<$dosql->GetTotalRow($two);$j++){
          $row2 = $dosql->GetArray($two);
          $xiazhu_type =  $row2['xiazhu_type'];
         $arr=explode("+",$xiazhu_type);
          $ml=$arr[0];   //一级目录
          $lb=$arr[1];   //下注的类别
          $bl=$arr[2];   //开奖的倍率
          if($ml==1){
         $type= tochange($ml,$lb);
          }else{
         $type= $lb;
          }
          $Data[$j]['id']=intval($row2['id']);
          $Data[$j]['type']=$type;
          $Data[$j]['money']=intval($row2['xiazhu_money']);
      }
      $State = 1;
      $Descriptor = '下注成功！';
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
     $Descriptor = '暂无下注记录！';
     $result = array (
                     'State' => $State,
                     'Descriptor' => $Descriptor,
                     'Version' => $Version,
                      'Data' => $Data,
                      );
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
