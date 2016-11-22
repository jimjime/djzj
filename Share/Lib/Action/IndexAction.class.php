<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 首页、列表页面、详细页
 */
 
class IndexAction extends AllAction{

    function _initialize(){
				
		$gametypeid=t(h($_GET['gtypeid']));

		if(!empty($gametypeid)){
			$_SESSION['gametypeid']=$gametypeid;
		}else{
			if(empty($_SESSION['gametypeid'])){
				$_SESSION['gametypeid']=1;
			}
		}
		//$_SESSION['user_id']="";
		if(empty($_SESSION['user_id'])){
			header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=loginbyweixin&m=User");
			exit;	
		}
		//$_SESSION['user_id']=1;
		
		$user=M("users")->where("userId=".$_SESSION['user_id']."")->find();
		$user_yue=floor($user['golds']);
		$this->assign('user_yue', $user_yue);

		//总水
		$thisday=date("Ymd",time());
		$thisday_begin=strtotime($thisday);  //每天00:00开始
		$jiange=300;  //间隔300s
		$renshu_max=15;  //人数最多15
		$renshu_min=1;  //人数最少1
		$jinbi_max=1300;  //金币最多1000
		$jinbi_min=1;  //金币最少1
		
		$game_shuizong=M("game_shuizong")->where("thisday=".$thisday." and state=1")->find();
		if(empty($game_shuizong)){
			if((time()-$thisday_begin)>$jiange){
				$cishu=floor((time()-$thisday_begin)/$jiange);
				for($i=0;$i<$cishu;$i++){
					$add['ren']+=rand($renshu_min,$renshu_max);
					$add['jinbi']+=rand($jinbi_min,$jinbi_max);
				}
				$add['thisday']=$thisday;
				$add['ctime']=time();
				$add['stime']=time();
				$add['state']=1;
				M("game_shuizong")->add($add);
			}
			$thisdayzong_ren=$add['ren'];
			$thisdayzong_jinbi=$add['jinbi'];
		}else{
			if((time()-$game_shuizong['stime'])>$jiange){
				$cishu=floor((time()-$game_shuizong['stime'])/$jiange);
				for($i=0;$i<$cishu;$i++){
					$game_shuizong['ren']+=rand($renshu_min,$renshu_max);
					$game_shuizong['jinbi']+=rand($jinbi_min,$jinbi_max);
				}
				$game_shuizong['stime']=time();
				M("game_shuizong")->save($game_shuizong);
			}
			$thisdayzong_ren=$game_shuizong['ren'];
			$thisdayzong_jinbi=$game_shuizong['jinbi'];
		}
		$this->assign('thisdayzong_ren', $thisdayzong_ren);
		$this->assign('thisdayzong_jinbi', $thisdayzong_jinbi);


	}
	/**
	 * ------------------------------
	 * 权限判断
	 * ------------------------------
	 */
	function pan_quan_ajax($data){  //ajax判断
		$pan=in_array($data,$_SESSION['admin_quan']);
        if($pan){
			return true;
		}else{
			return false;
		}
	}
	function pan_quan($data){ //php跳转判断
		$pan=in_array($data,$_SESSION['admin_quan']);
        if(!$pan){
			$this->assign('jumpUrl', U('Index/index'));
			$this->error('您权限不足');
		}
	}
	 
	//首页
	function index(){
		$gametypeid=$_SESSION['gametypeid'];
		$list=M('game_ju')->where("gid=".$gametypeid." and starttime<=".time()." and endtime>=".time()." and state=1")->order("displayOrder asc,id desc")->select();
		foreach($list as $k=>$v){
			$cname=M("game_cstate")->where("id=".$v['cstate']."")->find();
			$list[$k]['cstatename']=$cname['name'];	
					
			$game=M("games")->where("gameId=".$v['gameid']."")->find();
			$list[$k]['game']=$game;

			$ptlogo=M("ptlogo")->where("id=".$game['ptid']."")->find();
			$list[$k]['ptlogo']=$ptlogo;
			
			$team1=M("teams")->where("teamId=".$game['team1_teamId']."")->find();
			$list[$k]['team1']=$team1;
			$team2=M("teams")->where("teamId=".$game['team2_teamId']."")->find();
			$list[$k]['team2']=$team2;
			
			$real_zong_ren=$real_zong_ren+$v['cren1']+$v['cren2'];
			$real_zong_jinbi=$real_zong_jinbi+$v['cjinbi1']+$v['cjinbi2'];
			
			//$xu_info=M("game_shui")->where("panid=".$v['id']." and state=1")->find();
			$baifenbi=floor(($v['cren1']/($v['cren1']+$v['cren2']))*100);
			if($v['cjinbi1']<=0){
				$peilv1=0.95;
			}else{
				$peilv1=floor((($v['cjinbi1']+($v['cjinbi2']*0.9))/$v['cjinbi1'])*100)/100;
			}
			if($v['cjinbi2']<=0){
				$peilv2=0.95;
			}else{
				$peilv2=floor((($v['cjinbi2']+($v['cjinbi1']*0.9))/$v['cjinbi2'])*100)/100;
			}
			
			$list[$k]['baifenbi']=$baifenbi;
			$list[$k]['peilv1']=$peilv1;
			$list[$k]['peilv2']=$peilv2;

		}
		$this->assign('list', $list);
		$webtitle="竞猜-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

 	//竞猜 投注
	function jctz(){
		$id=t(h($_POST['id']));
		$addgoldnum=t(h($_POST['addgoldnum']));
		$checkid=t(h($_POST['checkid']));
		$userid=$_SESSION['user_id'];
		$gametypeid=$_SESSION['gametypeid'];
		
		$game_info=M('game_ju')->where("id=".$id."")->find();
		if($game_info['cstate']==2){
			$cc['jieguo']="fengpan";
			echo json_encode($cc);exit;
		}
		if($game_info['cstate']==3){
			$cc['jieguo']="jiesuan";
			echo json_encode($cc);exit;
		}
		if($game_info['endtime']<time()){
			$cc['jieguo']="yiend";
			echo json_encode($cc);exit;
		}
		if($game_info['starttime']>time()){

			$cc['jieguo']="nostart";
			echo json_encode($cc);exit;
		}
		
		$user_info=M("users")->where("userId=".$userid."")->find();
		$game_type=M("game_type")->where("game_typeId=".$gametypeid."")->find();
		
		$new['changeAmount']=-$addgoldnum;
		$new['amount']=$user_info['golds']-$addgoldnum;
		$new['ctime']=time();
		$new['userId']=$userid;
		$new['fid']=$game_info['fenlei'];
		$new['panid']=$game_info['id'];
		$new['gid']=$game_type['game_typeId'];
		if($checkid==2){
			$new['checkx']=2;
			$new['detail']="竞猜 ".$game_info['name']." ".$game_info['cname']." ".$game_info['check2'];
		}else{
			$new['checkx']=1;
			$new['detail']="竞猜 ".$game_info['name']." ".$game_info['cname']." ".$game_info['check1'];
		}
		$new['jieortou']=2;

		$record_res=M("user_golds_record")->add($new);
		if($record_res){
			$user_info['golds']=$new['amount'];
			$user_res=M("users")->save($user_info);
			
			if($checkid==2){
				$game_info['cren2']=$game_info['cren2']+1;
				$game_info['cjinbi2']=$game_info['cjinbi2']+$addgoldnum;
			}else{
				$game_info['cren1']=$game_info['cren1']+1;
				$game_info['cjinbi1']=$game_info['cjinbi1']+$addgoldnum;
			}
			$game_res=M("game_ju")->save($game_info);
			
			$thisday=date("Ymd",time());
			$shuizong=M("game_shuizong")->where("thisday=".$thisday." and state=1")->find();
			if(!empty($shuizong)){
				$shuizong['ren']=$shuizong['ren']+1;
				$shuizong['jinbi']=$shuizong['jinbi']+$addgoldnum;
				$shuizong_res=M("game_shuizong")->save($shuizong);
			}else{
				$shuizong['ren']=$shuizong['ren']+1;
				$shuizong['jinbi']=$shuizong['jinbi']+$addgoldnum;
				$shuizong['state']=1;
				$shuizong['ctime']=time();
				$shuizong['stime']=time();
				$shuizong['thisday']=$thisday;
				$shuizong_res=M("game_shuizong")->add($shuizong);				
			}
			
			$baifenbi=floor(($game_info['cren1']/($game_info['cren1']+$game_info['cren2']))*100);
			if($game_info['cjinbi1']<=0){
				$peilv1=0.95;
			}else{
				$peilv1=floor((($game_info['cjinbi1']+($game_info['cjinbi2']*0.9))/$game_info['cjinbi1'])*100)/100;
			}
			if($game_info['cjinbi2']<=0){
				$peilv2=0.95;
			}else{
				$peilv2=floor((($game_info['cjinbi2']+($game_info['cjinbi1']*0.9))/$game_info['cjinbi2'])*100)/100;
			}
			
			$cc['baifenbi']=$baifenbi;
			$cc['peilv1']=$peilv1;
			$cc['peilv2']=$peilv2;
			$cc['user_yue']=floor($user_info['golds']);
			$cc['ren']=floor($shuizong['ren']);
			$cc['jinbi']=floor($shuizong['jinbi']);
			$cc['pid']=$new['panid'];
			$cc['jieguo']="tzcg";
			
			echo json_encode($cc);exit;
		
		}

		$cc['jieguo']="tzsb";
		echo json_encode($cc);exit;
	}	 

	//文章页
	function art(){
		$aid=t(h($_GET['aid']));
		$fid=t(h($_GET['fid']));
		$gid=t(h($_SESSION['gametypeid']));
		if(!empty($aid)){
			$art=M('sys_art')->where("sys_artId=".$aid."")->find();
		}else{
			$art=M('sys_art')->where("gid=".$gid." and fid=".$fid." and state=1")->find();
		}
		$this->assign($art);
		$webtitle=$art['name'];
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	//个人中心-首页
	function mycenter(){
		$uid=$_SESSION['user_id'];
		$user_info=M('users')->where("userId=".$uid."")->find();
		$user_info['golds']=floor($user_info['golds']*100)/100;
		$this->assign($user_info);
		$webtitle="个人中心-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	//切换游戏页
	function chtype(){
		$webtitle="切换游戏-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//金币明细
	function mygolds(){
		$uid=$_SESSION['user_id'];
		$list=M("user_golds_record")->where("userId=".$uid."")->order("ctime desc,user_golds_recordId desc")->select();
		foreach($list as $k=>$v){
			$list[$k]['changeAmount']=floor($v['changeAmount']*100)/100;
			if($list[$k]['changeAmount']>=0){
				$list[$k]['changeAmount']="+".$list[$k]['changeAmount'];
			}
			$list[$k]['amount']=floor($v['amount']*100)/100;
		}
		$this->assign('list',$list);
		$webtitle="金币明细-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	//竞猜历史消息
	function myquiz(){
		$uid=$_SESSION['user_id'];
		$list=M("user_golds_record")->where("userId=".$uid." and fid=1 and jieortou=2")->order("ctime desc,user_golds_recordId desc")->select();
		foreach($list as $k=>$v){
			$list[$k]['changeAmount']=abs(floor($v['changeAmount']*100)/100);
			
			$game_info=M('game_ju')->where("id=".$v['panid']."")->find();
			$list[$k]['cname']=$game_info['cname'];
			if($v['checkx']==2){
				$list[$k]['checkname']=$game_info['check2'];
				$peilv1=floor((($game_info['cjinbi1']+($game_info['cjinbi2']*0.9))/$game_info['cjinbi1'])*100)/100;
				$list[$k]['peilv']=$peilv1;
			}else{
				$list[$k]['checkname']=$game_info['check1'];
				$peilv2=floor((($game_info['cjinbi2']+($game_info['cjinbi1']*0.9))/$game_info['cjinbi2'])*100)/100;
				$list[$k]['peilv']=$peilv2;
			}
			
			if($v['nowin']==1){
				$list[$k]['jieguoid']=2;
				$list[$k]['jieguo']="失败";
				$list[$k]['jgolds']="-".abs($list[$k]['changeAmount'])."金币";
			}else{
				$jieguo=M("user_golds_record")->where("recordid=".$v['user_golds_recordId']." and nowin=0 and jieortou=1")->find();
				if($jieguo['changeAmount']>0){
					$list[$k]['jieguoid']=1;
					$list[$k]['jieguo']="成功";
					$list[$k]['jgolds']="+".abs(floor($jieguo['changeAmount']*100)/100)."金币";
				}else{
					$list[$k]['jieguo']="等待结算";
				}
			}

			$games=M("games")->where("gameId=".$game_info['gameid']."")->find();
			$list[$k]['gamename']=$games['name'];
		}
		$this->assign('list',$list);

		$list_bs=M("user_golds_record")->where("userId=".$uid." and fid=2 and jieortou=2")->order("ctime desc,user_golds_recordId desc")->select();
		foreach($list_bs as $k=>$v){
			$list_bs[$k]['changeAmount']=abs(floor($v['changeAmount']*100)/100);
			
			$game_info=M('game_ju')->where("id=".$v['panid']."")->find();
			$list_bs[$k]['cname']=$game_info['cname'];
			$list_bs[$k]['name']=$game_info['name'];
			if($v['checkx']==2){
				$list_bs[$k]['checkname']=$game_info['check2'];
				$peilv1=floor((($game_info['cjinbi1']+($game_info['cjinbi2']*0.9))/$game_info['cjinbi1'])*100)/100;
				$list_bs[$k]['peilv']=$peilv1;
			}else{
				$list_bs[$k]['checkname']=$game_info['check1'];
				$peilv2=floor((($game_info['cjinbi2']+($game_info['cjinbi1']*0.9))/$game_info['cjinbi2'])*100)/100;
				$list_bs[$k]['peilv']=$peilv2;
			}
			
			if($v['nowin']==1){
				$list_bs[$k]['jieguoid']=2;
				$list_bs[$k]['jieguo']="失败";
				$list_bs[$k]['jgolds']="-".abs($list_bs[$k]['changeAmount'])."金币";
			}else{
				$jieguo=M("user_golds_record")->where("recordid=".$v['user_golds_recordId']." and nowin=0 and jieortou=1")->find();
				if($jieguo['changeAmount']>0){
					$list_bs[$k]['jieguoid']=1;
					$list_bs[$k]['jieguo']="成功";
					$list_bs[$k]['jgolds']="+".abs(floor($jieguo['changeAmount']*100)/100)."金币";
				}else{
					$list_bs[$k]['jieguo']="等待结算";
				}
			}
				
			$games=M("games")->where("gameId=".$game_info['gameid']."")->find();
			$list_bs[$k]['gamename']=$games['name'];
		}
		$this->assign('list_bs',$list_bs);
		//英雄猜 历史消息
		$list_yxc=M("user_golds_record")->where("userId=".$uid." and herocheck IS NOT NULL and jieortou=2")->order("ctime desc,user_golds_recordId desc")->select();
		foreach($list_yxc as $k=>$v){
			$list_yxc[$k]['changeAmount']=abs(floor($v['changeAmount']*100)/100);
			
			$checksids_arr=explode(",",$v['herocheck']);
			$peilv=1;
			foreach($checksids_arr as $kk=>$vv){
				$property_info=M("roulette_property")->where("roulette_propertyId=".$vv."")->find();
				$peilv=$peilv*$property_info['peilv'];
				$checkname=$checkname.",".$property_info['name'];
			}
			$list_yxc[$k]['checkname']=trim($checkname,",");
			$list_yxc[$k]['peilv']=floor($peilv*100)/100;
			
			if($v['nowin']==1){
				$list_yxc[$k]['jieguoid']=2;
				$list_yxc[$k]['jieguo']="失败";
				$list_yxc[$k]['jgolds']="-".$list_yxc[$k]['changeAmount']."金币";
			}else{
				$jieguo=M("user_golds_record")->where("recordid=".$v['user_golds_recordId']."")->find();
				if($jieguo['changeAmount']>0){
					$list_yxc[$k]['jieguoid']=1;
					$list_yxc[$k]['jieguo']="成功";
					$list_yxc[$k]['jgolds']="+".abs(floor($jieguo['changeAmount']*100)/100)."金币";
				}else{
					$list_yxc[$k]['jieguo']="未知";
				}
			}
			
			$games=M("game_type")->where("game_typeId=".$v['gid']."")->find();
			$list_yxc[$k]['gamename']=$games['cnName'];
		}
		$this->assign('list_yxc',$list_yxc);

		$webtitle="竞猜历史消息-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	//金币兑换
	function tobuy(){
		$webtitle="金币兑换-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	
	//金币兑换-确认兑换
	function tobuygo(){
		$uid=t(h($_SESSION['user_id']));
		$user_info=M("users")->where("userId=".$uid."")->find();
		$this->assign('user_info',$user_info);
		
		$webtitle="确认兑换-金币兑换-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	function dotobuygo(){
		$uid=t(h($_SESSION['user_id']));
		$bnum=t(h($_POST['bnum']));
		$username=t(h($_POST['username']));
		$tel=t(h($_POST['tel']));
		$detail=t(h($_POST['detail']));
		if(empty($username)){
			echo "nousername";exit;
		}
		if(empty($tel)){
			echo "notel";exit;
		}
		if($bnum==100){
			$bgolds=11000;
		}else if($bnum==50){
			$bgolds=5700;
		}else{
			$bgolds=2400;
		}
		$user_info=M("users")->where("userId=".$uid."")->find();
		
		$new['changeAmount']=-$bgolds;
		$new['amount']=$user_info['golds']-$bgolds;
		if($new['amount']<0){
			echo "yuebuzu";exit;
		}
		$new['detail']="金币兑换 ".$bnum."元 ".$detail."";
		$new['ctime']=time();
		$new['userId']=$uid;
		$new['txstate']=1;
		$gold_res=M("user_golds_record")->add($new);
		if($gold_res){
			$user_info['golds']=$new['amount'];
			if(empty($user_info['tel'])){
				$user_info['tel']=$tel;
			}
			if(empty($user_info['alipay'])){
				$user_info['alipay']=$username;
			}
			$user_res=M("users")->save($user_info);
			if($user_res){
				echo "duihuancg";exit;
			}else{
				echo "duihuansb";exit;
			}
		}else{
			echo "duihuansb";exit;
		}
		
	}
	
	//金币充值
	function topay(){
		
		$webtitle="金币充值-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//战友招募
	function recom(){
		$uid=$_SESSION['user_id'];
		$user_info=M('users')->where("userId=".$uid."")->find();

		$user_golds_info=M('user_golds_record')->where("userId=".$uid." and recomid>=1")->select();
		foreach($user_golds_info as $k=>$v){
			$user_info['recomgolds']+=$v['changeAmount'];
		}
		$user_info['recomgolds']=floor($user_info['recomgolds']*100)/100;
		$this->assign($user_info);
		
		$webtitle="战友招募-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//战友招募-我的战友
	function recoms(){
		$uid=$_SESSION['user_id'];
		//$user_info=M('users')->where("userId=".$uid."")->find();

		$user_recoms=M('users')->where("invite_by_userId=".$uid."")->select();
		foreach($user_recoms as $k=>$v){
			$user_recoms_golds_info=M('user_golds_record')->where("userId=".$uid." and recomid=".$v['userId']."")->select();
			foreach($user_recoms_golds_info as $kk=>$vv){
				$user_recoms[$k]['recomgolds']+=$vv['changeAmount'];
			}
			$user_recoms[$k]['recomgolds']+=floor($user_recoms[$k]['recomgolds']*100)/100;
		}

		$this->assign('user_recoms',$user_recoms);
		
		$webtitle="我的战友-战友招募-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//战友招募-我的二维码
	function myqr(){
		$uid=$_SESSION['user_id'];
		
		$webtitle="我的二维码-战友招募-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//英雄猜 do 开奖 
	function hero_do_kj(){
		$today=date("Ymd",time());
		$today_shi_time=strtotime($today);
		$hei_end_time=strtotime($today."02:00:00");
		$gid=$_SESSION['gametypeid'];
		//$today_shi=date("Ymd H:i:s",$today_shi_time);
		
		$cha_time=time()-$today_shi_time;
		$bai_shi_time=strtotime($today."10:00:00");
		$hei_shi_time=strtotime($today."22:00:00");
		if(time()<=$hei_end_time){
			$cha_cishu=floor((time()-$today_shi_time)/(5*60));
			$shengyumiao=5*60-floor((time()-$today_shi_time)%(5*60));
		}else if(time()>$hei_end_time && time()<=$bai_shi_time){
			$cha_cishu=23;
			$shengyumiao=$bai_shi_time-time();
		}else if(time()>$bai_shi_time && time()<=$hei_shi_time){
			$cha_cishu=24+floor((time()-$bai_shi_time)/(10*60));
			$shengyumiao=10*60-floor((time()-$bai_shi_time)%(10*60));
		}else if(time()>$hei_shi_time){
			$cha_cishu=24+72+floor((time()-$hei_shi_time)/(5*60));
			$shengyumiao=5*60-floor((time()-$hei_shi_time)%(5*60));
		}
		$now_cha_cishu=$cha_cishu+1;//正在投注的期数
		
		if($cha_cishu>0){ //需要获取的期数大于0时，才去获取，否则不获取
			$roulette_log_old=M("roulette_log")->where("day=".$today." and gid=".$gid."")->order("qi desc")->find();
			$cha_cishu=$cha_cishu-floor($roulette_log_old['qi']);
			if($cha_cishu>1 || ($cha_cishu==1 && $shengyumiao<3*60)){ //需要获取的期数大于0时，才去获取，否则不获取
				$src = 'http://www.cp66607.com/api/cqssc?act=lishikaijianghaoma&limit='.$cha_cishu;
				$json = file_get_contents(urldecode($src));
				$json = json_decode($json);
				foreach($json as $k=>$v){
					$kaijiang[$k]['num']=$v->cn1.$v->cn2.$v->cn3.$v->cn4.$v->cn5;
					$kaijiang[$k]['day']=substr($v->cissue,0,8);
					$kaijiang[$k]['qi']=substr($v->cissue,-3,3);
					$kaijiang[$k]['ktime']=$v->ckjtime;
				}
				foreach($kaijiang as $k=>$v){
					$old_rlog[$k]=M("roulette_log")->where("day=".$v['day']." and gid=".$gid." and qi='".$v['qi']."'")->find();
					if(empty($old_rlog[$k])){//如果没有旧的开奖记录，则存储
						$new_log['ctime']=time();
						$new_log['num']=$v['num'];
						$new_log['day']=$v['day'];
						$new_log['qi']=$v['qi'];
						if($v['qi']==120){
							$next_day=date("Ymd",(strtotime($v['day'])+60*60*24));
							$new_log['ktime']=strtotime("".$next_day." ".$v['ktime'].":00");
						}else{
							$new_log['ktime']=strtotime("".$v['day']." ".$v['ktime'].":00");
						}
						$new_log['gid']=$gid;
						if($gid==1){
							$new_log['heroid']=$v['num']%129+1;
						}else if($gid==2){
							$new_log['heroid']=$v['num']%112+1+129;
						}
						$add_rlog[$k]=M("roulette_log")->add($new_log);
						if($add_rlog[$k]){
							$hero_info[$k]=M("roulette_hero_property")->where("roulette_heroId=".$new_log['heroid']."")->select();
							foreach($hero_info[$k] as $kk=>$vv){
								$hero_win_pro[$k][]=$v['roulette_propertyId'];
							}
							
							$touzhu_log[$k]=M("user_golds_record")->where("jieortou=2 and gid=".$gid." and herodayqi=".$v['day'].$v['qi']."")->order("ctime asc,user_golds_recordId asc")->select();
							foreach($touzhu_log[$k] as $kk=>$vv){
								$touzhu_check=explode(',',$vv['herocheck']);
								$touzhu_check_count=count($touzhu_check);
								$array_intersect=array_intersect($touzhu_check,$hero_win_pro[$k]);
								$array_intersect_count=count($array_intersect);
								if($array_intersect_count==$touzhu_check_count){
									$peilv=1;
									foreach($array_intersect as $k=>$v){
										$property_info=M("roulette_property")->where("roulette_propertyId=".$v."")->find();
										$peilv=$peilv*$property_info['peilv'];
									}
									$win_golds['changeAmount']=abs($vv['changeAmount'])*$peilv;
									
									$user_info=M("users")->where("userId=".$vv['userId']."")->find();
									$win_golds['amount']=$user_info['golds']+$win_golds['changeAmount'];
									$win_golds['detail']="英雄猜 第 ".$v['day'].$v['qi']." 期 奖励";
									$win_golds['ctime']=time();
									$win_golds['userId']=$vv['userId'];
									$win_golds['gid']=$vv['gid'];
									$win_golds['recordid']=$vv['user_golds_record'];
									$win_golds['jieortou']=1;
									$win_golds['recomid']=$vv['recomid'];
									$win_golds['herocheck']=$vv['herocheck'];
									$win_golds['herodayqi']=$vv['herodayqi'];
									M("user_golds_record")->add($win_golds);
									
								}else{
									$touzhu_log[$k][$kk]['nowin']=1;
									M("user_golds_record")->save($touzhu_log[$k][$kk]);
								}
								
							}
							
							
							
						}
						
					}
				}
			}
		}
		//昨天的开奖记录
		$yest_day=date("Ymd",(time()-60*60*24));
		$yest_day_log=M("roulette_log")->where("day=".$yest_day." and gid=".$gid."")->select();
		
		if(count($yest_day_log)<120){//需要获取的期数小于120期时，才去获取，否则不获取

			$src = 'http://www.cp66607.com/api/cqssc?act=lishikaijianghaoma&limit=240';
			$json = file_get_contents(urldecode($src));
			$json = json_decode($json);
			foreach($json as $k=>$v){
				$kaijiang[$k]['num']=$v->cn1.$v->cn2.$v->cn3.$v->cn4.$v->cn5;
				$kaijiang[$k]['day']=substr($v->cissue,0,8);
				$kaijiang[$k]['qi']=substr($v->cissue,-3,3);
				$kaijiang[$k]['ktime']=$v->ckjtime;
			}
			foreach($kaijiang as $k=>$v){
				$old_rlog[$k]=M("roulette_log")->where("day=".$v['day']." and gid=".$gid." and qi='".$v['qi']."'")->find();
				if(empty($old_rlog[$k])){//如果没有旧的开奖记录，则存储
					$new_log['ctime']=time();
					$new_log['num']=$v['num'];
					$new_log['day']=$v['day'];
					$new_log['qi']=$v['qi'];
					if($v['qi']==120){
						$next_day=date("Ymd",(strtotime($v['day'])+60*60*24));
						$new_log['ktime']=strtotime("".$next_day." ".$v['ktime'].":00");
					}else{
						$new_log['ktime']=strtotime("".$v['day']." ".$v['ktime'].":00");
					}
					$new_log['gid']=$gid;
					if($gid==1){
						$new_log['heroid']=$v['num']%129+1;
					}else if($gid==2){
						$new_log['heroid']=$v['num']%112+1+129;
					}
					$add_rlog[$k]=M("roulette_log")->add($new_log);
					if($add_rlog[$k]){
						$hero_info[$k]=M("roulette_hero_property")->where("roulette_heroId=".$new_log['heroid']."")->select();
						foreach($hero_info[$k] as $kk=>$vv){
							$hero_win_pro[$k][]=$v['roulette_propertyId'];
						}
						
						$touzhu_log[$k]=M("user_golds_record")->where("jieortou=2 and gid=".$gid." and herodayqi=".$v['day'].$v['qi']."")->order("ctime asc,user_golds_recordId asc")->select();
						foreach($touzhu_log[$k] as $kk=>$vv){
							$touzhu_check=explode(',',$vv['herocheck']);
							$touzhu_check_count=count($touzhu_check);
							$array_intersect=array_intersect($touzhu_check,$hero_win_pro[$k]);
							$array_intersect_count=count($array_intersect);
							if($array_intersect_count==$touzhu_check_count){
								$peilv=1;
								foreach($array_intersect as $k=>$v){
									$property_info=M("roulette_property")->where("roulette_propertyId=".$v."")->find();
									$peilv=$peilv*$property_info['peilv'];
								}
								$win_golds['changeAmount']=abs($vv['changeAmount'])*$peilv;
								
								$user_info=M("users")->where("userId=".$vv['userId']."")->find();
								$win_golds['amount']=$user_info['golds']+$win_golds['changeAmount'];
								$win_golds['detail']="英雄猜 第 ".$v['day'].$v['qi']." 期 奖励";
								$win_golds['ctime']=time();
								$win_golds['userId']=$vv['userId'];
								$win_golds['gid']=$vv['gid'];
								$win_golds['recordid']=$vv['user_golds_record'];
								$win_golds['jieortou']=1;
								$win_golds['recomid']=$vv['recomid'];
								$win_golds['herocheck']=$vv['herocheck'];
								$win_golds['herodayqi']=$vv['herodayqi'];
								M("user_golds_record")->add($win_golds);
								
							}else{
								$touzhu_log[$k][$kk]['nowin']=1;
								M("user_golds_record")->save($touzhu_log[$k][$kk]);
							}
							
						}
						
						
						
					}
					
				}
			}
		}
		
		
		if($now_cha_cishu<10){
			$cc['now_cha_cishu']="00".$now_cha_cishu;
		}else if($now_cha_cishu<100 && $now_cha_cishu>=10){
			$cc['now_cha_cishu']="0".$now_cha_cishu;
		}else{
			$cc['now_cha_cishu']=$now_cha_cishu;
		}
		$cc['shengyumiao']=$shengyumiao;
		
		return $cc;
	}
	//英雄猜
	function hero(){
		
		$uid=$_SESSION['user_id'];
		$gid=$_SESSION['gametypeid'];
		if($gid==2){
			$webtitle1="Dota2";
		}else{
			$webtitle1="LOL";
		}

		//英雄猜开奖
		$now_tou=$this->hero_do_kj();
		$this->assign('now_tou',$now_tou);
		//今日开奖记录
		$today=date("Ymd",time());
		$roulette_log=M("roulette_log")->where("day=".$today." and gid=".$gid."")->order("qi desc")->select();
		if($now_tou['now_cha_cishu']-$roulette_log[0]['qi']>=2){
			$daikaijiangqi=$now_tou['now_cha_cishu']-1;
		}else{
			$daikaijiangqi=$now_tou['now_cha_cishu']-1+1;
		}
		if($daikaijiangqi<10){
			$daikaijiangqi="00".$daikaijiangqi;
		}else if($daikaijiangqi>=10 && $daikaijiangqi<100){
			$daikaijiangqi="0".$daikaijiangqi;
		}else{
			$daikaijiangqi=$daikaijiangqi;
		}
		$this->assign('daikaijiangqi',$daikaijiangqi);
		foreach($roulette_log as $k=>$v){
			$hero_info=M("roulette_hero")->where("roulette_heroId=".$v['heroid']."")->find();
			$roulette_log[$k]['hero_info']=str_replace(",", "-", $hero_info['description']);
			if($gid==1){
				$roulette_log[$k]['hero_info']=trim(stristr($roulette_log[$k]['hero_info'],"-"),"-");
			}
		}
		$this->assign('roulette_log',$roulette_log);

		//var_dump( $now_tou);exit;
		
		$roulette_property_type=M('roulette_property_type')->where("IsDeleted=0 and game_typeId=".$gid."")->order("roulette_property_typeId asc")->select();
		foreach($roulette_property_type as $k=>$v){
			$roulette_property_type[$k]['roulette_property']=M("roulette_property")->where("roulette_property_typeId=".$v['roulette_property_typeId']." and IsDeleted=0")->order("peilv asc")->select();
			$roulette_property_type[$k]['num']=count($roulette_property_type[$k]['roulette_property']);
		}
		$this->assign('roulette_property_type',$roulette_property_type);
		
		$webtitle=$webtitle1."英雄猜-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//英雄猜-投注
	function hero_tz(){
		
		$uid=$_SESSION['user_id'];
		$gid=$_SESSION['gametypeid'];

		//英雄猜开奖
		//$now_tou=$this->hero_do_kj();
		
		$goldname=t(h($_POST['goldname']));
		$checksids=t(h($_POST['checksids']));
//		$checksids_arr=explode(",",$checksids);
//		foreach($checksids_arr as $k=>$v){
//			$peilv_info=M("roulette_property")->where("roulette_propertyId=".$v."")->find();
//			$peilv=floor($peilv*$peilv_info['peilv']*100)/100;
//		}
		
		$user_info=M("users")->where("userId=".$uid."")->find();

		$today=date("Ymd",time());
		$today_shi_time=strtotime($today);
		$hei_end_time=strtotime($today."02:00:00");
		$gid=$_SESSION['gametypeid'];
		//$today_shi=date("Ymd H:i:s",$today_shi_time);
		
		$cha_time=time()-$today_shi_time;
		$bai_shi_time=strtotime($today."10:00:00");
		$hei_shi_time=strtotime($today."22:00:00");
		if(time()<=$hei_end_time){
			$cha_cishu=floor((time()-$today_shi_time)/(5*60));
			$shengyumiao=5*60-floor((time()-$today_shi_time)%(5*60));
		}else if(time()>$hei_end_time && time()<=$bai_shi_time){
			$cha_cishu=24;
			$shengyumiao=$bai_shi_time-time();
		}else if(time()>$bai_shi_time && time()<=$hei_shi_time){
			$cha_cishu=24+floor((time()-$bai_shi_time)/(10*60));
			$shengyumiao=10*60-floor((time()-$bai_shi_time)%(10*60));
		}else if(time()>$hei_shi_time){
			$cha_cishu=24+72+floor((time()-$hei_shi_time)/(5*60));
			$shengyumiao=5*60-floor((time()-$hei_shi_time)%(5*60));
		}
		$now_cha_cishu=$cha_cishu+1;//正在投注的期数
		if($now_cha_cishu<10){
			$now_cha_cishu="00".$now_cha_cishu;
		}else if($now_cha_cishu>=10 && $now_cha_cishu<100){
			$now_cha_cishu="0".$now_cha_cishu;
		}else{
			$now_cha_cishu=$now_cha_cishu;
		}

		$game_type=M("game_type")->where("game_typeId=".$gid."")->find();
		
		$new['changeAmount']=-($goldname);
		$new['amount']=$user_info['golds']-($goldname);
		$new['detail']="英雄猜 ".$game_type['cnName']." 第 ".$today.$now_cha_cishu." 期 投注";
		$new['ctime']=time();
		$new['userId']=$uid;
		$new['gid']=$gid;
		$new['jieortou']=2;
		$new['herocheck']=$checksids;
		$new['herodayqi']=$today.$now_cha_cishu;
		
		$res=M("user_golds_record")->add($new);
		
		if($res){
		
			$shuizong=M("game_shuizong")->where("thisday=".$today." and state=1")->find();
			if(!empty($shuizong)){
				$shuizong['ren']=$shuizong['ren']+1;
				$shuizong['jinbi']=$shuizong['jinbi']+$goldname;
				$shuizong_res=M("game_shuizong")->save($shuizong);
			}else{
				$shuizong['ren']=$shuizong['ren']+1;
				$shuizong['jinbi']=$shuizong['jinbi']+$goldname;
				$shuizong['state']=1;
				$shuizong['ctime']=time();
				$shuizong['stime']=time();
				$shuizong['thisday']=$today;
				$shuizong_res=M("game_shuizong")->add($shuizong);				
			}

			$cc['uyue']=floor($new['amount']*100)/100;
			$cc['jieguo']="cg";
			$cc['ren']=floor($shuizong['ren']);
			$cc['jinbi']=floor($shuizong['jinbi']);
		}else{
			$cc['jieguo']="sb";
		}
		echo json_encode($cc);exit;
	}	

	
} 