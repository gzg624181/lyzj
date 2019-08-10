<?php

 header("Content-Type: text/html; charset=utf-8");
 require_once("../../include/config.inc.php");
 $postXml = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数
// 接受不到参数可以使用 file_get_contents("php://input"); PHP 高版本中$GLOBALS 好像已经被废弃了
if (empty($postXml)) {
    return false;
}

//将 xml 格式转换成数组
function xmlToArray($xml) {
    //禁止引用外部 xml 实体
    libxml_disable_entity_loader(true);
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $val = json_decode(json_encode($xmlstring), true);
    return $val;
}

function write_log($data)
{
  //设置支付的log日志
  $yeartime = date("Ymd");
  $listtime = date("YmdHis");
  //设置目录信息
  $url = './log/'.$yeartime.'/'.$listtime.".txt";
  $dir_name = dirname($url);
  //目录不存在就创建
  if(!file_exists($idr_name)){
    //iconv防止中文乱码
    $res = mkdir(iconv("UTF-8","GBK",$dir_name),0777,true);
  }
  $results = print_r($data, true);
  file_put_contents($url, $results);
}
$attr = xmlToArray($postXml);
write_log($attr);


if(array_key_exists("return_code", $attr)
  && array_key_exists("result_code", $attr)
  && $attr["return_code"] == "SUCCESS"
  && $attr["result_code"] == "SUCCESS")
{
 $orderid = $attr['out_trade_no'];
 $transaction_id = $attr['transaction_id'];

 //更改用户的支付状态为已经支付，保存微信系统自带的订单号码

 $dosql->ExecNoneQuery("UPDATE `pmw_order` SET pay_state=1,transaction_id='$transaction_id' WHERE orderid='$orderid'");

 //用户支付成功之后，将订单的数量更改掉

 $r = $dosql->GetOne("SELECT nums,tid from pmw_order where orderid='$orderid'");
 $nums = $r['nums'];
 $tid  = $r['tid'];

 //更改订单数量
 $solds = get_solds($tid,$nums);

 $dosql->ExecNoneQuery("UPDATE pmw_ticket set solds = $solds where id=$tid");


  //特别备注：下面的代码是终止微信支付成功之后的回调，告诉微信端已经支付成功！！！
 echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
}
 ?>
