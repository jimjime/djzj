<?php
if(isset($_GET['portal'])){
	$dirs = array('./data/portal');
}
if(isset($_GET['all'])){
	$dirs	=	array('./_runtime');
}else{
//缓存目录
	$dirs	=	array('./manage/Runtime','./Share/Runtime','./Mobile/Runtime','./Weixin/Runtime','./Alipays/Runtime');
}
//清理缓存
foreach($dirs as $value)
{
	rmdirr($value);

	@mkdir($value,0777,true);

}

echo "缓存已清空";
exit;

function rmdirr($dirname) {

	if (!file_exists($dirname)) {
		return false;
	}

	if (is_file($dirname) || is_link($dirname)) {
		return unlink($dirname);
	}

	$dir = dir($dirname);

	while (false !== $entry = $dir->read()) {

		if ($entry == '.' || $entry == '..') {
			continue;
		}

		rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
	}

	$dir->close();

	return rmdir($dirname);
}
?>