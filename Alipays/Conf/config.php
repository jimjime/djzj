<?php
/**
 *
 * @package
 * @since 1.0
 */

$config=require './config.inc.php';
$protconf=array(
		TMPL_PARSE_STRING => array(
				'__PUBLIC__' => './Public/Mobile',
				'__JS__' => './Public/js',
				'__IMG__' => './Public/Mobile/img',
				'__YANZHENG__' => './vcode',

		),
		'TMPL_CACHE_ON'=>false,//   Ĭ��guanbiģ�建��
		'HTML_CACHE_ON'   => false,   // Ĭ�Ϲرվ�̬����
		
		
		
		'URL_MODEL' 	=> 0,//����REWRITEģʽ
		'URL_HTML_SUFFIX'	=> '.html',

// 		'APP_DEBUG'			=> true

);
return array_merge($config,$protconf);
?>