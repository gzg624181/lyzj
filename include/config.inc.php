<?php

/*
 * 说明：前端引用文件
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 21:58:30
person: Feng
**************************
*/

require_once(dirname(__FILE__).'/common.inc.php');
require_once(PHPMYWIND_INC.'/func.class.php');
require_once(PHPMYWIND_INC.'/page.class.php');
//require_once(PHPMYWIND_INC.'/common.php');       //后期这方法可以取消
require_once(PHPMYWIND_INC.'/common.class.php');   //公用方法
require_once(PHPMYWIND_INC.'/common.agency.php');  //旅行社类
require_once(PHPMYWIND_INC.'/common.guide.php');   //导游类
if(!defined('IN_PHPMYWIND')) exit('Request Error!');

//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//网站开关
if($cfg_webswitch == 'N')
{
	echo $cfg_switchshow.'<br /><br /><i>'.$cfg_webname.'</i>';
	exit();
}
?>
