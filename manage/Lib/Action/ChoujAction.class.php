<?php
 
class ChoujAction extends AllAction{

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
	

	 //抽奖记录
	function cjjl(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		
		$list=M('active_record')->order("lotteryTime desc,id desc")->select();
		foreach($list as $k=>$v){
			$user=M("users")->field("name")->where("userId=".$v['userId']."")->find();
			$list[$k]['username']=$user['name'];
			$active_prize=M("active_prize")->field("prize")->where("id=".$v['prizeId']."")->find();
			$list[$k]['prizename']=$active_prize['prize'];
			if($v['isuse']==0){
				$list[$k]['zt']="未申请兑换";
			}else if($v['isuse']==1){
				$list[$k]['zt']="申请兑换";
			}else{
				$list[$k]['zt']="已兑换";
			}
			
		}
		$this->assign('list', $list);
		$this->display();
	} 
	function dhjl(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$isuse=t(h($_GET['isuse']));//状态:0为未申请兑换，1为申请兑换，2为已兑换或已使用
		
		$list=M('active_record')->where("isuse=".$isuse."")->order("lotteryTime desc,id desc")->select();
		foreach($list as $k=>$v){
			$user=M("users")->field("name,tel,qq")->where("userId=".$v['userId']."")->find();
			$list[$k]['username']=$user['name'];
			$list[$k]['tel']=$user['tel'];
			$list[$k]['qq']=$user['qq'];
			$active_prize=M("active_prize")->field("prize")->where("id=".$v['prizeId']."")->find();
			$list[$k]['prizename']=$active_prize['prize'];
			if($v['isuse']==0){
				$list[$k]['zt']="未申请兑换";
			}else if($v['isuse']==1){
				$list[$k]['zt']="申请兑换";
			}else{
				$list[$k]['zt']="已兑换";
			}
			
		}
		$this->assign('list', $list);
		$this->display();
	} 
	function exchange_isuse(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_GET['id']));

		if(empty($id)){

		}else{
			
			$res=M("active_record")->where("id=".$id."")->setField('isuse',2);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}
	function exchange_nouse(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_GET['id']));

		if(empty($id)){

		}else{
			
			$res=M("active_record")->where("id=".$id."")->setField('isuse',0);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}
	
	
	  
	 
	
} 
