<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 短信接口封装，主要封装短信单发与群发
 */
 
class SmsModel extends Model{


var $uid = '92340';	//用户账号
var $pwd = '6432bb';	//用户密码
var $http = 'http://http.shunsms.com/tx/';		//发送地址
//定时发12
//$res = sendSMS($mobile,$content,$time);
//echo $res;

public function sendSMS($mobile,$content,$time='',$mid='')
{
	$data = array
		(
		'uid'=>$this->uid,					//用户账号
		'pwd'=>strtolower(md5($this->pwd)),	//MD5位32密码小写
		'mobile'=>$mobile,				//号码
		'content'=>$content,			//内容
		'time'=>$time,					//空即时发送
		'mid'=>$mid						//子扩展号
		);
	$re= $this->postSMS($this->http,$data);
	return trim($re);
}

public function postSMS($url,$data='')
{
	$row = parse_url($url);
	$host = $row['host'];
	$port = 80;
	$file = $row['path'];
	while (list($k,$v) = each($data)) 
	{
	    global $post;
		$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
	}
	$post = substr( $post , 0 , -1 );
	$len = strlen($post);
	$fp = fsockopen( $host ,$port, $errno, $errstr, 10);
	if (!$fp) {
		return "$errstr ($errno)\n";
	} else {
		$receive = '';
		$out = "POST $file HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Content-type: application/x-www-form-urlencoded\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Content-Length: $len\r\n\r\n";
		$out .= $post;		
		fwrite($fp, $out);
		while (!feof($fp)) {
			$receive .= fgets($fp, 128);
		}
		fclose($fp);
		$receive = explode("\r\n\r\n",$receive);
		unset($receive[0]);
		return implode("",$receive);
	}
}


	
	
	
	
}