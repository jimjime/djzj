<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 登陆 注册
 */
 
class UserAction extends AllAction{

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
	function pan_quan($data){ //php跳转判断
		$pan=in_array($data,$_SESSION['admin_quan']);
        if(!$pan){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
	}
	

    /**
     +----------------------------------------------------------
     * 登录
     +----------------------------------------------------------
    */
    public function login(){
		if($_SESSION['manage_id']){
			$this->assign('jumpUrl',U('Index/shouye'));
			$this->error("已登录！");
		}else{
			$this->assign('pagetitle', '欢迎登录');
			$this->display();
		}
    }
    

    /*验证码	---index/login*/
    public function vdcode(){
    	$type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("ORG.Util.Image");
        Image::buildImageVerify(5,1,$type);
    }
    
    public function checklogin(){
		
		$yonghuming=t(h($_POST['yonghuming']));
		$mima=t(h($_POST['mima']));

    	if(''==$yonghuming){
    		$this->error('用户名为空！');
    	}elseif (''==$mima){
    		$this->error('密码为空！');
    	}
		elseif (!M()->autoCheckToken($_POST)){
    		$this->error('禁止外部提交！');
    	}
		else {
    		
    		$res = M('admin')->where("adm_name='".$yonghuming."'")->find();
    		if($res){
    			
    			if( $res['pwd'] == md5($mima)){
					if($res['state']==1){
						
						$_SESSION['manage_id']=$res['id'];
						$_SESSION['manage_name']=$res['adm_name'];
						$_SESSION['gid']=$res['gid'];
						
						$admin_log['ip']=$this->getIP();
						$admin_log['ctime']=time();
						$admin_log['adminid']=$_SESSION['manage_id'];
						$admin_log['state']=1;
						
						M('admin_log')->add($admin_log);//保存登录记录
						
						//$this->assign('waitSecond', 0);
						$this->assign('jumpUrl', U('Index/shouye'));
						$this->success("{$_SESSION['manage_name']}登录成功！");

					}else{
						if($res['state']==0){
							$this->error("此用户尚未激活！");
						}
						if($res['state']==3){
							$this->error("此用户已删除！");
						}
					}
    			}else {
//     				echo $res['passwd']; die;
    				$this->error("密码错误！");
    			}
    			
    		}else {
    			$this->error("无此用户！");
    		}
    	}
    }
    public function logout(){
    	import("ORG.Util.Session");
		$s = new Session();
		$s->destroy();
		
		$this->assign('jumpUrl', U('User/login'));
		$this->success("已退出！");
    }
    
	function getIP() /*获取客户端IP*/  
	{  
		if (@$_SERVER["HTTP_X_FORWARDED_FOR"])  
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
		else if (@$_SERVER["HTTP_CLIENT_IP"])  
		$ip = $_SERVER["HTTP_CLIENT_IP"];  
		else if (@$_SERVER["REMOTE_ADDR"])  
		$ip = $_SERVER["REMOTE_ADDR"];  
		else if (@getenv("HTTP_X_FORWARDED_FOR"))  
		$ip = getenv("HTTP_X_FORWARDED_FOR");  
		else if (@getenv("HTTP_CLIENT_IP"))  
		$ip = getenv("HTTP_CLIENT_IP");  
		else if (@getenv("REMOTE_ADDR"))  
		$ip = getenv("REMOTE_ADDR");  
		else  
		$ip = "Unknown";  
		return $ip;  
	}    

	//通用删除、恢复
	function admin_del(){
		$id=t(h($_POST['id']));
	    $sql_m=t(h($_POST['sql_m']));
		$idname=t(h($_POST['idname']));
		$del = M("".$sql_m."")->where("".$idname." IN ($id)")->setField('state','2');//2为已删除
        echo $del?'cg':'sb';
	}
	function admin_cddel(){
		if(!$this->pan_quan_ajax(1)){
			echo 'wqx';exit;
		}
		$id=t(h($_POST['id']));
	    $sql_m=t(h($_POST['sql_m']));
		$idname=t(h($_POST['idname']));
		$del = M("".$sql_m."")->where("".$idname." IN ($id)")->delete();//彻底删除
        echo $del?'cg':'sb';
	}
	function admin_hui(){
		$id=t(h($_POST['id']));
	    $sql_m=t(h($_POST['sql_m']));
		$idname=t(h($_POST['idname']));
		$del = M("".$sql_m."")->where("".$idname." IN ($id)")->setField('state','1');//1为正常
        echo $del?'cg':'sb';
	}
	 //管理员列表
	function admins(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$groups=M('admin_group')->where("state=1")->order("id asc")->select();
		$this->assign('groups', $groups);
		$list=M('admin')->order("id desc")->select();
		foreach($list as $k=>$v){
			$groupone=M('admin_group')->find($v['gid']);
			$list[$k]['gname']=$groupone['name'];
			$ltime=M("admin_log")->where("adminid=".$v['id']." and state=1")->order("ctime desc")->find();
			$list[$k]['ltime']=$ltime['ctime'];
		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	function admins_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['adm_name']=t(h($_POST['adm_name']));
			$add['pwd']=md5(t(h($_POST['pwd'])));
			$add['gid']=t(h($_POST['gid']));
			$add['ctime']=time();
			$add['state']=1;
			if(empty($add['pwd']) || empty($add['adm_name']) || $add['gid']<=0){
				$this->error('请输入密码、用户名、分组');
			}
			$res=M("admin")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['adm_name']=t(h($_POST['adm_name']));
			/*
			if(empty(t(h($_POST['pwd'])))){
				
			}else{
				$add['pwd']=md5(t(h($_POST['pwd'])));
			}*/
			$add['pwd']=md5(t(h($_POST['pwd'])));
			$add['gid']=t(h($_POST['gid']));
			$add['stime']=time();
			$add['state']=1;
			if(empty($add['adm_name']) || $add['gid']<=0){
				$this->error('请输入用户名、分组');
			}
			$res=M("admin")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	//管理员分组
	function admin_group(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$quanxian=M('admin_quan')->where("state=1")->order("id desc")->select();
		$this->assign('quanxian', $quanxian);
		$list=M('admin_group')->order("id desc")->select();
		foreach($list as $k=>$v){
			$list[$k]['qxarray']=explode(',',$v['quanxian']);

		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	function admin_group_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['quanxian']=implode(',',$_POST['quanxian']);
			$add['ctime']=time();
			$add['state']=1;
			if(empty($add['name'])){
				$this->error('请输入名称');
			}
			$res=M("admin_group")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['name']=t(h($_POST['name']));
			$add['quanxian']=implode(',',$_POST['quanxian']);
			$add['stime']=time();
			$add['state']=1;
			if(empty($add['name'])){
				$this->error('请输入名称');
			}
			$res=M("admin_group")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	//权限列表
	function admin_quan(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('admin_quan')->order("id desc")->select();
		$this->assign('list', $list);
		
		$this->display();
	} 
	function admin_quan_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['ctime']=time();
			$add['state']=1;
			if(empty($add['name'])){
				$this->error('请输入名称');
			}
			$res=M("admin_quan")->add($add);
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
			if(empty($add['name'])){
				$this->error('请输入名称');
			}
			$res=M("admin_quan")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 

	function upload_img_php(){
	
		if(''==$_POST['name']){
    		$this->error('请填写姓名！');
    	}elseif (''==$_POST['yiming']){
    		$this->error('请填写艺名！');
    	}elseif (''==$_POST['sex']){
    		$this->error('请选择性别！');
    	}elseif (''==$_POST['year'] || ''==$_POST['month'] || ''==$_POST['day']){
    		$this->error('请选择出生日期！');
    	}elseif (''==$_POST['minzu']){
    		$this->error('请填写民族！');
    	}elseif (''==$_POST['shengao']){
    		$this->error('请填写身高！');
    	}elseif (''==$_POST['tizhong']){
    		$this->error('请填写体重！');
    	}elseif (''==$_POST['xingzuo']){
    		$this->error('请选择星座！');
    	}elseif (''==$_POST['yuyan']){
    		$this->error('请填写语言！');
    	}elseif (''==$_POST['home']){
    		$this->error('请填写籍贯！');
    	}elseif (''==$_POST['area']){
    		$this->error('请填写现居住地！');
    	}elseif (''==$_POST['email']){
    		$this->error('请填写邮箱！');
    	}elseif (''==$_POST['tel']){
    		$this->error('请填写手机号！');
    	}elseif (''==$_POST['xingqu']){
    		$this->error('请填写特长爱好！');
    	}elseif (''==$_POST['jianjie']){
    		$this->error('请填写主播宣言！');
    	}else {
			
			if(empty($_COOKIE['loginuserid'])){
				$this->error('请先登录！');
			}
			$uid=$_COOKIE['loginuserid'];
    		
			$user_show['id']=$uid;
			$user_show['name']=t(h($_POST['name']));
			$user_show['email']=t(h($_POST['email']));
			$user_show['tel']=t(h($_POST['tel']));
			
			$user_show_info['uid']=$uid;
			$user_show_info['yiming']=t(h($_POST['yiming']));
			$user_show_info['sex']=t(h($_POST['sex']));
			$user_show_info['year']=t(h($_POST['year']));
			$user_show_info['month']=t(h($_POST['month']));
			$user_show_info['day']=t(h($_POST['day']));
			$user_show_info['minzu']=t(h($_POST['minzu']));
			$user_show_info['shengao']=t(h($_POST['shengao']));
			$user_show_info['tizhong']=t(h($_POST['tizhong']));
			$user_show_info['xingzuo']=t(h($_POST['xingzuo']));
			$user_show_info['yuyan']=t(h($_POST['yuyan']));
			$user_show_info['home']=t(h($_POST['home']));
			$user_show_info['area']=t(h($_POST['area']));
			$user_show_info['xingqu']=t(h($_POST['xingqu']));
			$user_show_info['jianjie']=t(h($_POST['jianjie']));
			$user_show_info['peixun']=t(h($_POST['peixun']));
			$user_show_info['guhua']=t(h($_POST['guhua']));
			$user_show_info['ctime']=time();
			$user_show_info['state']=1;
			
			M("user")->save($user_show);
			
			$user_show_info_jiu=M("user_info")->where("uid=".$uid."")->find();
			if(empty($user_show_info_jiu)){
				M("user_info")->add($user_show_info);
			}else{
				$user_show_info['id']=$user_show_info_jiu['id'];
				M("user_info")->save($user_show_info);
			}

			$folder="/data/baomingxiezhen/";
			for ($i=0;$i<count($_FILES);$i++) 
			{ 
				if(!empty($_FILES[$i]['name'])){
					$picname = $_FILES[$i]['name'];
					$picsize = $_FILES[$i]['size'];
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
				
						move_uploaded_file($_FILES[$i]['tmp_name'], $pic_path);
					}
					$size = round($picsize/2048,2);
					$arr[$i] = array(
						'name'=>$picname,
						'pic'=>$pics,
						'pic_path'=>$folder.$pics,
						'size'=>$size
					);
					
					$photo['name']="写真照".($i+1);
					$photo['url']=$folder.$pics;
					$photo['state']=1;
					$photo['ctime']=time();
					$photo['suid']=$uid;
					$photo['uid']=$uid;
						
					$photo_file=M("article_photo_files")->where("suid=".$uid." and state=1")->find();
					if(!empty($photo_file)){
						$photo['fid']=$photo_file['id'];
						M("article_photo")->add($photo);
					}else{
						$new_photo_file['uid']=$uid;
						$new_photo_file['suid']=$uid;
						$new_photo_file['state']=1;
						$new_photo_file['ctime']=time();
						$new_photo_file['name']="写真集";
						$new_photo_file['url']=$folder.$pics;
						$new_files=M("article_photo_files")->add($new_photo_file);
						
						$photo['fid']=$new_files;
						M("article_photo")->add($photo);
					}
				}
			} 
			$photo_file_jiu=M("article_photo_files")->where("suid=".$uid." and state=1")->find();
			$photo_jiu=M("article_photo")->where("suid=".$uid." and fid=".$photo_file_jiu['id']." and state=1")->select();
			if(count($arr)<2 && count($photo_jiu)==0){
				$this->error('照片最少两张！');
			}
			if(count($arr)<1 && count($photo_jiu)==1){
				$this->error('照片最少两张！');
			}

			$this->success('报名成功');
		}
	}  
		 
	
} 
