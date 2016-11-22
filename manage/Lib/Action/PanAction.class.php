<?php
 
class PanAction extends AllAction{

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
	

	 //用户列表
	function pan(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$gid=t(h($_GET['gid']));
		$fid=t(h($_GET['fid']));
		$showid=t(h($_GET['showid']));
		$this->assign('gid', $gid);
		$tupian=M('teams')->where("state=1 and gid=".$gid."")->order("teamId asc")->select();
		$this->assign('tupian', $tupian);
		if($showid==1){
			$list=M('game_ju')->where("gid=".$gid." and starttime<=".time()." and endtime>=".time()."")->order("displayOrder asc,id desc")->select();
		}else if($showid==2){
			$list=M('game_ju')->where("gid=".$gid." and starttime>".time()."")->order("displayOrder asc,id desc")->select();
		}else{
			$list=M('game_ju')->where("gid=".$gid." and endtime<".time()."")->order("displayOrder asc,id desc")->select();
		}
		foreach($list as $k=>$v){
			$game=M("games")->where("gameId=".$v['gameid']."")->find();
			$list[$k]['game']=$game;

			$team1=M("teams")->where("teamId=".$game['team1_teamId']."")->find();
			$list[$k]['tname1']=$team1['name'];
			$team2=M("teams")->where("teamId=".$game['team2_teamId']."")->find();
			$list[$k]['tname2']=$team2['name'];
			
			$jinbi1=0;
			$ren1=0;
			$jinbi2=0;
			$ren2=0;
			$tou=M("user_golds_record")->where("panid=".$v['id']." and jieortou=2")->order("ctime asc,user_golds_recordId asc")->select();
			$list[$k]['real_jinbi1']=0;
			$list[$k]['real_ren1']=0;
			$list[$k]['real_jinbi2']=0;
			$list[$k]['real_ren2']=0;
			foreach($tou as $kk=>$vv){
				$user=M("users")->where("userId=".$vv['userId']."")->find();
				$tou[$kk]['user']=$user;
				$tou[$kk]['changeAmount']=abs($vv['changeAmount']);
				if($vv['checkx']==1){
					$list[$k]['real_jinbi1']=$list[$k]['real_jinbi1']+$tou[$kk]['changeAmount'];
					$list[$k]['real_ren1']=$list[$k]['real_ren1']+1;
				}else if($vv['checkx']==2){
					$list[$k]['real_jinbi2']=$list[$k]['real_jinbi2']+$tou[$kk]['changeAmount'];
					$list[$k]['real_ren2']=$list[$k]['real_ren2']+1;
				}

			}

			$list[$k]['zongjinbi']=$v['cjinbi1']+$v['cjinbi2'];
			$list[$k]['zongren']=$v['cren1']+$v['cren2'];
			$list[$k]['tou']=$tou;
			
			$cname=M("game_cstate")->where("id=".$v['cstate']."")->find();
			$list[$k]['cstatename']=$cname['name'];			
		}
		$this->assign('list', $list);
		$this->display();
	} 
	function pan_change(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_GET['id']));
		$doid=t(h($_GET['doid']));

		if(empty($id)){

		}else{
			$ju=M("game_ju")->where("id=".$id."")->find();
			$displayOrder_jiu=$ju['displayOrder'];
			
			if($doid==2){
				$displayOrder_new=$displayOrder_jiu+1;
			}else{
				if($displayOrder_jiu<=0){
					$displayOrder_new=0;
				}else{
					$displayOrder_new=$displayOrder_jiu-1;
				}
			}
			
			$res=M("game_ju")->where("id=".$id."")->setField('displayOrder',$displayOrder_new);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	//封盘
	function pan_feng(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){

		}else{
			
			$res=M("game_ju")->where("id=".$id."")->setField('cstate',2);
			if($res){
				$this->success("封盘成功！");
			}else{
				$this->error("封盘失败！");
			}
		}
	} 
	//重新开盘
	function pan_kai(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){

		}else{
			
			$res=M("game_ju")->where("id=".$id."")->setField('cstate',1);
			if($res){
				$this->success("开盘成功！");
			}else{
				$this->error("开盘失败！");
			}
		}
	} 
	//结算
	function pan_jie(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		$true_check=t(h($_POST['true_check']));
		if(empty($id)){

		}else{
			$pan_info=M("game_ju")->where("id=".$id."")->find();
			$game_info=M("games")->where("gameId=".$pan_info['gameid']."")->find();
			//赔率
			if($true_check==1){
				$peilv=floor((($pan_info['cjinbi1']+($pan_info['cjinbi2']*0.9))/$pan_info['cjinbi1'])*100)/100;
			}else if($true_check==2){
				$peilv=floor((($pan_info['cjinbi2']+($pan_info['cjinbi1']*0.9))/$pan_info['cjinbi2'])*100)/100;
			}else{
				$this->error("请选择正确选项！");
			}
			//胜利的投注
			$touzhu=M("user_golds_record")->where("panid=".$id." and jieortou=2 and checkx=".$true_check."")->order("ctime asc,user_golds_recordId asc")->select();
			foreach($touzhu as $k=>$v){
			
				$user_info[$k]=M("users")->where("userId=".$v['userId']."")->find();
				//本人奖励
				//查询是否已经结算过
				$sf_js[$k]=M("user_golds_record")->where("recordid=".$v['user_golds_recordId']."")->find();
				if(empty($sf_js[$k])){
					$shengli[$k]['changeAmount']=abs($v['changeAmount'])*$peilv;
					$shengli[$k]['amount']=$user_info[$k]['golds']+$shengli[$k]['changeAmount'];
					$shengli[$k]['ctime']=time();
					$shengli[$k]['userId']=$v['userId'];
					$shengli[$k]['adminid']=$_SESSION['manage_id'];
					$shengli[$k]['panid']=$v['panid'];
					$shengli[$k]['fid']=$v['fid'];
					$shengli[$k]['gid']=$v['gid'];
					$shengli[$k]['recordid']=$v['user_golds_recordId'];
					$shengli[$k]['checkx']=$v['checkx'];
					$shengli[$k]['jieortou']=1;
					$shengli[$k]['fromid']=2;
					
					if($true_check==1){
						$shengli[$k]['detail']="".$game_info['name']." ".$pan_info['name']." ".$pan_info['cname']." ".$pan_info['check1']." 胜利";
					}else{
						$shengli[$k]['detail']="".$game_info['name']." ".$pan_info['name']." ".$pan_info['cname']." ".$pan_info['check2']." 胜利";
					}
					
					M("user_golds_record")->add($shengli[$k]);
					//模板消息发送
					$mbxx_tztime=date("Y-m-d H:i:s",$v['ctime']);
					$mbxx_kjtime=date("Y-m-d H:i:s",time());
					$this->sendTemplateJc($shengli[$k]['userId'],$shengli[$k]['changeAmount'],$shengli[$k]['detail'],$mbxx_tztime,$mbxx_kjtime);
					
					$user_info[$k]['golds']=$shengli[$k]['amount'];
					M("users")->save($user_info[$k]);
					
				}
				
				
			}

			if($true_check==1){
				$false_check=2;
			}else{
				$false_check=1;
			}
			//失败的投注
			$false_touzhu=M("user_golds_record")->where("panid=".$id." and jieortou=2 and checkx=".$false_check."")->order("ctime asc,user_golds_recordId asc")->select();
			foreach($false_touzhu as $k=>$v){
				//失败 本人
				$false_user_info[$k]=M("users")->where("userId=".$v['userId']."")->find();
				M("user_golds_record")->where("user_golds_recordId=".$v['user_golds_recordId']."")->setField('nowin',1);
				
				
			}
			
			$res1=M("game_ju")->where("id=".$id."")->setField('cstate',3);
			$res2=M("game_ju")->where("id=".$id."")->setField('wincheck',$true_check);
			if($res2){
				$this->success("结算成功！");
			}else{
				$this->error("结算失败！");
			}
		}
	} 
	//重新结算
	function pan_chongjie(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		$true_check=t(h($_POST['true_check']));
		if(empty($id)){

		}else{
			$pan_info=M("game_ju")->where("id=".$id."")->find();
			$game_info=M("games")->where("gameId=".$pan_info['gameid']."")->find();
			//赔率
			if($true_check==1){
				$peilv=floor((($pan_info['cjinbi1']+($pan_info['cjinbi2']*0.9))/$pan_info['cjinbi1'])*100)/100;
			}else if($true_check==2){
				$peilv=floor((($pan_info['cjinbi2']+($pan_info['cjinbi1']*0.9))/$pan_info['cjinbi2'])*100)/100;
			}else{
				$this->error("请选择正确选项！");
			}
			if($true_check==1){
				$false_check=2;
			}else{
				$false_check=1;
			}
			//先取消 旧 的胜利结算
			$jiu_jiesuan=M("user_golds_record")->where("panid=".$id." and jieortou=1")->select();
			foreach($jiu_jiesuan as $k=>$v){
			
				$jiu_jiesuan_user_info[$k]=M("users")->where("userId=".$v['userId']."")->find();
				//本人奖励
				$jiu_jiesuan_user_info[$k]['golds']=$jiu_jiesuan_user_info[$k]['golds']-abs($v['changeAmount']);
				M("users")->save($jiu_jiesuan_user_info[$k]);
				
			}
			M("user_golds_record")->where("panid=".$id." and jieortou=1")->delete();
			//取消 旧 的 失败结算
			$jiu_jiesuan_bai=M("user_golds_record")->where("panid=".$id." and jieortou=2 and checkx=".$true_check." and nowin=1")->select();
			foreach($jiu_jiesuan_bai as $k=>$v){
			
				//失败 本人
				M("user_golds_record")->where("user_golds_recordId=".$v['user_golds_recordId']."")->setField('nowin',0);
				
			}
			
			//胜利的投注
			$touzhu=M("user_golds_record")->where("panid=".$id." and jieortou=2 and checkx=".$true_check."")->order("ctime asc,user_golds_recordId asc")->select();
			foreach($touzhu as $k=>$v){
			
				$user_info[$k]=M("users")->where("userId=".$v['userId']."")->find();
				//本人奖励
				//查询是否已经结算过
				$sf_js[$k]=M("user_golds_record")->where("recordid=".$v['user_golds_recordId']."")->find();
				if(empty($sf_js[$k])){
					$shengli[$k]['changeAmount']=abs($v['changeAmount'])*$peilv;
					$shengli[$k]['amount']=$user_info[$k]['golds']+$shengli[$k]['changeAmount'];
					$shengli[$k]['ctime']=time();
					$shengli[$k]['userId']=$v['userId'];
					$shengli[$k]['adminid']=$_SESSION['manage_id'];
					$shengli[$k]['panid']=$v['panid'];
					$shengli[$k]['fid']=$v['fid'];
					$shengli[$k]['gid']=$v['gid'];
					$shengli[$k]['recordid']=$v['user_golds_recordId'];
					$shengli[$k]['checkx']=$v['checkx'];
					$shengli[$k]['jieortou']=1;
					$shengli[$k]['fromid']=2;
					
					if($true_check==1){
						$shengli[$k]['detail']="".$game_info['name']." ".$pan_info['name']." ".$pan_info['cname']." ".$pan_info['check1']." 胜利";
					}else{
						$shengli[$k]['detail']="".$game_info['name']." ".$pan_info['name']." ".$pan_info['cname']." ".$pan_info['check2']." 胜利";
					}
					
					M("user_golds_record")->add($shengli[$k]);
					$user_info[$k]['golds']=$shengli[$k]['amount'];
					M("users")->save($user_info[$k]);
					
					//模板消息发送
					$mbxx_tztime=date("Y-m-d H:i:s",$v['ctime']);
					$mbxx_kjtime=date("Y-m-d H:i:s",time());
					//$this->sendTemplateJc($shengli[$k]['userId'],$shengli[$k]['changeAmount'],$shengli[$k]['detail'],$mbxx_tztime,$mbxx_kjtime);
				
				}
				
			}
			//失败的投注
			$false_touzhu=M("user_golds_record")->where("panid=".$id." and jieortou=2 and checkx=".$false_check."")->order("ctime asc,user_golds_recordId asc")->select();
			foreach($false_touzhu as $k=>$v){
				//失败 本人
				M("user_golds_record")->where("user_golds_recordId=".$v['user_golds_recordId']."")->setField('nowin',1);
								
			}

			$res=M("game_ju")->where("id=".$id."")->setField('wincheck',$true_check);
			if($res){
				$this->success("重新结算成功！");
			}else{
				$this->error("重新结算失败！");
			}
		}
	} 
	//流盘
	function pan_liu(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){

		}else{
//			$pan_info=M("game_ju")->where("id=".$id."")->find();
//			$game_info=M("games")->where("gameId=".$pan_info['gameid']."")->find();

			
			//先取消 旧 的胜利结算
			$jiu_jiesuan=M("user_golds_record")->where("panid=".$id." and jieortou=1")->select();
			foreach($jiu_jiesuan as $k=>$v){
			
				$jiu_jiesuan_user_info[$k]=M("users")->where("userId=".$v['userId']."")->find();
				//本人奖励
				$jiu_jiesuan_user_info[$k]['golds']=$jiu_jiesuan_user_info[$k]['golds']-abs($v['changeAmount']);
				M("users")->save($jiu_jiesuan_user_info[$k]);
				
			}
			M("user_golds_record")->where("panid=".$id." and jieortou=1")->delete();
			//取消 旧 的 失败结算 或 投注
			$jiu_touzhu=M("user_golds_record")->where("panid=".$id." and jieortou=2")->select();
			foreach($jiu_touzhu as $k=>$v){
				if($v['nowin']!=0){
					//取消 失败状态 本人
					M("user_golds_record")->where("user_golds_recordId=".$v['user_golds_recordId']."")->setField('nowin',0);
				}
				$user_info[$k]=M("users")->where("userId=".$v['userId']."")->find();
				//本人 返回 投注 金币
				$fanhui[$k]['changeAmount']=abs($v['changeAmount']);
				$fanhui[$k]['amount']=$user_info[$k]['golds']+$fanhui[$k]['changeAmount'];
				$fanhui[$k]['ctime']=time();
				$fanhui[$k]['userId']=$v['userId'];
				$fanhui[$k]['adminid']=$_SESSION['manage_id'];
				$fanhui[$k]['panid']=$v['panid'];
				$fanhui[$k]['fid']=$v['fid'];
				$fanhui[$k]['gid']=$v['gid'];
				$fanhui[$k]['recordid']=$v['user_golds_recordId'];
				$fanhui[$k]['checkx']=$v['checkx'];
				$fanhui[$k]['jieortou']=1;
				$fanhui[$k]['liupan']=1;
				$fanhui[$k]['fromid']=2;
				$fanhui[$k]['detail']="流盘 返还";
				
				M("user_golds_record")->add($fanhui[$k]);
				$user_info[$k]['golds']=$fanhui[$k]['amount'];
				M("users")->save($user_info[$k]);
				
			}
			
			$lpyin=M("game_ju")->where("id=".$id."")->find();
			$lpyin['cstate']=4;
			$lpyin['lpyin']=t(h($_POST['lpyin']));
			$res=M("game_ju")->save($lpyin);
			if($res){
				$this->success("流盘成功！");
			}else{
				$this->error("流盘失败！");
			}
		}
	} 
	  
	 
	
} 
