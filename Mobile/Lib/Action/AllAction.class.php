<?php
class AllAction extends Action{
    function _initialize(){
		
	}

	//二维 数组 获取 各字段语言 优先级别
	function get_lang_attr($attr,$data){
		if($_COOKIE["langid"]>0){
			$lid=$_COOKIE["langid"];
		}else{
			$lid=1;
		}
		foreach($attr as $k=>$v){
			$attr[$k]["".$data.""]=$v["".$data.$lid.""];
			if( empty($attr[$k]["".$data.""]) ){
				$attr[$k]["".$data.""]=$v["".$data."3"];				
				if( empty($attr[$k]["".$data.""]) ){
					$attr[$k]["".$data.""]=$v["".$data."1"];				
					if( empty($attr[$k]["".$data.""]) ){
						$attr[$k]["".$data.""]=$v["".$data.""];				
					}
				}
			}
			if(get_magic_quotes_gpc()!='-1' || get_magic_quotes_gpc()==true){
				$attr[$k]["".$data.""]=stripslashes($attr[$k]["".$data.""]); 
			}
		}
		return $attr;
	}
	//一维 数组 获取 各字段语言 优先级别
	function get_lang($attr,$data){
		if($_COOKIE["langid"]>0){
			$lid=$_COOKIE["langid"];
		}else{
			$lid=1;
		}
		$beifen_attr_data=$attr["".$data.""];
		$attr["".$data.""]=$attr["".$data.$lid.""];
		if( empty($attr["".$data.""]) ){
			$attr["".$data.""]=$attr["".$data."3"];				
			if( empty($attr["".$data.""]) ){
				$attr["".$data.""]=$attr["".$data."1"];				
				if( empty($attr["".$data.""]) ){
					$attr["".$data.""]=$beifen_attr_data;				
				}
			}
		}
		foreach($attr as $k=>$v){
			if(get_magic_quotes_gpc()!='-1' || get_magic_quotes_gpc()==true){
				$attr[$k]=stripslashes($v); 
			}
		}
		return $attr;
	}
	//获取缩略图thumb
	function get_thumb($data){
		
		$thumb_array=explode("/",$data);
		$num=count($thumb_array)-1;
		
		$i=0;
		for($i;$i<$num;$i++){
			$thumb_str.=$thumb_array[$i]."/";
		}
		
		$thumb="./".$thumb_str."/thumb/".$thumb_array[$num]; //得到缩略图地址
		$thumb=str_replace('//','/',$thumb);
		//判断缩略图是否存在
		$pan=is_file($thumb);
		if($pan){
			return $thumb;
		}else{
			return $data;
		}
	}
	//获取缩略图thumb2
	function get_thumb2($data){
		
		$thumb_array=explode("/",$data);
		$num=count($thumb_array)-1;
		
		$i=0;
		for($i;$i<$num;$i++){
			$thumb_str.=$thumb_array[$i]."/";
		}
		
		$thumb="./".$thumb_str."/thumb2/".$thumb_array[$num]; //得到缩略图地址
		$thumb=str_replace('//','/',$thumb);
		//判断缩略图是否存在
		$pan=is_file($thumb);
		if($pan){
			return $thumb;
		}else{
			return $data;
		}
	}
	//获取缩略图thumb3
	function get_thumb3($data){
		
		$thumb_array=explode("/",$data);
		$num=count($thumb_array)-1;
		
		$i=0;
		for($i;$i<$num;$i++){
			$thumb_str.=$thumb_array[$i]."/";
		}
		
		$thumb="./".$thumb_str."/thumb3/".$thumb_array[$num]; //得到缩略图地址
		$thumb=str_replace('//','/',$thumb);
		//判断缩略图是否存在
		$pan=is_file($thumb);
		if($pan){
			return $thumb;
		}else{
			return $data;
		}
	}
	//获取当前网址对应的id
	function get_webid(){
		$wangzhi=$_SERVER['HTTP_HOST'];
		$webs=M('webs')->where("website='".$wangzhi."' and state=1")->find();
		return $webs['id'];
	}
	//获取当前网址对应的pz_menu_id
	function get_webmenuid(){
		$wangzhi=$_SERVER['HTTP_HOST'];
		$webs=M('webs')->where("website='".$wangzhi."' and state=1")->find();
		return $webs['menuid'];
	}
	
	
//	//二维 数组 获取 各字段
//	function get_lang_attr($attr,$data){
//		$lid=$_COOKIE["langid"];
//		foreach($attr as $k=>$v){
//			$attr[$k]["".$data.""]=$v["".$data.$lid.""];
//			if( empty($attr[$k]["".$data.""]) ){
//				$attr[$k]["".$data.""]=$v["".$data."3"];				
//				if( empty($attr[$k]["".$data.""]) ){
//					$attr[$k]["".$data.""]=$v["".$data."1"];				
//					if( empty($attr[$k]["".$data.""]) ){
//						$attr[$k]["".$data.""]=$v["".$data.""];				
//					}
//				}
//			}
//		}
//		return $attr;
//	}
//	//一维 数组 获取 各字段语言 优先级别
//	function get_lang($attr,$data){
//		$lid=$_COOKIE["langid"];
//		$attr["".$data.""]=$attr["".$data.$lid.""];
//		if( empty($attr["".$data.""]) ){
//			$attr["".$data.""]=$attr["".$data."3"];				
//			if( empty($attr["".$data.""]) ){
//				$attr["".$data.""]=$attr["".$data."1"];				
//				if( empty($attr["".$data.""]) ){
//					$attr["".$data.""]=$attr["".$data.""];				
//				}
//			}
//		}
//		return $attr;
//	}
}
?>