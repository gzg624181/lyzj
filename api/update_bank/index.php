<?php
    /**
	   * 链接地址：update_bank  更改银行卡资料
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
     * id               银行卡的id
     * cardnumber       银行卡的卡号
     * cardname         银行卡的名称
     * name             联系人姓名
     * tel              联系电话
     *
     */
require_once("../../include/config.inc.php");
header("content-type:application/json;charset=utf-8");
$body = file_get_contents('php://input');
$_POST = json_decode($body,true);

$Data = array();
$Version=date("Y-m-d H:i:s");
$token = $_POST['token'];
$id = $_POST['id'];

if(isset($_POST['cardname'])){
$cardname = $_POST['cardname'];
}

if(isset($_POST['cardnumber'])){
$cardnumber = $_POST['cardnumber'];
}

if(isset($_POST['name'])){
$name = $_POST['name'];
}

if(isset($_POST['tel'])){
$tel = $_POST['tel'];
}

if(isset($token) && $token==$cfg_auth_key){

  $arr=$_POST;
  $nums= count($arr)-1;
  $newarr=array_keys($arr);

  $lastkey = $newarr[$nums];  //最后一个参数的键

  $sql = "UPDATE `#@__bank` set ";


  if(isset($name)){
    if($lastkey == "name"){
    $sql .= " name='$name' ";
    }else{
    $sql .= " name='$name',";
    }
  }

  if(isset($cardname)){
    if($lastkey=="cardname"){
      $sql .= " cardname='$cardname' ";
    }else{
      $sql .= " cardname='$cardname',";
    }
  }

  if(isset($cardnumber)){
    if($lastkey=="cardnumber"){
      $sql .= " cardnumber='$cardnumber' ";
    }else{
      $sql .= " cardnumber='$cardnumber',";
    }
  }

  if(isset($tel)){
    if($lastkey == "tel"){
    $sql .= "tel='$tel' ";
    }else{
    $sql .= "tel='$tel',";
    }
  }

  $sql .= "WHERE id=$id";
  $dosql->ExecNoneQuery($sql);
  $r=$dosql->GetOne("SELECT * FROM pmw_bank where id=$id");
  if(is_array($r)){
  $State = 1;
  $Descriptor = '提现资料修改成功!';
  $result = array (
              'State' => $State,
              'Descriptor' => $Descriptor,
              'Version' => $Version,
              'Data' => $r
               );
  echo phpver($result);
}else{
  $State = 0;
  $Descriptor = '提现资料修改失败!';
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
