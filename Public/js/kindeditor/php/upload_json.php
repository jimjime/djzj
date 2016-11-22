<?php
/**
 * KindEditor PHP
 *
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 *
 */

require_once 'JSON.php';

//$php_path = dirname(__FILE__) . '/';
//$php_url = dirname($_SERVER['PHP_SELF']) . '/';

//文件保存目录路径
$save_path = '../../../../data/uploads/';
//文件保存目录URL
$save_url = '../../../../data/uploads/';
//定义允许上传的文件扩展名
$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	'flash' => array('swf', 'flv'),
	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
);
//最大文件大小
$max_size = 5000000;

$save_path = realpath($save_path) . '/';

//PHP上传失败
if (!empty($_FILES['imgFile']['error'])) {
	switch($_FILES['imgFile']['error']){
		case '1':
			$error = '超过php.ini允许的大小。';
			break;
		case '2':
			$error = '超过表单允许的大小。';
			break;
		case '3':
			$error = '图片只有部分被上传。';
			break;
		case '4':
			$error = '请选择图片。';
			break;
		case '6':
			$error = '找不到临时目录。';
			break;
		case '7':
			$error = '写文件到硬盘出错。';
			break;
		case '8':
			$error = 'File upload stopped by extension。';
			break;
		case '999':
		default:
			$error = '未知错误。';
	}
	alert($error);
}

//有上传文件时
if (empty($_FILES) === false) {
	//原文件名
	$file_name = $_FILES['imgFile']['name'];
	//服务器上临时文件名
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//文件大小
	$file_size = $_FILES['imgFile']['size'];
	//检查文件名
	if (!$file_name) {
		alert("请选择文件。");
	}
	//检查目录
	if (@is_dir($save_path) === false) {
		alert("上传目录不存在。");
	}
	//检查目录写权限
	if (@is_writable($save_path) === false) {
		alert("上传目录没有写权限。");
	}
	//检查是否已上传
	if (@is_uploaded_file($tmp_name) === false) {
		alert("上传失败。");
	}
	//检查文件大小
	if ($file_size > $max_size) {
		alert("上传文件大小超过限制。");
	}
	//检查目录名
	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) {
		alert("目录名不正确。");
	}
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
	}
	//创建文件夹
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) {
			mkdir($save_path);
		}
	}
	$ymd = date("Ymd");
	$save_path .= $ymd . "/";
	$save_url .= $ymd . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//新文件名
	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//移动文件
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("上传文件失败。");
	}
	@chmod($file_path, 0644);
	$file_url = $save_url . $new_file_name;

	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 0, 'url' => $file_url));
	
	/* 水印配置开始 */
    $water_mark = 1;//1为加水印, 其它为不加
    $water_img = '../../../../Public/js/upload/watercs.gif';//水印图片,默认填写空,请将图片上传至网站根目录的upfiles下,例: water.gif
    if($water_mark == 1){
        $s = new Image_process( $file_path );
        $s->watermarkImage($water_img);
    }
	
    /* 水印配置结束 */	
		
	exit;
}

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}

/*
    用法:
    $s = new Image_process( $item );
    $s->watermarkImage($logo);  //生成水印
    $s->scaleImage(0.8);
    $s->fixSizeImage(200,false);//生成缩略图
 */
class Image_process{
    public $source;//原图
    public $source_width;//宽
    public $source_height;//高
    public $source_type_id;
    public $orign_name;
    public $orign_dirname;
    //传入图片路径
    public function __construct($source){
        $this->typeList      = array(1=>'gif',2=>'jpg',3=>'png');
        $ginfo               = @getimagesize($source);
        $this->source_width  = $ginfo[0];
        $this->source_height = $ginfo[1];
        $this->source_type_id= $ginfo[2];
        $this->orign_url     = $source;
        $this->orign_name    = basename($source);
        $this->orign_dirname = dirname($source);
    }
 
    //判断并处理,返回PHP可识别编码
    public function judgeType($type,$source){
        if($type==1){
            return ImageCreateFromGIF($source);//gif
        }else if($type==2){
            return ImageCreateFromJPEG($source);//jpg
        }else if($type==3){
            return ImageCreateFromPNG($source);//png
        }else{
            return false;
        } 
    }
 
    //生成水印图
    public function watermarkImage($logo){
        $linfo        = @getimagesize($logo);
        $logo_width   = $linfo[0];
        $logo_height  = $linfo[1];
        $logo_type_id = $linfo[2];
        $sourceHandle = $this->judgeType($this->source_type_id,$this->orign_url);
        $logoHandle   = $this->judgeType($logo_type_id,$logo);
 
        if( !$sourceHandle || ! $logoHandle ){
            return false;
        }
        $x = $this->source_width - $logo_width;
        $y = $this->source_height- $logo_height;
		
		//改变水印图大小
//		$logo_new_width=$this->source_width;
//		$logo_new_height=$this->source_width/$logo_width*$logo_height;
		
//		$logo_new = imagecreatetruecolor($logo_new_width, $logo_new_height); 
//		//$logo_new = imagecreate($logo_new_width, $logo_new_height); 
//		$white_color = imagecolorallocate($logo_new, 153,154,155); 
//		imagecolortransparent($logo_new,$white_color);
//		imagefill($logo_new,0,0,$white_color); 
		
//		imagesavealpha($logoHandle,true);//这里很重要 意思是不要丢了$sourePic图像的透明色;    
//		$logo_new = imagecreatetruecolor($logo_new_width, $logo_new_height); 
//		imagealphablending($logo_new,false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;            
//		imagesavealpha($logo_new,true);//这里很重要,意思是不要丢了$thumb图像的透明色; 
		
           	
		//imagecolorallocatealpha($logoHandle, 0,0,0,127);
		//imagecopyresized($waterimg,$waterimgj,0,0,0,0,$old_w,$old_h,$water_w,$water_h);
		//imagealphablending($logoHandle, true);
		//imagecopyresized($logo_new, $logoHandle, 0, 0, 0, 0, $logo_new_width, $logo_new_height, $logo_width,$logo_height);
		//imagecopyresampled($logo_new, $logoHandle, 0, 0, 0, 0, $logo_new_width, $logo_new_height, $logo_width,$logo_height);
		
		//if(function_exists("ImageCopyResampled")){
		//		ImageCopyResampled($logo_new, $logoHandle, 0, 0, 0, 0, $logo_new_width, $logo_new_height, $logo_width,$logo_height); 
		//} else{
		//	ImageCopyResized($logo_new, $logoHandle, 0, 0, 0, 0, $logo_new_width, $logo_new_height, $logo_width,$logo_height);
		//} 
		//改变背景图片大小
		$source_w_change=$logo_width;
		$source_h_change=$source_w_change/$this->source_width*$this->source_height;
		if($this->source_type_id!='gif' && function_exists('imagecreatetruecolor')){
			$sourceHandle_change = imagecreatetruecolor($source_w_change, $source_h_change); 
		} else{
			$sourceHandle_change = imagecreate($source_w_change, $source_h_change); 
		}
		// 复制图片
		if(function_exists("ImageCopyResampled")){
				ImageCopyResampled($sourceHandle_change, $sourceHandle, 0, 0, 0, 0, $source_w_change, $source_h_change, $this->source_width,$this->source_height); 
		} else{
			ImageCopyResized($sourceHandle_change, $sourceHandle, 0, 0, 0, 0, $source_w_change, $source_h_change, $this->source_width,$this->source_height);
		} 

        //ImageCopy($sourceHandle,$logoHandle,$x,$y,0,0,$logo_width,$logo_width) or die("fail to combine");
        //ImageCopy($sourceHandle,$logoHandle,0,0,0,0,$this->source_width,$this->source_height,50) or die("fail to combine");
		imagealphablending($sourceHandle, true); 
		//imagecopymerge($sourceHandle,$logoHandle,0,0,0,0,$this->source_width,$this->source_height,30);//将水印图100%覆盖原图
		imagecopymerge($sourceHandle_change,$logoHandle,0,0,0,0,$source_w_change,$source_h_change,30);//将水印图100%覆盖原图
		
		//将图片改回原来图片大小
		if(function_exists("ImageCopyResampled")){
				ImageCopyResampled($sourceHandle, $sourceHandle_change, 0, 0, 0, 0, $this->source_width, $this->source_height, $source_w_change,$source_h_change); 
		} else{
			ImageCopyResized($sourceHandle, $sourceHandle_change, 0, 0, 0, 0, $this->source_width, $this->source_height, $source_w_change,$source_h_change);
		} 
		//将图片改为标准宽900像素大小
//		if($this->source_width>908){
//			$bz_w=908;
//			$bz_h=$bz_w/$this->source_width*$this->source_height;
//		}else{
//			$bz_w=$this->source_width;
//			$bz_h=$this->source_height;
//		}
//		
//		if(function_exists("ImageCopyResampled")){
//				ImageCopyResampled($sourceHandle, $sourceHandle_change, 0, 0, 0, 0, $bz_w, $bz_h, $source_w_change,$source_h_change); 
//		} else{
//			ImageCopyResized($sourceHandle, $sourceHandle_change, 0, 0, 0, 0, $bz_w, $bz_h, $source_w_change,$source_h_change);
//		} 
				
        $newPic = $this->orign_dirname .'\water_'. time().'.'. $this->typeList[$this->source_type_id];
        $newPic = $this->orign_url;
        if( $this->saveImage($sourceHandle,$newPic)){
            imagedestroy($sourceHandle);
            imagedestroy($sourceHandle_change);
            imagedestroy($logoHandle);
        }
    }
 
    // fix 宽度
    // height = true 固顶高度
    // width  = true 固顶宽度
    public function fixSizeImage($width,$height){
        if( $width > $this->source_width) $this->source_width;
        if( $height > $this->source_height ) $this->source_height;
        if( $width === false){
            $width = floor($this->source_width / ($this->source_height / $height));
        }
        if( $height === false){
            $height = floor($this->source_height / ($this->source_width / $width));
        }
        $this->tinyImage($width,$height);
    }
 
    //比例缩放
    // $scale 缩放比例
    public function scaleImage($scale){
        $width  = floor($this->source_width * $scale);
        $height = floor($this->source_height * $scale);
        $this->tinyImage($width,$height);
    }
 
    //创建略缩图
    private function tinyImage($width,$height){
        $tinyImage = imagecreatetruecolor($width, $height );
        $handle    = $this->judgeType($this->source_type_id,$this->orign_url);
        if(function_exists('imagecopyresampled')){
            imagecopyresampled($tinyImage,$handle,0,0,0,0,$width,$height,$this->source_width,$this->source_height);
        }else{
            imagecopyresized($tinyImage,$handle,0,0,0,0,$width,$height,$this->source_width,$this->source_height);
        }
 
        $newPic = time().'_'.$width.'_'.$height.'.'. $this->typeList[$this->source_type_id];
        $newPic = $this->orign_dirname .'\thumb_'. $newPic;
        if( $this->saveImage($tinyImage,$newPic)){
            imagedestroy($tinyImage);
            imagedestroy($handle);
        }
    }
 
    //保存图片
    private function saveImage($image,$url){
        if(ImageJpeg($image,$url)){
            return true;
        }
    }
}