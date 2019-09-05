<?php
    /**
	   * 链接地址：update_agency  更改旅行社资料
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
     * id               旅行社id
     * images           旅行社头像
     * name             联系人姓名
     * tel              联系电话
     * province        默认定位省份数字   （新增）  live_province
     * city            默认定位城市数字   （新增）  live_city
     */
require_once("../../include/config.inc.php");
header("content-type:application/json;charset=utf-8");
$body = file_get_contents('php://input');
$_POST = json_decode($body,true);

$Data = array();
$Version=date("Y-m-d H:i:s");
$token = $_POST['atoken'];
$id = $_POST['id'];


if(isset($_POST['images'])){
$images = $_POST['images'];
}

if(isset($_POST['name'])){
$name = $_POST['name'];
}

if(isset($_POST['tel'])){
$tel = $_POST['tel'];
}

if(isset($_POST['province'])){
$province = $_POST['province'];
}

if(isset($_POST['city'])){
$city = $_POST['city'];
}

if(isset($_POST['live_province'])){
$live_province = $_POST['live_province'];
}

if(isset($_POST['live_city'])){
$live_city = $_POST['live_city'];
}

if(isset($token) && $token==$cfg_auth_key){

  $sql = "UPDATE `#@__agency` set ";

  if(isset($city)){
  $sql .= " city=$city,";
  }

  if(isset($images)){
    $savepath= "../../uploads/image/";
    $images = Common::base64_image_content($images,$savepath);
    $images=str_replace("../../",'',$images);
    $sql .= " images='$images',";
  }

  if(isset($live_province)){
  $sql .= " live_province='$live_province',";
  }

  if(isset($live_city)){
      $sql .= " live_city='$live_city',";
  }

  if(isset($province)){
      $sql .= " province=$province,";
  }

  if(isset($tel)){
      $sql .= "tel='$tel',";
  }

  if(isset($name)){
    $sql .= " name='$name',";
  }
  $sql .= " id=$id WHERE id=$id";
  $dosql->ExecNoneQuery($sql);
  $r=$dosql->GetOne("SELECT * FROM pmw_agency where id=$id");
  if(is_array($r)){
  $Data[]=$r;
  $Data[0]['type']='agency';
  $Data[0]['cardpic']=$cfg_weburl."/".$r['cardpic'];
  $Data[0]['images']=$cfg_weburl."/".$r['images'];
  $State = 1;
  $Descriptor = '旅行社信息修改成功!';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $Data
               );
  echo phpver($result);
}else{
  $State = 0;
  $Descriptor = '旅行社信息修改失败!';
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
