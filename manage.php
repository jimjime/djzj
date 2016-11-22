<?php
header('Content-Type:text/html;charset=utf-8');
define('APP_DEBUG',true);
/*
if($_SERVER['HTTP_HOST']=='esports666.com' || $_SERVER['HTTP_HOST']=='123.56.86.98'){
    header("Location: http://www.esports666.com".$_SERVER['REQUEST_URI']."");
    exit;
}*/
 

//error_reporting(E_ERROR | E_PARSE | E_STRICT);

define('__BASE__', dirname(__FILE__));
define('APP_NAME', 'manage');
define('APP_PATH', './manage/');

require('./core/mcbn.php');
