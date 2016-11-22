<?php
 
class RollAction extends AllAction{

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
	
	//编号记录
	function active_roll_record(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$tid=t(h($_GET['tid']));
		$list=M('active_roll_record')->where("termId=".$tid."")->order("id desc")->select();
		foreach($list as $k=>$v){
			$list[$k]['user']=M("users")->where("userId=".$v['userId']."")->find();
		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	//活动列表
	function active_roll_term(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('active_roll_term')->order("id desc")->select();
		$this->assign('list', $list);
		
		$this->display();
	} 
	function active_roll_term_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		//图片上传 - 活动背景图
		$folder="/data/roll/bg/";
		$typess = array('.gif','.jpg','.png');
		if(!empty($_FILES['background']['name'])){
			$picname = $_FILES['background']['name'];
			$picsize = $_FILES['background']['size'];
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
		
				move_uploaded_file($_FILES['background']['tmp_name'], $pic_path);
			}
			
			$add['background']=$folder.$pics;
				
		}
		//图片上传 - 奖品1
		$folder_jp1="/data/roll/jp1/";
		$typess_jp1 = array('.gif','.jpg','.png');
		if(!empty($_FILES['prizePic1']['name'])){
			$picname_jp1 = $_FILES['prizePic1']['name'];
			$picsize_jp1 = $_FILES['prizePic1']['size'];
			if ($picname_jp1 != "") {
				if ($picsize_jp1 > 1024000) {
					echo '图片大小不能超过	1MB';
					exit;
				}
				$type_jp1 = strstr($picname_jp1, '.');
				if (!in_array($type_jp1,$typess_jp1)) {
					echo '图片格式不对！';
					exit;
				}
				$rand_jp1 = rand(1000, 9999);
				$pics_jp1 = date("YmdHis",time()) . "_".$rand_jp1 . $type_jp1;
				//上传路径
				$targetPath_jp1 = $_SERVER['DOCUMENT_ROOT'] . $folder_jp1;
				$pic_path_jp1 =  str_replace('//','/',$targetPath_jp1) . $pics_jp1;
				//mkdir(str_replace('//','/',$targetPath), 0755, true);
				if(!is_dir(str_replace('//','/',$targetPath_jp1))){
					mkdir(str_replace('//','/',$targetPath_jp1), 0755, true); //创建 目录
				}
		
				move_uploaded_file($_FILES['prizePic1']['tmp_name'], $pic_path_jp1);
			}
			
			$add['prizePic1']=$folder_jp1.$pics_jp1;
				
		}
		//图片上传 - 奖品2
		$folder_jp2="/data/roll/jp2/";
		$typess_jp2 = array('.gif','.jpg','.png');
		if(!empty($_FILES['prizePic2']['name'])){
			$picname_jp2 = $_FILES['prizePic2']['name'];
			$picsize_jp2 = $_FILES['prizePic2']['size'];
			if ($picname_jp2 != "") {
				if ($picsize_jp2 > 1024000) {
					echo '图片大小不能超过	1MB';
					exit;
				}
				$type_jp2 = strstr($picname_jp2, '.');
				if (!in_array($type_jp2,$typess_jp2)) {
					echo '图片格式不对！';
					exit;
				}
				$rand_jp2 = rand(1000, 9999);
				$pics_jp2 = date("YmdHis",time()) . "_".$rand_jp2 . $type_jp2;
				//上传路径
				$targetPath_jp2 = $_SERVER['DOCUMENT_ROOT'] . $folder_jp2;
				$pic_path_jp2 =  str_replace('//','/',$targetPath_jp2) . $pics_jp2;
				//mkdir(str_replace('//','/',$targetPath), 0755, true);
				if(!is_dir(str_replace('//','/',$targetPath_jp2))){
					mkdir(str_replace('//','/',$targetPath_jp2), 0755, true); //创建 目录
				}
		
				move_uploaded_file($_FILES['prizePic2']['tmp_name'], $pic_path_jp2);
			}
			
			$add['prizePic2']=$folder_jp2.$pics_jp2;
				
		}
		//图片上传 - 奖品3
		$folder_jp3="/data/roll/jp3/";
		$typess_jp3 = array('.gif','.jpg','.png');
		if(!empty($_FILES['prizePic3']['name'])){
			$picname_jp3 = $_FILES['prizePic3']['name'];
			$picsize_jp3 = $_FILES['prizePic3']['size'];
			if ($picname_jp3 != "") {
				if ($picsize_jp3 > 1024000) {
					echo '图片大小不能超过	1MB';
					exit;
				}
				$type_jp3 = strstr($picname_jp3, '.');
				if (!in_array($type_jp3,$typess_jp3)) {
					echo '图片格式不对！';
					exit;
				}
				$rand_jp3 = rand(1000, 9999);
				$pics_jp3 = date("YmdHis",time()) . "_".$rand_jp3 . $type_jp3;
				//上传路径
				$targetPath_jp3 = $_SERVER['DOCUMENT_ROOT'] . $folder_jp3;
				$pic_path_jp3 =  str_replace('//','/',$targetPath_jp3) . $pics_jp3;
				//mkdir(str_replace('//','/',$targetPath), 0755, true);
				if(!is_dir(str_replace('//','/',$targetPath_jp3))){
					mkdir(str_replace('//','/',$targetPath_jp3), 0755, true); //创建 目录
				}
		
				move_uploaded_file($_FILES['prizePic3']['tmp_name'], $pic_path_jp3);
			}
			
			$add['prizePic3']=$folder_jp3.$pics_jp3;
				
		}
		//图片上传 - 奖品1 用户头像
		$folder_tx1="/data/roll/tx1/";
		$typess_tx1 = array('.gif','.txg','.png');
		if(!empty($_FILES['userHeader1']['name'])){
			$picname_tx1 = $_FILES['userHeader1']['name'];
			$picsize_tx1 = $_FILES['userHeader1']['size'];
			if ($picname_tx1 != "") {
				if ($picsize_tx1 > 1024000) {
					echo '图片大小不能超过	1MB';
					exit;
				}
				$type_tx1 = strstr($picname_tx1, '.');
				if (!in_array($type_tx1,$typess_tx1)) {
					echo '图片格式不对！';
					exit;
				}
				$rand_tx1 = rand(1000, 9999);
				$pics_tx1 = date("YmdHis",time()) . "_".$rand_tx1 . $type_tx1;
				//上传路径
				$targetPath_tx1 = $_SERVER['DOCUMENT_ROOT'] . $folder_tx1;
				$pic_path_tx1 =  str_replace('//','/',$targetPath_tx1) . $pics_tx1;
				//mkdir(str_replace('//','/',$targetPath), 0755, true);
				if(!is_dir(str_replace('//','/',$targetPath_tx1))){
					mkdir(str_replace('//','/',$targetPath_tx1), 0755, true); //创建 目录
				}
		
				move_uploaded_file($_FILES['userHeader1']['tmp_name'], $pic_path_tx1);
			}
			
			$add['userHeader1']=$folder_tx1.$pics_tx1;
				
		}
		//图片上传 - 奖品2 用户头像
		$folder_tx2="/data/roll/tx2/";
		$typess_tx2 = array('.gif','.txg','.png');
		if(!empty($_FILES['userHeader2']['name'])){
			$picname_tx2 = $_FILES['userHeader2']['name'];
			$picsize_tx2 = $_FILES['userHeader2']['size'];
			if ($picname_tx2 != "") {
				if ($picsize_tx2 > 1024000) {
					echo '图片大小不能超过	1MB';
					exit;
				}
				$type_tx2 = strstr($picname_tx2, '.');
				if (!in_array($type_tx2,$typess_tx2)) {
					echo '图片格式不对！';
					exit;
				}
				$rand_tx2 = rand(1000, 9999);
				$pics_tx2 = date("YmdHis",time()) . "_".$rand_tx2 . $type_tx2;
				//上传路径
				$targetPath_tx2 = $_SERVER['DOCUMENT_ROOT'] . $folder_tx2;
				$pic_path_tx2 =  str_replace('//','/',$targetPath_tx2) . $pics_tx2;
				//mkdir(str_replace('//','/',$targetPath), 0755, true);
				if(!is_dir(str_replace('//','/',$targetPath_tx2))){
					mkdir(str_replace('//','/',$targetPath_tx2), 0755, true); //创建 目录
				}
		
				move_uploaded_file($_FILES['userHeader2']['tmp_name'], $pic_path_tx2);
			}
			
			$add['userHeader2']=$folder_tx2.$pics_tx2;
				
		}
		//图片上传 - 奖品3 用户头像
		$folder_tx3="/data/roll/tx3/";
		$typess_tx3 = array('.gif','.txg','.png');
		if(!empty($_FILES['userHeader3']['name'])){
			$picname_tx3 = $_FILES['userHeader3']['name'];
			$picsize_tx3 = $_FILES['userHeader3']['size'];
			if ($picname_tx3 != "") {
				if ($picsize_tx3 > 1024000) {
					echo '图片大小不能超过	1MB';
					exit;
				}
				$type_tx3 = strstr($picname_tx3, '.');
				if (!in_array($type_tx3,$typess_tx3)) {
					echo '图片格式不对！';
					exit;
				}
				$rand_tx3 = rand(1000, 9999);
				$pics_tx3 = date("YmdHis",time()) . "_".$rand_tx3 . $type_tx3;
				//上传路径
				$targetPath_tx3 = $_SERVER['DOCUMENT_ROOT'] . $folder_tx3;
				$pic_path_tx3 =  str_replace('//','/',$targetPath_tx3) . $pics_tx3;
				//mkdir(str_replace('//','/',$targetPath), 0755, true);
				if(!is_dir(str_replace('//','/',$targetPath_tx3))){
					mkdir(str_replace('//','/',$targetPath_tx3), 0755, true); //创建 目录
				}
		
				move_uploaded_file($_FILES['userHeader3']['tmp_name'], $pic_path_tx3);
			}
			
			$add['userHeader3']=$folder_tx3.$pics_tx3;
				
		}
		
		if(empty($id)){
			$add['state']=1;
			$add['gid']=t(h($_POST['gid']));
			$add['name']=t(h($_POST['name']));
			$add['endTime']=strtotime(t(h($_POST['endTime'])).":00");
			$add['prizeName1']=t(h($_POST['prizeName1']));
			$add['prizeName2']=t(h($_POST['prizeName2']));
			$add['prizeName3']=t(h($_POST['prizeName3']));
			$add['userName1']=t(h($_POST['userName1']));
			$add['userName2']=t(h($_POST['userName2']));
			$add['userName3']=t(h($_POST['userName3']));
			$add['userRecordid1']=t(h($_POST['userRecordid1']));
			$add['userRecordid2']=t(h($_POST['userRecordid2']));
			$add['userRecordid3']=t(h($_POST['userRecordid3']));

			$res=M("active_roll_term")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['gid']=t(h($_POST['gid']));
			$add['name']=t(h($_POST['name']));
			$add['endTime']=strtotime(t(h($_POST['endTime'])).":00");
			$add['prizeName1']=t(h($_POST['prizeName1']));
			$add['prizeName2']=t(h($_POST['prizeName2']));
			$add['prizeName3']=t(h($_POST['prizeName3']));
			$add['userName1']=t(h($_POST['userName1']));
			$add['userName2']=t(h($_POST['userName2']));
			$add['userName3']=t(h($_POST['userName3']));
			$add['userRecordid1']=t(h($_POST['userRecordid1']));
			$add['userRecordid2']=t(h($_POST['userRecordid2']));
			$add['userRecordid3']=t(h($_POST['userRecordid3']));

			$res=M("active_roll_term")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	 
	
} 
