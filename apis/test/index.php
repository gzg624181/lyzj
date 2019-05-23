<?php
/**
 * Created by PhpStorm.
 * User: xym
 * Date: 2018/7/31
 * Time: 下午9:34
 * 生成二维码带logo
 */
function createQr($url=''){
    require_once 'phpqrcode.php';
    $value = $url;					//二维码内容
    $errorCorrectionLevel = 'H';	//容错级别
    $matrixPointSize = 7;			//生成图片大小
    //生成二维码图片
    $erweima_name='pic'.date("Ymdhis");
    $url="uploads/erweima/".$erweima_name.".png";
    $save_path="../../".$url;         //生成成功之后的二维码地址
    QRcode::png($value,$save_path , $errorCorrectionLevel, $matrixPointSize, 2);

    $logo = '../../uploads/erweima/5.jpg'; 	//准备好的logo图片
    $QR = $save_path;			//已经生成的原始二维码图

    if ($logo !== FALSE) {
    $QR = imagecreatefromstring(file_get_contents($QR));
    $logo = imagecreatefromstring(file_get_contents($logo));
    $QR_width = imagesx($QR);//二维码图片宽度
    $QR_height = imagesy($QR);//二维码图片高度
    $logo_width = imagesx($logo);//logo图片宽度
    $logo_height = imagesy($logo);//logo图片高度
    $logo_qr_width = $QR_width / 5;
    $scale = $logo_width/$logo_qr_width;
    $logo_qr_height = $logo_height/$scale;
    $from_width = ($QR_width - $logo_qr_width) / 2;
    //重新组合图片并调整大小
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
    $logo_qr_height, $logo_width, $logo_height);
}

    //输出图片
    $erweima_name='logo'.date("Ymdhis");
    $logo_url="uploads/erweima/".$erweima_name.".png";
    $logo_path="../../".$url;         //生成成功
   imagepng($QR, $logo_path);
   imagedestroy($QR);
   imagedestroy($logo);
   //return '<img src="qrcode_pay.png" alt="使用微信扫描支付">';
}

//调用查看结果
echo createQr('http://www.zrcase.com/');
