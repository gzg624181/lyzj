<?php
    /**
	   * 链接地址：get_comment  获取导游的所有评价
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
     * @提供返回参数账号 导游id
     */
require_once("../../include/config.inc.php");
header("content-type:application/json; charset=utf-8");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){


      $dosql->Execute("SELECT b.star,b.content,b.addtime,a.company,a.images,c.title from pmw_agency a left join pmw_comment b on a.id=b.aid left join pmw_travel c on b.tid=c.id WHERE b.gid=$id");

      $num=$dosql->GetTotalRow();
      if($num==0){
        $State = 0;
        $Descriptor = '评价内容获取为空';
        $result = array (
                    'State' => $State,
                    'Descriptor' => $Descriptor,
                    'Version' => $Version,
                    'Data' => $Data
                     );
        echo phpver($result);
      }else{
      for($i=0;$i<$dosql->GetTotalRow();$i++){
        $row=$dosql->GetArray();
        $Data[]=$row;
        if($row['images']==""){
        $images=$cfg_weburl."/templates/default/images/noimage.jpg";
        }elseif(check_str($row['images'],"https")){
        $images=$row['images'];   //用户头像
        }else{
        $images=$cfg_weburl."/".$row['images'];
        }
        $Data[$i]['images']=$images;
      }
      $State = 1;
      $Descriptor = '评价内容获取成功！';
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
