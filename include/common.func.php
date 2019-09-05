<?php	if(!defined('IN_PHPMYWIND')) exit('Request Error!');

/*
**************************
(C)2010-2015 phpMyWind.com
update: 2014-5-31 21:57:40
person: Feng
project :前后台所有通用方法
**************************
*/

//如果数据库更新了数据 ，则先删除redis缓存，然后更新数据 ，再更新redis缓存  ,data  json字符串


function update_redis($redis_key,$data){

	//连接本地的 Redis 服务
	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);
	//判断是否存在这个redis的  key
  if($redis->exists($redis_key)){
  //删除这个redis
	$redis->del($redis_key);
  $redis->set($redis_key,$data);
	}else{
	$redis->set($redis_key,$data);
	}

}

//将php数组转换为json
function phpver($result){
	if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		$json = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function ($matches) {
				return iconv('UCS-2BE', 'UTF-8', pack('H4', $matches[1]));
		}, json_encode($result));
		 return $json;
} else {
		$json = json_encode($result, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		return $json;
}
}


//匹配测试
function check_str($str, $substr)  //原字符  ，需要匹配的字符
{
 $nums=substr_count($str,$substr);

 if ($nums>=1)
 {
	return true;
 }
 else
 {
	return false;
 }
}


//curl请求函数，微信都是通过该函数请求,后台采用https_request方法
function https_request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

//获取微信小程序 access_token
function get_access_token($appid,$appsecret){
  $arr = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);  //去除对象里面的斜杠
  $result = json_decode($arr, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
  //logs('log.txt',$result);
  $access_token = $result['access_token'];
  return $access_token;
}

//获取微信小程序openid
function get_openid($code,$appid,$appsecret){
  $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code=' . $code . '&grant_type=authorization_code';
  $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl
  $json = json_decode($info);//对json数据解码
  $arr = get_object_vars($json);
  $openid = $arr['openid'];
  return $openid;
}

function logs($file,$data){
  file_put_contents($file,print_r($data,true));
}



 function save_erweima($access_token,$xiaochengxu_path,$save_path,$url,$id,$time,$poster) {
       $post_url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$access_token";
       $width = '200';
   	//前面是推荐码，商户端是1，客户端是0
     	$scene=$id."&".$time."&".$poster;
       $post_data='{"page":"'.$xiaochengxu_path.'","width":'.$width.',"scene":"'.$scene.'"}';
       $opts = array('http' =>
           array(
               'method'  => 'POST',
               'header'  => 'Content-type: application/json',
               'content' => $post_data
           )
       );
       $context = stream_context_create($opts);
       $result = file_get_contents($post_url, false, $context);
       $file_path = $save_path;
       $bytes = file_put_contents($file_path, $result);
       return $url;
   }


 function pngMerge($o_pic,$out_pic){
   $begin_r = 255;
   $begin_g = 250;
   $begin_b = 250;
   list($src_w, $src_h) = getimagesize($o_pic);// 获取原图像信息 宽高
   $src_im = imagecreatefrompng($o_pic); //读取png图片
   //print_r($src_im);
   imagesavealpha($src_im,true);//这里很重要 意思是不要丢了$src_im图像的透明色
   $src_white = imagecolorallocatealpha($src_im, 255, 255, 255,127); // 创建一副白色透明的画布
   for ($x = 0; $x < $src_w; $x++) {
    for ($y = 0; $y < $src_h; $y++) {
   	 $rgb = imagecolorat($src_im, $x, $y);
   	 $r = ($rgb >> 16) & 0xFF;
   	 $g = ($rgb >> 8) & 0xFF;
   	 $b = $rgb & 0xFF;
   	 if($r==255 && $g==255 && $b == 255){
   	 imagefill($src_im,$x, $y, $src_white); //填充某个点的颜色
   	 imagecolortransparent($src_im, $src_white); //将原图颜色替换为透明色
   	 }
   	 if (!($r <= $begin_r && $g <= $begin_g && $b <= $begin_b)) {
   		imagefill($src_im, $x, $y, $src_white);//替换成白色
   		imagecolortransparent($src_im, $src_white); //将原图颜色替换为透明色
   	 }
    }
   }
   $target_im = imagecreatetruecolor($src_w, $src_h);//新图
   imagealphablending($target_im,false);//这里很重要,意思是不合并颜色,直接用$target_im图像颜色替换,包括透明色;
   imagesavealpha($target_im,true);//这里很重要,意思是不要丢了$target_im图像的透明色;
   $tag_white = imagecolorallocatealpha($target_im, 255, 255, 255,127);//把生成新图的白色改为透明色 存为tag_white
   imagefill($target_im, 0, 0, $tag_white);//在目标新图填充空白色
   imagecolortransparent($target_im, $tag_white);//替换成透明色
   imagecopymerge($target_im, $src_im, 0, 0, 0, 0, $src_w, $src_h, 100);//合并原图和新生成的透明图
   imagepng($target_im,$out_pic);
   // return $out_pic;
   }

 function img_water_mark($srcImg, $waterImg, $savepath=null, $savename=null, $position=5, $opacity=50){
       $temp = pathinfo($srcImg);
       $name = $temp['basename'];
       $path = $temp['dirname'];
     //  $exte = $temp['extension'];
       $savename = $savename ? $savename : $name;
       $savepath = $savepath ? $savepath : $path;
       $savefile = $savepath.'/'.$savename;

       $srcinfo = @getimagesize($srcImg);
       if(!$srcinfo){
           return -1;
       }
       $waterinfo = @getimagesize($waterImg);
       if(!$waterinfo){
           return -2;
       }
       $srcImgObj = img_create_from_ext($srcImg);
       if(!$srcImgObj){
           return -3;
       }
       $waterImgObj = img_create_from_ext($waterImg);
       if(!$waterImgObj){
           return -4;
       }
       switch ($position) {
           case 1:
               $x=$y=0;
               break;
           case 2:
               $x=$srcinfo[0] /2.8;
               $y=$waterinfo[1]/1.5;
               break;
           case 3:
               $x=($srcinfo[0] - $waterinfo[0])/2;
               $y=($srcinfo[1] - $waterinfo[1])/2;
               break;
           case 4:
               $x=0;
               $y=$srcinfo[1] - $waterinfo[1];
               break;
           case 5:
               $x=$srcinfo[0] /2;
               $y=$srcinfo[1] - $waterinfo[1]*1.5;
               break;
       }
       // 合并图片+水印
       imagecopymerge($srcImgObj, $waterImgObj, $x, $y, 0, 0, $waterinfo[0], $waterinfo[1], $opacity);

       switch ($srcinfo[2]) {
           case 1:
               imagegif($srcImgObj, $savefile);
               break;
           case 2:
               imagejpeg($srcImgObj, $savefile);
               break;
           case 3:
               imagepng($srcImgObj, $savefile);
               break;
           default: return -5;
       }
       imagedestroy($srcImgObj);
       imagedestroy($waterImgObj);
       return $savefile;
   }

   /**
    *图片加水印
    *@param $srcImg 原图
    *@param $waterImg 水印图片
    *@param $savepath 保存路径
    *@param $savename 保存名字
    *@param $position 水印位置
    *1：左上  2：右上 3:居中 4：左下 5：右下
    *@param $opacity 透明度
    *0:全透明 100：完全不透明
    *@return  成功 -- 加水印后的新图片地址
    *         失败 -- -1：源文件不存在，-2：水印不存在，-3源文件图片对象建立失败，-4：水印文件图像对象建立失败，-5：加水印后的新图片保存失败
    * 获取源文件路径、宽高等信息，得出保存后文件保存路径、水印放置位置->建立源文件和水印图片对象->合并图片对象（imagecopymerge）->销毁图片对象
    */

  function  img_create_from_ext($imgfile){
       $info = getimagesize($imgfile);
       $im = null;
       switch ($info[2]) {
           case 1:
               $im = imagecreatefromgif($imgfile);
               break;
           case 2:
               $im = imagecreatefromjpeg($imgfile);
               break;
           case 3:
               $im = imagecreatefrompng($imgfile);
               break;
       }
       return $im;
   }

/*
 * 函数说明：截取指定长度的字符串
 *         utf-8专用 汉字和大写字母长度算1，其它字符长度算0.5
 *
 * @param  string  $str  原字符串
 * @param  int     $len  截取长度
 * @param  string  $etc  省略字符...
 * @return string        截取后的字符串
 */
if(!function_exists('ReStrLen'))
{
	function ReStrLen($str, $len=10, $etc='...')
	{
		$restr = '';
		$i = 0;
		$n = 0.0;

		//字符串的字节数
		$strlen = strlen($str);
		while(($n < $len) and ($i < $strlen))
		{
		   $temp_str = substr($str, $i, 1);

		   //得到字符串中第$i位字符的ASCII码
		   $ascnum = ord($temp_str);

		   //如果ASCII位高与252
		   if($ascnum >= 252)
		   {
				//根据UTF-8编码规范，将6个连续的字符计为单个字符
				$restr = $restr.substr($str, $i, 6);
				//实际Byte计为6
				$i = $i + 6;
				//字串长度计1
				$n++;
		   }
		   else if($ascnum >= 248)
		   {
				$restr = $restr.substr($str, $i, 5);
				$i = $i + 5;
				$n++;
		   }
		   else if($ascnum >= 240)
		   {
				$restr = $restr.substr($str, $i, 4);
				$i = $i + 4;
				$n++;
		   }
		   else if($ascnum >= 224)
		   {
				$restr = $restr.substr($str, $i, 3);
				$i = $i + 3 ;
				$n++;
		   }
		   else if ($ascnum >= 192)
		   {
				$restr = $restr.substr($str, $i, 2);
				$i = $i + 2;
				$n++;
		   }

		   //如果是大写字母 I除外
		   else if($ascnum>=65 and $ascnum<=90 and $ascnum!=73)
		   {
				$restr = $restr.substr($str, $i, 1);
				//实际的Byte数仍计1个
				$i = $i + 1;
				//但考虑整体美观，大写字母计成一个高位字符
				$n++;
		   }

		   //%,&,@,m,w 字符按1个字符宽
		   else if(!(array_search($ascnum, array(37, 38, 64, 109 ,119)) === FALSE))
		   {
				$restr = $restr.substr($str, $i, 1);
				//实际的Byte数仍计1个
				$i = $i + 1;
				//但考虑整体美观，这些字条计成一个高位字符
				$n++;
		   }

		   //其他情况下，包括小写字母和半角标点符号
		   else
		   {
				$restr = $restr.substr($str, $i, 1);
				//实际的Byte数计1个
				$i = $i + 1;
				//其余的小写字母和半角标点等与半个高位字符宽
				$n = $n + 0.5;
		   }
		}

		//超过长度时在尾处加上省略号
		if($i < $strlen)
		{
		   $restr = $restr.$etc;
		}

		return $restr;
	}
}


//获得当前的页面文件的url
if(!function_exists('GetCurUrl'))
{
	function GetCurUrl()
	{
		if(!empty($_SERVER['REQUEST_URI']))
		{
			$nowurls = explode('?',$_SERVER['REQUEST_URI']);
			$nowurl = $nowurls[0];
		}
		else
		{
			$nowurl = $_SERVER['PHP_SELF'];
		}

		return $nowurl;
	}
}


//获取IP
if(!function_exists('GetIP'))
{
	function GetIP()
	{
		static $ip = NULL;
		if($ip !== NULL) return $ip;

		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown',$arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip  = trim($arr[0]);
		}
		else if(isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(isset($_SERVER['REMOTE_ADDR']))
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		//IP地址合法验证
		$ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
		return $ip;
	}
}


//查看数据大小
if(!function_exists('GetRealSize'))
{
	function GetRealSize($size)
	{
		$kb = 1024;          // Kilobyte
		$mb = 1024 * $kb;    // Megabyte
		$gb = 1024 * $mb;    // Gigabyte
		$tb = 1024 * $gb;    // Terabyte

		if($size < $kb)
			return $size.'B';

		else if($size < $mb)
			return round($size/$kb,2).'KB';

		else if($size < $gb)
			return round($size/$mb,2).'MB';

		else if($size < $tb)
			return round($size/$gb,2).'GB';

		else
			return round($size/$tb,2).'TB';
	}
}


//获取文件夹大小
if(!function_exists('GetDirSize'))
{
	function GetDirSize($dir)
	{
		$handle = opendir($dir);
		$fsize  = '';

		while(($fname = readdir($handle)) !== false)
		{
			if($fname != '.' && $fname != '..')
			{
				if(is_dir("$dir/$fname"))
					$fsize += GetDirSize("$dir/$fname");
				else
					$fsize += filesize("$dir/$fname");
			}
		}

		closedir($handle);
		if(empty($fsize)) $fsize = 0;

		return $fsize;
	}
}


//返回格林威治标准时间
if(!function_exists('MyDate'))
{
	function MyDate($format='Y-m-d H:i:s', $timest=0)
	{
		global $cfg_timezone;

		$addtime = $cfg_timezone * 3600;
		if(empty($format))
			$format = 'Y-m-d H:i:s';

		return gmdate($format, $timest+$addtime);
	}
}


//返回格式化(Y-m-d H:i:s)的时间
if(!function_exists('GetDateTime'))
{
	function GetDateTime($mktime)
	{
		return MyDate('Y-m-d H:i:s',$mktime);
	}
}


//返回格式化(Y-m-d)的日期
if(!function_exists('GetDateMk'))
{
	function GetDateMk($mktime)
	{
		return MyDate('Y-m-d', $mktime);
	}
}


//从普通时间转换为Linux时间截
if(!function_exists('GetMkTime'))
{
	function GetMkTime($dtime)
	{
		if(!preg_match("/[^0-9]/", $dtime))
		{
			return $dtime;
		}
		$dtime = trim($dtime);
		$dt = array(1970, 1, 1, 0, 0, 0);
		$dtime = preg_replace("/[\r\n\t]|日|秒/", " ", $dtime);
		$dtime = str_replace("年", "-", $dtime);
		$dtime = str_replace("月", "-", $dtime);
		$dtime = str_replace("时", ":", $dtime);
		$dtime = str_replace("分", ":", $dtime);
		$dtime = trim(preg_replace("/[ ]{1,}/", " ", $dtime));
		$ds = explode(" ", $dtime);
		$ymd = explode("-", $ds[0]);
		if(!isset($ymd[1])) $ymd = explode(".", $ds[0]);
		if(isset($ymd[0])) $dt[0] = $ymd[0];
		if(isset($ymd[1])) $dt[1] = $ymd[1];
		if(isset($ymd[2])) $dt[2] = $ymd[2];
		if(strlen($dt[0])==2) $dt[0] = '20'.$dt[0];
		if(isset($ds[1]))
		{
			$hms = explode(":", $ds[1]);
			if(isset($hms[0])) $dt[3] = $hms[0];
			if(isset($hms[1])) $dt[4] = $hms[1];
			if(isset($hms[2])) $dt[5] = $hms[2];
		}
		foreach($dt as $k=>$v)
		{
			$v = preg_replace("/^0{1,}/", '', trim($v));
			if($v == '')
			{
				$dt[$k] = 0;
			}
		}

		$mt = mktime($dt[3], $dt[4], $dt[5], $dt[1], $dt[2], $dt[0]);
		if(!empty($mt)) return $mt;
		else return time();
	}
}


//创建多级目录
if(!function_exists('MkDirs'))
{
	function MkDirs($dir)
	{
		return is_dir($dir) or (MkDirs(dirname($dir)) and mkdir($dir, 0777));
	}
}


//显示信息
if(!function_exists('ShowMsg'))
{
	function ShowMsg($msg='', $gourl='-1')
	{
		if($gourl == '-1')
			echo '<script>alert("'.$msg.'");history.go(-1);</script>';

		else if($gourl == '0')
			echo '<script>alert("'.$msg.'");location.reload();</script>';

		else
			echo '<script>alert("'.$msg.'");location.href="'.$gourl.'";</script>';
	}
}


//读取文件内容
if(!function_exists('Readf'))
{
	function Readf($file)
	{
		if(file_exists($file) && is_readable($file))
		{
			if(function_exists('file_get_contents'))
			{
				$str = file_get_contents($file);
			}
			else
			{
				$str = '';

				$fp = fopen($file, 'r');
				while(!feof($fp))
				{
					$str .= fgets($fp, 1024);
				}
				fclose($fp);
			}
			return $str;
		}
		else
		{
			return FALSE;
		}
	}
}


//写入文件内容
if(!function_exists('Writef'))
{
	function Writef($file,$str,$mode='w')
	{
		if(file_exists($file) && is_writable($file))
		{
			$fp = fopen($file, $mode);
			flock($fp, 3);
			fwrite($fp, $str);
			fclose($fp);

			return TRUE;
		}
		else if(!file_exists($file))
		{
			$fp = fopen($file, $mode);
			flock($fp, 3);
			fwrite($fp, $str);
			fclose($fp);
		}
		else
		{
			return FALSE;
		}
	}
}


//查看url中是否包含http
if(!function_exists('IsHttpUrl'))
{
	function IsHttpUrl($url)
	{
		if(!preg_match("/^(http|ftp):/", $url))
		{
			$url = 'http://'.$url;
		}

		return $url;
	}
}


//执行时间函数
if(!function_exists('ExecTime'))
{
	function ExecTime()
	{
		$time = explode(" ", microtime());
		$usec = (double)$time[0];
		$sec = (double)$time[1];
		return $sec + $usec;
	}
}


//清除HTML
if(!function_exists('ClearHtml'))
{
	function ClearHtml($str)
	{
		$str = strip_tags($str);

		//首先去掉头尾空格
		$str = trim($str);

		//接着去掉两个空格以上的
		$str = preg_replace('/\s(?=\s)/', '', $str);

		//最后将非空格替换为一个空格
		$str = preg_replace('/[\n\r\t]/', ' ', $str);

		return $str;
	}
}


//获取指定长度随机字符串
if(!function_exists('GetRandStr'))
{
	function GetRandStr($length=6)
	{
		//'!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$random_str = '';

		for($i=0; $i<$length; $i++)
		{
			//这里提供两种字符获取方式
			//第一种是使用 substr 截取$chars中的任意一位字符；
			//第二种是取字符数组 $chars 的任意元素
			//$password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
			$random_str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}

		return $random_str;
	}
}


/* 参数解释
   $string： 明文 或 密文
   $operation：DECODE表示解密,其它表示加密
   $key： 密匙
   $expiry：密文有效期*/
if(!function_exists('AuthCode'))
{
	function AuthCode($string, $operation='DECODE', $key='', $expiry=0)
	{
		// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
		// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
		// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
		// 当此值为 0 时，则不产生随机密钥
		$ckey_length = 4;
		// 密匙
		$key = md5($key ? $key : $GLOBALS['cfg_auth_key']);
		// 密匙a会参与加解密
		$keya = md5(substr($key, 0, 16));
		// 密匙b会用来做数据完整性验证
		$keyb = md5(substr($key, 16, 16));
		// 密匙c用于变化生成的密文
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
		// 参与运算的密匙
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
		// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
		// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
		$result = '';
		$box = range(0, 255);
		$rndkey = array();

		// 产生密匙簿
		for($i = 0; $i <= 255; $i++)
		{
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
		for($j = $i = 0; $i < 256; $i++)
		{
			//$j是三个数相加与256取余
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		// 核心加解密部分
		for($a = $j = $i = 0; $i < $string_length; $i++)
		{
			//在上面基础上再加1 然后和256取余
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;//$j加$box[$a]的值 再和256取余
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			// 从密匙簿得出密匙进行异或，再转成字符，加密和解决时($box[($box[$a] + $box[$j]) % 256])的值是不变的。
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE')
		{
			// substr($result, 0, 10) == 0 验证数据有效性
			// substr($result, 0, 10) - time() > 0 验证数据有效性
			// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
			// 验证数据有效性，请看未加密明文的格式
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
			{
				return substr($result, 26);
			}
			else
			{
				return '';
			}
		}
		else
		{
			// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
			// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
}


/*字符串转数组*/
if(!function_exists('String2Array'))
{
	function String2Array($data)
	{
		if($data == '') return array();
		@eval("\$array = $data;");
		return $array;
	}
}


/**
*判断是否是通过手机访问
*/
if(!function_exists('isMobile'))
{
	function IsMobile()
	{

		//如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  return TRUE;

		//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if(isset($_SERVER['HTTP_VIA']))
		{
			//找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}

		//判断手机发送的客户端标志,兼容性有待提高
		if(isset($_SERVER['HTTP_USER_AGENT']))
		{

			$clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');

			//从HTTP_USER_AGENT中查找手机浏览器的关键字
			if(preg_match('/('.implode('|', $clientkeywords).')/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
			{
				return TRUE;
			}
		}

		//协议法，因为有可能不准确，放到最后判断
		if(isset($_SERVER['HTTP_ACCEPT']))
		{
			//如果只支持wml并且不支持html那一定是移动设备
			//如果支持wml和html但是wml在html之前则是移动设备
			if((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) &&
			   (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false ||
			   (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
			{
					return TRUE;
			}
		}

		return FALSE;
	}
}
?>
