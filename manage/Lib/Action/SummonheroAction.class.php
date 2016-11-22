<?php
 
class SummonheroAction extends AllAction{

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
	

	 //赔率
	function optionlist(){

		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$gid=t(h($_GET['gid']));

		$list=array();
		$res=M('summon_option')->where("`game_typeId`=".$gid." and `isDeleted`=0 ")->order("summonId desc")->select();
		foreach($res as $k=>$v){
			if($v['special']==1){
				$list['s']=$v;
			}
			if($v['level']==1){
				$list['1'][]=$v;
		}
			if($v['level']==2){
				$list['2'][]=$v;
			}
			if($v['level']==3){
				$list['3'][]=$v;
			}
		}
		echo "<pre/>";
		print_r($list);
		die;
		foreach($list as $k=>$v){

			$list[$k]['shuxing_arr']=explode(',',$v['num']);
			foreach($list[$k]['shuxing_arr'] as $kk=>$vv){
				$shuxing=M("roulette_property")->where("roulette_propertyId=".$vv."")->find();
				$shuxing_name_arr[$k][]=$shuxing['name'];
			}
			$list[$k]['shuxing_name']=implode(',',$shuxing_name_arr[$k]);
			
		}
		$this->assign('list', $list);
		$this->display();
	} 
	function peilv_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['peilv']=t(h($_POST['peilv']));
			$add['gid']=t(h($_POST['gid']));
			$add['num']=implode(',',$_POST['shuxing']);
			$add['adminid']=$_SESSION['manage_id'];
			$add['ctime']=time();
			$add['state']=1;
			if(empty($add['peilv'])){
				$this->error('请输入赔率');
			}
			$res=M("roulette_peilv")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['peilv']=t(h($_POST['peilv']));
			$add['gid']=t(h($_POST['gid']));
			$add['num']=implode(',',$_POST['shuxing']);
			$add['adminid']=$_SESSION['manage_id'];
			$add['stime']=time();
			$add['state']=1;
			if(empty($add['peilv'])){
				$this->error('请输入赔率');
			}
			$res=M("roulette_peilv")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}
	
	
	  
	 
	
} 
