<?php
 
class NewsAction extends AllAction{

	/**
	 * ------------------------------
	 * 权限判断
	 * ------------------------------
	 */
	function pan_quan_ajax($data){  //ajax判断
		$gid=$_SESSION['gid'];
		$group=M("admin_group")->where("id=".$gid."")->find();
		$quanxian=explode(',',$group['quanxian']);
		$pan=in_array($data,$quanxian);
        if($pan){
			return true;
		}else{
			return false;
		}
	}

	//文章列表
	function getArticles(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('article')->order("ctime desc,id desc")->select();
		foreach($list as $k=>$v){
			$list[$k]['gametype']=M("game_type")->where("game_typeId=".$v['gid']."")->find();
			$list[$k]['fenlei']=M("article_class")->where("id=".$v['class']."")->find();
		}
	
		$this->assign('list', $list);
		
		$this->display();
	} 
	function getArticles_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_GET['id']));
		$fenlei=M("article_class")->where("state=1")->select();
		$this->assign('fenlei', $fenlei);
		$fenleis=M("game_type")->where("state=1")->select();
		$this->assign('fenleis', $fenleis);
		$list=M('article')->where("id =".$id."")->find();
		foreach($list as $k=>$v){
			if(get_magic_quotes_gpc()!='-1' || get_magic_quotes_gpc()==true){
				$list[$k]=stripslashes($v); 
			} 
		}
		$this->assign($list);
		$this->display();
	} 
	function getArticles_edit_do(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		$folder="/data/news/";
		$typess = array('.gif','.jpg','.png');
		if(!empty($_FILES['header']['name'])){
			$picname = $_FILES['header']['name'];
			$picsize = $_FILES['header']['size'];
			if ($picname != "") {
				if ($picsize > 1024000) {
					echo '图片大小不能超过	1MB';
					exit;
				}
				$type = strstr($picname, '.');
				if (!in_array($type,$typess)) {
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
		
				move_uploaded_file($_FILES['header']['tmp_name'], $pic_path);
			}
			
			$add['header']=$folder.$pics;
				

		}

		if($id<=0){
			$add['title']=t(h($_POST['title']));
			$add['class']=t(h($_POST['class']));
			$add['gid']=t(h($_POST['gid']));
			// $add['fid']=t(h($_POST['fid']));
			$add['content']=$_POST['content'];
			$add['ctime']=time();
			$add['state']=1;
			// $add['adminid']=t(h($_SESSION['manage_id']));
			$res=M("article")->add($add);
			if($res){
				$this->assign('jumpUrl', U('Art/sys_art'));
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{

		
			$add['id']=$id;
			$add['title']=t(h($_POST['title']));
			$add['class']=t(h($_POST['class']));
			$add['gid']=t(h($_POST['gid']));
			$add['content']=$_POST['content'];
			//$add['ctime']=time();
			$add['state']=1;
			// $add['adminid']=t(h($_SESSION['manage_id']));
			$res=M("article")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	//文章分类列表
	function getArticleClasses(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('article_class')->order("id asc")->select();

		$this->assign('list', $list);
		
		$this->display();
	} 
	function getArticleClasses_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['ctime']=time();
			$add['state']=1;

			$res=M("article_class")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['name']=t(h($_POST['name']));
			$add['stime']=time();
			$add['state']=1;

			$res=M("article_class")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
		$this->display();
	} 
	function users_changetime(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('sys_art')->where("ctime<=0")->order("sys_artId desc")->select();
		foreach($list as $k=>$v){
			$ctime=strtotime($v['create_time']);
			$changetime['ctime']=$ctime;
			$stime=strtotime($v['update_time']);
			$changetime['stime']=$stime;
			$changetime['sys_artId']=$v['sys_artId'];
			if($lasttime>0){
				$save=M("sys_art")->save($changetime);
			}
			var_dump($changetime['stime']);
		}
		
		
	} 
	 
	
} 
