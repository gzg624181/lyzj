<?php
    /**
	   * 链接地址：get_index_banner  获取首页banner图片
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

       //判断营销活动的开关是否开启
       $r = $dosql->GetOne("SELECT varvalue from pmw_webconfig where varname='cfg_task'");
       $cfg_task = $r['varvalue'];

      $dosql->Execute("SELECT * from pmw_banner where typename='index' and checkinfo=1");
      $num=$dosql->GetTotalRow();
      if($num==0){
        $State = 0;
        $Descriptor = '图片获取为空';
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
        $id = $Data[$i]['id'];
        //首页营销banner图片根据营销开关是否显示出来,指定的id为49，不允许删除

        if($id==49){

          //当营销的开关关闭的时候，则在首页不显示
          if($cfg_task == "N" ){
            unset($Data[$i]);
          }elseif($cfg_task == "Y" ){
            $Data[$i]['linkurl']="指向营销推广的页面的url";
            $pic=$cfg_weburl."/".$row['pic'];
            $Data[$i]['pic']=$pic;
            $Data[$i]['type']="share";
          }

        }else{
          $pic=$cfg_weburl."/".$row['pic'];
          $content=stripslashes($row['content']);
          $content=Common::rePic($content, $cfg_weburl);
          $Data[$i]['content']=$content;
          $Data[$i]['pic']=$pic;
        }

      }
      $State = 1;
      $Descriptor = '图片获取成功！';
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
