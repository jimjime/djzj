<?php
 
class ArtAction extends AllAction{

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
	function sys_art(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('sys_art')->order("sys_artId asc")->select();
		foreach($list as $k=>$v){
			$list[$k]['gametype']=M("game_type")->where("game_typeId=".$v['gid']."")->find();
			$list[$k]['fenlei']=M("sys_art_fenlei")->where("id=".$v['fid']."")->find();
		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	function sys_art_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_GET['id']));
		$fenlei=M("sys_art_fenlei")->where("state=1")->select();
		$this->assign('fenlei', $fenlei);
		$fenleis=M("game_type")->where("state=1")->select();
		$this->assign('fenleis', $fenleis);
		$list=M('sys_art')->where("sys_artId=".$id."")->find();
		foreach($list as $k=>$v){
			if(get_magic_quotes_gpc()!='-1' || get_magic_quotes_gpc()==true){
				$list[$k]=stripslashes($v); 
			} 
		}
		$this->assign($list);
		$this->display();
	} 
	function sys_art_edit_do(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if($id<=0){
			$add['name']=t(h($_POST['name']));
			$add['gid']=t(h($_POST['gid']));
			$add['fid']=t(h($_POST['fid']));
			$add['content']=$_POST['content'];
			$add['ctime']=time();
			$add['state']=1;
			$add['adminid']=t(h($_SESSION['manage_id']));

			$res=M("sys_art")->add($add);
			if($res){
				$this->assign('jumpUrl', U('Art/sys_art'));
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['sys_artId']=$id;
			$add['name']=t(h($_POST['name']));
			$add['gid']=t(h($_POST['gid']));
			$add['fid']=t(h($_POST['fid']));
			$add['content']=$_POST['content'];
			$add['stime']=time();
			$add['state']=1;
			$add['adminid']=t(h($_SESSION['manage_id']));

			$res=M("sys_art")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	//文章分类列表
	function sys_art_fenlei(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('sys_art_fenlei')->order("id asc")->select();
		$this->assign('list', $list);
		
		$this->display();
	} 
	function sys_art_fenlei_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['ctime']=time();
			$add['state']=1;

			$res=M("sys_art_fenlei")->add($add);
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

			$res=M("sys_art_fenlei")->save($add);
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
