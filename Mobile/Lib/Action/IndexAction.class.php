<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 首页、列表页面、详细页
 */
 
class IndexAction extends AllAction{

    function _initialize(){
		$_SESSION['user_id']=2;

		//判断登录
		if(empty($_SESSION['user_id'])){
			//$_SESSION['lyurl']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$_SESSION['lyurl']=$_SERVER['HTTP_REFERER'];
			if($_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=index&m=Index" && $_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=dld&m=Index" && $_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=dld&m=index" && $_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=art&m=index&aid=8" && $_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=myqr&m=index" ){
				$_SESSION['lyurl']="http://".$_SERVER['HTTP_HOST']."/index.php?a=games&m=Index";
			}
			header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=loginbyweixin&m=User");
			exit;	
		}else{
			//判断游戏id lol为1 dota为2
			$gametypeid_get=t(h($_GET['gtypeid']));
			if($gametypeid_get>=1){
				$_SESSION['gametypeid']=$gametypeid_get;
				M("users")->where("userId=".$_SESSION['user_id']."")->setField('gid',$_SESSION['gametypeid']);
			}else{
				if(empty($_SESSION['gametypeid'])){
					$user_info_fo_gametypeid=M("users")->where("userId=".$_SESSION['user_id']."")->find();
					if($user_info_fo_gametypeid['gid']<=0){
						header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=chtype&m=Chgame");
						exit;
					}else{
						$_SESSION['gametypeid']=$user_info_fo_gametypeid['gid'];
					}
				}
			}

			$user=M("users")->where("userId=".$_SESSION['user_id']."")->find();
			$qiyuesiri_time=strtotime("2016-07-04 00:00:00");
			//首次登录 判断
			$first_login=M("user_golds_record")->where("fromid=8 and userId=".$user['userId']."")->find();
			if(empty($first_login)){
				$first_login_gold['changeAmount']=88;
				$first_login_gold['detail']="首次登录礼包 ".$first_login_gold['changeAmount']."金币";
				$first_login_gold['fromid']=8;
				$first_login_gold['amount']=$user['golds']+$first_login_gold['changeAmount'];
				$first_login_gold['ctime']=time();
				$first_login_gold['userId']=$user['userId'];
				$first_login_gold['gid']=$_SESSION['gametypeid'];
				$frqiandaocg=M("user_golds_record")->add($first_login_gold);	
	
				$user['firsttime']=time();
				
				$this->assign('frqiandaocg', $frqiandaocg);
				
			}
			//登录 签到 判断
			$today_time=strtotime(date("Ymd",time())." 00:00:00");
			if($user['lastqdtime']<$today_time){ //1471854859
			
				$bef_yetday_qiandao=M("user_golds_record")->where("fromid=1 and ctime BETWEEN ".$user['lastqdtime']." AND ".$today_time." and userId=".$user['userId']."")->order("ctime desc")->select();
				$bef_yetday_qiandao_jlgolds=0;
				foreach($bef_yetday_qiandao as $k=>$v){
					$bef_yetday_qiandao_jlgolds=$bef_yetday_qiandao_jlgolds+abs($v['changeAmount']);
				}
				$bef_yetday_golds=M("user_golds_record")->where("fromid IN (2,3,11,12) and ctime BETWEEN ".$user['lastqdtime']." AND ".$today_time." and userId=".$user['userId']." and changeAmount<0")->order("ctime desc")->select();
				$bef_yetday_golds_liushui=0;
				foreach($bef_yetday_golds as $k=>$v){
					$bef_yetday_golds_liushui=$bef_yetday_golds_liushui+abs($v['changeAmount']);
				}
				if($bef_yetday_golds_liushui<$bef_yetday_qiandao_jlgolds){
					$jl_qingling_golds['changeAmount']=-abs($bef_yetday_qiandao_jlgolds-$bef_yetday_golds_liushui);
					$jl_qingling_golds['detail']="每日签到奖励金币清零";
					$jl_qingling_golds['fromid']=9;
					$jl_qingling_golds['amount']=$user['golds']+$first_login_gold['changeAmount']-abs($jl_qingling_golds['changeAmount']);
					$bef_yetday_time=strtotime(date("Ymd",$user['lastqdtime'])." 23:59:59");
					$jl_qingling_golds['ctime']=$bef_yetday_time;
					$jl_qingling_golds['userId']=$user['userId'];
					$jl_qingling_golds['gid']=$_SESSION['gametypeid'];
					$jl_qingling_golds['notixian']=0;
					M("user_golds_record")->add($jl_qingling_golds);	
				}
	
				$qiandao=M("user_golds_record")->where("fromid=1 and ctime>=".$today_time." and userId=".$user['userId']."")->find();
				if(empty($qiandao)){
					//判断 连续 签到天数
					$lastqdday=date("Ymd",$user['lastqdtime']);
					$qdnum=$user['qdnum'];
					$pre_day=date("Ymd",(time()-60*60*24));
					if($lastqdday<$pre_day){
						$new_qdnum=1;
					}else{
						$new_qdnum=$qdnum+1;
					}
					if($new_qdnum>=1 && $new_qdnum<3){
						$qiandao_gold['changeAmount']=30;
						$qiandao_gold['detail']="每日登录奖励";
					}else if($new_qdnum>=3 && $new_qdnum<5){
						$qiandao_gold['changeAmount']=50;
						$qiandao_gold['detail']="连续三天登录奖励";
					}else if($new_qdnum>=5){
						$qiandao_gold['changeAmount']=70;
						$qiandao_gold['detail']="连续五天登录奖励";
					}
					$qiandao_gold['fromid']=1;
					$qiandao_gold['amount']=$user['golds']+$qiandao_gold['changeAmount']+$first_login_gold['changeAmount']-abs($jl_qingling_golds['changeAmount']);
					$qiandao_gold['ctime']=time();
					$qiandao_gold['userId']=$user['userId'];
					$qiandao_gold['gid']=$_SESSION['gametypeid'];
					$qiandao_gold['notixian']=1;
					$qiandao_gold_add_res=M("user_golds_record")->add($qiandao_gold);	
					
					if($qiandao_gold_add_res){
						$user['golds']=$user['golds']+$qiandao_gold['changeAmount']+$first_login_gold['changeAmount']-abs($jl_qingling_golds['changeAmount']);
						$user['qdnum']=$new_qdnum;
						$user['lastqdtime']=time();
		
						$qiandaocg=M("users")->save($user);
						$this->assign('qiandaocg', $qiandaocg);
						$this->assign('new_qdnum', $new_qdnum);
					}
					
				}
			}

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

		//紧急通告显示
		$bigmess=M("big_mess")->order("id desc")->find();
		$bigmess_rec=M("big_mess_rec")->where("uid=".$_SESSION['user_id']." and mid=".$bigmess['id']."")->find();
		if(empty($bigmess_rec)){
			$this->assign('bigmess',$bigmess);
		}

	}
	
	function hidebigmess(){
		$mid=t(h($_POST['mid']));
		$uid=$_SESSION['user_id'];
		$bigmess_rec['mid']=$mid;
		$bigmess_rec['uid']=$uid;
		$bigmess_rec_add=M("big_mess_rec")->add($bigmess_rec);
		echo $bigmess_rec_add;
		exit;
	}
	//充值提示
	//TM00006
	// @param $golds 充值金额 rmb
	//        $userId 充值用户id
	function sendTemplatePaid($golds,$userId)
	{
		//$golds = 600;
		//$userId = 172;
		import("@.ORG.weixin");
		$options = array(
			'token'=>'weixin', //填写你设定的ke
			'encodingaeskey'=>'Sk9YaEQNrTEeMC2hdurQjQFRlG7WLzLtBrslzBKdkO2', //填写加密用的EncodingAESKey
			'appid'=>'wxab899c981736ec18', //填写高级调用功能的app id, 请在微信开发模式后台查询
			'appsecret'=>'7d18567f174fd042958daa084836114d',
		);
		$userInfo = M('users')->field('openId,name')->where('userId = '.$userId)->find();
		$userOpenId = $userInfo['openId'];
		$username = $userInfo['name'];
		$weObj = new Wechat($options);
		$weObj->checkAuth();
		// var_dump($access_token); 
		// $data['access_token'] = $access_token;
		$data = array(
			'touser' => $userOpenId,
			'template_id' => 'CkmtDTW0PKu0RzS2lMDdKkMHKGQs0h910CbBmV1T4Ng',
			'url' =>'http://wap.esports666.com/index.php?a=index&m=index',
			'topcolor'=> '#1f262a',
			'data'=>array(
				'result'=>array(
					'value'=>'您好，您已成功进行充值！',
					'color'=>''
					),
				'accountType'=>array(
					'value'=>'昵称',
					"color"=>"#173177"
					),
				'account'=>array(
					'value'=>$username,
					"color"=>"#173177"
					),
				'amount'=>array(
					'value'=>$golds,
					"color"=>"#173177"
					),
				'result'=>array(
					'value'=>'充值成功',
					"color"=>"#173177"
					),
				'remark'=>array(
					'value'=>'如有疑问，请加Q群联系管理 lol:528279088 dota:295416108',
					"color"=>"#173177"
					)
				)
			);
			$re = $weObj->sendTemplateMessage($data);
			return $re;
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
		
		$list_zhubo=M('game_ju')->where("gid=".$gametypeid." and starttime<=".time()." and endtime>=".time()." and state=1 and fenlei=1")->order("displayOrder asc,id desc")->select();
		foreach($list_zhubo as $k=>$v){
			if(!in_array($v['gameid'],$list_zhubo_gameid_arr)){
				$list_zhubo_gameid_arr[]=$v['gameid'];
				$list_zhubo_game=M("games")->where("gameId=".$v['gameid']."")->find();
				$list_zhubos_li=M("teams")->where("teamId=".$list_zhubo_game['team1_teamId']."")->find();
				$list_zhubos_li['pt']=M("ptlogo")->where("id=".$list_zhubo_game['ptid']."")->find();
				$list_zhubos_li['gameid']=$list_zhubo_game['gameId'];
				$list_zhubos[]=$list_zhubos_li;
			}
		}
		$this->assign('list_zhubos', $list_zhubos);
		//var_dump($list_zhubo_game);
		$list=M('game_ju')->where("gid=".$gametypeid." and cstate<=2 and wincheck<=0 and state=1 and fenlei=2")->order("ktime asc,id desc")->select();
		$gamekey=0;
		foreach($list as $k=>$v){
			if(!in_array($v['gameid'],$list_gameid_arr)){
				$list_gameid_arr[$gamekey]=$v['gameid'];
				$game=M("games")->where("gameId=".$v['gameid']."")->find();
				$list_game[$gamekey]=$game;
				$gamekey++;
			}


		}
		foreach($list_game as $k=>$v){
			$list_game[$k]["gamejus"]=M('game_ju')->where("gid=".$gametypeid." and gameid=".$v['gameId']." and  cstate<=2 and wincheck<=0 and state=1 and fenlei=2")->order("displayOrder asc,id desc")->select();
			foreach($list_game[$k]["gamejus"] as $kk=>$vv){
				$cname=M("game_cstate")->where("id=".$vv['cstate']."")->find();
				$list_game[$k]["gamejus"][$kk]['cstatename']=$cname['name'];	
						
				
				//$real_zong_ren=$real_zong_ren+$v['cren1']+$v['cren2'];
				$real_zong_jinbi=$real_zong_jinbi+$vv['cjinbi1']+$vv['cjinbi2'];
				
				if($vv['cjinbi1']<=0){
					$peilv1=0.95;
				}else{
					$peilv1=floor((($vv['cjinbi1']+($vv['cjinbi2']*0.9))/$vv['cjinbi1'])*100)/100;
				}
				if($vv['cjinbi2']<=0){
					$peilv2=0.95;
				}else{
					$peilv2=floor((($vv['cjinbi2']+($vv['cjinbi1']*0.9))/$vv['cjinbi2'])*100)/100;
				}
				
				$list_game[$k]["gamejus"][$kk]['peilv1']=$peilv1;
				$list_game[$k]["gamejus"][$kk]['peilv2']=$peilv2;
				
			}
			
			$first_ju=M('game_ju')->where("gid=".$gametypeid." and gameid=".$v['gameId']." and wincheck<=0 and  state=1 and fenlei=2 and cstate=1 and ktime>".time()."")->order("ktime asc")->find();
			if(!empty($first_ju)){
				$list_game[$k]['start_time']=$first_ju['ktime'];
				$list_game[$k]['cstate']=1;
				$cname=M("game_cstate")->where("id=1")->find();
				$list_game[$k]['cstatename']=$cname['name'];	
				
			}else{
				$jingcai_ju=M('game_ju')->where("gid=".$gametypeid." and gameid=".$v['gameId']." and wincheck<=0 and  state=1 and fenlei=2 and cstate=1 and ktime<".time()."")->find();
				if(!empty($jingcai_ju)){
					$list_game[$k]['start_time']=$first_ju['ktime'];
					$list_game[$k]['cstate']=1;
					$cname=M("game_cstate")->where("id=1")->find();
					$list_game[$k]['cstatename']=$cname['name'];	
				}else{
				
					$fengpan_ju=M('game_ju')->where("gid=".$gametypeid." and gameid=".$v['gameId']." and wincheck<=0 and  state=1 and fenlei=2 and cstate<3 and ktime<".time()."")->find();
					if(!empty($fengpan_ju)){
						$list_game[$k]['cstate']=2;
						$cname=M("game_cstate")->where("id=2")->find();
						$list_game[$k]['cstatename']=$cname['name'];	
					}else{
						$list_game[$k]['cstate']=3;
						$cname=M("game_cstate")->where("id=3")->find();
						$list_game[$k]['cstatename']=$cname['name'];	
						//$list_game[$k]['win1count']=M('game_ju')->where("gid=".$gametypeid." and gameid=".$v['gameId']." and state=1 and fenlei=2 and cstate=3 and wincheck=1")->count();
						//$list_game[$k]['win2count']=M('game_ju')->where("gid=".$gametypeid." and gameid=".$v['gameId']." and state=1 and fenlei=2 and cstate=3 and wincheck=2")->count();
					}
				}
			}
			
			$team1=M("teams")->where("teamId=".$v['team1_teamId']."")->find();
			$list_game[$k]['team1']=$team1;
			$team2=M("teams")->where("teamId=".$v['team2_teamId']."")->find();
			$list_game[$k]['team2']=$team2;
		}
		$this->assign('list_game', $list_game);
		
		$list_game_jie=M("games")->where("state=1 and fenlei=2 and game_typeId=".$gametypeid." and bifen!=''")->order("ktime desc,gameId desc")->limit("0,5")->select();
		//var_dump($list_game_jie);exit;
		foreach($list_game_jie as $k=>$v){
			$list_game_jie[$k]["gamejus"]=M('game_ju')->where("gid=".$gametypeid." and gameid=".$v['gameId']." and state=1 and fenlei=2")->order("displayOrder asc,id desc")->select();
			foreach($list_game_jie[$k]["gamejus"] as $kk=>$vv){
				$cname=M("game_cstate")->where("id=".$vv['cstate']."")->find();
				$list_game_jie[$k]["gamejus"][$kk]['cstatename']=$cname['name'];
						
				
				//$real_zong_ren=$real_zong_ren+$v['cren1']+$v['cren2'];
				$real_zong_jinbi=$real_zong_jinbi+$vv['cjinbi1']+$vv['cjinbi2'];
				
				if($vv['cjinbi1']<=0){
					$peilv1=0.95;
				}else{
					$peilv1=floor((($vv['cjinbi1']+($vv['cjinbi2']*0.9))/$vv['cjinbi1'])*100)/100;
				}
				if($vv['cjinbi2']<=0){
					$peilv2=0.95;
				}else{
					$peilv2=floor((($vv['cjinbi2']+($vv['cjinbi1']*0.9))/$vv['cjinbi2'])*100)/100;
				}
				
				$list_game_jie[$k]["gamejus"][$kk]['peilv1']=$peilv1;
				$list_game_jie[$k]["gamejus"][$kk]['peilv2']=$peilv2;
				
			}
			
			$list_game_jie[$k]['cstate']=3;
			$cname=M("game_cstate")->where("id=3")->find();
			$list_game_jie[$k]['cstatename']=$cname['name'];	
			
			$team1=M("teams")->where("teamId=".$v['team1_teamId']."")->find();
			$list_game_jie[$k]['team1']=$team1;
			$team2=M("teams")->where("teamId=".$v['team2_teamId']."")->find();
			$list_game_jie[$k]['team2']=$team2;
		}
		$this->assign('list_game_jie', $list_game_jie);
		$webtitle="竞猜-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		//$this->assign('zixun',$this->getArticles());
		$this->display();
	}
	function more_game_jie(){
		$gametypeid=$_SESSION['gametypeid'];
		$nowpage=t(h($_POST["nowpage"]));
		$start=$nowpage*5;
		$list_game_jie=M("games")->where("state=1 and fenlei=2 and game_typeId=".$gametypeid." and bifen!=''")->order("ktime desc,gameId desc")->limit("".$start.",5")->select();
		//var_dump($list_game_jie);exit;
		foreach($list_game_jie as $k=>$v){
			$list_game_jie[$k]["gamejus"]=M('game_ju')->where("gid=".$gametypeid." and gameid=".$v['gameId']." and state=1 and fenlei=2")->order("displayOrder asc,id desc")->select();
			
			$list_game_jie[$k]['cstate']=3;
			$cname=M("game_cstate")->where("id=3")->find();
			$list_game_jie[$k]['cstatename']=$cname['name'];	
			
			$team1=M("teams")->where("teamId=".$v['team1_teamId']."")->find();
			$list_game_jie[$k]['team1']=$team1;
			$team2=M("teams")->where("teamId=".$v['team2_teamId']."")->find();
			$list_game_jie[$k]['team2']=$team2;
			
			$ccc.="
				<a href=\"javascript:;\" onclick=\"show_pan(".$v['gameId'].",this)\">
					<div class=\"bet1\">
						<div class=\"logo1\">
							<img src=\"".$list_game_jie[$k]['team1']['avatar']."\" alt=\"".$list_game_jie[$k]['team1']['name']."\">
							<div class=\"tname\">
								".$list_game_jie[$k]['team1']['name']."
							</div>
						</div>	
						<div class=\"logomid\">
							<div class=\"logomid_li2\">
								<span class=\"logomid_li2_bf\" >".$v['bifen']."</span>
							</div>
							<div class=\"logomid_li3\">
							".$v['name']."
							</div>
						</div>
						<div class=\"logo2\">
							<img src=\"".$list_game_jie[$k]['team2']['avatar']."\" alt=\"".$list_game_jie[$k]['team2']['name']."\">
							<div class=\"tname\">
								".$list_game_jie[$k]['team2']['name']."
							</div>
						</div>
						
						<div class=\"clear\"></div>
					</div>
				</a>
				<div class=\"bet_pan bet_pan".$v['gameId']."\">
			";

			foreach($list_game_jie[$k]["gamejus"] as $kk=>$vv){
				$cname=M("game_cstate")->where("id=".$vv['cstate']."")->find();
				$list_game_jie[$k]["gamejus"][$kk]['cstatename']=$cname['name'];
						
				$real_zong_jinbi=$real_zong_jinbi+$vv['cjinbi1']+$vv['cjinbi2'];
				
				if($vv['cjinbi1']<=0){
					$peilv1=0.95;
				}else{
					$peilv1=floor((($vv['cjinbi1']+($vv['cjinbi2']*0.9))/$vv['cjinbi1'])*100)/100;
				}
				if($vv['cjinbi2']<=0){
					$peilv2=0.95;
				}else{
					$peilv2=floor((($vv['cjinbi2']+($vv['cjinbi1']*0.9))/$vv['cjinbi2'])*100)/100;
				}
				
				$list_game_jie[$k]["gamejus"][$kk]['peilv1']=$peilv1;
				$list_game_jie[$k]["gamejus"][$kk]['peilv2']=$peilv2;
				if($vv['wincheck']==1){
					$ccc.="
						<div class=\"bet_pan_li\">
							<div class=\"bet_pan_peilv\">
								<div class=\"bet_pan_peilv_win win_icon1\">
								胜
								</div>
								<span class=\"bet_pan_peilv1_".$vv['id']."\">".$peilv1."</span><br />".$vv['check1']."
							</div>
							<div class=\"bet_pan_zt\">
								<div class=\"bet_pan_zt_li yjszt\">
									<div class=\"bet_pan_zt_li_span1\">
									".$vv['cname']."
									</div>
									<div class=\"bet_pan_zt_li_span2\">
									".$list_game_jie[$k]["gamejus"][$kk]['cstatename']."
									</div>
								</div>
							</div>
							<div class=\"bet_pan_peilv\">
								<span class=\"bet_pan_peilv2_".$vv['id']."\">".$peilv2."</span><br />".$vv['check2']."
							</div>
							<div class=\"clear\"></div>
						</div>
					";
				}else{
					$ccc.="
						<div class=\"bet_pan_li\">
							<div class=\"bet_pan_peilv\">
								<span class=\"bet_pan_peilv1_".$vv['id']."\">".$peilv1."</span><br />".$vv['check1']."
							</div>
							<div class=\"bet_pan_zt\">
								<div class=\"bet_pan_zt_li yjszt\">
									<div class=\"bet_pan_zt_li_span1\">
									".$vv['cname']."
									</div>
									<div class=\"bet_pan_zt_li_span2\">
									".$list_game_jie[$k]["gamejus"][$kk]['cstatename']."
									</div>
								</div>
							</div>
							<div class=\"bet_pan_peilv\">
								<div class=\"bet_pan_peilv_win win_icon2\">
								胜
								</div>
								<span class=\"bet_pan_peilv2_".$vv['id']."\">".$peilv2."</span><br />".$vv['check2']."
							</div>
							<div class=\"clear\"></div>
						</div>
					";
				}
				
			}
			$ccc.="
				</div>
			";
			
		}
		$aa['ccc']=$ccc;
		$aa['nextpage']=$nowpage+1;
		echo  json_encode($aa);
	}
	//主播竞猜 二级页
	function indexx(){
		$gametypeid=$_SESSION['gametypeid'];
		$gameid=t(h($_GET['gameid']));;
		$list_zhubo_game=M("games")->where("gameId=".$gameid."")->find();
		$list_zhubo_game['zhubo_info']=M("teams")->where("teamId=".$list_zhubo_game['team1_teamId']."")->find();
		$list_zhubo_game['pt']=M("ptlogo")->where("id=".$list_zhubo_game['ptid']."")->find();
		$this->assign('list_zhubo_game', $list_zhubo_game);

		//var_dump($list_zhubo_game);
		$list=M('game_ju')->where("gameid=".$gameid." and starttime<=".time()." and endtime>=".time()." and state=1")->order("displayOrder asc,id desc")->select();
		foreach($list as $k=>$v){
			$cname=M("game_cstate")->where("id=".$v['cstate']."")->find();
			$list[$k]['cstatename']=$cname['name'];	
			
			$real_zong_ren=$real_zong_ren+$v['cren1']+$v['cren2'];
			$real_zong_jinbi=$real_zong_jinbi+$v['cjinbi1']+$v['cjinbi2'];
			
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
			
			$list[$k]['peilv1']=$peilv1;
			$list[$k]['peilv2']=$peilv2;

		}
		$this->assign('list', $list);
		$webtitle="竞猜-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

 	//竞猜 投注
	function jctz(){

		$id=t(h($_POST['id']));
		$addgoldnum=floor(t(h($_POST['addgoldnum'])));
		$checkid=t(h($_POST['checkid']));
		$userid=$_SESSION['user_id'];
		$gametypeid=$_SESSION['gametypeid'];
		if($addgoldnum<1){
			$cc['jieguo']="no1";
			echo json_encode($cc);exit;
		}
		
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
		if($user_info['golds']<$addgoldnum){
			$cc['jieguo']="yuebuzu";
			$cc['user_yue']=floor($user_info['golds']);
			echo json_encode($cc);exit;
		}
		if($user_info['invite_by_userId']>0){
			//查询有没有参与过竞猜
			$zy_ly_user=M("users")->where("userId=".$user_info['invite_by_userId']."")->find();
			
			$shifoujc=M("user_golds_record")->where("userId=".$userid." and fromid IN (2,3)")->find();
//			$today_cs_time=strtotime(date("Y-m-d",time())." 00:00:00");
//			$user_zhaomu_today_cishu=M("user_golds_record")->where("userId=".$zy_ly_user['userId']." and fromid=10 and ctime>=".$today_cs_time." ")->count();
//			if(empty($shifoujc) && $user_zhaomu_today_cishu<5){
			if(empty($shifoujc)){
//				$zy_ziji['changeAmount']=88;
//				$zy_ziji['amount']=$user_info['golds']+88;
//				$zy_ziji['ctime']=time();
//				$zy_ziji['userId']=$userid;
//				$zy_ziji['gid']=$gametypeid;
//				$zy_ziji['fromid']=10;
//				$zy_ziji['detail']="战友招募 首次竞猜奖励";
//				M("user_golds_record")->add($zy_ziji);
				
				$zy_laiyuan['changeAmount']=88;
				$zy_laiyuan['amount']=$zy_ly_user['golds']+$zy_laiyuan['changeAmount'];
				$zy_laiyuan['ctime']=time();
				$zy_laiyuan['userId']=$zy_ly_user['userId'];
				$zy_laiyuan['gid']=$gametypeid;
				$zy_laiyuan['fromid']=10;
				$zy_laiyuan['recomid']=$userid;
				$zy_laiyuan['detail']="战友招募 首次竞猜奖励";
				$add_zy_laiyuan=M("user_golds_record")->add($zy_laiyuan);
			}
			
			$zy_laiyuan_ticheng['changeAmount']=$addgoldnum*0.03;
			$zy_laiyuan_ticheng['amount']=$zy_ly_user['golds']+$zy_laiyuan['changeAmount']+$zy_laiyuan_ticheng['changeAmount'];
			$zy_laiyuan_ticheng['ctime']=time();
			$zy_laiyuan_ticheng['userId']=$zy_ly_user['userId'];
			$zy_laiyuan_ticheng['gid']=$gametypeid;
			$zy_laiyuan_ticheng['fromid']=6;
			$zy_laiyuan_ticheng['recomid']=$userid;
			$zy_laiyuan_ticheng['detail']="战友招募奖励";
			$add_zy_laiyuan_ticheng=M("user_golds_record")->add($zy_laiyuan_ticheng);
			
			if($add_zy_laiyuan_ticheng){
				$zy_ly_user['golds']=$zy_laiyuan_ticheng['amount'];
				$laiyuan_user_save=M("users")->save($zy_ly_user);
			}
		}
		
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
		$new['fromid']=2;

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
		//是否首充
		$sf_sc=M("user_golds_record")->where("userId=".$uid." and fromid=4 and changeAmount>=1000")->find();
		$this->assign('sf_sc',$sf_sc);
		//已赚金币数
		$yiz_golds_list=M("user_golds_record")->field("changeAmount")->where("userId=".$uid." and fromid IN (2,3,6,10,11,12,14) and changeAmount>0")->select();
		$yiz_golds=0;
		foreach($yiz_golds_list as $k=>$v){
			$yiz_golds=$yiz_golds+$v['changeAmount'];
		}
		$yiz_golds=floor($yiz_golds);
		$this->assign('yiz_golds',$yiz_golds);
		$webtitle="个人中心-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	//切换游戏页
	function chtype(){
		$webtitle="切换游戏-玩贝电竞";
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
		$webtitle="金币明细-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	//竞猜历史消息
	function myquiz(){
		//英雄猜开奖
//		$now_tou=$this->hero_do_kj();


		$uid=$_SESSION['user_id'];
		$list=M("user_golds_record")->where("userId=".$uid." and fid=1 and jieortou=2")->order("ctime desc,user_golds_recordId desc")->select();
		foreach($list as $k=>$v){
			$list[$k]['changeAmount']=abs(floor($v['changeAmount']*100)/100);
			
			$game_info=M('game_ju')->where("id=".$v['panid']."")->find();
			$list[$k]['cname']=$game_info['cname'];
			if($v['checkx']==1){
				$list[$k]['checkname']=$game_info['check1'];
				$peilv1=floor((($game_info['cjinbi1']+($game_info['cjinbi2']*0.9))/$game_info['cjinbi1'])*100)/100;
				$list[$k]['peilv']=$peilv1;
			}else{
				$list[$k]['checkname']=$game_info['check2'];
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
					if($game_info['cstate']==4){
						$list[$k]['jieguo']="流盘 ".$game_info['lpyin']."";
					}else{
						$list[$k]['jieguo']="成功";
					}
					$list[$k]['jieguoid']=1;
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
			if($v['checkx']==1){
				$list_bs[$k]['checkname']=$game_info['check1'];
				$peilv1=floor((($game_info['cjinbi1']+($game_info['cjinbi2']*0.9))/$game_info['cjinbi1'])*100)/100;
				$list_bs[$k]['peilv']=$peilv1;
			}else{
				$list_bs[$k]['checkname']=$game_info['check2'];
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
					if($game_info['cstate']==4){
						$list_bs[$k]['jieguo']="流盘 ".$game_info['lpyin']."";
					}else{
						$list_bs[$k]['jieguo']="成功";
					}
					$list_bs[$k]['jieguoid']=1;
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
			$checkname="";
			foreach($checksids_arr as $kk=>$vv){
				$property_info=M("roulette_property")->where("roulette_propertyId=".$vv."")->find();
				$checkname=$checkname.",".$property_info['name'];
			}
			$peilv_info=M("roulette_peilv")->where("num='".$v['herocheck']."'")->find();
			$peilv=$peilv_info['peilv'];
			$list_yxc[$k]['checkname']=trim($checkname,",");
			$list_yxc[$k]['peilv']=$peilv;

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
		//大乱斗 历史消息
		$list_dld=M("user_golds_record")->where("userId=".$uid." and changeAmount<0 and dldid>=1")->order("ctime desc,user_golds_recordId desc")->select();
		foreach($list_dld as $k=>$v){
			$list_dld[$k]['changeAmount']=abs(floor($v['changeAmount']*100)/100);
			$list_dld[$k]['dld']=M("dld")->where("id=".$v['dldid']."")->find();
			if($list_dld[$k]['dld']['uid']<=0){
				if($list_dld[$k]['dld']['ktime']<=time()){
					$kj=$this->dld_kj($v['dldid']);
					if($kj){
						header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=myquiz&m=Index");
						exit;
					}

				}
				$list_dld[$k]['jieguo']="未知";
			}else{
				if($list_dld[$k]['dld']['uid']==$v['userId']){
					$list_dld[$k]['jieguoid']=1;
					$list_dld[$k]['jieguo']="成功";
					$list_dld[$k]['jgolds']="+".abs(floor($list_dld[$k]['dld']['yjine']*100)/100)."金币";
				}else{
					$list_dld[$k]['jieguoid']=2;
					$list_dld[$k]['jieguo']="失败";
					$list_dld[$k]['jgolds']="-".$list_dld[$k]['changeAmount']."金币";
				}
			}
			$games=M("game_type")->where("game_typeId=".$v['gid']."")->find();
			$list_dld[$k]['gamename']=$games['cnName'];

		}
		$this->assign('list_dld',$list_dld);

		$webtitle="竞猜历史消息-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	//金币兑换
	function tobuy(){
		$webtitle="金币兑换-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	
	//金币兑换-确认兑换
	function tobuygo(){
		$uid=t(h($_SESSION['user_id']));
		$user_info=M("users")->where("userId=".$uid."")->find();
		$this->assign('user_info',$user_info);
		
		$webtitle="确认兑换-金币兑换-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	function dotobuygo(){
		$uid=t(h($_SESSION['user_id']));
		$bnum=t(h($_POST['bnum']));
		$username=t(h($_POST['username']));
		$tel=t(h($_POST['tel']));
		$realname=t(h($_POST['realname']));
		if(empty($username)){
			echo "nousername";exit;
		}
		if(empty($realname)){
			echo "norealname";exit;
		}
		if(empty($tel)){
			echo "notel";exit;
		}
        if($bnum>100){
            $bgolds=floor($bnum*110);
        }else if($bnum==100){
			$bgolds=11000;
		}else{
			$bgolds=5700;
		}
		$user_info=M("users")->where("userId=".$uid."")->find();
		
		$new['changeAmount']=-$bgolds;
		$new['amount']=$user_info['golds']-$bgolds;
		if($new['amount']<0){
			echo "yuebuzu";exit;
		}
		$new['detail']="金币兑换 ".$bnum."元 ".$realname."";
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
			if(empty($user_info['realname'])){
				$user_info['realname']=$realname;
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
		$uid=$_SESSION['user_id'];
		$appid = "wxab899c981736ec18";
		$appsecret = "7d18567f174fd042958daa084836114d";

//		if (isset($_GET['code'])){
//		
//			$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code";
//			$res = $this->https_request($url);
//			$res=(json_decode($res, true));
//			
//			$this->access_token = $res["access_token"];
//			$row=$this->get_jsapi_ticket();
//			
//			$jsapi_ticket=$row['ticket'];
//			
//			$cc['appId']=$appid;
//			$cc['timestamp']=time();
//			$cc['nonceStr']="wxab899123asdlkjij";
//			$cc['signature']=sha1("jsapi_ticket=".$jsapi_ticket."&noncestr=".$cc['nonceStr']."&timestamp=".$cc['timestamp']."&url=http://wap.esports666.com/index.php?a=topay&m=index");
//	
//			$this->assign('cc',$cc);
//			
//		}else{
//			$appid = "wxab899c981736ec18";
//			$appsecret = "7d18567f174fd042958daa084836114d";
//	
//			$url_code="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=http%3a%2f%2f".$_SERVER['HTTP_HOST']."%2findex.php%3fa%3dtopay%26m%3dindex&response_type=code&scope=snsapi_base&state=type";
//			header("Location: ".$url_code."");
//			
//			exit;
//		}
		//是否首充
		$sf_sc=M("user_golds_record")->where("userId=".$uid." and fromid=4 and changeAmount>=1000")->find();
		$this->assign('sf_sc',$sf_sc);
		
		$webtitle="金币充值-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	function dotopay(){
		$paytag=t(h($_GET['paytag']));
		//$yzcode=t(h($_GET['yzcode']));
		$user_id=$_SESSION['user_id'];
		$tpnum=$_SESSION['tpnum']/100;
		$ddid=t(h($_GET['ddid']));
		//$tpnum=1/100;
		if($paytag==md5($_SESSION['user_id'].$_SESSION['tpnum']) && !empty($ddid)){
			if($tpnum>0 && $tpnum<1 ){
				$golds=round(abs($tpnum*100));
				$s_golds=0;
			}else if($tpnum>=1 && $tpnum<20){
				$golds=round(abs($tpnum*100));
				$s_golds=round(abs($tpnum*5));
			}else if($tpnum>=20){
				$golds=round(abs($tpnum*100));
				$s_golds=round(abs($tpnum*10));
			}else{
				echo "充值金额要大于0";exit;
				header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=topay&m=Index");
				exit;
			}
			$old_record=M("user_golds_record")->where("ddid='".$ddid."'")->find();
			if(!empty($old_record)){
				echo "已充值成功";
				header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=topay&m=Index");
				exit;
			}else{
				$user_info=M("users")->where("userId=".$user_id."")->find();
				//是否首充
				$sf_sc=M("user_golds_record")->where("userId=".$user_id." and fromid=4 and changeAmount>=1000")->find();
				
				$chongzhi['fromid']=4;
				$chongzhi['changeAmount']=$golds;
				$chongzhi['amount']=$user_info['golds']+$chongzhi['changeAmount'];
				$chongzhi['detail']="充值".$tpnum."元";
				$chongzhi['ctime']=time();
				$chongzhi['userId']=$user_id;
				$chongzhi['ddid']=$ddid;
				$chongzhi_res=M("user_golds_record")->add($chongzhi);
				if($s_golds>0){
					$song['fromid']=5;
					$song['changeAmount']=$s_golds;
					$song['amount']=$chongzhi['amount']+$song['changeAmount'];
					$song['detail']="充值".$tpnum."元 赠送";
					$song['ctime']=time();
					$song['userId']=$user_id;
					$song['ddid']=$ddid;
					$song_res=M("user_golds_record")->add($song);
				}
				if(empty($sf_sc) && $golds>=1000){
					$sc_jl['fromid']=13;
					$sc_jl['changeAmount']=200;
					$sc_jl['amount']=$chongzhi['amount']+$song['changeAmount']+$sc_jl['changeAmount'];
					$sc_jl['detail']="首充礼包";
					$sc_jl['ctime']=time();
					$sc_jl['userId']=$user_id;
					$sc_jl['ddid']=$ddid;
					$sc_jl_res=M("user_golds_record")->add($sc_jl);
				}

				$user_info['golds']=$user_info['golds']+$golds+$s_golds+$sc_jl['changeAmount'];
				$user_info_res=M("users")->save($user_info);
				$this->sendTemplatePaid($tpnum,$user_id);
				if($chongzhi_res && $user_info_res){
					header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=topay&m=Index");
				}
			}
			
		}else{
			echo "充值失败";exit;
			header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=topay&m=Index");
			exit;
		}
		
	}

    function dotopay_session(){
		$tpnum=t(h($_POST['topaymoney']));
		if($tpnum>0){
			$tpnum=$tpnum;
		}else{
			$tpnum=10;
		}
		$_SESSION['tpnum']=$tpnum*100;
		$_SESSION['ddid']=date("YmdHis",time())."_".$_SESSION['user_id']."_".rand(1000,9999);
        $tp['tpnum']=$tpnum;
        $tp['ddid']=$_SESSION['ddid'];
        $tp['uid']=$_SESSION['user_id'];

        echo json_encode($tp);exit;
	}
	function dotowxpay(){
//		$tpnum=t(h($_GET['tpnum']));
//		if($tpnum==20){
//			$tpnum=20;
//		}else if($tpnum==50){
//			$tpnum=50;
//		}else if($tpnum==100){
//			$tpnum=100;
//		}else if($tpnum==200){
//			$tpnum=200;
//		}else if($tpnum>0){
//			$tpnum=$tpnum;
//		}else{
//			$tpnum=10;
//		}
		$tpnum=$_SESSION['tpnum'];
		require_once "./wxpay/lib/WxPay.Api.php";
		require_once "./wxpay/example/WxPay.JsApiPay.php";
		//var_dump($tpnum);
		require_once './wxpay/example/log.php';
		
		//初始化日志
		$logHandler= new CLogFileHandler("./wxpay/logs/".date('Y-m-d').'.log');
		$log = Log::Init($logHandler, 15);
		
		
		//①、获取用户openid
		$tools = new JsApiPay();
		$openId = $tools->GetOpenid();
		
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee($_SESSION['tpnum']);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://wap.esports666.com/index.php?a=dotopay&m=index");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
		foreach($order as $key=>$value){
			echo "<font color='#00ff55;'>$key</font> : $value <br/>";
		}
		$jsApiParameters = $tools->GetJsApiParameters($order);
		
		//获取共享收货地址js函数参数
		//$editAddress = $tools->GetEditAddressParameters();
		
		$this->assign('jsApiParameters',$jsApiParameters);
		//$this->assign('editAddress',$editAddress);
		$this->assign('tpnum',$tpnum);
		
		$webtitle="确认支付-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	function getwxpay_info(){
		$appid = "wxab899c981736ec18";
		$appsecret = "7d18567f174fd042958daa084836114d";

		$url_code="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=http%3a%2f%2f".$_SERVER['HTTP_HOST']."%2findex.php%3fa%3dtopay%26m%3dindex&response_type=code&scope=snsapi_base&state=type";
		header("Location: ".$url_code."");
		
		exit;

		$res_code= $this->https_request($url_code);
		$res_code=(json_decode($res_code, true));
		
		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code";
		$res = $this->https_request($url);
		$res=(json_decode($res, true));
		
		$this->access_token = $res["access_token"];

		$cc['appId']=$appid;
		$cc['timestamp']=time();
		$cc['nonceStr']="wxab899123asdlkjij";
		$cc['signature']="wxab899c981736ec18";

		echo json_encode($cc);exit;
	}
	function get_jsapi_ticket()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$this->access_token."&type=jsapi";
        $res = $this->https_request($url);
        return json_decode($res, true);
    }
	function getcode(){
		$appid = "wxab899c981736ec18";
		$appsecret = "7d18567f174fd042958daa084836114d";

		if (isset($_GET['code'])){
		
			$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code";
			$res = $this->https_request($url);
			$res=(json_decode($res, true));
			
			$this->access_token = $res["access_token"];
			$row=$this->get_jsapi_ticket();
			
			$jsapi_ticket=$row['ticket'];
			
			$cc['appId']=$appid;
			$cc['timestamp']=time();
			$cc['nonceStr']="wxab899123asdlkjij";
			$cc['signature']=sha1("jsapi_ticket=".$jsapi_ticket."&noncestr=".$cc['nonceStr']."&timestamp=".$cc['timestamp']."&url=http://wap.esports666.com/index.php?a=topay&m=index");
	
			echo json_encode($cc);exit;
		}
	}
	function https_request($url, $data = null)//微信登录https请求
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
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
		
		$webtitle="战友招募-玩贝电竞";
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
			$user_recoms[$k]['recomgolds']=floor($user_recoms[$k]['recomgolds']*100)/100;
		}

		$this->assign('user_recoms',$user_recoms);
		
		$webtitle="我的战友-战友招募-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	function http_post_json($url, $jsonStr)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8',
			'Content-Length: ' . strlen($jsonStr)
			)
		);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return array($httpCode, $response);
	}      
	//战友招募-我的二维码
	function myqr(){
		$uid=$_SESSION['user_id'];
		$user_info=M("users")->where("userId=".$uid."")->find();
		$qrcjiangetime=time()-$user_info['qrctime'];
		if($qrcjiangetime>(60*60*24*4) || empty($user_info['qr_ticket']) || empty($user_info['qr_img'])){
		//if(1){
			$wx_token=M("wx_token")->where("id=1")->find();
			$time_jiange=time()-$wx_token['ctime'];
			$wx_token_val=$_SESSION['wx_token_val'];
			$wx_token_time=$_SESSION['wx_token_time'];
//			if(empty($wx_token) || $time_jiange>7200){
//				$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$wx_token['appid']."&secret=".$wx_token['appsecret']."";
//				$res = $this->https_request($url);
//				$res=(json_decode($res, true));
//				$wx_token['token']=$res["access_token"];
//				$wx_token['ctime']=time();
//				M("wx_token")->save($wx_token);
//				
//			}
			//if(empty($wx_token['token']) || $time_jiange>=7200){
			//if(1){
//				$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$wx_token['appid']."&secret=".$wx_token['appsecret']."";
//				$res = $this->https_request($url);
//				$res=(json_decode($res, true));
//				$wx_token['token']=$res["access_token"];
//				$wx_token['ctime']=time();
//				M("wx_token")->save($wx_token);
				import("@.ORG.weixin");		 
				//$this->appid=$wx_token['appid'];
				//$this->appsecret=$wx_token['appsecret'];
				$options = array(
					'token'=>'weixin', //填写你设定的ke
					'encodingaeskey'=>'Sk9YaEQNrTEeMC2hdurQjQFRlG7WLzLtBrslzBKdkO2', //填写加密用的EncodingAESKey
					'appid'=>'wxab899c981736ec18', //填写高级调用功能的app id, 请在微信开发模式后台查询
					'appsecret'=>'7d18567f174fd042958daa084836114d'
				);
				$weObj = new Wechat($options);
				$res=$weObj->checkAuth();
				$wx_token['token']=$res;
				$wx_token['ctime']=time();
				M("wx_token")->save($wx_token);
				//$_SESSION['wx_token_val']=$res["access_token"];
				
			//}

			$urls = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$wx_token['token']."";//POST指向的链接      
			$datas = array(      
				'expire_seconds'=>604800,     
				'action_name'=>'QR_SCENE',     
				'action_info'=>array(
					'scene'=>array(
						'scene_id'=>$uid
					)
				)     
			);    
			$datas = json_encode($datas,true);
	
			list($returnCode, $returnContent)=$this->http_post_json($urls, $datas); 

			$returnContent = json_decode($returnContent); 
			$user_info['qr_ticket']=$returnContent->ticket;
//			var_dump($returnContent);	
//			var_dump($returnContent);	
//			var_dump($returnContent);	
//			var_dump($returnContent);	
			
			$urltu="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($user_info['qr_ticket'])."";

			$user_info['qr_url']=$returnContent->url;
			
			$user_info['qrctime']=time();
			$user_info['qr_img']=$urltu;

			M("users")->save($user_info);
			
		}
		
		$this->assign('user_info',$user_info);
		//$this->assign('urltu',$urltu);
		
		$webtitle="截图发给小伙伴-我的二维码-战友招募-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//英雄猜 do 开奖 
	function hero_do_kj(){
		$gid=$_SESSION['gametypeid'];
		$today=date("Ymd",time());
		$today_shi_time=strtotime($today);
		$hei_end_time=strtotime($today."02:00:00");

		//$today_shi=date("Ymd H:i:s",$today_shi_time);

		$cha_time=time()-$today_shi_time;
		$bai_shi_time=strtotime($today."10:00:00");
		$hei_shi_time=strtotime($today."22:00:00");


		if(time()<=$hei_end_time){
			//00--02
			$cha_cishu=floor((time()-$today_shi_time)/(5*60));
			$shengyumiao=5*60-floor((time()-$today_shi_time)%(5*60));
		}else if(time()>$hei_end_time && time()<=$bai_shi_time){
			//02--10
			$cha_cishu=23;
			$shengyumiao=$bai_shi_time-time();
		}else if(time()>$bai_shi_time && time()<=$hei_shi_time){
			//10--22
			$cha_cishu=24+floor((time()-$bai_shi_time)/(10*60));
			$shengyumiao=10*60-floor((time()-$bai_shi_time)%(10*60));
		}else if(time()>$hei_shi_time){
			//22--24
			$cha_cishu=24+72+floor((time()-$hei_shi_time)/(5*60));
			$shengyumiao=5*60-floor((time()-$hei_shi_time)%(5*60));
		}
		$now_cha_cishu=$cha_cishu+1;//正在投注的期数


		if($cha_cishu>0){ //需要获取的期数大于0时，才去获取，否则不获取
			$roulette_log_old=M("roulette_log")->where("day=".$today." and gid=".$gid."")->order("qi desc")->find();
			$cha_cishu=$cha_cishu-floor($roulette_log_old['qi']);

			if($cha_cishu>=1){
				require('./phpQuery/phpQuery.php');
				$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=0&span='.$cha_cishu);  //获取$cha_cishu期
				$companies = pq('.chart-table')->find('.zdww5')->find("tr");  
				$i=0;
				foreach($companies as $k=>$company){
					$dayqi=pq($company)->find('td.tdbg_1:eq(0)')->text();
					if(!empty($dayqi)){
						$dayqi_arr=explode('-',$dayqi);
						$kaijiang[$i]['day']='20'.$dayqi_arr[0];
						$kaijiang[$i]['qi']=$dayqi_arr[1];
					}
					$num=pq($company)->find('td.tdbg_1:eq(1)')->text();
					if(!empty($num)){
						$kaijiang[$i]['num']=$num;
						$i++;
					}
				} 
				krsort($kaijiang);

				foreach($kaijiang as $k=>$v){
					$old_rlog[$k]=M("roulette_log")->where("day=".$v['day']." and gid=".$gid." and qi='".$v['qi']."'")->find();

					if(empty($old_rlog[$k])){//如果没有旧的开奖记录，
						$new_log['ctime']=time();
						$new_log['num']=$v['num'];
						$new_log['day']=$v['day'];
						$new_log['qi']=$v['qi'];
						$new_log['ktime']=time();
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
								$hero_win_pro[$k][]=$vv['roulette_propertyId'];
							}
							
							$touzhu_log[$k]=M("user_golds_record")->where("jieortou=2 and gid=".$gid." and herodayqi=".$v['day'].$v['qi']."")->order("ctime asc,user_golds_recordId asc")->select();
							foreach($touzhu_log[$k] as $kk=>$vv){
								$touzhu_check=explode(',',$vv['herocheck']);
								$touzhu_check_count=count($touzhu_check);
								$array_intersect=array_intersect($touzhu_check,$hero_win_pro[$k]);
								$array_intersect_count=count($array_intersect);
								if($array_intersect_count==$touzhu_check_count){
									$roulette_peilv=M("roulette_peilv")->where("num='".$vv['herocheck']."'")->find();
									$peilv=$roulette_peilv['peilv'];
									$win_golds['changeAmount']=abs($vv['changeAmount'])*$peilv;
									
									$user_info=M("users")->where("userId=".$vv['userId']."")->find();
									$win_golds['amount']=$user_info['golds']+$win_golds['changeAmount'];
									$win_golds['detail']="英雄猜 第 ".$v['day'].$v['qi']." 期 奖励";
									$win_golds['ctime']=time();
									$win_golds['userId']=$vv['userId'];
									$win_golds['gid']=$vv['gid'];
									$win_golds['recordid']=$vv['user_golds_recordId'];
									$win_golds['jieortou']=1;
									$win_golds['recomid']=$vv['recomid'];
									$win_golds['herocheck']=$vv['herocheck'];
									$win_golds['herodayqi']=$vv['herodayqi'];
									$win_golds['fromid']=3;

									M("user_golds_record")->add($win_golds);
	
									$user_info["golds"]=$win_golds['amount'];
									M("users")->save($user_info);
									
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
		$yest_day_log=M("roulette_log")->where("day=".$yest_day." and gid=".$gid."")->order("qi desc")->find();
		$yest_day_qi=120-$yest_day_log['qi'];//昨天剩余期数
		$yest_today_zong_qi=$yest_day_qi+$cha_cishu+2;

		if($yest_day_qi>=1){//昨天剩余期数大于等于1时，才去获取，否则不获取

			require_once('./phpQuery/phpQuery.php');
			$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=0&span='.$yest_today_zong_qi);  //获取$cha_cishu期
			$companies = pq('.chart-table')->find('.zdww5')->find("tr");  
			$i=0;
			foreach($companies as $k=>$company)  
			{  
				$dayqi=pq($company)->find('td.tdbg_1:eq(0)')->text();
				if(!empty($dayqi)){
					$dayqi_arr=explode('-',$dayqi);
					$kaijiang[$i]['day']='20'.$dayqi_arr[0];
					$kaijiang[$i]['qi']=$dayqi_arr[1];
				}
				$num=pq($company)->find('td.tdbg_1:eq(1)')->text();
				if(!empty($num)){
					$kaijiang[$i]['num']=$num;
					$i++;
				}
			} 
			krsort($kaijiang);
			
			foreach($kaijiang as $k=>$v){
				$old_rlog[$k]=M("roulette_log")->where("day=".$v['day']." and gid=".$gid." and qi='".$v['qi']."'")->find();
				if(empty($old_rlog[$k])){//如果没有旧的开奖记录，则存储
					$new_log['ctime']=time();
					$new_log['num']=$v['num'];
					$new_log['day']=$v['day'];
					$new_log['qi']=$v['qi'];
//					if($v['qi']==120){
//						$next_day=date("Ymd",(strtotime($v['day'])+60*60*24));
//						$new_log['ktime']=strtotime("".$next_day." ".$v['ktime'].":00");
//					}else{
//						$new_log['ktime']=strtotime("".$v['day']." ".$v['ktime'].":00");
//					}
					$new_log['ktime']=time();
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
							$hero_win_pro[$k][]=$vv['roulette_propertyId'];
						}
						
						$touzhu_log[$k]=M("user_golds_record")->where("jieortou=2 and gid=".$gid." and herodayqi=".$v['day'].$v['qi']."")->order("ctime asc,user_golds_recordId asc")->select();
						foreach($touzhu_log[$k] as $kk=>$vv){
							$touzhu_check=explode(',',$vv['herocheck']);
							$touzhu_check_count=count($touzhu_check);
							$array_intersect=array_intersect($touzhu_check,$hero_win_pro[$k]);
							$array_intersect_count=count($array_intersect);
							if($array_intersect_count==$touzhu_check_count){
								$roulette_peilv=M("roulette_peilv")->where("num='".$vv['herocheck']."'")->find();
								$peilv=$roulette_peilv['peilv'];
								$win_golds['changeAmount']=abs($vv['changeAmount'])*$peilv;
								
								$user_info=M("users")->where("userId=".$vv['userId']."")->find();
								$win_golds['amount']=$user_info['golds']+$win_golds['changeAmount'];
								$win_golds['detail']="英雄猜 第 ".$v['day'].$v['qi']." 期 奖励";
								$win_golds['ctime']=time();
								$win_golds['userId']=$vv['userId'];
								$win_golds['gid']=$vv['gid'];
								$win_golds['recordid']=$vv['user_golds_recordId'];
								$win_golds['jieortou']=1;
								$win_golds['recomid']=$vv['recomid'];
								$win_golds['herocheck']=$vv['herocheck'];
								$win_golds['herodayqi']=$vv['herodayqi'];
								$win_golds['fromid']=3;
								M("user_golds_record")->add($win_golds);

								$user_info["golds"]=$win_golds['amount'];
								M("users")->save($user_info);
								
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
		/*$today=date("Ymd",$time);
		//今天0点时间戳
		$today_shi_time=strtotime($today);
		//2点时间戳
		$hei_end_time=strtotime($today."02:00:00");
		//今天开始了多久
		$cha_time=$time-$today_shi_time;

		//10点时的时间戳（白天）
		$bai_shi_time=strtotime($today."10:00:00");
		//22点时的时间戳（晚上）
		$hei_shi_time=strtotime($today."22:00:00");

		echo $bai_shi_time;
		echo "<br/>";
		echo $hei_shi_time;
		die;

		if($time<=$hei_end_time){
			//22点之前
			$cha_cishu=floor(($time-$today_shi_time)/(5*60));
			$shengyumiao=5*60-floor(($time-$today_shi_time)%(5*60));
		}else if($time>$hei_end_time && $time<=$bai_shi_time){
			//22点之后 10点之前
			$cha_cishu=23;
			$shengyumiao=$bai_shi_time-$time;
		}else if($time>$bai_shi_time && $time<=$hei_shi_time){
			//10点之后 22点之前
			$cha_cishu=24+floor(($time-$bai_shi_time)/(10*60));
			$shengyumiao=10*60-floor(($time-$bai_shi_time)%(10*60));
		}else if($time>$hei_shi_time){
			//22点之后
			$cha_cishu=24+72+floor(($time-$hei_shi_time)/(5*60));
			$shengyumiao=5*60-floor(($time-$hei_shi_time)%(5*60));
		}
		$now_cha_cishu=$cha_cishu+1;//正在投注的期数
		//期数拼接0
		if($now_cha_cishu<10){
			$cc['now_cha_cishu']="00".$now_cha_cishu;
		}else if($now_cha_cishu<100 && $now_cha_cishu>=10){
			$cc['now_cha_cishu']="0".$now_cha_cishu;
		}else{
			$cc['now_cha_cishu']=$now_cha_cishu;
		}
		$cc['shengyumiao']=$shengyumiao;
//		$now_tou=$this->hero_do_kj();
		$now_tou=$cc;*/

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
			foreach($roulette_property_type[$k]['roulette_property'] as $kk=>$vv){
				$peilv=M("roulette_peilv")->where("num='".$vv['roulette_propertyId']."' and state=1")->find();
				$roulette_property_type[$k]['roulette_property'][$kk]['peilv']=$peilv['peilv'];
			}
			$roulette_property_type[$k]['num']=count($roulette_property_type[$k]['roulette_property']);
		}
		$this->assign('roulette_property_type',$roulette_property_type);
		$all_peilv=M("roulette_peilv")->where("gid=".$gid."")->select();
		$this->assign('all_peilv',$all_peilv);
		
		$webtitle=$webtitle1."英雄猜-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		//dd($roulette_property_type);
		$this->display();
	}
	//英雄猜-投注
	function hero_tz(){

		$uid=$_SESSION['user_id'];
		$gid=$_SESSION['gametypeid'];


		$goldname=floor(t(h($_POST['goldname'])));
		if($goldname<1){
			$cc['jieguo']="no1";
			echo json_encode($cc);exit;
		}
		$checksids=t(h($_POST['checksids']));
		if(empty($checksids) || $checksids==0 || $checksids==""){
			$cc['jieguo']="nocheck";
			echo json_encode($cc);exit;
		}

		$checksids_arr=explode(",",$checksids);

		if(in_array(12,$checksids_arr) && count($checksids_arr)>=2){
			$cc['jieguo']="rsnomore";
			echo json_encode($cc);exit;
		}
		
		$user_info=M("users")->where("userId=".$uid."")->find();


//		if($goldname<1){
//			$cc['jieguo']="no1";
//			echo json_encode($cc);exit;
//		}
		if($user_info['golds']<$goldname){
			$cc['jieguo']="yuebuzu";
			$cc['user_yue']=floor($user_info['golds']);
			echo json_encode($cc);exit;
		}
		if($user_info['invite_by_userId']>0){
			//查询有没有参与过竞猜
			$zy_ly_user=M("users")->where("userId=".$user_info['invite_by_userId']."")->find();
			
			$shifoujc=M("user_golds_record")->where("userId=".$uid." and fromid IN (2,3)")->find();
			//$today_cs_timess=strtotime(date("Y-m-d",time())." 00:00:00");
			//$user_zhaomu_today_cishu=M("user_golds_record")->where("userId=".$zy_ly_user['userId']." and fromid=10 and ctime>=".$today_cs_timess."")->count();
			//if(empty($shifoujc) && $user_zhaomu_today_cishu<5){
			if(empty($shifoujc)){
//				$zy_ziji['changeAmount']=88;
//				$zy_ziji['amount']=$user_info['golds']+88;
//				$zy_ziji['ctime']=time();
//				$zy_ziji['userId']=$userid;
//				$zy_ziji['gid']=$gametypeid;
//				$zy_ziji['fromid']=10;
//				$zy_ziji['detail']="战友招募 首次竞猜奖励";
//				M("user_golds_record")->add($zy_ziji);
				
				$zy_laiyuan['changeAmount']=88;
				$zy_laiyuan['amount']=$zy_ly_user['golds']+$zy_laiyuan['changeAmount'];
				$zy_laiyuan['ctime']=time();
				$zy_laiyuan['userId']=$zy_ly_user['userId'];
				$zy_laiyuan['gid']=$gid;
				$zy_laiyuan['fromid']=10;
				$zy_laiyuan['recomid']=$uid;
				$zy_laiyuan['detail']="战友招募 首次竞猜奖励";
				$add_zy_laiyuan=M("user_golds_record")->add($zy_laiyuan);
			}
			
			$zy_laiyuan_ticheng['changeAmount']=$goldname*0.03;
			$zy_laiyuan_ticheng['amount']=$zy_ly_user['golds']+$zy_laiyuan['changeAmount']+$zy_laiyuan_ticheng['changeAmount'];
			$zy_laiyuan_ticheng['ctime']=time();
			$zy_laiyuan_ticheng['userId']=$zy_ly_user['userId'];
			$zy_laiyuan_ticheng['gid']=$gid;
			$zy_laiyuan_ticheng['fromid']=6;
			$zy_laiyuan_ticheng['recomid']=$uid;
			$zy_laiyuan_ticheng['detail']="战友招募奖励";
			$add_zy_laiyuan_ticheng=M("user_golds_record")->add($zy_laiyuan_ticheng);
			
			if($add_zy_laiyuan_ticheng){
				$zy_ly_user['golds']=$zy_laiyuan_ticheng['amount'];
				$laiyuan_user_save=M("users")->save($zy_ly_user);
			}
		}

		$today=date("Ymd",time());
		$today_shi_time=strtotime($today);
		$hei_end_time=strtotime($today."02:00:00");
		//$gid=$_SESSION['gametypeid'];
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
		$new['fromid']=3;

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
			
			$user_info['golds']=$new['amount'];
			M("users")->save($user_info);

			$cc['uyue']=floor($new['amount']*100)/100;
			$cc['jieguo']="cg";
			$cc['ren']=floor($shuizong['ren']);
			$cc['jinbi']=floor($shuizong['jinbi']);
		}else{
			$cc['jieguo']="sb";
		}

		echo json_encode($cc);exit;
	}
	//大乱斗
	function dld(){
		$gid=$_SESSION["gametypeid"];
		$uid=$_SESSION["user_id"];
		$dld_b=M("dld_b")->where("gid=".$gid."")->order("xu asc,id asc,jine asc")->select();
		$this->assign('dld_b',$dld_b);

		$old_dld=M("dld")->where("gid=".$gid." and uid>0")->order("id desc")->limit("0,3")->select();

		$dld=M("dld")->where("gid=".$gid." and uid<=0")->find();
		if(empty($dld)){
			$add_newdld["qi"]=$old_dld[0]['qi']+1;
			$add_newdld["gid"]=$gid;
			$newdldid=M("dld")->add($add_newdld);
			$nowdld['id']=$newdldid;
			$nowdld['qi']=$add_newdld["qi"];
			$nowdld['rennum']=0;
			$nowdld['bnum']=0;
			$nowdld['zjine']=0;
			$nowdld['shengyumiao']=-1;
			$nowdld['my_dld_jine']=0;

		}else{

			$nowdld['id']=$dld['id'];
			$nowdld['qi']=$dld["qi"];
			$nowdld['ktime']=$dld["ktime"];
			$nowdld['shengyumiao']=$dld["ktime"]-time();
			if($nowdld['ktime']==0){
				$nowdld['rennum']=0;
				$nowdld['bnum']=0;
				$nowdld['zjine']=0;
				$nowdld['my_dld_jine']=0;
				$nowdld['shengyumiao']=-1;

			}
			if($nowdld['ktime']>0 && $nowdld['ktime']<time()){
				$kj_res=$this->dld_kj($dld['id']);
				$add_newdld["qi"]=$dld["qi"]+1;
				$add_newdld["gid"]=$gid;
				$newdldid=M("dld")->add($add_newdld);
				$nowdld['id']=$newdldid;
				$nowdld['qi']=$add_newdld["qi"];
				$nowdld['rennum']=0;
				$nowdld['bnum']=0;
				$nowdld['zjine']=0;
				$nowdld['my_dld_jine']=0;
				$nowdld['ktime']=0;
				$nowdld['shengyumiao']=-1;
				$shifounew=1;
			}else{
				$dld_log=M("dld_log")->where("did=".$dld['id']."")->select();
				foreach($dld_log as $k=>$v){
					if(!in_array($v['uid'],$uid_array)){
						$nowdld['rennum']=$nowdld['rennum']+1;
						$uid_array[]=$v['uid'];
						$nowdld_users[]=M("users")->where("userId=".$v['uid']."")->find();
					}
					$nowdld['bnum']=$nowdld['bnum']+1;
					$jine=M('dld_b')->where("id=".$v['bid']."")->find();
					$nowdld['zjine']=$nowdld['zjine']+$jine['jine'];
				}
				foreach($nowdld_users as $k=>$v){
					$user_dld_log=M("dld_log")->where("did=".$dld['id']." and uid=".$v['userId']."")->select();
					foreach($user_dld_log as $kk=>$vv){
						$user_dld_jine=M('dld_b')->where("id=".$vv['bid']."")->find();
						$nowdld_users[$k]['dldjine']=$nowdld_users[$k]['dldjine']+$user_dld_jine['jine'];
						$nowdld_users[$k]['dldimgs'][]=$user_dld_jine;
					}
					if($v['userId']==$uid){
						$nowdld['my_dld_jine']=$nowdld_users[$k]['dldjine'];
					}

				}
				if(count($nowdld_users)>=2){
					if($nowdld['bnum']>=10){
						$nowdld['ktime']=time()+10;
						$dld_ktime_save_res2=M("dld")->where("id=".$dld['id']."")->setField('ktime',$nowdld['ktime']);
						$nowdld['shengyumiao']=10;
					}else{
						if($nowdld['ktime']<=0){
							$nowdld['ktime']=time()+60;
							$dld_ktime_save_res=M("dld")->where("id=".$dld['id']."")->setField('ktime',$nowdld['ktime']);
						}
						$nowdld['shengyumiao']=$nowdld['ktime']-time();
					}
				}else{
					$nowdld['shengyumiao']=-1;
				}

			}

		}
		//var_dump($nowdld);exit;
		$this->assign('nowdld',$nowdld);
		$this->assign('nowdld_users',$nowdld_users);

		$old_dld=M("dld")->where("gid=".$gid." and uid>0")->order("id desc")->limit("0,3")->select();
		//var_dump($gid);exit;
		foreach($old_dld as $k=>$v){
			$old_dld[$k]['users']=M("users")->where("userId=".$v['uid']."")->find();
			$old_dld_imgs=M("dld_log")->where("did=".$v['id']." and uid=".$v['uid']."")->select();
			foreach($old_dld_imgs as $kk=>$vv){
				$old_dld_b=M('dld_b')->where("id=".$vv['bid']."")->find();
				$old_dld[$k]['jilu_img'][]=$old_dld_b;
			}
		}
		$this->assign('old_dld',$old_dld);

		$webtitle="大乱斗-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	//大乱斗 开奖
	function dld_kj($did){
		$dld_log=M("dld_log")->where("did=".$did."")->select();
		foreach($dld_log as $k=>$v){
			if(!in_array($v['uid'],$uid_array)){
				$nowdld['rennum']=$nowdld['rennum']+1;
				$uid_array[]=$v['uid'];
				$nowdld_users[]=M("users")->where("userId=".$v['uid']."")->find();
			}
			$nowdld['bnum']=$nowdld['bnum']+1;
			$jine=M('dld_b')->where("id=".$v['bid']."")->find();
			$nowdld['zjine']=$nowdld['zjine']+$jine['jine'];
		}
		//判断已投注人数是否大于等于2人 开始
		if(count($nowdld_users)>=2) {
			foreach($nowdld_users as $k=>$v){
				$user_dld_log=M("dld_log")->where("did=".$did." and uid=".$v['userId']."")->select();
				foreach($user_dld_log as $kk=>$vv){
					$user_dld_jine=M('dld_b')->where("id=".$vv['bid']."")->find();
					$nowdld_users[$k]['dldjine']=$nowdld_users[$k]['dldjine']+$user_dld_jine['jine'];
					$nowdld_users[$k]['dldimgs'][]=$user_dld_jine['img'];
				}

			}
			$suiji_num=mt_rand(1,$nowdld['zjine']);
			$kj_sum=0;
			foreach ($nowdld_users as $k => $v) {
				$kj_sum +=intval($v['dldjine']);
				if ($suiji_num<=$kj_sum) {
					$kj_user['uid'] = $v['userId'];
					$kj_user['jine']=$v['dldjine'];
					$kj_user['dldimgs']=$v['dldimgs'];
					$kj_user['zjine']=$nowdld['zjine'];
					$kj_user['gailv']=floor(($kj_user['jine']/$kj_user['zjine'])*100*100)/100;
					$kj_user['yjine']=floor(($kj_user['zjine']-$kj_user['jine'])*(0.98)+$kj_user['jine']);
					$kj_user['old_yue']=$v['golds'];
					break;
				}
			}
			$kj_user_rec['fromid']=14;
			$kj_user_rec['changeAmount']=$kj_user['yjine'];
			$kj_user_rec['amount']=$kj_user['old_yue']+$kj_user_rec['changeAmount'];
			$kj_user_rec['detail']="大乱斗 奖励";
			$kj_user_rec['ctime']=time();
			$kj_user_rec['userId']=$kj_user['uid'];
			$kj_user_rec['gid']=$dld_log[0]['gid'];
			//保存金币明细
			$golds_change_add=M("user_golds_record")->add($kj_user_rec);
			//保存用户余额
			$user_yue_save=M("users")->where("userId=".$kj_user['uid']."")->setField('golds',$kj_user_rec['amount']);
			$kj_user['new_yue']=$kj_user_rec['amount'];

			//开奖
			$kj_res1=M("dld")->where("id=".$did."")->setField('uid',$kj_user['uid']);
			$kj_res2=M("dld")->where("id=".$did."")->setField('gailv',$kj_user['gailv']);
			$kj_res3=M("dld")->where("id=".$did."")->setField('yjine',$kj_user['yjine']);


//return $user_dld_log;exit;
			if($kj_res1 && $kj_res2 && $kj_res3){
				return $kj_user;
			}else{
				return false;
			}

		}else{
			return false;
		}
		//判断已投注人数是否大于等于2人 结束

	}
	//大乱斗投注
	function dld_tz(){
		$gid=$_SESSION["gametypeid"];
		//查询旧记录
		$old_dld=M("dld")->where("gid=".$gid." and uid>0")->order("id desc")->limit("0,2")->select();
		$dld=M("dld")->where("gid=".$gid." and uid<=0")->find();
		if(empty($dld)){
			$add_newdld["qi"]=$old_dld[0]['qi']+1;
			$add_newdld["gid"]=$gid;
			$newdldid=M("dld")->add($add_newdld);
			$nowdld['id']=$newdldid;
			$nowdld['qi']=$add_newdld["qi"];
			$nowdld['rennum']=0;
			$nowdld['bnum']=0;
			$nowdld['zjine']=0;
			$nowdld['shengyumiao']=-1;
			$shifounew=1;
		}else{

			$nowdld['id']=$dld['id'];
			$nowdld['qi']=$dld["qi"];
			$nowdld['ktime']=$dld["ktime"];
			$nowdld['shengyumiao']=$dld["ktime"]-time();
			if($nowdld['ktime']>0 && $nowdld['ktime']<time()){
				$kj_res=$this->dld_kj($dld['id']);

				$add_newdld["qi"]=$dld["qi"]+1;
				$add_newdld["gid"]=$gid;
				$newdldid=M("dld")->add($add_newdld);
				$nowdld['id']=$newdldid;
				$nowdld['qi']=$add_newdld["qi"];
				$nowdld['rennum']=0;
				$nowdld['bnum']=0;
				$nowdld['zjine']=0;
				$nowdld['shengyumiao']=-1;
				$shifounew=1;

			}else{
				$dld_log=M("dld_log")->where("did=".$nowdld['id']."")->select();
				foreach($dld_log as $k=>$v){
					if(!in_array($v['uid'],$uid_array)){
						$nowdld['rennum']=$nowdld['rennum']+1;
						$uid_array[]=$v['uid'];
						$nowdld_users[]=M("users")->where("userId=".$v['uid']."")->find();
					}
					$nowdld['bnum']=$nowdld['bnum']+1;
					$jine=M('dld_b')->where("id=".$v['bid']."")->find();
					$nowdld['zjine']=$nowdld['zjine']+$jine['jine'];
				}

				$shifounew=0;
			}
		}
		//添加投注记录
		$tz_b_ids=t(h($_POST['tz_b_ids']));
		$uid=$_SESSION["user_id"];
		$tz_b_id_arr=explode(",",$tz_b_ids);
		$tz_b_id_count=count($tz_b_id_arr);
		//当前用户已投注数
		$yi_tz_my_dld_count=M("dld_log")->where("did=".$nowdld['id']." and uid=".$uid."")->count();
		if($tz_b_id_count<=0){
			$cc['jieguo']="zb1";
			echo  json_encode($cc);
			exit;
		}
		if(($tz_b_id_count+$yi_tz_my_dld_count)>3){
			$cc['jieguo']="zb3";
			echo  json_encode($cc);
			exit;
		}
		if(($tz_b_id_count+$nowdld['bnum'])>10){
			$cc['jieguo']="zb11";
			echo  json_encode($cc);
			exit;
		}
		$user_info=M("users")->where("userId=".$uid."")->find();
		foreach($tz_b_id_arr as $k=>$v){
			$b_info=M("dld_b")->where("id=".$v."")->find();
			$tz_jine+=$b_info['jine'];
			$tz_imgs.="<div class=\"dld_jchi_li\">
						<img src=\"".$b_info['img']."\">
					</div>";
		}
		if($tz_jine>$user_info['golds']){
			$cc['tz_jine']=$tz_jine;
			$cc['user_yue']=$user_info['golds'];
			$cc['jieguo']="yuebuzu";
			echo  json_encode($cc);
			exit;
		}
		foreach($tz_b_id_arr as $k=>$v) {
			$dld_log_add[$k]['did']=$nowdld['id'];
			$dld_log_add[$k]['bid']=$v;
			$dld_log_add[$k]['ctime']=time();
			$dld_log_add[$k]['uid']=$uid;
			$dld_log_add[$k]['gid']=$gid;
			$dld_log_add_res=M("dld_log")->add($dld_log_add[$k]);
		}
		$user_yue=$user_info['golds']-$tz_jine;
		$user_yue_save_res=M("users")->where("userId=".$uid."")->setField('golds',$user_yue);
		if($user_yue_save_res){
			//金币明细记录
			$jbmx['fromid']=14;
			$jbmx['changeAmount']=-$tz_jine;
			$jbmx['amount']=$user_yue;
			$jbmx['detail']="大乱斗 投注";
			$jbmx['ctime']=time();
			$jbmx['userId']=$uid;
			$jbmx['gid']=$gid;
			$jbmx['dldid']=$nowdld['id'];

			//保存金币明细
			$golds_change_add=M("user_golds_record")->add($jbmx);

			$cc['jieguo']="cg";
			$cc['uid']=$uid;
			$cc['user_yue']=floor($user_yue);
			$cc['tz_jine']=$tz_jine;
			$cc['user_header']=$user_info['header'];
			$cc['user_name']=$user_info['name'];
			$cc['tz_imgs']=$tz_imgs;
			$cc['shifounew']=$shifounew;
			//再次判断已经投注的用户数量 ，从而判断是否开始倒计时读秒
			$new_dld_log=M("dld_log")->where("did=".$nowdld['id']."")->select();
			foreach($new_dld_log as $k=>$v){
				if(!in_array($v['uid'],$new_uid_array)){
					$new_uid_array[]=$v['uid'];
				}
			}
			if(count($new_uid_array)>=2){
				if(($tz_b_id_count+$nowdld['bnum'])>=10){
					$nowdld['ktime']=time()+10;
					$dld_ktime_save_res2=M("dld")->where("id=".$nowdld['id']."")->setField('ktime',$nowdld['ktime']);
					$nowdld['shengyumiao']=10;
				}else{
					if($nowdld['ktime']<=0){
						$nowdld['ktime']=time()+60;
						$dld_ktime_save_res=M("dld")->where("id=".$nowdld['id']."")->setField('ktime',$nowdld['ktime']);
					}
					$nowdld['shengyumiao']=$nowdld['ktime']-time();
				}
			}else{
				$nowdld['shengyumiao']=-1;
			}
			$cc['shengyumiao']=$nowdld['shengyumiao'];
			$cc['zjine']=$tz_jine+$nowdld['zjine'];
			$cc['bnum']=count($new_dld_log);
			$cc['rennum']=count($new_uid_array);
			echo  json_encode($cc);
			exit;
		}else{
			$cc['jieguo']="sb";
			echo  json_encode($cc);
			exit;
		}
	}
	//ajax 大乱斗 开奖
	function ajax_dld_kj(){
		$gid=$_SESSION["gametypeid"];
		$dld=M("dld")->where("gid=".$gid." and uid<=0 and ktime>0 and ktime<=".time()."")->find();
		if(!empty($dld)){
			$did=$dld['id'];
			$dld_log=M("dld_log")->where("did=".$did."")->select();
			foreach($dld_log as $k=>$v){
				if(!in_array($v['uid'],$uid_array)){
					$nowdld['rennum']=$nowdld['rennum']+1;
					$uid_array[]=$v['uid'];
					$nowdld_users[]=M("users")->where("userId=".$v['uid']."")->find();
				}
				$nowdld['bnum']=$nowdld['bnum']+1;
				$jine=M('dld_b')->where("id=".$v['bid']."")->find();
				$nowdld['zjine']=$nowdld['zjine']+$jine['jine'];
			}
			//判断已投注人数是否大于等于2人 开始
			if(count($nowdld_users)>=2){
				foreach($nowdld_users as $k=>$v){
					$user_dld_log=M("dld_log")->where("did=".$did." and uid=".$v['userId']."")->select();
					foreach($user_dld_log as $kk=>$vv){
						$user_dld_jine=M('dld_b')->where("id=".$vv['bid']."")->find();
						$nowdld_users[$k]['dldjine']=$nowdld_users[$k]['dldjine']+$user_dld_jine['jine'];
						$nowdld_users[$k]['dldimgs'][]=$user_dld_jine['img'];
					}

				}
				$suiji_num=mt_rand(1,$nowdld['zjine']);
				$kj_sum=0;
				foreach ($nowdld_users as $k => $v) {
					$kj_sum +=intval($v['dldjine']);
					if ($suiji_num<=$kj_sum) {
						$kj_user['uid'] = $v['userId'];
						$kj_user['name'] = $v['name'];
						$kj_user['header'] = $v['header'];
						$kj_user['jine']=$v['dldjine'];
						$kj_user['dldimgs']=$v['dldimgs'];
						$kj_user['zjine']=$nowdld['zjine'];
						$kj_user['gailv']=floor(($kj_user['jine']/$kj_user['zjine'])*100*100)/100;
						$kj_user['yjine']=floor(($kj_user['zjine']-$kj_user['jine'])*(0.98)+$kj_user['jine']);
						$kj_user['old_yue']=$v['golds'];
						break;
					}
				}
				$kj_user_rec['fromid']=14;
				$kj_user_rec['changeAmount']=$kj_user['yjine'];
				$kj_user_rec['amount']=$kj_user['old_yue']+$kj_user_rec['changeAmount'];
				$kj_user_rec['detail']="大乱斗 奖励";
				$kj_user_rec['ctime']=time();
				$kj_user_rec['userId']=$kj_user['uid'];
				$kj_user_rec['gid']=$dld_log[0]['gid'];
				$kj_user_rec['dldid']=$did;
				//保存金币明细
				$golds_change_add=M("user_golds_record")->add($kj_user_rec);
				//保存用户余额
				$user_yue_save=M("users")->where("userId=".$kj_user['uid']."")->setField('golds',$kj_user_rec['amount']);
				$kj_user['new_yue']=$kj_user_rec['amount'];
				//开奖
				$kj_res=M("dld")->where("id=".$did."")->setField('uid',$kj_user['uid']);
				$kj_res=M("dld")->where("id=".$did."")->setField('gailv',$kj_user['gailv']);
				$kj_res=M("dld")->where("id=".$did."")->setField('yjine',$kj_user['yjine']);

				foreach($kj_user['dldimgs'] as $k=>$v){
					$kj_user['kj_imgs'].="<span>
								<img src=\"".$v."\">
							</span>";
				}
				$kj_user['kj_imgs'].="<div class=\"clear\"></div>";
				$kj_user['kj_html']="<div class=\"dld_jilu_li\">
				<div class=\"dld_jilu_li_l\">
					<img src=\"".$kj_user['header']."\">
				</div>
				<div class=\"dld_jilu_li_r\">
					<div class=\"dld_jilu_li_r_t\">
						<span class=\"span1\">".$kj_user['name']."</span>
					<span class=\"span2\">
						以<span class=\"span3\">".$kj_user['gailv']."%</span>的概率赢得了<span class=\"span3\">".$kj_user['yjine']."</span>金币
					</span>
						<span class=\"span4\">".$dld['qi']."期</span>
					</div>
					<div class=\"dld_jilu_li_r_x\">
						".$kj_user['kj_imgs']."
					</div>
				</div>
				<div class=\"clear\"></div>
			</div>";
				echo  json_encode($kj_user);
				exit;
			}else{
				$kj_user['yikai']=1;
				$kj_user['nonew']=1;
				echo  json_encode($kj_user);
				exit;

			}
			//判断已投注人数是否大于等于2人 结束

		}else{
			$kj_user['yikai']=1;
			$kj_user['nonew']=1;
			echo  json_encode($kj_user);
			exit;

		}



	}
	//获取最新奖池图片
	function get_dld_jc(){
		$gid=$_SESSION["gametypeid"];
		$uid=$_SESSION["user_id"];
		$now_jc_num=t(h($_POST['now_jc_num']));
		$nowdld_users_ids=trim(t(h($_POST['nowdld_users_ids'])),",");
		$nowdldqi=t(h($_POST['nowdldqi']))+1;
		$dld=M("dld")->where("gid=".$gid." and uid<=0 ")->find();
		if(empty($dld)){
			//$add_newdld["qi"]=$old_dld[0]['qi']+1;
			//$add_newdld["gid"]=$gid;
			//$newdldid=M("dld")->add($add_newdld);
			$cc['moreuser']=" ";
			$cc['moreimg']=" ";
			$cc['zjine']=0;
			$cc['bnum']=0;
			$cc['rennum']=0;
			$cc['shengyumiao']=-1;

			$old_dld=M("dld")->where("gid=".$gid." and qi='".$nowdldqi."' and uid>0")->find();
			if(!empty($old_dld)){
				$old_dld['users']=M("users")->where("userId=".$old_dld['uid']."")->find();
				$more_dld_jilus.="<div class=\"dld_jilu_li\">
					<div class=\"dld_jilu_li_l\">
						<img src=\"".$old_dld['users']['header']."\">
					</div>
					<div class=\"dld_jilu_li_r\">
						<div class=\"dld_jilu_li_r_t\">
							<span class=\"span1\">".$old_dld['users']['name']."</span>
						<span class=\"span2\">
							以<span class=\"span3\">".$old_dld['gailv']."%</span>的概率赢得了<span class=\"span3\">".$old_dld['yjine']."</span>金币
						</span>
							<span class=\"span4\">".$old_dld['qi']."期</span>
						</div>
						<div class=\"dld_jilu_li_r_x\">";

				$old_dld_imgs=M("dld_log")->where("did=".$old_dld['id']." and uid=".$old_dld['uid']."")->select();
				foreach($old_dld_imgs as $kk=>$vv){
					$old_dld_b=M('dld_b')->where("id=".$vv['bid']."")->find();
					$more_dld_jilus.="<span>
									<img src=\"".$old_dld_b['img']."\" >
								</span>";
				}
				$more_dld_jilus.="<div class=\"clear\"></div>
						</div>
					</div>
					<div class=\"clear\"></div>
				</div>";

				$cc['more_dld_jilus']=$more_dld_jilus;

			}

			echo json_encode($cc);
			exit;

		}else{
			$did=$dld['id'];
			$dld_log=M("dld_log")->where("did=".$did."")->order("id asc")->select();
			foreach($dld_log as $k=>$v){
				$jine=M('dld_b')->where("id=".$v['bid']."")->find();
				if($k>=0){
					$more_dld_jc_img.="<div class=\"dld_jchi_li\">
										<img src=\"".$jine['img']."\">
									</div>";
				}
				if(!in_array($v['uid'],$new_uid_array)){
					$new_uid_array[]=$v['uid'];
					$nowdld_users[]=M("users")->where("userId=".$v['uid']."")->find();
				}
				$zjine=$zjine+$jine['jine'];
			}

			$more_dld_jc_img.="<div class=\"clear\"></div>";

			$nowdld_users_ids_arr=explode(",",$nowdld_users_ids);
			foreach($nowdld_users as $k=>$v){

				$user_dld_log=M("dld_log")->where("did=".$did." and uid=".$v['userId']."")->select();
				foreach($user_dld_log as $kk=>$vv){
					$user_dld_jine=M('dld_b')->where("id=".$vv['bid']."")->find();
					$nowdld_users[$k]['dldjine']=$nowdld_users[$k]['dldjine']+$user_dld_jine['jine'];

				}
				if($v['userId']==$uid){
					$my_dld_jine=$nowdld_users[$k]['dldjine'];
				}
				//$more_dld_new_users.="<div class=\"dld_user dld_user_id".$v['userId']."\" data-uid=\"".$v['userId']."\">
				//	<div class=\"dld_user_header\">
				//		<img src=\"".$v['header']."\">
				//	</div>
				//	<div class=\"dld_user_text\">
				//		".$v['name']."
				//	</div>
				//	<div class=\"dld_user_text dld_user_jine\">
				//		".$nowdld_users[$k]['dldjine']."
				//	</div>
				//	<div class=\"clear\"></div>
				//</div>";
				if(!in_array($v['userId'],$nowdld_users_ids_arr)){
					if(!empty($v['header'])){
						$more_dld_new_users.="<div class=\"evdlduser dld_user_id".$v['userId']."\" data-uid=\"".$v['userId']."\">
							<img src=\"".$v['header']."\">
                		</div>";
					}else{
						$more_dld_new_users.="<div class=\"evdlduser dld_user_id".$v['userId']."\" data-uid=\"".$v['userId']."\">
							<img src=\"./Public/Mobile/images/user-inco.png\">
                		</div>";
					}

				}

			}
			//$more_dld_new_users.="<div class=\"clear\"></div>";

			$cc['moreuser']=$more_dld_new_users;
			if($my_dld_jine>0){
				$cc['my_dld_jine']=$my_dld_jine;
			}else{
				$cc['my_dld_jine']=0;
			}

			$cc['moreimg']=$more_dld_jc_img;
			if($zjine<=0){
				$zjine=0;
			}
			$cc['zjine']=$zjine;
			$cc['bnum']=count($dld_log);
			$cc['rennum']=count($new_uid_array);
			if($cc['rennum']>=2){
				if($cc['bnum']>=10){
					$cc['shengyumiao']=10;
				}else{
					$dld_ktime_new=M("dld")->where("id=".$did."")->find();
					$cc['shengyumiao']=$dld_ktime_new['ktime']-time();
				}
			}else{
				$cc['shengyumiao']=-1;
			}
			if($dld['qi']!=$nowdldqi){
				$old_dld=M("dld")->where("gid=".$gid." and qi='".$nowdldqi."' and uid>0")->find();

				$old_dld['users']=M("users")->where("userId=".$old_dld['uid']."")->find();
				$more_dld_jilus.="<div class=\"dld_jilu_li\">
					<div class=\"dld_jilu_li_l\">
						<img src=\"".$old_dld['users']['header']."\">
					</div>
					<div class=\"dld_jilu_li_r\">
						<div class=\"dld_jilu_li_r_t\">
							<span class=\"span1\">".$old_dld['users']['name']."</span>
						<span class=\"span2\">
							以<span class=\"span3\">".$old_dld['gailv']."%</span>的概率赢得了<span class=\"span3\">".$old_dld['yjine']."</span>金币
						</span>
							<span class=\"span4\">".$old_dld['qi']."期</span>
						</div>
						<div class=\"dld_jilu_li_r_x\">";

						$old_dld_imgs=M("dld_log")->where("did=".$old_dld['id']." and uid=".$old_dld['uid']."")->select();
						foreach($old_dld_imgs as $kk=>$vv){
							$old_dld_b=M('dld_b')->where("id=".$vv['bid']."")->find();
							$more_dld_jilus.="<span>
									<img src=\"".$old_dld_b['img']."\" >
								</span>";
						}
						$more_dld_jilus.="<div class=\"clear\"></div>
						</div>
					</div>
					<div class=\"clear\"></div>
				</div>";


				$cc['more_dld_jilus']=$more_dld_jilus;

			}
			echo json_encode($cc);
			exit;


		}

	}
	//大乱斗 历史中奖记录 更多
	function more_dld_jilu(){
		$gid=$_SESSION['gametypeid'];
		$nowpage=t(h($_POST["nowpage"]));
		$start=$nowpage*3;
		$old_dld=M("dld")->where("gid=".$gid." and uid>0")->order("id desc")->limit("".$start.",3")->select();
		foreach($old_dld as $k=>$v){
			$old_dld[$k]['users']=M("users")->where("userId=".$v['uid']."")->find();
			if(!empty($old_dld[$k]['users']['header'])){
				$more_dld_jilus.="<div class=\"dld_jilu_li\">
				<div class=\"dld_jilu_li_l\">
					<img src=\"".$old_dld[$k]['users']['header']."\">
				</div>
				<div class=\"dld_jilu_li_r\">
					<div class=\"dld_jilu_li_r_t\">
						<span class=\"span1\">".$old_dld[$k]['users']['name']."</span>
					<span class=\"span2\">
						以<span class=\"span3\">".$v['gailv']."%</span>的概率赢得了<span class=\"span3\">".$v['yjine']."</span>金币
					</span>
						<span class=\"span4\">".$v['qi']."期</span>
					</div>
					<div class=\"dld_jilu_li_r_x\">";
			}else{
				$more_dld_jilus.="<div class=\"dld_jilu_li\">
				<div class=\"dld_jilu_li_l\">
					<img src=\"./Public/Mobile/images/user-inco.png\">
				</div>
				<div class=\"dld_jilu_li_r\">
					<div class=\"dld_jilu_li_r_t\">
						<span class=\"span1\">".$old_dld[$k]['users']['name']."</span>
					<span class=\"span2\">
						以<span class=\"span3\">".$v['gailv']."%</span>的概率赢得了<span class=\"span3\">".$v['yjine']."</span>金币
					</span>
						<span class=\"span4\">".$v['qi']."期</span>
					</div>
					<div class=\"dld_jilu_li_r_x\">";
			}


			$old_dld_imgs=M("dld_log")->where("did=".$v['id']." and uid=".$v['uid']."")->select();
			foreach($old_dld_imgs as $kk=>$vv){
				$old_dld_b=M('dld_b')->where("id=".$vv['bid']."")->find();
				//$old_dld[$k]['jilu_img'][]=$old_dld_b;
				$more_dld_jilus.="<span>
								<img src=\"".$old_dld_b['img']."\" >
							</span>";
			}
			$more_dld_jilus.="<div class=\"clear\"></div>
					</div>
				</div>
				<div class=\"clear\"></div>
			</div>";

		}
		$cc['ccc']=$more_dld_jilus;
		$cc['nextpage']=$nowpage+1;
		echo json_encode($cc);
		exit;

	}
	//比赛 参赛页面
	function lolteams(){
		$uid=$_SESSION["user_id"];
		$bs_xiangmu=M("bs_xiangmu")->order("xu asc,id asc")->select();
		$this->assign("bs_xiangmu",$bs_xiangmu);
		$bs_xuexiao=M("bs_xuexiao")->order("xu asc,id asc")->select();
		$this->assign("bs_xuexiao",$bs_xuexiao);
		$bs_weizhi=M("bs_weizhi")->order("xu asc,id asc")->select();
		$this->assign("bs_weizhi",$bs_weizhi);
		$zd=M("bs_team")->where("duid=".$uid."")->find();
		$zd_xiangmu=M("bs_xiangmu")->where("id=".$zd['xiangmu']."")->find();
		$zd['xiangmuname']=$zd_xiangmu['name'];
		$zd_userinfo=M("bs_userinfo")->where("uid=".$zd['duid']."")->find();
		$zd['duname']=$zd_userinfo['name'];
		$zd['duphone']=$zd_userinfo['phone'];
		$zd['duxuexiaoid']=$zd_userinfo['xuexiao'];
		$zd_xuexiao=M("bs_xuexiao")->where("id=".$zd_userinfo['xuexiao']."")->find();
		$zd['duxuexiaoname']=$zd_xuexiao['name'];
		$zd['dubanji']=$zd_userinfo['banji'];
		$zd['duweizhiid']=$zd_userinfo['weizhi'];
		$zd_weizhi=M("bs_weizhi")->where("id=".$zd_userinfo['weizhi']."")->find();
		$zd['duweizhiname']=$zd_weizhi['name'];
		$this->assign("zd",$zd);

		$webtitle="参赛-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	function doteam(){
		$uid=$_SESSION["user_id"];
		$zd_logo=t(h($_POST['zd_logo']));
		$zd_name=t(h($_POST['zd_name']));
		$zd_xiangmu=t(h($_POST['zd_xiangmu']));
		$zd_duname=t(h($_POST['zd_duname']));
		$zd_duphone=t(h($_POST['zd_duphone']));
		$zd_duxuexiaoid=t(h($_POST['zd_duxuexiaoid']));
		$zd_dubanji=t(h($_POST['zd_dubanji']));
		$zd_duweizhiid=t(h($_POST['zd_duweizhiid']));
		if(empty($zd_logo)){
			$cc['jieguo']="nologo";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_name)){
			$cc['jieguo']="noname";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_xiangmu)){
			$cc['jieguo']="noxiangmu";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_duname)){
			$cc['jieguo']="noduname";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_duphone)){
			$cc['jieguo']="noduphone";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_duxuexiaoid)){
			$cc['jieguo']="noduxuexiao";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_dubanji)){
			$cc['jieguo']="nodubanji";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_duweizhiid)){
			$cc['jieguo']="noduweizhi";
			echo json_encode($cc);
			exit;
		}
		//存战队信息
		$team['logo']=$zd_logo;
		$team['name']=$zd_name;
		$team['xuexiao']=$zd_duxuexiaoid;
		$team['xiangmu']=$zd_xiangmu;
		$team['duid']=$uid;
		$team_jiu=M('bs_team')->where("duid=".$uid."")->find();
		if(empty($team_jiu)){
			$team_add_res=M("bs_team")->add($team);
			$dui_user['tid']=$team_add_res;
		}else{
			$team['id']=$team_jiu['id'];
			$team_save_res=M("bs_team")->save($team);
			$dui_user['tid']=$team_jiu['id'];
		}
		//存战队队长信息
		$dui_user['uid']=$uid;
		$dui_user['name']=$zd_duname;
		$dui_user['xuexiao']=$zd_duxuexiaoid;
		$dui_user['phone']=$zd_duphone;
		$dui_user['banji']=$zd_dubanji;
		$dui_user['weizhi']=$zd_duweizhiid;
		$dui_user_jiu=M('bs_userinfo')->where("uid=".$uid."")->find();
		if(empty($dui_user_jiu)){
			$dui_user_add_res=M('bs_userinfo')->add($dui_user);
		}else{
			$dui_user['id']=$dui_user_jiu['id'];
			$dui_user_save_res=M('bs_userinfo')->save($dui_user);

		}

		$cc['jieguo']="cg";
		echo json_encode($cc);
		exit;


	}
	//战队主页
	function teamsindex(){
		$uid=$_SESSION['user_id'];
		$id=t(h($_GET['id']));
		if(empty($id)){
			$team=M('bs_team')->where("duid=".$uid."")->find();
			if(empty($team)) {
				$team_duiyuan_info=M('bs_userinfo')->where("uid=".$uid." and tid>0")->find();
				if(empty($team_duiyuan_info)){
					header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php?a=lolteams&m=Index");
					exit;
				}else{
					$team=M('bs_team')->where("id=".$team_duiyuan_info['tid']."")->find();
				}

			}
		}else{
			$team=M('bs_team')->where("id=".$id."")->find();
		}

		$team_xiangmu=M('bs_xiangmu')->where("id=".$team['xiangmu']."")->find();
		$team['xiangmuname']=$team_xiangmu['name'];
		$team_dui=M('bs_userinfo')->where("uid=".$team['duid']."")->find();
		$team_duxuexiao=M('bs_xuexiao')->where("id=".$team_dui['xuexiao']."")->find();
		$team['duxuexiao']=$team_duxuexiao['name'];
		$team['duphone']=$team_dui['phone'];
		$team_dui_header=M("users")->where("userId=".$team['duid']."")->find();
		$team['duheader']=$team_dui_header['header'];
		$team['duname']=$team_dui['name'];
		$team['dubanji']=$team_dui['banji'];
		$team_dui_weizhi=M("bs_weizhi")->where("id=".$team_dui['weizhi']."")->find();
		$team['duweizhi']=$team_dui_weizhi['name'];
		$this->assign('team',$team);

		$team_users=M("bs_userinfo")->where("tid=".$team['id']." and uid!=".$team['duid']."")->select();
		foreach($team_users as $k=>$v){
			$header=M("users")->where("userId=".$v['uid']."")->find();
			$team_users[$k]['header']=$header['header'];
			$weizhi=M("bs_weizhi")->where("id=".$v['weizhi']."")->find();
			$team_users[$k]['weizhiname']=$weizhi['name'];
		}
		$this->assign('team_users',$team_users);

		$shifouyouzd=M('bs_userinfo')->where("uid=".$uid." and tid>0")->find();
		if(empty($shifouyouzd)){
			$yiyouzd=0;
		}else{
			$yiyouzd=1;
		}
		$this->assign("yiyouzd",$yiyouzd);
		//参赛记录
		$jilu=M("bs_saicheng")->where("team1=".$team['id']." or team2=".$team['id']."")->select();
		foreach($jilu as $k=>$v){
			$jilu[$k]['team1info']=M('bs_team')->where("id=".$v['team1']."")->find();
			$jilu[$k]['team2info']=M('bs_team')->where("id=".$v['team2']."")->find();
		}
		$this->assign('jilu',$jilu);
		//加入战队
		$geren=M("bs_userinfo")->where("uid=".$uid."")->find();
		$geren_xuexiao=M("bs_xuexiao")->where("id=".$geren['xuexiao']."")->find();
		$geren['xuexiaoname']=$geren_xuexiao['name'];
		$geren_weizhi=M("bs_weizhi")->where("id=".$geren['weizhi']."")->find();
		$geren['weizhiname']=$geren_weizhi['name'];
		$this->assign('geren',$geren);
		$bs_xuexiao=M("bs_xuexiao")->order("xu asc,id asc")->select();
		$this->assign("bs_xuexiao",$bs_xuexiao);
		$bs_weizhi=M("bs_weizhi")->order("xu asc,id asc")->select();
		$this->assign("bs_weizhi",$bs_weizhi);


		$webtitle=$team['name']."-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	//战队列表
	function teamlist(){
		$uid=$_SESSION['user_id'];
		$bs_xiangmu=M("bs_xiangmu")->order("xu asc,id asc")->select();
		$this->assign("bs_xiangmu",$bs_xiangmu);
		$bs_xuexiao=M("bs_xuexiao")->order("xu asc,id asc")->select();
		$this->assign("bs_xuexiao",$bs_xuexiao);
		$xid=t(h($_GET['xid']));
		$mid=t(h($_GET['mid']));

		$bs_xiangmuname=M("bs_xiangmu")->where("id=".$mid."")->find();
		$this->assign('bs_xiangmuname',$bs_xiangmuname);
		$bs_xuexiaoname=M("bs_xuexiao")->where("id=".$xid."")->find();
		$this->assign('bs_xuexiaoname',$bs_xuexiaoname);

		if(empty($xid) && empty($mid)){
			$teams=M("bs_team")->order("jiangjin desc,id asc")->select();
		}else if(empty($xid) && !empty($mid)){
			$teams=M("bs_team")->where("xiangmu=".$mid."")->order("jiangjin desc,id asc")->select();
		}else if(!empty($xid) && empty($mid)){
			$teams=M("bs_team")->where("xuexiao=".$xid."")->order("jiangjin desc,id asc")->select();
		}else{
			$teams=M("bs_team")->where("xuexiao=".$xid." and xiangmu=".$mid."")->order("jiangjin desc,id asc")->select();
		}

		foreach($teams as $k=>$v){
			$xiangmu=M('bs_xiangmu')->where("id=".$v['xiangmu']."")->find();
			$teams[$k]['xiangmuname']=$xiangmu['name'];
			$teams[$k]['rennum']=M('bs_userinfo')->where("tid=".$v['id']."")->count();
		}
		$this->assign('teams',$teams);



		$webtitle="战队列表-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	//加入战队
	function jointeam(){
		$uid=$_SESSION["user_id"];

		$geren_name=t(h($_POST['geren_name']));
		$geren_phone=t(h($_POST['geren_phone']));
		$geren_xuexiao=t(h($_POST['geren_xuexiao']));
		$geren_banji=t(h($_POST['geren_banji']));
		$geren_weizhi=t(h($_POST['geren_weizhi']));
		$geren_teamid=t(h($_POST['geren_teamid']));

		if(empty($geren_teamid)){
			$cc['jieguo']="nogerenteamid";
			echo json_encode($cc);
			exit;
		}
		if(empty($geren_name)){
			$cc['jieguo']="nogerenname";
			echo json_encode($cc);
			exit;
		}
		if(empty($geren_phone)){
			$cc['jieguo']="nogerenphone";
			echo json_encode($cc);
			exit;
		}
		if(empty($geren_xuexiao)){
			$cc['jieguo']="nogerenxuexiao";
			echo json_encode($cc);
			exit;
		}
		if(empty($geren_banji)){
			$cc['jieguo']="nogerenbanji";
			echo json_encode($cc);
			exit;
		}
		if(empty($geren_weizhi)){
			$cc['jieguo']="nogerenweizhi";
			echo json_encode($cc);
			exit;
		}

		//存战队 队员信息
		$dui_user['uid']=$uid;
		$dui_user['name']=$geren_name;
		$dui_user['xuexiao']=$geren_xuexiao;
		$dui_user['phone']=$geren_phone;
		$dui_user['banji']=$geren_banji;
		$dui_user['weizhi']=$geren_weizhi;
		$dui_user['tid']=$geren_teamid;
		$dui_user_jiu=M('bs_userinfo')->where("uid=".$uid."")->find();
		if(empty($dui_user_jiu)){
			$dui_user_add_res=M('bs_userinfo')->add($dui_user);
		}else{
			$dui_user['id']=$dui_user_jiu['id'];
			$dui_user_save_res=M('bs_userinfo')->save($dui_user);
			$teaminfo=M('bs_team')->where("duid=".$uid."")->find();
			if(!empty($teaminfo)){
				$teaminfosave=M('bs_team')->where("duid=".$uid."")->setField('xuexiao',$geren_xuexiao);
			}

		}

		$cc['jieguo']="cg";
		echo json_encode($cc);
		exit;

	}
	//战队成员信息
	function perinfo(){
		$uid=$_SESSION['user_id'];
		$getuid=t(h($_GET['uid']));

		$geren=M('bs_userinfo')->where("uid=".$getuid."")->find();

		$xuexiao=M("bs_xuexiao")->where("id=".$geren['xuexiao']."")->find();
		$geren['xuexiaoname']=$xuexiao['name'];
		$geren_weizhi=M("bs_weizhi")->where("id=".$geren['weizhi']."")->find();
		$geren['weizhiname']=$geren_weizhi['name'];
		$this->assign('geren',$geren);

		$bs_xuexiao=M("bs_xuexiao")->order("xu asc,id asc")->select();
		$this->assign("bs_xuexiao",$bs_xuexiao);
		$bs_weizhi=M("bs_weizhi")->order("xu asc,id asc")->select();
		$this->assign("bs_weizhi",$bs_weizhi);


		$webtitle="战队成员信息-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	//我的SOLO
	function soloindex(){
		$uid=$_SESSION['user_id'];

		$solo_info=M('bs_userinfo')->where("uid=".$uid." and soloname!=''")->find();
		if(empty($solo_info)){
			header("Location:http://".$_SERVER['HTTP_HOST']."/index.php?a=sologames&m=index");
			exit;
		}

		$xuexiao=M("bs_xuexiao")->where("id=".$solo_info['xuexiao']."")->find();
		$solo_info['xuexiaoname']=$xuexiao['name'];
		$this->assign('solo_info',$solo_info);

		//参赛记录
		$jilu=M("bs_saicheng")->where("user1=".$solo_info['uid']." or user2=".$solo_info['uid']."")->select();
		foreach($jilu as $k=>$v){
			$jilu[$k]['user1info']=M('bs_userinfo')->where("uid=".$v['user1']."")->find();
			$jilu[$k]['user2info']=M('bs_userinfo')->where("uid=".$v['user2']."")->find();
		}
		$this->assign('jilu',$jilu);

		$webtitle=$solo_info['soloname']."-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	//SOLO
	function sologames(){
		$uid=$_SESSION['user_id'];
		$bs_xuexiao=M("bs_xuexiao")->order("xu asc,id asc")->select();
		$this->assign("bs_xuexiao",$bs_xuexiao);

		$zd=M("bs_userinfo")->where("uid=".$uid."")->find();
		if(!empty($zd)){
			$zd_xuexiao=M("bs_xuexiao")->where("id=".$zd['xuexiao']."")->find();
			$zd['xuexiaoname']=$zd_xuexiao['name'];
			$zd_weizhi=M("bs_weizhi")->where("id=".$zd['weizhi']."")->find();
			$zd['weizhiname']=$zd_weizhi['name'];
		}
		$this->assign("zd",$zd);

		$webtitle="报名红牛SOLO赛-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	function dosolo(){
		$uid=$_SESSION["user_id"];
		$zd_solologo=t(h($_POST['zd_solologo']));
		$zd_soloname=t(h($_POST['zd_soloname']));
		$zd_xuanyan=t(h($_POST['zd_xuanyan']));
		$zd_name=t(h($_POST['zd_name']));
		$zd_phone=t(h($_POST['zd_phone']));
		$zd_xuexiao=t(h($_POST['zd_xuexiao']));
		$zd_banji=t(h($_POST['zd_banji']));
		if(empty($zd_solologo)){
			$cc['jieguo']="nosolologo";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_soloname)){
			$cc['jieguo']="nosoloname";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_xuanyan)){
			$cc['jieguo']="noxuanyan";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_name)){
			$cc['jieguo']="noname";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_phone)){
			$cc['jieguo']="nophone";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_xuexiao)){
			$cc['jieguo']="noxuexiao";
			echo json_encode($cc);
			exit;
		}
		if(empty($zd_banji)){
			$cc['jieguo']="nobanji";
			echo json_encode($cc);
			exit;
		}


		//存战队队长信息
		$dui_user['solologo']=$zd_solologo;
		$dui_user['soloname']=$zd_soloname;
		$dui_user['xuanyan']=$zd_xuanyan;
		$dui_user['uid']=$uid;
		$dui_user['name']=$zd_name;
		$dui_user['xuexiao']=$zd_xuexiao;
		$dui_user['phone']=$zd_phone;
		$dui_user['banji']=$zd_banji;
		$dui_user_jiu=M('bs_userinfo')->where("uid=".$uid."")->find();
		if(empty($dui_user_jiu)){
			$dui_user_add_res=M('bs_userinfo')->add($dui_user);
		}else{
			$dui_user['id']=$dui_user_jiu['id'];
			$dui_user_save_res=M('bs_userinfo')->save($dui_user);

		}

		$cc['jieguo']="cg";
		echo json_encode($cc);
		exit;

	}
	//赛程
	function lolrace(){
		$uid=$_SESSION['user_id'];
		$bs_xiangmu=M("bs_xiangmu")->order("xu asc,id asc")->select();
		$this->assign("bs_xiangmu",$bs_xiangmu);
		$bs_xuexiao=M("bs_xuexiao")->order("xu asc,id asc")->select();
		$this->assign("bs_xuexiao",$bs_xuexiao);
		$xid=t(h($_GET['xid']));
		if(empty($xid)){
			$xid=1;
		}
		$mid=t(h($_GET['mid']));
		if(empty($mid)){
			$mid=2;
		}

		$bs_xiangmuname=M("bs_xiangmu")->where("id=".$mid."")->find();
		$this->assign('bs_xiangmuname',$bs_xiangmuname);
		$bs_xuexiaoname=M("bs_xuexiao")->where("id=".$xid."")->find();
		$this->assign('bs_xuexiaoname',$bs_xuexiaoname);
		//参赛记录
		$days=M("bs_day")->order("day desc,id desc")->select();
		$weekarray=array("日","一","二","三","四","五","六");
		foreach($days as $k=>$v){
			$days_time=strtotime($v['day']." 00:00:00");
			$days[$k]['xingqi']=$weekarray[date("w",$days_time)];
			$days[$k]['jilu']=M("bs_saicheng")->where("xiangmu=".$mid." and qu=".$xid." and day=".$v['id']."")->select();
			foreach($days[$k]['jilu'] as $kk=>$vv){
				if($mid==3){
					$days[$k]['jilu'][$kk]['user1info']=M('bs_userinfo')->where("uid=".$vv['user1']."")->find();
					$days[$k]['jilu'][$kk]['user2info']=M('bs_userinfo')->where("uid=".$vv['user2']."")->find();
				}else{
					$days[$k]['jilu'][$kk]['team1info']=M('bs_team')->where("id=".$vv['team1']."")->find();
					$days[$k]['jilu'][$kk]['team2info']=M('bs_team')->where("id=".$vv['team2']."")->find();
				}
			}
		}

		$this->assign('days',$days);

		$webtitle="赛程-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	//赛事
	function games(){
		$uid=$_SESSION['user_id'];


		$webtitle="赛事-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	//众筹
	function cfunding(){
		$uid=$_SESSION['user_id'];
		$id=t(h($_GET['tid']));
		$team=M("bs_team")->where("id=".$id."")->find();
		$this->assign('team',$team);

		$user=M("users")->where("userId=".$uid."")->find();
		$yiyouzhuli=M("bs_chou")->where("uid=".$uid." and tid=".$id."")->find();
		if(!empty($yiyouzhuli)){
			$user['zhulizhi']=$yiyouzhuli['zhuli'];
		}else{
			$user['zhulizhi']=0;
		}
		$dazhuli=M("bs_chou")->where("uid=".$uid." and tid=".$id." and zhuli>=".$user['zhulizhi']." and ctime<".$user['ctime']."")->count();
		$user['paiming']=$dazhuli+1;
		$this->assign('user',$user);

		$zhulilist=M("bs_chou")->where("tid=".$id."")->order("zhuli desc,ctime asc")->select();
		foreach($zhulilist as $k=>$v){
			$zhulilist[$k]['userinfo']=M("users")->where("userId=".$v['uid']."")->find();
		}
		$this->assign('zhulilist',$zhulilist);

		$webtitle="众筹-玩贝电竞";
		$this->assign('webtitle',$webtitle);

		$this->display();
	}
	//助力战队
	function dozhuli(){
		$tid=t(h($_POST['tid']));
		$uid=$_SESSION['user_id'];

		$user_info=M("users")->where("userId=".$uid."")->find();
		if($user_info['golds']<110){
			$cc['jieguo']="yuebuzu";
			$cc['user_yue']=floor($user_info['golds']);
			echo json_encode($cc);exit;
		}

		$new['changeAmount']=-110;
		$new['amount']=$user_info['golds']-110;
		$new['ctime']=time();
		$new['userId']=$uid;
		$new['fromid']=15;
		$new['detail']="战队众筹";

		$record_res=M("user_golds_record")->add($new);
		if($record_res){
			$user_info['golds']=$new['amount'];
			$user_res=M("users")->save($user_info);

			$yiyouzhuli=M("bs_chou")->where("uid=".$uid." and tid=".$tid."")->find();
			if(!empty($yiyouzhuli)){
				$yiyouzhuli_save=M("bs_chou")->where("id=".$yiyouzhuli['id']."")->setInc('zhuli',1);
				$yiyouzhuli_save_time=M("bs_chou")->where("id=".$yiyouzhuli['id']."")->setField('ctime',time());
			}else{
				$newzhuli['uid']=$uid;
				$newzhuli['tid']=$tid;
				$newzhuli['zhuli']=1;
				$newzhuli['ctime']=time();
				$yiyouzhuli_add=M("bs_chou")->add($newzhuli);
			}

			$team_save=M("bs_team")->where("id=".$tid."")->setInc('jiangjin',1);
			$nowteam=M("bs_team")->where("id=".$tid."")->find();

			if($user_info['invite_by_userId']>0 && $user_info['invite_by_userId']!=$uid){
				//查询有没有参与过助力本战队
				$zy_ly_user=M("users")->where("userId=".$user_info['invite_by_userId']."")->find();

				$shifoujc=M("bs_chou")->where("uid=".$zy_ly_user['userId']." and tid=".$tid."")->find();
				if(empty($shifoujc)){
					$zy_laiyuan['uid']=$zy_ly_user['userId'];
					$zy_laiyuan['tid']=$tid;
					$zy_laiyuan['zhuli']=$zy_ly_user['zhuli']+1;
					$zy_laiyuan['ctime']=time();
					$add_zy_laiyuan=M("bs_chou")->add($zy_laiyuan);
				}else{
					$save_zy_laiyuan=M("bs_chou")->where("id=".$shifoujc['id']."")->setInc('zhuli',1);
					$save_zy_laiyuan_time=M("bs_chou")->where("id=".$shifoujc['id']."")->setField('ctime',time());
				}

			}

			$cc['jieguo']="cg";
			$cc['jiangjin']=$nowteam['jiangjin'];

			echo json_encode($cc);exit;

		}



		$cc['jieguo']="sb";
		echo json_encode($cc);exit;
	}
	function aa(){
		dd($_SESSION);
	}
	function bb(){
		unset($_SESSION['token']);
	}
	//设置令牌
	function setFormCode(){
		if(empty($_SESSION['token'])){
			echo $_SESSION['token']=md5($_SESSION['user_id'].$_SESSION['gametypeid'].time());
		}else{
			echo false;
		}
	}


}
