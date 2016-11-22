<?php
 
class ExchangeAction extends AllAction{

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
	

	 //兑换信息
	function exchange(){
		//echo 111;
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$txstate=t(h($_GET['txstate']));//提现状态:1为提现申请未处理，2为提现申请已处理
		
		$list=M('user_golds_record')->where("txstate=".$txstate."")->order("ctime desc,user_golds_recordId desc")->select();
		echo M('user_golds_record')->getLastSql();
		//var_dump($list);
		foreach($list as $k=>$v){
			$user=M("users")->where("userId=".$v['userId']."")->find();
			$list[$k]['user']=$user;
			//$list[$k]['ctime']=date('Y-m-d H:i:s',$v['ctime']);
		}
		//echo 222;
		//var_dump($list);
		$this->assign('list', $list);
		$this->display();
	} 
	function exchange_change(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_GET['id']));

		if(empty($id)){

		}else{
			$user_golds_record=M("user_golds_record")->where("user_golds_recordId=".$id."")->find();
			$changegolds=abs($user_golds_record['changeAmount']);
			if($changegolds==2400){
				$changermb=20;
			}else if($changegolds==5700){
				$changermb=50;
			}else if($changegolds==11000){
				$changermb=100;
			}
			
			$res=M("user_golds_record")->where("user_golds_recordId=".$id."")->setField('txstate',2);
			if($res){
				$this->sendTemplateConvert($changermb,$changegolds,$user_golds_record['userId'],$user_golds_record['user_golds_recordId']);
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}
	
	
	function yxcpeilvjisuan(){
		
		exit;
	}
	
	function yxcdojiesuan(){
//		$old_touzhu_log=M("user_golds_record")->where("jieortou=2 and herodayqi>0 and nowin=0")->order("ctime asc,user_golds_recordId asc")->select();
//		foreach($old_touzhu_log as $k=>$v){
//			$old_touzhu_win_log[$k]=M("user_golds_record")->where("jieortou=1 and herodayqi='".$v['herodayqi']."' and herocheck='".$v['herocheck']."' and nowin=0")->order("ctime asc,user_golds_recordId asc")->select();
//			foreach($old_touzhu_win_log[$k] as $kk=>$vv){
//				$old_touzhu_win_log[$k][$kk]['recordid']=$v['user_golds_recordId'];
//				M("user_golds_record")->save($old_touzhu_win_log[$k][$kk]);
//			}
//			
//		}



//		$old_rlog=M("roulette_log")->order("day asc,qi asc")->select();
//		foreach($old_rlog as $k=>$v){
//			$hero_info[$k]=M("roulette_hero_property")->where("roulette_heroId=".$v['heroid']."")->select();
//			foreach($hero_info[$k] as $kks=>$vvs){
//				$hero_win_pro[$k][]=$vvs['roulette_propertyId'];
//			}
//			$touzhu_log[$k]=M("user_golds_record")->where("jieortou=2 and gid=".$v['gid']." and herodayqi=".$v['day'].$v['qi']." and nowin=1")->order("ctime asc,user_golds_recordId asc")->select();
//			foreach($touzhu_log[$k] as $kk=>$vv){
//				$touzhu_check=explode(',',$vv['herocheck']);
//				$touzhu_check_count=count($touzhu_check);
//				$array_intersect=array_intersect($touzhu_check,$hero_win_pro[$k]);
//				$array_intersect_count=count($array_intersect);
////				if($array_intersect_count==$touzhu_check_count){
////					var_dump($array_intersect_count);
////				}
//				if($array_intersect_count==$touzhu_check_count){
//					$peilv=1;
//					foreach($array_intersect as $kkk=>$vvv){
//						$property_info=M("roulette_property")->where("roulette_propertyId=".$vvv."")->find();
//						$peilv=$peilv*$property_info['peilv'];
//					}
//					$user_info=M("users")->where("userId=".$vv['userId']."")->find();
//					
//					$win_golds['changeAmount']=abs($vv['changeAmount'])*$peilv;
//					$win_golds['amount']=$user_info['golds']+$win_golds['changeAmount'];
//					$win_golds['detail']="英雄猜 第 ".$v['day'].$v['qi']." 期 奖励";
//					$win_golds['ctime']=time();
//					$win_golds['userId']=$vv['userId'];
//					$win_golds['gid']=$vv['gid'];
//					$win_golds['recordid']=$vv['user_golds_recordId'];
//					$win_golds['jieortou']=1;
//					$win_golds['recomid']=$vv['recomid'];
//					$win_golds['herocheck']=$vv['herocheck'];
//					$win_golds['herodayqi']=$vv['herodayqi'];
//					$win_golds['fromid']=3;
//
//					$res=M("user_golds_record")->add($win_golds);
//					
//					$user_info["golds"]=$win_golds['amount'];
//					M("users")->save($user_info);
//					
//					if($vv['nowin']==1){
//						$touzhu_log[$k][$kk]['nowin']=0;
//						M("user_golds_record")->save($touzhu_log[$k][$kk]);
//					}
//					
//				}else{
//					$touzhu_log[$k][$kk]['nowin']=1;
//					M("user_golds_record")->save($touzhu_log[$k][$kk]);
//				}
//				
//			}
//		}
		$this->success("修改成功！");
	} 
	  
	 
	
} 
