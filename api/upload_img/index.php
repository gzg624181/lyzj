<?php
    /**
	   * 链接地址：upload_img    微信小程序前端上传图片
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
     * @提供返回参数账号  live_city  live_province
     */
		require_once("../../include/config.inc.php");
		header("Content-type:application:application/json; charset:utf-8");
		$Data = array();
		$Version=date("Y-m-d H:i:s");


  $uplad_tmp_name=$_FILES['imgfile']['tmp_name']; //保存的是文件上传到服务器临时文件夹之后的文件名
  $uplad_name    =$_FILES['imgfile']['name'];     //保存的文件在上传者机器上的文件名


  /*图片上传处理*/
  //把图片传到服务器
  //上传文件路径
  //如果当前图片不为空
          if(!empty($uplad_name))
          {
                  $uptype = explode(".",$uplad_name);
                  $uparray = array_reverse($uptype);  //图片的格式（.png, .jpg .gif)


                  $new_file = $_SERVER['DOCUMENT_ROOT']."/uploads/image/".date('Ymd')."/";

                  if(!file_exists($new_file)){ //检查是否有该文件夹，如果没有就创建，并给予最高权限
                  mkdir($new_file, 0700);
                  }

                  $newname = $new_file.time().rand(111,999).".".$uparray[0]; //图片名以时间命名
                  //echo  $newname;

                  $uplad_name= $newname;
                  //如果上传的文件没有在服务器上存在
                  if(!file_exists($uplad_name))
                  {
                      //把图片文件从临时文件夹中转移到我们指定上传的目录中
                   move_uploaded_file($uplad_tmp_name,$newname);
                  }
          }

      $Data = str_replace($_SERVER['DOCUMENT_ROOT']."/",'',$newname);

      $State = 1;
      $Descriptor = '图片上传成功';
      $result = array (
                      'State' => $State,
                      'Descriptor' => $Descriptor,
      				         'Version' =>  $Version,
                       'Data' => $Data,
                       );
      echo phpver($result);


?>
