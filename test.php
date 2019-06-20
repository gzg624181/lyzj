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
// $myfile=fopen('cc.txt','r');
// $base64_image_content=fgets($myfile);
//
// $arr=explode("|",$base64_image_content);
// $savepath= "./uploads/image/";
// $pic="";
// //这个是自定义函数，将Base64图片转换为本地图片并保存
// for($i=0;$i<count($arr);$i++){
//   $pics  = base64_image_contents($arr[$i],$savepath);
//   $thispic = str_replace("./",$cfg_weburl.'/',$pics)."|";
//   $pic .= $thispic;
// }
// // echo $pic;
// function base64_image_contents($base64_image_content,$path){
//    //匹配出图片的格式
//     if (preg_match('/^(data:\s*image\/(\w+);base64,)/',
//     $base64_image_content, $result)){ //后缀
//     $type = $result[2]; //创建文件夹，以年月日
//     $new_file = $path.date('Ymd',time())."/";
//     if(!file_exists($new_file)){ //检查是否有该文件夹，如果没有就创建，并给予最高权限
//     mkdir($new_file, 0700);
//     }
//     $new_file = $new_file.time().rand(111,999).".{$type}"; //图片名以时间命名
//     //保存为文件
//     if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
//     //返回这个图片的路径
//     return $new_file;
//    }else{
//   return false;
//   }}else{ return false; }
//  }
//
//
//  $data='[{"jinName":"12","starttime":"12","days":"12"},{"jinName":"1","starttime":"1","days":"1"}]';
//  //转换成数组
// $arr = json_decode($data,true);
// //输出
// // print_r($arr);
//
// $content=array(
//   "0" => array(
//           "jinName" =>  1,
//         "starttime" => 2,
//              "days" => 3
//            )
// );
//
// $fruits = array (
//   "fruits" => array("a" => "orange", "b" => "banana", "c" => "apple"),
//   "numbers" => array(1, 2, 3, 4, 5, 6),
//   "holes"  => array("first", 5 => "second", "third")
// );
//
//
// $content=array(
//   "0"=>array(
//           "jinName" =>  1,
//         "starttime" =>  2,
//              "days" => 3
//            ),
//   "1"=>array(
//           "jinName" =>  4,
//         "starttime" =>  5,
//              "days" =>  6
//            ),
//   "2"=>array(
//          "jinName" =>  7,
//        "starttime" => 8,
//             "days" => 8
//           ),
//   "3"=>array(
//          "jinName" =>  1,
//        "starttime" => 1,
//             "days" =>2
//           ),
//   "4"=>array(
//           "jinName" =>  2,
//         "starttime" => 3,
//              "days" => 4
//            ),
//   "5"=>array(
//           "jinName" =>  5,
//         "starttime" => 3,
//              "days" => 4
//           ),
//   "6"=>array(
//           "jinName" =>  4,
//         "starttime" => 5,
//              "days" =>5
//          ),
//    "7"=>array(
//          "jinName" =>  3,
//        "starttime" => 3,
//             "days" => 4
//     ),
//   "8"=>array(
//         "jinName" =>  5,
//       "starttime" => 3,
//            "days" => 2
//     ),
//   "9"=>array(
//           "jinName" =>  3,
//         "starttime" => 3,
//              "days" => 4
//             )
// );
// $json=phpver($content);
// // print_r($json);
//
// $list= '["2019-06-17","2019-06-20","2019-06-21","2019-06-22"]';
// $ar=json_decode($list,true);
// //print_r($ar);
//
//
// function get_agency($id){
//
//  global $dosql;
//
//  $r=$dosql->GetOne("SELECT * FROM pmw_agency where id=$id");
//
//  $return= $r;
//
//  return $return ;
//
// }
//
// // print_r(get_agency(24));
//
//
// //获取所有旅行社的发布行程的年份
//
// function get_years($id){
//
// global $dosql;
//
// $dosql->Execute("SELECT complete_y FROM pmw_travel where aid=$id and state=2 group by complete_y");
// while($show=$dosql->GetArray()){
// 	$return[]=$show;
// }
//
// return $return;
//
// }
//
// print_r(get_years(20));
/*
$gid=1;
$id=14;
$k=$dosql->GetOne("SELECT state,starttime,endtime from pmw_travel where id=$id");
$state=$k['state'];
if($state==0){

  //判断当前的行程的起始时间
  $starttime = $k['starttime'];  //本次行程的开始时间

  $endtime = $k['endtime'];     //本次行程的截至时间

  //计算出当前导游已经预约过的行程的所有的开始时间

  $one=1;

  $num =0;
  $dosql->Execute("SELECT * FROM pmw_travel where state=1 or state=2 and gid=$gid",$one);

  while($sow=$dosql->GetArray($one)){

   $f=$sow['starttime'];

   $e=$sow['endtime'];

   if($starttime < $e && $e < $endtime){

      $num=1;

      break;

   }elseif($f< $endtime && $endtime< $e){

     $num=2;

     break;

   }elseif($starttime <= $f && $e <= $endtime){

     $num=3;

     break;

   }elseif($f< $starttime && $endtime< $e){

     $num=4;

     break;

   }

  }

  echo $num;
}

$t=strtotime('+7 day');

echo $t;
echo "<hr>";
echo date("Y-m-d H:i:s",$t);

// echo get_new_formid("oz7S15BU6YPAdg8d3aDTwovdFjl0");

$id=13;

$k=$dosql->GetOne("SELECT state,starttime,endtime from pmw_travel where id=$id");
$state=$k['state'];
//判断当前的行程的起始时间
$starttime = $k['starttime'];  //本次行程的开始时间
echo date("Y-m-d",$starttime);

$endtime = $k['endtime'];     //本次行程的截至时间
echo date("Y-m-d",$endtime);

$one=1;

$num =0;

$gid=1;
$dosql->Execute("SELECT * FROM pmw_travel where (state=1 or state=2) and gid=$gid",$one);

while($sow=$dosql->GetArray($one)){

 $f=$sow['starttime'];

 $e=$sow['endtime'];

 if($starttime < $e && $e < $endtime){

    $num=1;

    break;

 }elseif($f< $endtime && $endtime< $e){

   $num=2;

   break;

 }elseif($starttime <= $f && $e <= $endtime){

   $num=3;

   break;

 }elseif($f< $starttime && $endtime< $e){

   $num=4;

   break;
 }

}

echo $num;
*/
//
// function pngMerge($o_pic,$out_pic){
//  $begin_r = 98;
//  $begin_g = 98;
//  $begin_b = 98;
//  list($src_w, $src_h) = getimagesize($o_pic);// 获取原图像信息
//  $src_im = imagecreatefromjpeg($o_pic);
//  //imagecopymerge($target_im, $src_im, 0, 0, 0, 0, $src_w, $src_h, 100);
//  //imagecopyresampled($target_im, $src_im, 0, 0, 0, 0, $src_w, $src_h, $src_w, $src_h);
//  $i = 0;
//  $src_white = imagecolorallocate($src_im, 255, 255, 255);
//  for ($x = 0; $x < $src_w; $x++) {
//    for ($y = 0; $y < $src_h; $y++) {
//     $rgb = imagecolorat($src_im, $x, $y);
//     $r = ($rgb >> 16) & 0xFF;
//     $g = ($rgb >> 8) & 0xFF;
//     $b = $rgb & 0xFF;
//     if($r==255 && $g==255 && $b == 255){
//       $i ++;
//       continue;
//     }
//     if (!($r <= $begin_r && $g <= $begin_g && $b <= $begin_b)) {
//       imagefill($src_im, $x, $y, $src_white);//替换成白色
//     }
//    }
//  }
//  $target_im = imagecreatetruecolor($src_w, $src_h);//新图
//  $tag_white = imagecolorallocate($target_im, 255, 255, 255);
//  imagefill($target_im, 0, 0, $tag_white);
//  imagecolortransparent($target_im, $tag_white);
//  imagecopymerge($target_im, $src_im, 0, 0, 0, 0, $src_w, $src_h, 100);
// }
// $o_pic = 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1561008806808&di=6a74347ba057df6269559851323985a4&imgtype=0&src=http%3A%2F%2Fpic.58pic.com%2F58pic%2F14%2F62%2F31%2F84D58PIC7Vy_1024.png';
// $name = pngMerge($o_pic,'aaaa.png');
// print_r($name);


echo token($cfg_music_appid,$cfg_music_appsecret);
?>
