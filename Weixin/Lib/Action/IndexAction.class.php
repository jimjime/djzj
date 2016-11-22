<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 首页、列表页面、详细页
 */
 
class IndexAction extends AllAction{

    function _initialize(){
				


	}
	 
	//首页
	function index(){
		//$gametypeid=$_SESSION['gametypeid'];
//		$qr_ticket=t(h($_GET['qrt']));
//		
//		$user_info=M("users")->where("qr_ticket=".$qr_ticket."")->find();
//		if(!empty($user_info)){
//			echo "heihei";	
//		}else{
//			echo "haha";	
//		}
//		exit;
		import("@.ORG.weixin");		 

		$options = array(
			'token'=>'weixin', //填写你设定的ke
			'encodingaeskey'=>'Sk9YaEQNrTEeMC2hdurQjQFRlG7WLzLtBrslzBKdkO2', //填写加密用的EncodingAESKey
			'appid'=>'wxab899c981736ec18', //填写高级调用功能的app id, 请在微信开发模式后台查询
			'appsecret'=>'7d18567f174fd042958daa084836114d'
		);
		$weObj = new Wechat($options);
		$weObj->valid();
		$type = $weObj->getRev()->getRevType();
		switch($type) {
			case Wechat::MSGTYPE_TEXT:
				$weObj->text("小菠已经收到您的消息～\n欢迎加入交流qq群获取最新动态～\n以及随时私聊小菠～[飞吻]\n群号\nLOL:  528279088\nDOTA: 295416108\n\n<a href='http://wap.esports666.com/index.php?a=teamsindex&m=Index'>西安Show联赛【报名】[勾引]</a>\n参与战队众筹，不打比赛也有奖哦！")->reply();
				exit;
				break;
			case Wechat::MSGTYPE_EVENT:
				break;
			case Wechat::MSGTYPE_IMAGE:
				$weObj->text("小菠已经收到您的消息～\n欢迎加入交流qq群获取最新动态～\n以及随时私聊小菠～[飞吻]\n群号\nLOL:  528279088\nDOTA: 295416108\n\n<a href='http://wap.esports666.com/index.php?a=teamsindex&m=Index'>西安Show联赛【报名】[勾引]</a>\n参与战队众筹，不打比赛也有奖哦！")->reply();
				exit;
				break;
				break;
			default:
				$weObj->text("小菠已经收到您的消息～\n欢迎加入交流qq群获取最新动态～\n以及随时私聊小菠～[飞吻]\n群号\nLOL:  528279088\nDOTA: 295416108\n\n<a href='http://wap.esports666.com/index.php?a=teamsindex&m=Index'>西安Show联赛【报名】[勾引]</a>\n参与战队众筹，不打比赛也有奖哦！")->reply();
		}
		
		
		$shijian_tui = $weObj->getRev()->getRevScanInfo();
		$huifucontent_tui="关注玩贝电竞，全面的赛事竞猜及资讯，更有欢乐竞猜小游戏。\n1.首次登录即送88金币[愉快]\n2.首次充值即可获得“首充大礼包”[礼物]\n3.同时招募战友更有多多活动！[握手]\n偷偷告诉你：每天小菠都会送你30-70金币哦[嘘]\n<a href='http://wap.esports666.com/index.php?a=teamsindex&m=Index'>西安Show联赛【报名】[勾引]</a>\n参与战队众筹，不打比赛也有奖哦！";
		switch($shijian_tui) {
			default:

				$qr_ticket=$weObj->getRev()->getRevTicket();
				$qr_SceneId=$weObj->getRev()->getRevSceneId();
				$qr_SceneId_first=substr($qr_SceneId,0,1);
				$openid=$weObj->getRev()->getRevFrom();
				if($qr_SceneId_first=="c"){
					//判断ticket来源
					$qudao_ticket=M("user_invite_channel")->where("qr_ticket='".$qr_ticket."'")->find();
					if(!empty($qudao_ticket)){
					
						$add_user["openId"]=$openid;
						$add_user["invite_by_channelId"]=$qudao_ticket['user_invite_channelId'];
						$add_user["ctime"]=time();
						
						//判断是否有旧用户
						$old_user=M("users")->where("openId='".$openid."'")->find();
						if(empty($old_user)){
							M("users")->add($add_user);
							$qudao_ticket['user_count']=$qudao_ticket['user_count']+1;
						}else{
							if(empty($old_user['invite_by_userId']) && empty($old_user['invite_by_channelId'])){
								$old_user['invite_by_channelId']=$qudao_ticket['user_invite_channelId'];
								M("users")->save($old_user);
								$qudao_ticket['user_count']=$qudao_ticket['user_count']+1;
							}
						}
						M("user_invite_channel")->save($qudao_ticket);
						//$weObj->text($qr_SceneId)->reply();
						
					}
				}else{
					//判断ticket来源
					$user_ticket=M("users")->where("qr_ticket='".$qr_ticket."'")->find();
					if(!empty($user_ticket)){
						$add_user["openId"]=$openid;
						$add_user["invite_by_userId"]=$user_ticket['userId'];
						$add_user["ctime"]=time();
						
						//判断是否有旧用户
						$old_user=M("users")->where("openId='".$openid."'")->find();
						if(empty($old_user)){
							M("users")->add($add_user);
						}else{
							if(empty($old_user['invite_by_userId']) && empty($old_user['invite_by_channelId'])){
								if($user_ticket['userId']!=$old_user['userId']){
									$old_user['invite_by_userId']=$user_ticket['userId'];
									M("users")->save($old_user);
								}
							}
						}
						
					}
				}

				$weObj->text($huifucontent_tui)->reply();
				break;
				
		}
		
		$shijian = $weObj->getRev()->getRevEvent();
		$huifucontent="关注玩贝电竞，全面的赛事竞猜及资讯，更有欢乐竞猜小游戏。\n1.首次登录即送88金币[愉快]\n2.首次充值即可获得“首充大礼包”[礼物]\n3.同时招募战友更有多多活动！[握手]\n偷偷告诉你：每天小菠都会送你30-70金币哦[嘘]\n<a href='http://wap.esports666.com/index.php?a=teamsindex&m=Index'>西安高校Show联赛报名入口[勾引]</a>\n参与战队助力，不赢比赛也有奖哦！";
		switch($shijian) {
			case Wechat::EVENT_SUBSCRIBE:
				$weObj->text($huifucontent)->reply();
				exit;
				break;
				
		}
		//获取菜单操作:
		$menu = $weObj->getMenu();
		//设置菜单
		$newmenu =  array(
			"button"=>
			array(
				array(
					'type'=>'view',
					'name'=>'竞猜',
					'url'=>'http://wap.esports666.com/index.php?a=index&m=index'
				),
				array(
					'type'=>'view',
					'name'=>'赛事',
					'url'=>'http://wap.esports666.com/index.php?a=games&m=index'
				),
				array(
					'name'=>'服务中心',
					'sub_button'=>array(
						array(
							'type'=>'view',
							'name'=>'常见问题',
							'url'=>'http://mp.weixin.qq.com/s?__biz=MzIzNjM1MTEyOQ==&mid=2247483657&idx=1&sn=df0542f635cead0a7108e752e3868b31&scene=4#wechat_redirect'
						),
						array(
							'type'=>'view',
							'name'=>'联系我们',
							'url'=>'http://wap.esports666.com/index.php?a=art&m=index&aid=8'
						),
						array(
							'type'=>'view',
							'name'=>'战友招募',
							'url'=>'http://wap.esports666.com/index.php?a=myqr&m=index'
						),
					),
				),
			)
		);

		$result = $weObj->createMenu($newmenu);

		
	}


	
} 
