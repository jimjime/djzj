<?php
 
class SaishiAction extends AllAction{

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

	//赛程日期
	function bs_day(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('bs_day')->order("day desc,id desc")->select();
		$this->assign('list', $list);

		$this->display();
	}
	function bs_day_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['day']=t(h($_POST['day']));

			$res=M("bs_day")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['day']=t(h($_POST['day']));
			$res=M("bs_day")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}
	//赛区管理
	function bs_xuexiao(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('bs_xuexiao')->order("xu asc,id asc")->select();
		$this->assign('list', $list);

		$this->display();
	}
	function bs_xuexiao_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['xu']=t(h($_POST['xu']));

			$res=M("bs_xuexiao")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['name']=t(h($_POST['name']));
			$add['xu']=t(h($_POST['xu']));
			$res=M("bs_xuexiao")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}

	//赛程列表
	function bs_saicheng(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$days=M("bs_day")->order("day desc,id desc")->select();
		$this->assign('days', $days);
		$xiangmus=M("bs_xiangmu")->order("xu asc,id desc")->select();
		$this->assign('xiangmus', $xiangmus);
		$qus=M("bs_xuexiao")->order("xu asc,id desc")->select();
		$this->assign('qus', $qus);
		$teams=M("bs_team")->order("id asc")->select();
		$this->assign('teams', $teams);
		$users=M("bs_userinfo")->order("id asc")->select();
		$this->assign('users', $users);
		$list=M('bs_saicheng')->order("day desc,id desc")->select();
		foreach($list as $k=>$v){
			$day=M("bs_day")->where("id=".$v['day']."")->find();
			$list[$k]['dayday']=$day['day'];
			$list[$k]['team1info']=M("bs_team")->where("id=".$v['team1']."")->find();
			$list[$k]['team2info']=M("bs_team")->where("id=".$v['team2']."")->find();
			$list[$k]['user1info']=M("bs_userinfo")->where("uid=".$v['user1']."")->find();
			$list[$k]['user2info']=M("bs_userinfo")->where("uid=".$v['user2']."")->find();
			$xiangmu=M("bs_xiangmu")->where("id=".$v['xiangmu']."")->find();
			$list[$k]['xiangmuname']=$xiangmu['name'];
			$xuexiao=M("bs_xuexiao")->where("id=".$v['qu']."")->find();
			$list[$k]['quname']=$xuexiao['name'];
		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	function bs_saicheng_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));

		if(empty($id)){
			$add['day']=t(h($_POST['day']));
			$add['shifen']=t(h($_POST['shifen']));
			$add['xiangmu']=t(h($_POST['xiangmu']));
			$add['qu']=t(h($_POST['qu']));
			$add['jibie']=t(h($_POST['jibie']));
			$add['team1']=t(h($_POST['team1']));
			$add['team2']=t(h($_POST['team2']));
			$add['user1']=t(h($_POST['user1']));
			$add['user2']=t(h($_POST['user2']));
			$add['didian']=t(h($_POST['didian']));
			$add['jieguo']=t(h($_POST['jieguo']));
			$add['xu']=t(h($_POST['xu']));

			$res=M("bs_saicheng")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['day']=t(h($_POST['day']));
			$add['shifen']=t(h($_POST['shifen']));
			$add['xiangmu']=t(h($_POST['xiangmu']));
			$add['qu']=t(h($_POST['qu']));
			$add['jibie']=t(h($_POST['jibie']));
			$add['team1']=t(h($_POST['team1']));
			$add['team2']=t(h($_POST['team2']));
			$add['user1']=t(h($_POST['user1']));
			$add['user2']=t(h($_POST['user2']));
			$add['didian']=t(h($_POST['didian']));
			$add['jieguo']=t(h($_POST['jieguo']));
			$add['xu']=t(h($_POST['xu']));
			$res=M("bs_saicheng")->save($add);
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

	//战队列表
	function bs_team(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$xiangmus=M("bs_xiangmu")->where("id<=2")->order("xu asc,id asc")->select();
		$this->assign('xiangmus', $xiangmus);
		$list=M('bs_team')->order("jiangjin desc,id asc")->select();
		foreach($list as $k=>$v){
			$xiangmuname=M("bs_xiangmu")->where("id=".$v['xiangmu']."")->find();
			$list[$k]['xiangmuname']=$xiangmuname['name'];
		}
		$this->assign('list', $list);

		$this->display();
	}
	function bs_team_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));


		$folder="/data/zhandui/";
		$typess = array('.gif','.jpg','.png');
		if(!empty($_FILES['img']['name'])){
			$picname = $_FILES['img']['name'];
			$picsize = $_FILES['img']['size'];
			if ($picname != "") {
				if ($picsize > 512000) {
					echo '图片大小不能超过200KB';
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

			$add['logo']=$folder.$pics;

		}

		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['xiangmu']=t(h($_POST['xiangmu']));

			$res=M("bs_team")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['name']=t(h($_POST['name']));
			$add['xiangmu']=t(h($_POST['xiangmu']));

			$res=M("bs_team")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}


} 
