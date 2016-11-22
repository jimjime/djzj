<?php
/**
* 默认验证码session为vcode.即:$_SESSION['vcode'];
* 注意在给变量符值时不要把变量的名子和SESSION冲突
* 注:在验证时不分大小写
*/
include("inc_vcode_class.php");
$v	=	new vcode;
//$config['width']	=	50;			//验证码宽
//$config['height']	=	20;			//验证码高
//$config['vcode']	=	"vcode";	//检查验证码时用的SESSION
//$config['type']	=	"default";	//验证码展示的类型default:大写字母,string:小写字母,int:数字
//$config['length']	=	4;			//验证码长度
//$config['interfere']=	10;			//干扰线强度,范围为1-30,0或空为不起用干扰线
//$v->init($config);	//配置
	
$v->create();
?>