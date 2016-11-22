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

	//召唤英雄
	function summonHero(){

	}
	//召唤英雄投注
	function summonHerosBat(){

	}

	function summonHerosBat(){

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

					if(empty($old_rlog[$k])){//如果没有旧的开奖记录，则存储
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
