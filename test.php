<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
// include("sendmessage.php");
// $appid=$cfg_appid;
// $appsecret=$cfg_appsecret;
// $code="011ZW5ci0uBaip1Xfmei0Tpcci0ZW5cJ";
// $openid=get_openid($code,$appid,$appsecret);
// echo $openid;

// 获取ajax传来的base64编码，$_POST['img']是你后台获取到的图片
// $r=$dosql->GetOne("SELECT pics FROM pmw_guide where id=24");
// $base64_image_content = $r['pics'];
$myfile=fopen('cc.txt','r');
$base64_image_content=fgets($myfile);

$arr=explode("|",$base64_image_content);
$savepath= "./uploads/image/";
$pic="";
//这个是自定义函数，将Base64图片转换为本地图片并保存
for($i=0;$i<count($arr);$i++){
  $pics  = base64_image_contents($arr[$i],$savepath);
  $thispic = str_replace("./",$cfg_weburl.'/',$pics)."|";
  $pic .= $thispic;
}
echo $pic;
function base64_image_contents($base64_image_content,$path){
   //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/',
    $base64_image_content, $result)){ //后缀
    $type = $result[2]; //创建文件夹，以年月日
    $new_file = $path.date('Ymd',time())."/";
    if(!file_exists($new_file)){ //检查是否有该文件夹，如果没有就创建，并给予最高权限
    mkdir($new_file, 0700);
    }
    $new_file = $new_file.time().rand(111,999).".{$type}"; //图片名以时间命名
    //保存为文件
    if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
    //返回这个图片的路径
    return $new_file;
   }else{
  return false;
  }}else{ return false; }
 }








?>
