<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 首页、列表页面、详细页
 */
 
class IndexAction extends AllAction{

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
	//首页shishi
//	function indexshishi(){
//		$del = M("gg_lbimg")->where("id>0")->setField('webid','1');//1为发布
//        echo $del?'1':'0';
//		$this->display('index');
//	}
	 
	//首页
	function shouye(){
		$this->display();
	}
	function index(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$today_cs_time=strtotime(date("Y-m-d",time())." 00:00:00");
		$yestday_cs_time=$today_cs_time-(60*60*24);
		//总用户数
		$user_count=M("users")->count();
		$this->assign('user_count', $user_count);
		//昨日新用户
		$yestday_user_count=M("users")->where("ctime>=".$yestday_cs_time." and ctime<".$today_cs_time."")->count();
		$this->assign('yestday_user_count', $yestday_user_count);
		//昨日总充值数
		$yestday_chongzhi=M("user_golds_record")->field("changeAmount")->where("fromid=4 and ctime>=".$yestday_cs_time." and ctime<".$today_cs_time."")->select();
		foreach($yestday_chongzhi as $k=>$v){
			$yestday_chongzhi_golds=$yestday_chongzhi_golds+$v['changeAmount'];
		}
		$yestday_chongzhi_rmb=$yestday_chongzhi_golds/100;
		$this->assign('yestday_chongzhi_rmb', $yestday_chongzhi_rmb);
		//昨日总兑换数
		$yestday_duihuan=M("user_golds_record")->field("changeAmount")->where("txstate>=1 and ctime>=".$yestday_cs_time." and ctime<".$today_cs_time."")->select();
		foreach($yestday_duihuan as $k=>$v){
			if(abs($v['changeAmount'])==2400){
				$yestday_duihuan_rmb=$yestday_duihuan_rmb+20;
			}else if(abs($v['changeAmount'])==5700){
				$yestday_duihuan_rmb=$yestday_duihuan_rmb+50;
			}else if(abs($v['changeAmount'])==11000){
				$yestday_duihuan_rmb=$yestday_duihuan_rmb+100;
			}else if(abs($v['changeAmount'])>11000){
				$yestday_duihuan_rmb=$yestday_duihuan_rmb+(abs($v['changeAmount'])/110);
			}
		}
		$this->assign('yestday_duihuan_rmb', $yestday_duihuan_rmb);
		//昨日竞猜人次
		$yestday_jcs=M("user_golds_record")->where("fromid IN (2,3) and changeAmount<0 and ctime>=".$yestday_cs_time." and ctime<".$today_cs_time."")->select();
		$yestday_jc_count=count($yestday_jcs);
		$this->assign('yestday_jc_count', $yestday_jc_count);
		foreach($yestday_jcs as $k=>$v){
			$yestday_jcs_golds=$yestday_jcs_golds+abs($v['changeAmount']);
			if(!in_array($v['userId'], $yestday_jcs_userid_arr)){
				$yestday_jcs_userid_arr[]=$v['userId'];
			}
		}
		$yestday_jcs_golds=floor($yestday_jcs_golds);
		$this->assign('yestday_jcs_golds', $yestday_jcs_golds);
		//昨日竞猜人数
		$yestday_jcs_user_num=count($yestday_jcs_userid_arr);
		$this->assign('yestday_jcs_user_num', $yestday_jcs_user_num);
		//今日新用户
		$today_user_count=M("users")->where("ctime>=".$today_cs_time."")->count();
		$this->assign('today_user_count', $today_user_count);
		//今日总充值数
		$today_chongzhi=M("user_golds_record")->field("changeAmount")->where("fromid=4 and ctime>=".$today_cs_time."")->select();
		foreach($today_chongzhi as $k=>$v){
			$today_chongzhi_golds=$today_chongzhi_golds+$v['changeAmount'];
		}
		$today_chongzhi_rmb=$today_chongzhi_golds/100;
		$this->assign('today_chongzhi_rmb', $today_chongzhi_rmb);
		//今日总兑换数
		$today_duihuan=M("user_golds_record")->field("changeAmount")->where("txstate>=1 and ctime>=".$today_cs_time."")->select();
		foreach($today_duihuan as $k=>$v){
			if(abs($v['changeAmount'])==2400){
				$today_duihuan_rmb=$today_duihuan_rmb+20;
			}else if(abs($v['changeAmount'])==5700){
				$today_duihuan_rmb=$today_duihuan_rmb+50;
			}else if(abs($v['changeAmount'])==11000){
				$today_duihuan_rmb=$today_duihuan_rmb+100;
			}else if(abs($v['changeAmount'])>11000){
				$today_duihuan_rmb=$today_duihuan_rmb+(abs($v['changeAmount'])/110);
			}
		}
		$this->assign('today_duihuan_rmb', $today_duihuan_rmb);
		//今日竞猜人次
		$today_jcs=M("user_golds_record")->where("fromid IN (2,3) and changeAmount<0 and ctime>=".$today_cs_time."")->select();
		$today_jc_count=count($today_jcs);
		$this->assign('today_jc_count', $today_jc_count);
		foreach($today_jcs as $k=>$v){
			$today_jcs_golds=$today_jcs_golds+abs($v['changeAmount']);
			if(!in_array($v['userId'], $today_jcs_userid_arr)){
				$today_jcs_userid_arr[]=$v['userId'];
			}
		}
		$today_jcs_golds=floor($today_jcs_golds);
		$this->assign('today_jcs_golds', $today_jcs_golds);
		//今日竞猜人数
		$today_jcs_user_num=count($today_jcs_userid_arr);
		$this->assign('today_jcs_user_num', $today_jcs_user_num);

		/*以上为色块所用接口*/
		/*表格数据接口 start*/
			$timeWeight = 60*60*24;

			$thistime = $today_cs_time;

			$lasttime = $thistime - $timeWeight;

			$re = M("users")->where("ctime<".$thistime." and ctime > 0")->count();

			// 总用户数
			// var_dump($re);
			// echo "<br>";

			// var_dump($today_cs_time);
			// var_dump($thistime);
			// var_dump($yestday_cs_time);
			// var_dump($lasttime);

			// $begintime = "2016-07-04 00:00:00";
			// $begintime =  strtotime($begintime);
			// var_dump($begintime);
			// die();

			$recordArr = array();

			while (intval(M("users")->where("ctime<".$thistime." and ctime > 1467561600")->count())>0) {
				$lasttime = $thistime - $timeWeight;

				//新增人数
				$userNum = M("users")->where("ctime>=".$lasttime." and ctime<".$thistime."")->count();

				//充值数
				$changeInCount = M("user_golds_record")->field("changeAmount")->where("fromid=4 and ctime>=".$lasttime." and ctime<".$thistime."")->select();
				$inNum = 0;
				foreach ($changeInCount as $key => $value) {
					$inNum += $value['changeAmount'];
				}

				//兑换数
				$changOutCount=M("user_golds_record")->field("changeAmount")->where("txstate>=1 and ctime>=".$lasttime." and ctime<".$thistime."")->select();
				$outNum = 0;
				foreach($changOutCount as $k=>$v){
					if(abs($v['changeAmount'])==2400){
						$outNum=$outNum+20;
					}else if(abs($v['changeAmount'])==5700){
						$outNum=$outNum+50;
					}else if(abs($v['changeAmount'])==11000){
						$outNum=$outNum+100;
					}else if(abs($v['changeAmount'])>11000){
						$outNum=$outNum+(abs($v['changeAmount'])/110);
					}
				}

				//竞猜人次
				// 竞猜金币
				$playSum = 0;
				$playeUserSum = 0;
				$playUserNum = 0;
				$playNum=M("user_golds_record")->where("fromid IN (2,3) and changeAmount<0 and ctime>=".$lasttime." and ctime<".$thistime."")->select();
				$playUserNum=count($playNum);
				foreach($playNum as $k=>$v){
					$playSum=$playSum+abs($v['changeAmount']);
					if(!in_array($v['userId'], $playUserArr)){
						$playUserArr[]=$v['userId'];
					}
				}
				$playSum=floor($playSum);
				$playeUserSum=count($playUserArr);
				$playeUserSumci=count($playNum);
				unset($playUserArr);



				// echo "时间：".date("Y-m-d",$thistime)."| 新增人数：".$userNum."|充值数:".($inNum/100)."|兑换数：".$outNum."|竞猜金币：".$playSum."|竞猜人次：".$playeUserSum."<br>";

				$record = array(
					"time"=>date("Y-m-d",$lasttime),
					'userNum'=>$userNum,
					'inNum'=>$inNum/100,
					'outNum'=>$outNum,
					'playSum'=>$playSum,
					'playeUserSumci'=>$playeUserSumci,
					'playeUserSum'=>$playeUserSum);
				// var_dump($record);
				array_push($recordArr, $record);

				$thistime = $lasttime;

			}
			// var_dump($recordArr);
		/*表格数据接口 end*/
		$this->assign('record',$recordArr);
		$this->display();

	}
	/*function yxc_kj(){
		$qishu=t(h($_GET['qnum']));
		if(empty($qishu)){
			$kj_jg=$this->yxc_kj_do(30);
		}else{
			$kj_jg=$this->yxc_kj_do($qishu);
		}
		if(!empty($_SESSION['manage_id'])){
			$add_kj_jg['uid']=$_SESSION['manage_id'];
		}
		$add_kj_jg['ctime']=time();
		$add_kj_jg['qi']=$kj_jg['qishu'];
		$add_kj_jg['game1']=$kj_jg['count1'];
		$add_kj_jg['game2']=$kj_jg['count2'];
		M("roulette_logdo")->add($add_kj_jg);
		var_dump($kj_jg);
		exit;

	}
	function yxc_kj_do($qishu){
		
		require('./phpQuery/phpQuery.php');
		
		$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=0&span='.$qishu);  //获取$cha_cishu期
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
		//$kaijiang循环 开始
		foreach($kaijiang as $k=>$v){
			$old_rlog1[$k]=M("roulette_log")->where("day=".$v['day']." and gid=1 and qi='".$v['qi']."'")->find();
			//如果没有旧的开奖记录，则存储 开始
			if(empty($old_rlog1[$k])){
				$new_log1['ctime']=time();
				$new_log1['num']=$v['num'];
				$new_log1['day']=$v['day'];
				$new_log1['qi']=$v['qi'];
				$new_log1['ktime']=time();
				$new_log1['gid']=1;
				$new_log1['heroid']=$v['num']%129+1;
				$add_rlog1[$k]=M("roulette_log")->add($new_log1);
				//判断 开奖结果 是否存储成功 开始
				if($add_rlog1[$k]){
					//中奖号码 换算为英雄 id
					$hero_info1[$k]=M("roulette_hero_property")->where("roulette_heroId=".$new_log1['heroid']."")->select();
					foreach($hero_info1[$k] as $kk=>$vv){
						$hero_win_pro1[$k][]=$vv['roulette_propertyId'];
					}
					//投注 循环 开始
					$touzhu_log1[$k]=M("user_golds_record")->where("jieortou=2 and gid=1 and herodayqi=".$v['day'].$v['qi']."")->order("ctime asc,user_golds_recordId asc")->select();
					foreach($touzhu_log1[$k] as $kk=>$vv){
						$touzhu_check=explode(',',$vv['herocheck']);
						$touzhu_check_count=count($touzhu_check);
						$array_intersect=array_intersect($touzhu_check,$hero_win_pro1[$k]);
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
							
							$this->sendTemplateHero($win_golds['userId'],$win_golds['changeAmount'],($v['day'].$v['qi']),abs($vv['changeAmount']));

							$user_info["golds"]=$win_golds['amount'];
							M("users")->save($user_info);
							
						}else{
							$touzhu_log1[$k][$kk]['nowin']=1;
							M("user_golds_record")->save($touzhu_log1[$k][$kk]);
						}
						
					}
					//投注 循环 结束

				}
				//判断 开奖结果 是否存储成功 结束
				
			}
			//如果没有旧的开奖记录，则存储 结束
			
			$old_rlog2[$k]=M("roulette_log")->where("day=".$v['day']." and gid=2 and qi='".$v['qi']."'")->find();
			//如果没有旧的开奖记录，则存储 开始
			if(empty($old_rlog1[$k])){
				$new_log2['ctime']=time();
				$new_log2['num']=$v['num'];
				$new_log2['day']=$v['day'];
				$new_log2['qi']=$v['qi'];
				$new_log2['ktime']=time();
				$new_log2['gid']=2;
				$new_log2['heroid']=$v['num']%112+1+129;
				$add_rlog2[$k]=M("roulette_log")->add($new_log2);
				//判断 开奖结果 是否存储成功 开始
				if($add_rlog2[$k]){
					//中奖号码 换算为英雄 id
					$hero_info2[$k]=M("roulette_hero_property")->where("roulette_heroId=".$new_log2['heroid']."")->select();
					foreach($hero_info2[$k] as $kk=>$vv){
						$hero_win_pro2[$k][]=$vv['roulette_propertyId'];
					}
					//投注 循环 开始
					$touzhu_log2[$k]=M("user_golds_record")->where("jieortou=2 and gid=2 and herodayqi=".$v['day'].$v['qi']."")->order("ctime asc,user_golds_recordId asc")->select();
					foreach($touzhu_log2[$k] as $kk=>$vv){
						$touzhu_check=explode(',',$vv['herocheck']);
						$touzhu_check_count=count($touzhu_check);
						$array_intersect=array_intersect($touzhu_check,$hero_win_pro2[$k]);
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

							$this->sendTemplateHero($win_golds['userId'],$win_golds['changeAmount'],($v['day'].$v['qi']),abs($vv['changeAmount']));

							$user_info["golds"]=$win_golds['amount'];
							M("users")->save($user_info);
							
						}else{
							$touzhu_log2[$k][$kk]['nowin']=1;
							M("user_golds_record")->save($touzhu_log2[$k][$kk]);
						}
						
					}
					//投注 循环 结束

				}
				//判断 开奖结果 是否存储成功 结束
				
			}
			//如果没有旧的开奖记录，则存储 结束
		}
		//$kaijiang循环 结束
		
		
		$add_rlog_count['qishu']=$qishu;
		$add_rlog_count['count1']=count($add_rlog1);
		$add_rlog_count['count2']=count($add_rlog2);
		return $add_rlog_count;
	}*/


	
	
	
} 