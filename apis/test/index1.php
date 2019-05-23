<?php
/**
 * Created by PhpStorm.
 * User: xym
 * Date: 2018/7/31
 * Time: 上午9:58
 * 在本地生成图片文件
 */
function createQr($url=''){
    require_once 'phpqrcode.php';

    $value = $url;					//二维码内容

    $errorCorrectionLevel = 'L';	//容错级别
    $matrixPointSize = 5;			//生成图片大小

    //生成二维码图片

    $erweima_name=date("Ymdhis");
    $url="uploads/erweima/".$erweima_name.".png";
    $save_path="../../".$url;         //生成成功之后的二维码地址
    QRcode::png($value,$save_path , $errorCorrectionLevel, $matrixPointSize, 2);

    $QR = $save_path;				//已经生成的原始二维码图片文件


    $QR = imagecreatefromstring(file_get_contents($QR));

       //输出图片
       //  imagepng($QR, 'qrcode.png');
    //  imagedestroy($QR);
}

//调用查看结果
echo createQr('http://www.zrcase.com/');
?>
