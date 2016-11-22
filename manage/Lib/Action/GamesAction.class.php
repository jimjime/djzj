<?php
 
class GamesAction extends AllAction{

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
	function games(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$gid=t(h($_GET['gid']));
		$fid=t(h($_GET['fid']));
		$this->assign('gid', $gid);
		$tupian=M('teams')->where("state=1 and gid=".$gid."")->order("teamId asc")->select();
		$this->assign('tupian', $tupian);
		$pingtai=M('ptlogo')->where("state=1")->order("id asc")->select();
		$this->assign('pingtai', $pingtai);
		$list=M('games')->where("game_typeId=".$gid." and fenlei=".$fid."")->order("displayOrder asc,gameId desc")->select();
		foreach($list as $k=>$v){
			$team1=M("teams")->where("teamId=".$v['team1_teamId']."")->find();
			$list[$k]['tname1']=$team1['name'];
			$team2=M("teams")->where("teamId=".$v['team2_teamId']."")->find();
			$list[$k]['tname2']=$team2['name'];
			$ptlogo=M("ptlogo")->where("id=".$v['ptid']."")->find();
			$list[$k]['ptlogo']=$ptlogo['avatar'];
			
		}
		$this->assign('list', $list);
		if($fid==2){
			$this->display("games_bs");
		}else{
			$this->display();
		}
	} 
	function games_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));

		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['bifen']=t(h($_POST['bifen']));
			$add['displayOrder']=t(h($_POST['displayOrder']));
			if($add['displayOrder']<=0){
				$add['displayOrder']=9999999;
			}
			$ktime=t(h($_POST['ktime']));
			if(!empty($ktime)){
				$add['ktime']=strtotime($ktime);
			}
			$add['team1_teamId']=t(h($_POST['team1_teamId']));
			$team2_teamId=t(h($_POST['team2_teamId']));
			if(!empty($team2_teamId)){
				$add['team2_teamId']=$team2_teamId;
			}
			$add['game_typeId']=t(h($_POST['game_typeId']));
			$add['fenlei']=t(h($_POST['fenlei']));
			$add['ptid']=t(h($_POST['ptid']));
			$add['ctime']=time();
			$add['adminid']=$_SESSION['manage_id'];
			$add['state']=1;
			$res=M("games")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['gameId']=$id;
			$add['name']=t(h($_POST['name']));
			$add['bifen']=t(h($_POST['bifen']));
			$add['displayOrder']=t(h($_POST['displayOrder']));
			if($add['displayOrder']<=0){
				$add['displayOrder']=9999999;
			}
			$ktime=t(h($_POST['ktime']));
			if(!empty($ktime)){
				$add['ktime']=strtotime($ktime);
			}
			$add['team1_teamId']=t(h($_POST['team1_teamId']));
			$team2_teamId=t(h($_POST['team2_teamId']));
			if(!empty($team2_teamId)){
				$add['team2_teamId']=$team2_teamId;
			}
			$add['game_typeId']=t(h($_POST['game_typeId']));
			$add['fenlei']=t(h($_POST['fenlei']));
			$add['ptid']=t(h($_POST['ptid']));
			$add['stime']=time();
			$add['adminid']=$_SESSION['manage_id'];
			//$add['state']=1;
			$res=M("games")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	 //分类列表
	function game_fenlei(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('game_fenlei')->order("id asc")->select();
		$this->assign('list', $list);
		
		$this->display();
	} 
	function game_fenlei_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['ctime']=time();
			$add['adminid']=$_SESSION['manage_id'];
			$add['state']=1;

			$res=M("game_fenlei")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['name']=t(h($_POST['name']));
			$add['stime']=time();
			$add['adminid']=$_SESSION['manage_id'];
			//$add['state']=1;
			$res=M("game_fenlei")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	 //竞猜状态
	function game_cstate(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('game_cstate')->order("id asc")->select();
		$this->assign('list', $list);
		
		$this->display();
	} 
	function game_cstate_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['ctime']=time();
			$add['adminid']=$_SESSION['manage_id'];
			$add['state']=1;

			$res=M("game_cstate")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['name']=t(h($_POST['name']));
			$add['stime']=time();
			$add['adminid']=$_SESSION['manage_id'];
			//$add['state']=1;
			$res=M("game_cstate")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	//游戏 局/回合 管理
	function game_ju(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		
		$gid=t(h($_GET['gameid']));
		
		$game=M("games")->where("gameId=".$gid."")->find();
		$this->assign('game',$game);
		$team1=M('teams')->where("teamId=".$game['team1_teamId']."")->find();
		$this->assign('team1', $team1);
		$team2=M('teams')->where("teamId=".$game['team2_teamId']."")->find();
		$this->assign('team2', $team2);
		
		$cstate=M('game_cstate')->where("state=1")->order("id asc")->select();
		$this->assign('cstate', $cstate);
		
		$list=M('game_ju')->where("gameid=".$gid."")->order("displayOrder asc,id desc")->select();
		foreach($list as $k=>$v){
			$cname=M("game_cstate")->where("id=".$v['cstate']."")->find();
			$list[$k]['cstatename']=$cname['name'];			
		}
		$this->assign('list', $list);
		$this->display();
	} 
	function game_ju_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		$fid=t(h($_POST['fid']));
		$gid=t(h($_POST['gameid']));
		$game=M("games")->where("gameId=".$gid."")->find();
		$add['gid']=$game['game_typeId'];
		if(empty($id)){
			$add['gameid']=$gid;
			$add['fenlei']=$fid;
			$add['name']=t(h($_POST['name']));
			$add['displayOrder']=t(h($_POST['displayOrder']));
			if($add['displayOrder']<=0){
				$add['displayOrder']=9999999;
			}
			$add['ktime']=t(h($_POST['ktime']));
			if(!empty($add['ktime'])){
				$add['ktime']=strtotime($add['ktime']);
			}
			$add['cname']=t(h($_POST['cname']));
			$add['check1']=t(h($_POST['check1']));
			$add['check2']=t(h($_POST['check2']));
			$add['cren1']=t(h($_POST['cren1']));
			$add['cjinbi1']=t(h($_POST['cjinbi1']));
			$add['cren2']=t(h($_POST['cren2']));
			$add['cjinbi2']=t(h($_POST['cjinbi2']));
			$add['cstate']=t(h($_POST['cstate']));
			$add['starttime']=t(h($_POST['starttime']));
			if(!empty($add['starttime'])){
				$add['starttime']=strtotime($add['starttime']);
			}
			$add['endtime']=t(h($_POST['endtime']));
			if(!empty($add['endtime'])){
				$add['endtime']=strtotime($add['endtime']);
			}

			$add['stime']=time();
			$add['adminid']=$_SESSION['manage_id'];
			$add['state']=1;
			$res=M("game_ju")->add($add);
			if($res){
				$this->success("添加成功！");
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['id']=$id;
			$add['gameid']=$gid;
			$add['fenlei']=$fid;
			$add['name']=t(h($_POST['name']));
			$add['displayOrder']=t(h($_POST['displayOrder']));
			if($add['displayOrder']<=0){
				$add['displayOrder']=9999999;
			}
			$add['ktime']=t(h($_POST['ktime']));
			if(!empty($add['ktime'])){
				$add['ktime']=strtotime($add['ktime']);
			}
			$add['cname']=t(h($_POST['cname']));
			$add['check1']=t(h($_POST['check1']));
			$add['check2']=t(h($_POST['check2']));
			$add['cren1']=t(h($_POST['cren1']));
			$add['cjinbi1']=t(h($_POST['cjinbi1']));
			$add['cren2']=t(h($_POST['cren2']));
			$add['cjinbi2']=t(h($_POST['cjinbi2']));
			$add['cstate']=t(h($_POST['cstate']));
			$add['starttime']=t(h($_POST['starttime']));
			if(!empty($add['starttime'])){
				$add['starttime']=strtotime($add['starttime']);
			}
			$add['endtime']=t(h($_POST['endtime']));
			if(!empty($add['endtime'])){
				$add['endtime']=strtotime($add['endtime']);
			}

			$add['stime']=time();
			$add['adminid']=$_SESSION['manage_id'];
			//$add['state']=1;
			$res=M("game_ju")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	function games_changetime(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('games')->where("fenlei<=0 or state<=0")->order("gameId asc")->select();
		foreach($list as $k=>$v){
			if($v['team1_teamId']==$v['team2_teamId']){
				$changetime['fenlei']=1;
			}else{
				$changetime['fenlei']=2;
			}
			$changetime['state']=1;
			$changetime['gameId']=$v['gameId'];
			$save=M("games")->save($changetime);
		}
		$list2=M('games')->where("displayOrder=0")->order("gameId asc")->select();
		foreach($list2 as $k=>$v){
			$changetime['displayOrder']=9999999;
			$changetime['gameId']=$v['gameId'];
			$save=M("games")->save($changetime);
		}
		$list3=M('games')->where("ktime=0")->order("gameId asc")->select();
		foreach($list3 as $k=>$v){
			$changetime['ktime']=time();
			$changetime['gameId']=$v['gameId'];
			$save=M("games")->save($changetime);
		}
		echo 1;
		
		
	} 
	  
	 
	
} 
