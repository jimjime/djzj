<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 首页、列表页面、详细页
 */
 
class ChgameAction extends AllAction{

    function _initialize(){
				
		//$_SESSION['user_id']=1;
//		if($_SESSION['user_id']==774){
//			$_SESSION['user_id']="";
//			$_SESSION['gametypeid']="";
//		}
//		exit;
		//判断登录
		if(empty($_SESSION['user_id'])){
			//$_SESSION['lyurl']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$_SESSION['lyurl']=$_SERVER['HTTP_REFERER'];
			if($_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=index&m=Index" && $_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=roll&m=active" && $_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=indexs&m=active" && $_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=art&m=index&aid=8" && $_SESSION['lyurl']!="http://".$_SERVER['HTTP_HOST']."/index.php?a=myqr&m=index"){
				$_SESSION['lyurl']="http://".$_SERVER['HTTP_HOST']."/index.php?a=index&m=Index";
			}
			header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=loginbyweixin&m=User");
			exit;	
		}else{

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
			if($user['lastqdtime']<$today_time){
			
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
					M("user_golds_record")->add($qiandao_gold);	
					
					
					$user['golds']=$user['golds']+$qiandao_gold['changeAmount']+$first_login_gold['changeAmount']-abs($jl_qingling_golds['changeAmount']);
					$user['qdnum']=$new_qdnum;
					$user['lastqdtime']=time();
	
					$qiandaocg=M("users")->save($user);
					$this->assign('qiandaocg', $qiandaocg);
					$this->assign('new_qdnum', $new_qdnum);
					
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
	

	//切换游戏页
	function chtype(){
		$webtitle="切换游戏-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	
} 