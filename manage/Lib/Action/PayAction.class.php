<?php
 
class PayAction extends AllAction{

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
	

	 //充值记录
	function pay(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		
		$list=M('user_golds_record')->where("fromid=4")->order("ctime desc,user_golds_recordId desc")->select();
		foreach($list as $k=>$v){
			$user=M("users")->where("userId=".$v['userId']."")->find();
			$list[$k]['user']=$user;
			
		}
		$this->assign('list', $list);
		$this->display();
	} 
	  
	 
	
} 
