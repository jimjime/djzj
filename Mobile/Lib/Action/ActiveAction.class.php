<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 首页、列表页面、详细页
 */
 
class ActiveAction extends AllAction{

    function _initialize(){
				
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
			//判断游戏id lol为1 dota为2
			$gametypeid=t(h($_GET['gtypeid']));
			if(!empty($gametypeid)){
				$_SESSION['gametypeid']=$gametypeid;
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
			if($user['lastqdtime']<$today_time){
			
				$bef_yetday_qiandao=M("user_golds_record")->where("fromid=1 and ctime BETWEEN ".$user['lastqdtime']." AND ".$today_time." and userId=".$user['userId']."")->order("ctime desc")->select();
				$bef_yetday_qiandao_jlgolds=0;
				foreach($bef_yetday_qiandao as $k=>$v){
					$bef_yetday_qiandao_jlgolds=$bef_yetday_qiandao_jlgolds+abs($v['changeAmount']);
				}
				$bef_yetday_golds=M("user_golds_record")->where("fromid IN (2,3) and ctime BETWEEN ".$user['lastqdtime']." AND ".$today_time." and userId=".$user['userId']." and changeAmount<0")->order("ctime desc")->select();
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
	 
	//首页
	function indexs(){
		$zjts_info=$this->getzjinfo();
		$this->assign('zjts_info',$zjts_info);
		
		$uid=$_SESSION['user_id'];
		$pifu199count=M("active_record")->where("userId=".$uid." and prizeId=8")->count();
		$pifu69count=M("active_record")->where("userId=".$uid." and prizeId=7")->count();
		$this->assign('pifu199count',$pifu199count);
		$this->assign('pifu69count',$pifu69count);
//		$islc=M("user_golds_record")->where("userId=".$uid." and fromid=12 and changeAmount=-888")->find();
//		$this->assign('islc',$islc);
		
		$webtitle="活动-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	function getzjinfo(){
		$chars='BCDEFGHJKMNPQRSTWXYZbcdefhijkmnprstwxyz纣王偏宠妲己妖姐抽的是寂寞②号当铺，典当灵魂ぴ╰华灯初上、旧人可安°℡懒懒DE猪﹂生﹂世﹂双人ら 我爱白黑罗永美哈二风雨琪起夜晚请哦的看老师才是女人无情便是王美美的校霸花深爱是场谋杀！怎样自在怎样活姐的拽、你不懂漫长の人生丿super丶潮流灬煙消雲散只為成全-我在地狱仰望天堂┅涟漪、水波在泛滥══做个低调の孩纸只求一份安定↘▂_倥絔笑叹★尘世美◆乱世梦红颜?、花容月貌吥↘恠侑噯↘莮亾 ↘ 哋 洺 牸ㄟㄜ~{离隹}笑叹。红尘杯中酒，鸳鸯情。'; // 默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1  
		$maxPos=mb_strlen($chars,'utf-8')-1;
		//$pwd='';  
		$keyzhi1=rand(0,$maxPos);
		$pwd1=mb_substr($chars,$keyzhi1,1,'utf-8');
		$keyzhi2=rand(0,$maxPos);
		$pwd2=mb_substr($chars,$keyzhi2,1,'utf-8');
		$jgkey=rand(1,2);
		if($jgkey==1){
			$zjts_info="恭喜玩家".$pwd1."***".$pwd2."集齐十个【199皮肤碎片】";
		}else{
			$zjts_info="恭喜玩家".$pwd1."***".$pwd2."集齐五个【69皮肤碎片】";
		}
		return $zjts_info;  
	}
	function getzjinfo_ajax(){
		$zjts_info=$this->getzjinfo();
		echo $zjts_info;  
	}

	//单次抽奖接口
	function lottery()
	{
		$active = new ActiveModel();

		$userId = $_SESSION['user_id'];


		// 判断是否再来一次
		$again_num=M("active_record")->where("userId=".$userId." and prizeId=4 and isuse=0 ")->order("id asc")->find();
		if(!empty($again_num)){
			M("active_record")->where("id=".$again_num['id']."")->setField('isuse','2');
		}else{

			//判断余额不足情况
	
			$re = $active->yue($userId,88);
	
			if (!$re) {
				$jsonStr['jg'] = 'yuebuzu';
				$jsonStr['id'] = 0;
				echo json_encode($jsonStr);
				exit;
			}
			//扣除金币
			$re = $active->doCoin($userId,-88,11);
		}

		//获取碎片数
		$times = $active->getPiecesNumFromRecord($userId);

		$smallPiecesTimes = $times['smallPiecesTimes'];

		$bigPiecesTimes = $times['bigPiecesTimes'];

		//前提：smallPiecesTimes<=3 bigPiecesTimes<=8
		if (($smallPiecesTimes>1)||($bigPiecesTimes>1)) {

			if((($smallPiecesTimes==3)&&($bigPiecesTimes==8)) || (($smallPiecesTimes>3)&&($bigPiecesTimes>8))){
				$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = 3 and bigPiecesTimes = 8")->select();
			}elseif(($smallPiecesTimes>=3)&&($bigPiecesTimes<8)){
				$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = 3 and bigPiecesTimes = 7")->select();
			}elseif(($bigPiecesTimes>=8)&&($smallPiecesTimes<3)){
				$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = 2 and bigPiecesTimes = 8")->select();
			}else{
				$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = 2 and bigPiecesTimes = 7")->select();
			}

		}else{
			$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = ".$smallPiecesTimes." and bigPiecesTimes = ".$bigPiecesTimes)->select();
		}

		$percentArr = array();	
		foreach ($prizeArr as $key => $value) {

			array_push($percentArr,$value['percentage']);
		}
		
		//抽奖开始

		$randNum = mt_rand(1,array_sum($percentArr));

		$sum = 0;

		foreach ($prizeArr as $key => $value) {
			$sum +=intval($value['percentage']);
			if ($randNum<=$sum) {
				$prizeId = intval($value['prizeId']);
				break;
			}
		}

		//判断抽奖结果
		if ($prizeId == 1) {
			$re = $active->doCoin($userId,6,11);
		}

		if ($prizeId == 2) {
			$re = $active->doCoin($userId,66,11);
		}

		if ($prizeId == 3) {
			$re = $active->doCoin($userId,666,11);
		}

		if ($prizeId == 7) {
			$smallPiecesTimes++;
		}

		if ($prizeId == 8) {
			$bigPiecesTimes++;
		}

		//后台操作
		$userInfo = M('active_user_info');
		$re = $userInfo->where('userId = '.$userId)->find();
		$givePieces = intval($re['givePieces']);
		if ($givePieces > 0) {
			$prizeId = 7;
			$result = $userInfo->where('userId = '.$userId)->setDec('givePieces');
		}
		//保存抽奖记录
		$re = $active->insertIntoRecord($userId,$prizeId);
		
		//保存用户信息
		$userInfo = array(
			'userId'=>$userId,
			'smallPiecesTimes'=>$smallPiecesTimes,
			'bigPiecesTimes'=>$bigPiecesTimes);
		// var_dump($userInfo);
		$active->updateUserActiveInfo($userId,$userInfo);

		
		//保存包裹信息
		if ($prizeId>4) {
			$active->addPackInfo($userId,$prizeId);
		}
		
		$jsonStr['id'] =  $prizeId;
		$jsonStr['jg'] = 'cg';
		echo json_encode($jsonStr);
	}


	//连抽十次
	function decLottery()
	{
		$active = new ActiveModel();

		$userId = $_SESSION['user_id'];
		//判断是否参加过十连抽
//		$re = M('user_golds_record')->where("fromid = 12 and userId = ".$userId."")->find();
//		var_dump($re);
//		if ($re) {
//			$jsonStr['jg'] = 'cjg';
//			$jsonStr['id'] = 0;
//			echo json_encode($jsonStr);
//			exit;
//		}	
		//$userId = 3;

		// 判断余额不足情况
		$re = $active->yue($userId,888);

		if (!$re) {
			$jsonStr['jg'] = 'yuebuzu';
			$jsonStr['id'] = 0;
			echo json_encode($jsonStr);
			exit;
		}
		// 判断是否参加过十连抽

		//扣除金币

		//记录金币流水、用户余额
		$re = $active->doCoin($userId,-888,12);

		// 记录活动信息
		/*
		* tables:
		* active_record 
		* active_user_info 
		* active_user_package
		*/
		
		//保存用户信息
//		$userInfo = $active->getPiecesNumById($userId);
//		$userInfo['smallPiecesTimes']++;
//		$userInfo['bigPiecesTimes']++;
//		$active->updatePiecesNum($userId,$userInfo);

		/*十连抽开始*/
		$prizeInfoArr = array(
			1=>array(
				'picAdd'=>'./Public/Mobile/new/images/slchou_jinbi6.jpg',
				'prizeName'=>'6金币'
				),
			2=>array(
				'picAdd'=>'./Public/Mobile/new/images/slchou_jinbi66.jpg',
				'prizeName'=>'66金币'
				),
			3=>array(
				'picAdd'=>'./Public/Mobile/new/images/slchou_jinbi666.jpg',
				'prizeName'=>'666金币'
				),
			5=>array(
				'picAdd'=>'./Public/Mobile/new/images/slchou_dianquan.jpg',
				'prizeName'=>'100点卷'
				),
			6=>array(
				'picAdd'=>'./Public/Mobile/new/images/slchou_pifu3.jpg',
				'prizeName'=>'限时皮肤'
				),
			7=>array(
				'picAdd'=>'./Public/Mobile/new/images/slchou_pifu69.jpg',
				'prizeName'=>'69元皮肤碎片'
				),
			8=>array(
				'picAdd'=>'./Public/Mobile/new/images/slchou_pifu199.jpg',
				'prizeName'=>'199元皮肤碎片'
				)
			);
		// var_dump($prizeInfoArr);
		//$numArr = array('一','二','三','四','五','六','七','八','九','十');

		// var_dump($numArr);
		// 打乱数组
//		$prizeIdArr = array(
//			1=>1,
//			2=>1,
//			3=>1,7,2,6,8,2,5,2);		
//		shuffle($prizeIdArr);
		// var_dump($prizeIdArr);

		$i = 10;
		while ($i-->0) {

			//获取碎片数
			$times = $active->getPiecesNumFromRecord($userId);

			
			$smallPiecesTimes = $times['smallPiecesTimes'];

			$bigPiecesTimes = $times['bigPiecesTimes'];

			//前提：smallPiecesTimes<=3 bigPiecesTimes<=8
			
			if (($smallPiecesTimes>1)||($bigPiecesTimes>1)) {

				if((($smallPiecesTimes==3)&&($bigPiecesTimes==8))||(($smallPiecesTimes>3)&&($bigPiecesTimes>8))){
					$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = 3 and bigPiecesTimes = 8")->select();
				}elseif(($smallPiecesTimes>=3)&&($bigPiecesTimes<8)){
					$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = 3 and bigPiecesTimes = 7")->select();
				}elseif(($bigPiecesTimes>=8)&&($smallPiecesTimes<3)){
					$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = 2 and bigPiecesTimes = 8")->select();
				}else{
					$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = 2 and bigPiecesTimes = 7")->select();
				}

			}else{
				$prizeArr = M("active_prize_weight")->where(" smallPiecesTimes = ".$smallPiecesTimes." and bigPiecesTimes = ".$bigPiecesTimes)->select();
			}

			$percentArr = array();	
			foreach ($prizeArr as $key => $value) {

				array_push($percentArr,$value['percentage']);
			}
					
			//抽奖开始

			$randNum = mt_rand(1,array_sum($percentArr));

			$sum = 0;

			foreach ($prizeArr as $key => $value) {
				$sum +=intval($value['percentage']);
				if ($randNum<=$sum) {
					$prizeId = intval($value['prizeId']);
					break;
				}
			}

			if ($prizeId == 4) {
				$prizeId = 2;
			}
			//判断抽奖结果
			if ($prizeId == 1) {
				$re = $active->doCoin($userId,6,12);
			}

			if ($prizeId == 2) {
				$re = $active->doCoin($userId,66,12);
			}

			if ($prizeId == 3) {
				$re = $active->doCoin($userId,666,12);
			}

			if ($prizeId == 7) {
				$smallPiecesTimes++;
			}

			if ($prizeId == 8) {
				$bigPiecesTimes++;
			}



			// 后台操作
			$userInfo = M('active_user_info');
			$re = $userInfo->where('userId = '.$userId)->find();
			$givePieces = intval($re['givePieces']);
			if ($givePieces > 0) {
				$prizeId = 7;
				$result = $userInfo->where('userId = '.$userId)->setDec('givePieces');
			}
			//保存抽奖记录
			$re = $active->insertIntoRecord($userId,$prizeId);
			
			//保存用户信息
			$userInfo = array(
				'userId'=>$userId,
				'smallPiecesTimes'=>$smallPiecesTimes,
				'bigPiecesTimes'=>$bigPiecesTimes);
			// var_dump($userInfo);
			$active->updateUserActiveInfo($userId,$userInfo);

			
			//保存包裹信息
			if ($prizeId>4) {
				$active->addPackInfo($userId,$prizeId);
			}

			$resStr .= '<div class="tc_slchou_box_ul">
				<div class="tc_slchou_box_li">
					<div class="tc_slchou_box_li_t">
					第'.(10-$i).'抽
					</div>
					<div class="tc_slchou_box_li_z">
						<img src="'.$prizeInfoArr[$prizeId]['picAdd'].'" />
					</div>
					<div class="tc_slchou_box_li_d">
						'.$prizeInfoArr[$prizeId]['prizeName'].'
					</div>
				</div>
			</div>
			';
			// echo (10-$i);
			// echo($prizeId);
			// echo($prizeInfoArr[$prizeId]['picAdd']);
			// echo($prizeInfoArr[$prizeId]['prizeName']);		
			// echo "<br>";
		}


		// 拼接字符串

		// 获得金币
		//$re = $active->doCoin($userId,216,12);

		$jsonStr['jg'] = 'cg';
		$jsonStr['jieguo'] = $resStr;
		echo json_encode($jsonStr);
	}

	// 判断是否参加过十连抽
	function slcChareg($userId)
	{
		$userId = $_SESSION['user_id'];
		$re = M('user_golds_record')->where("fromid = 12 and userId = ".$userId."")->find();
		// var_dump($re);
		if (!$re) {
			$jsonStr['status'] = 'no';
		}else{
			$jsonStr['status'] = 'yes';
		}	
		echo json_encode($jsonStr);
	}

	// 测试用接口

	function test1()
	{
		$this->test(1);
		$active = new ActiveModel();
		$coinNum = $active->yue(2,88);
		var_dump($coinNum);
	}



	function test($test)
	{
		var_dump($test);
	}
	
	function ck(){
		$uid=$_SESSION['user_id'];
		$user_info=M("users")->where("userId=".$uid."")->find();
		$this->assign('user_info',$user_info);
		$jinbilist=M("active_record")->where("userId=".$uid." and prizeId IN (1,2,3)")->select();
		foreach($jinbilist as $k=>$v){
			if($v['prizeId']==1){
				$jinbi+=6;
			}else if($v['prizeId']==2){
				$jinbi+=66;
			}else if($v['prizeId']==3){
				$jinbi+=666;
			}
		}
		$this->assign('jinbi',$jinbi);
		$pifu199count=M("active_record")->where("userId=".$uid." and prizeId=8 and isuse=0")->count();
		$pifu199per=floor(($pifu199count/10)*100);
		$this->assign('pifu199count',$pifu199count);
		$this->assign('pifu199per',$pifu199per);
		$pifu69count=M("active_record")->where("userId=".$uid." and prizeId=7 and isuse=0")->count();
		$pifu69per=floor(($pifu69count/5)*100);
		$this->assign('pifu69count',$pifu69count);
		$this->assign('pifu69per',$pifu69per);
		$pifu3count=M("active_record")->where("userId=".$uid." and prizeId=6 and isuse=0")->count();
		$this->assign('pifu3count',$pifu3count);
		$dianquancount=M("active_record")->where("userId=".$uid." and prizeId=5 and isuse=0")->count();
		$this->assign('dianquancount',$dianquancount);
		$againcount=M("active_record")->where("userId=".$uid." and prizeId=4 and isuse=0")->count();
		$this->assign('againcount',$againcount);

		$webtitle="奖品仓库-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//奖品兑换ajax
	function dodhcjjg(){
		$uid=$_SESSION['user_id'];
		$dhid=t(h($_POST['dhid']));
		$qq=t(h($_POST['qq']));
		$tel=t(h($_POST['tel']));
		//echo $tel;exit;
		if(empty($qq) || $qq==""){
			echo "noqq";exit;
		}
		if(empty($tel) || $tel==""){
			echo "notel";exit;
		}
		if($dhid==5 || $dhid==6 || $dhid==7 || $dhid==8){
			$dhjiangpin['prizeId']=$dhid;
		}else{
			echo "duihuansb";exit;
		}
		$dhjiangpin['userId']=$uid;
		$dhjiangpin['isuse']=1;
		$active_record=M("active_record")->where("userId=".$dhjiangpin['userId']." and prizeId=".$dhjiangpin['prizeId']." and isuse=0")->select();
		$active_record_count=count($active_record);
		if(empty($active_record)){
			echo "duihuansb";exit;
		}else{
			if($dhid==7){
				if($active_record_count<5){
					echo "duihuansb";exit;
				}
			}
			if($dhid==8){
				if($active_record_count<10){
					echo "duihuansb";exit;
				}
			}
			foreach($active_record as $k=>$v){
				M("active_record")->where("id=".$v['id']."")->setField('isuse','1');
			}
//			$user_info=M("users")->where("userId=".$uid."")->find();
//			$user_info['qq']=$qq;
//			$user_info['tel']=$tel;
			M("users")->where("userId=".$uid."")->setField('qq',$qq);
			M("users")->where("userId=".$uid."")->setField('tel',$tel);
			echo "duihuancg";exit;
		}
		
	}

	// roll活动
	// 
	function roll()
	{
		$userId = $_SESSION['user_id'];
		$gid=$_SESSION['gametypeid'];
		// 获取当前期数
		$present = time();
		$termlist = M('active_roll_term')->where("state=1 and gid=".$gid."")->order("id desc")->limit("0,2")->select();
		$termInfo = $termlist[0];
		$termId = $termInfo['id'];
		// 当前一期参与次数
		$used_count = M('active_roll_record')->where('termId = '.$termId.' and userId = '.$userId)->count();
		// 上一期参与次数
		$lasttermInfo = $termlist[1];
		
		$lastTermId = $lasttermInfo['id'];
		//战友招募次数 
		$invite_count = M('users')->where('invite_by_userId = '.$userId)->count();
		// 用户一共参与次数
		$all_useds = M('active_roll_record')->where('userId = '.$userId)->select();
		foreach($all_useds as $k=>$v){
			if(!in_array($v['termId'],$all_usedid_arr)){
				$all_usedid_arr[]=$v['termId'];	
			}
		}
		if($used_count<=0){
			$all_used_qishu=count($all_usedid_arr)+1;
		}else{
			$all_used_qishu=count($all_usedid_arr);
		}
		$all_used_count=count($all_useds);
		
		

		if ($invite_count+$all_used_qishu - $all_used_count > 10 - $used_count) {
			$remainTimes = 10 - $used_count;
		}else{
			$remainTimes = $invite_count+$all_used_qishu - $all_used_count;
		}

		if($all_used_count >= ($invite_count+$all_used_qishu)) {
			$remainTimes = 0;
		}
		if($used_count >=10 ) {
			$remainTimes = 0;
		}
		if(empty($termInfo)) {
			$remainTimes = 0;
		}

//		if ($re['status']=='fail') {
//			
//		}else{
//			$this->assign('list',$re['content']);
//		}
		$last_join_count = M('active_roll_record')->where('termId = '.$lastTermId)->count();
		if(empty($last_join_count)){
			$last_join_count=0;
		}
		$thiscount=M('active_roll_record')->where('termId = '.$termId)->count();
		if(empty($thiscount)){
			$thiscount=0;
		}

		$this->assign('endTime',$termInfo['endTime']);
		$this->assign('termInfo',$termInfo);
		$this->assign('lasttermInfo',$lasttermInfo);
		$this->assign('lastcount',$last_join_count);
		$this->assign('thiscount',$thiscount);
		$this->assign('remainTimes',$remainTimes);
		$this->assign('current',$termId);
		$webtitle="Roll-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	function getUserNo()
	{
		$userId = $_SESSION['user_id'];
		$gid=$_SESSION['gametypeid'];
		// 获取当前期数
		$present = time();
		$termlist = M('active_roll_term')->where("state=1 and gid=".$gid."")->order("id desc")->limit("0,2")->select();
		$termInfo = $termlist[0];
		$termId = $termInfo['id'];
		//招募战友次数
		$invite_count = M('users')->where('invite_by_userId = '.$userId)->count();
		// 当前一期参与次数
		$used_count = M('active_roll_record')->where('termId = '.$termId.' and userId = '.$userId)->count();
		// 用户一共参与次数
		$all_useds = M('active_roll_record')->where('userId = '.$userId)->select();
		foreach($all_useds as $k=>$v){
			if(!in_array($v['termId'],$all_usedid_arr)){
				$all_usedid_arr[]=$v['termId'];	
			}
		}
		if($used_count<=0){
			$all_used_qishu=count($all_usedid_arr)+1;
		}else{
			$all_used_qishu=count($all_usedid_arr);
		}
		$all_used_count=count($all_useds);
		
		
		
		if($present>=$termInfo['endTime']) {
			$json['status'] = 'fail';
			$json['content'] = '活动开奖中';
			echo json_encode($json);
			die();
		}
		if($all_used_count >= ($invite_count+$all_used_qishu)) {
			$json['status'] = 'fail';
			$json['content'] = '招募战友次数不足';
			echo json_encode($json);
			die();
		}
		if($used_count >=10 ) {
			$json['status'] = 'fail';
			$json['content'] = '本次参加次数已经达到上限';
			echo json_encode($json);
			die();
		}
		if(empty($termInfo)) {
			$json['status'] = 'fail';
			$json['content'] = '活动尚未开始';
			echo json_encode($json);
			die();
		}

		$info = array(
			'userId'=>$userId,
			'termId'=>$termId
			);
		$re = M('active_roll_record')->add($info);
		$json['status'] = 'success';
		$json['content'] = $re; 

		if ($invite_count+$all_used_qishu - $all_used_count > 9 - $used_count) {
			$json['times'] = 9 - $used_count;
		}else{
			$json['times'] = $invite_count+$all_used_qishu-1 - $all_used_count;
		}

		if($all_used_count >= ($invite_count+$all_used_qishu-1)) {
			$json['times'] = 0;
		}
		if($used_count >=9 ) {
			$json['times'] = 0;
		}
		if(empty($termInfo)) {
			$json['times'] = 0;
		}

		echo json_encode($json);
	}

	function winlists()
	{
		$gid=$_SESSION['gametypeid'];
		$re = M('active_roll_term')->where("userRecordid1>=1 and userRecordid2>=1 and userRecordid3>=1 and gid=".$gid."")->order("id desc")->select();
		$this->assign('list',$re);
		$webtitle="获奖名单-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->display();
	}

	function getnos()
	{
		$rollid=t(h($_POST['rollid']));
		$userId = $_SESSION['user_id'];
		$re = M('active_roll_record')->where('userId = '.$userId.' and termId='.$rollid)->select();
		$json = array();
		foreach ($re as $key => $value) {
			$json[] = $value['id'];
		}
		echo json_encode($json);
	}
	

} 