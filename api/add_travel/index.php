<?php
    /**
	   * 链接地址：add_travel  旅行社发布旅游行程
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
     * @旅行社发布旅游行程   提供返回参数账号，
     * title           行程标题
     * starttime       开始时间
     * endtime         结束时间
     * num             团队人数
     * origin          客源地
     * content         添加行程
     * money           导游费用
     * other           其他备注
     */
require_once("../../include/config.inc.php");
$Data = array();
$Version=date("Y-m-d H:i:s");
if(isset($token) && $token==$cfg_auth_key){

  //备注 ：添加行程的时候content 内容以json字符串的形式保存在数据库中去

  $posttime=time();  //添加时间
  $days=($endtime-$starttime) / (60 * 60 * 24) +1;  //行程的天数
  $jiesuanmoney = $cfg_jiesuan * $days;
  $r=$dosql->GetOne("SELECT company from pmw_agency where id=$aid");
  $company=$r['company'];
  $sql = "INSERT INTO `#@__travel` (title,starttime,endtime,num,origin,content,money,other,posttime,aid,jiesuanmoney,company) VALUES ('$title',$starttime,$endtime,$num,'$origin','$content',$money,'$other',$posttime,$aid,'$jiesuanmoney','$company')";
  $dosql->ExecNoneQuery($sql);
  $State = 1;
  $Descriptor = '旅行行程发布成功！!';
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
