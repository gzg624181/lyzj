<?php
  function select_pic($url,$id,$images,$qrcode){
	require_once 'phpqrcode.php';
    $value = $url;					//二维码内容
    $errorCorrectionLevel = 'H';	//容错级别
    $matrixPointSize = 7;			//生成图片大小
    //生成二维码图片
    $erweima_name=date("Ymdhis");
    $url="uploads/erweima/".$erweima_name.".png";
    $save_path="../../".$url;         //生成成功之后的二维码地址
    QRcode::png($value,$save_path , $errorCorrectionLevel, $matrixPointSize, 2);

	  $logo = '../../uploads/avatar/'.$images.'.jpg';
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
    //删除源文件图片
    $newlogo="/".$qrcode;
    $filename=$_SERVER['DOCUMENT_ROOT'].$newlogo;
    if (file_exists($filename)) {
    unlink($filename);
    }

    //输出图片
    $logo_name=date("Ymdhis");
    $logo_url="uploads/erweima/".$logo_name.".png";
    $logo_path="../../".$url;         //生成成功
    imagepng($QR, $logo_path);
    imagedestroy($QR);
    imagedestroy($logo);
    return $logo_url;


	}
	?>
