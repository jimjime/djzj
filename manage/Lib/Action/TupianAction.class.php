<?php
 
class TupianAction extends AllAction{

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
	
	//图片分类
	function game_type(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('game_type')->order("displayOrder asc,game_typeId asc")->select();
		$this->assign('list', $list);
		
		$this->display();
	} 
	function game_type_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['cnName']=t(h($_POST['cnName']));
			$add['enName']=t(h($_POST['enName']));
			$add['ctime']=time();
			$add['state']=1;

			$res=M("game_type")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['game_typeId']=$id;
			$add['cnName']=t(h($_POST['cnName']));
			$add['enName']=t(h($_POST['enName']));
			$add['stime']=time();
			$add['state']=1;
			$res=M("game_type")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	 //图片列表
	function tupian(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$fenleis=M("game_type")->where("state=1")->select();
		$this->assign('fenleis', $fenleis);
		$list=M('teams')->order("teamId asc")->select();
		foreach($list as $k=>$v){
			$gname=M("game_type")->where("game_typeId=".$v['gid']."")->find();
			$list[$k]['gname']=$gname['cnName'];
		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	function tupian_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		

		$folder="/data/teamavatar/";
		$typess = array('.gif','.jpg','.png');
		if(!empty($_FILES['img']['name'])){
			$picname = $_FILES['img']['name'];
			$picsize = $_FILES['img']['size'];
			if ($picname != "") {
				if ($picsize > 512000) {
					echo '图片大小不能超过500KB';
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
		
				move_uploaded_file($_FILES['img']['tmp_name'], $pic_path);
			}
			
			$add['avatar']=$folder.$pics;
				
		}
		
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['gid']=t(h($_POST['gid']));
			$add['ctime']=time();
			$add['state']=1;

			$res=M("teams")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['teamId']=$id;
			$add['name']=t(h($_POST['name']));
			$add['gid']=t(h($_POST['gid']));
			$add['stime']=time();
			$add['state']=1;
			$res=M("teams")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	//平台logo列表
	function ptlogo(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('ptlogo')->order("id asc")->select();
		$this->assign('list', $list);
		
		$this->display();
	} 
	function ptlogo_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		

		$folder="/data/ptlogoavatar/";
		$typess = array('.gif','.jpg','.png');
		if(!empty($_FILES['img']['name'])){
			$picname = $_FILES['img']['name'];
			$picsize = $_FILES['img']['size'];
			if ($picname != "") {
				if ($picsize > 512000) {
					echo '图片大小不能超过500KB';
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
		
				move_uploaded_file($_FILES['img']['tmp_name'], $pic_path);
			}
			
			$add['avatar']=$folder.$pics;
				
		}
		
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['ctime']=time();
			$add['state']=1;

			$res=M("ptlogo")->add($add);
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
			$res=M("ptlogo")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	  
	function tupian_changetime(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('teams')->where("state=0")->select();
		foreach($list as $k=>$v){
			$changetime['state']=1;
			$changetime['teamId']=$v['teamId'];
			$save=M("teams")->save($changetime);
		}
		$list2=M('teams')->where("name like '%dota%' ")->select();
		foreach($list2 as $k=>$v){
			$changetime['gid']=2;
			$changetime['teamId']=$v['teamId'];
			$save=M("teams")->save($changetime);
		}
		$list3=M('teams')->where("name not like '%dota%' ")->select();
		foreach($list3 as $k=>$v){
			$changetime['gid']=1;
			$changetime['teamId']=$v['teamId'];
			$save=M("teams")->save($changetime);
		}
		echo 1;
		
		
	} 
	 
	
} 
