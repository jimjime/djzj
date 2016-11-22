<?php include_once("image.php") ?>
<?php
$folder="/data/tou_shi_info/";
if(empty($_GET['act'])){
	$picname = $_FILES['mypic']['name'];
	$picsize = $_FILES['mypic']['size'];
	if ($picname != "") {
		if ($picsize > 2048000) {
			echo '图片大小不能超过2M';
			exit;
		}
		$type = strstr($picname, '.');
		if ( $type != ".jpg") {
			echo '图片格式不对！';
			exit;
		}
		$rand = rand(1000, 9999);
		$pics = date("YmdHis",time()) . "_".$rand . $type;
		//上传路径
		$targetPath = $_SERVER['DOCUMENT_ROOT'] . $folder;
		$pic_path =  str_replace('//','/',$targetPath) . $pics;
		//mkdir(str_replace('//','/',$targetPath), 0755, true);
		if(!is_dir(str_replace('//','/',$targetPath))){
			mkdir(str_replace('//','/',$targetPath), 0755, true); //创建 目录
		}

		move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
		//水印地址
		$water = 'watercs003.gif';
		$img = new image();
		
		//$img->param($targetFile)->water($targetFile,$water,9);
		//$img->param($targetFile)->water($targetFile,$water,9);
		
		//生成缩略图
		if(!is_dir(str_replace('//','/',$targetPath).'thumb/')){
			mkdir(str_replace('//','/',$targetPath).'thumb/', 0755, true); //创建 thumb 目录
		}
		$thumb_url=str_replace('//','/',$targetPath).'thumb/'.$pics;
		$img->param($pic_path)->thumb($thumb_url,500,500,0,0);
	}
	$size = round($picsize/2048,2);
	$arr = array(
		'name'=>$picname,
		'pic'=>$pics,
		'pic_path'=>$folder.$pics,
		'size'=>$size
	);
	echo json_encode($arr);
	
}else{
	$action = $_GET['act'];
	if($action=='delimg'){
		$filename = $_POST['imagename'];
		if(!empty($filename)){
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $folder;
			$pic_path =  str_replace('//','/',$targetPath) . $filename;
			unlink($pic_path);
			$thumb_url=str_replace('//','/',$targetPath).'thumb/'.$filename;
			unlink($thumb_url);
			echo '1';
		}else{
			echo '删除失败.';
		}
	}
}
?>
