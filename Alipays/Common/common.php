<?php
/**
 *
 * @package 
 * @since 1.0
 */
 
	//发送邮件
	function sendemail($address,$sendname,$revname,$content,$ss){
		import('@.ORG.phpmailer');
		$mail = new phpmailer();
		$mail->IsSMTP(); // send via SMTP phperz~com
		$mail->Host = "smtp.qq.com"; // SMTP servers
		$mail->SMTPAuth = true; // turn on SMTP authentication
		$mail->Username = "1824383821"; // SMTP username 注意：普通邮件认证不需要加 @域名?
		$mail->Password = "dingding123"; // SMTP password
		$mail->From = "1824383821@qq.com";
		$mail->CharSet='UTF-8';
		$mail->FromName = $sendname;
		$mail->AddAddress($address, $revname);
		$mail->Subject = $ss;//邮件标题
		$mail->Body = $content;
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
		if(!$mail->Send())
		{
			echo "邮件发送失败. <p>";
			echo "错误原因: " . $mail->ErrorInfo;
			exit;
		}
		//echo "邮件发送成功";
	}
/**
 * 转换为安全的纯文本
 * 
 * @param string  $text
 * @param boolean $parse_br    是否转换换行符
 * @param int     $quote_style ENT_NOQUOTES:(默认)不过滤单引号和双引号 ENT_QUOTES:过滤单引号和双引号 ENT_COMPAT:过滤双引号,而不过滤单引号
 * @return string|null string:被转换的字符串 null:参数错误
 */
function t($text, $parse_br = false, $quote_style = ENT_NOQUOTES)
{
	if (is_numeric($text))
		$text = (string)$text;
	
	if (!is_string($text))
		return null;
	
	if (!$parse_br) {
		$text = str_replace(array("\r","\n","\t"), ' ', $text);
	} else {
		$text = nl2br($text);
	}
	
	$text = stripslashes($text);
	$text = htmlspecialchars($text, $quote_style, 'UTF-8');
	
	return $text;
}
//输出安全的html
function h($text, $tags = null){
	$text	=	trim($text);
	$text	=	preg_replace('/<!--?.*-->/','',$text);
	//完全过滤注释
	$text	=	preg_replace('/<!--?.*-->/','',$text);
	//完全过滤动态代码
	$text	=	preg_replace('/<\?|\?'.'>/','',$text);
	//完全过滤js
	$text	=	preg_replace('/<script?.*\/script>/','',$text);

	$text	=	str_replace('[','&#091;',$text);
	$text	=	str_replace(']','&#093;',$text);
	$text	=	str_replace('|','&#124;',$text);
	//过滤换行符
	$text	=	preg_replace('/\r?\n/','',$text);
	//br
	$text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
	$text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
	//过滤危险的属性，如：过滤on事件lang js
	while(preg_match('/(<[^><]+) (lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1],$text);
	}
	while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1].$mat[3],$text);
	}
	if(empty($tags)) {
		$tags = 'table|tbody|td|th|tr|i|b|u|strong|img|p|br|div|span|em|ul|ol|li|dl|dd|dt|a|alt|h[1-9]?';
		$tags.= '|object|param|embed';	// 音乐和视频
	}
	//允许的HTML标签
	$text	=	preg_replace('/<(\/?(?:'.$tags.'))( [^><\[\]]*)?>/i','[\1\2]',$text);
	//过滤多余html
	$text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|style|xml)[^><]*>/i','',$text);
	//过滤合法的html标签
	while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
		$text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
	}
	//转换引号
	while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
		$text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4],$text);
	}
	//过滤错误的单个引号
	// 修改:2011.05.26 kissy编辑器中表情等会包含空引号, 简单的过滤会导致错误
//	while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
//		$text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
//	}
	//转换其它所有不合法的 < >
	$text	=	str_replace('<','&lt;',$text);
	$text	=	str_replace('>','&gt;',$text);
    $text   =   str_replace('"','&quot;',$text);
    //$text   =   str_replace('\'','&#039;',$text);
	 //反转换
	$text	=	str_replace('[','<',$text);
	$text	=	str_replace(']','>',$text);
	$text	=	str_replace('|','"',$text);
	//过滤多余空格
	$text	=	str_replace('  ',' ',$text);
	return $text;
}

/**
 *
 +--------------------------------------------------
 * 获取广告代码
 +--------------------------------------------------
 * @param $atype	广告名
 +--------------------------------------------------
 * @return string	广告代码
 +--------------------------------------------------
 */
//function getad($title){
//	assert ( is_string($title) );
//	
//	$data = M('advance')->where("title='".$title."'")->field('url,pic')->find();
//	if($data){
//		$html = "<a href='".$data['url']."'><img src='".$data['pic']."' /></a>";
//	}else{
//	    $html = NULL;
//	}
//
//	return $html;
//}

/**
 *
 +--------------------------------------------------
 * 获取banner图代码
 +--------------------------------------------------
 * @author wangcheng	<360954149@qq.com>
 +--------------------------------------------------
 * @param $title	广告名
 +--------------------------------------------------
 * @return string	广告代码
 +--------------------------------------------------
 */
//function getbanner($title){
//	assert ( is_string($title) );
//	
//	$data = M('banner')->where("location='".$title."'")->field('url,pic')->find();
//	if($data){
//	    if(empty($data['url'])){
//			$html = "<img src='".$data['pic']."' />";
//		}else{
//			$html = "<a href='".$data['url']."'><img src='".$data['pic']."' /></a>";
//		}
//
//	}else{
//	    $html = NULL;
//	}
//	return $html;
//}


