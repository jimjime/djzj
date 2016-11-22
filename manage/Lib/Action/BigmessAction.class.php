<?php
 
class BigmessAction extends AllAction{

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
	
	//活动列表
	function bigmess(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('big_mess')->order("id desc")->select();
		$this->assign('list', $list);
		
		$this->display();
	} 
	function bigmess_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		
		if(empty($id)){
			$add['name']=t(h($_POST['name']));

			$res=M("big_mess")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['name']=t(h($_POST['name']));

			$res=M("big_mess")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	 
	
} 
