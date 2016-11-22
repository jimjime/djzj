<?php
class AllAction extends Action{
    function _initialize(){
		$admin_info=$_SESSION;
		$this->assign('admin_info', $admin_info);
		
		$aa=$this->getActionName();
			
		
	}

	//竞猜结果模板信息
	//Tm00013
	//@param $userId 用户id
	//		 $golds 赢得金币
	//		 $betTime 投注时间
	//		 $drawTime 开奖时间
	//		 $times 投注详情
	//@return $re 微信接口返回参数
	function sendTemplateJc($userId,$golds,$times,$betTime,$drawTime)
	{
		//$userId = 172;
		//$golds = 600;
		//$times = 26;
		//$betTime = date('ymd',time());
		//$drawTime = date('ymd',time());
		import("@.ORG.weixin");
		$options = array(
			'token'=>'weixin', //填写你设定的ke
			'encodingaeskey'=>'Sk9YaEQNrTEeMC2hdurQjQFRlG7WLzLtBrslzBKdkO2', //填写加密用的EncodingAESKey
			'appid'=>'wxab899c981736ec18', //填写高级调用功能的app id, 请在微信开发模式后台查询
			'appsecret'=>'7d18567f174fd042958daa084836114d',
		);
		$userOpenId = M('users')->field('openId')->where('userId = '.$userId)->find();
		$userOpenId = $userOpenId['openId'];
		$weObj = new Wechat($options);
		$weObj->checkAuth();
		// var_dump($access_token); 
		// $data['access_token'] = $access_token;
		$data = array(
			'touser' => $userOpenId,
			// 'omczmwNEdPIjXitzOvGqblw6S8nk',
			'template_id' => 'k4tqlTMmLEn5QoIBujDW1Q4Hm6jcgp9QecmJI09ONCU',
			'url' =>'http://wap.esports666.com/index.php?a=index&m=index',
			'topcolor'=> '#1f262a',
			'data'=>array(
				'first'=>array(
					'value'=>'恭喜您，竞猜赢得'.$golds.'金币',
					'color'=>'red'
					),
				'issueInfo'=>array(
					'value'=>''.$times.'',
					"color"=>"blue"
					),
				'betTime'=>array(
					'value'=>$betTime,
					"color"=>"#green"
					),
				'fee'=>array(
					'value'=>$golds.'金币',
					"color"=>"#black"
					),
				'drawTime'=>array(
					'value'=>$drawTime,
					"color"=>"#yellow"
					),
				'remark'=>array(
					'value'=>'金币已到账，祝您玩的开心',
					"color"=>"#173177"
					)
				)
			);
			$re = $weObj->sendTemplateMessage($data);
			return $re;

	}

	//英雄猜结果通知
	//TM00011
	//@param $userId 用户id
	//       $golds 赢得金币
	//@return $re 微信返回参数
	function sendTemplateHero($userId,$golds,$kjqi,$tzgolds)
	{
		//$userId = 172;
		//$golds = 666;
		import("@.ORG.weixin");
		$options = array(
			'token'=>'weixin', //填写你设定的ke
			'encodingaeskey'=>'Sk9YaEQNrTEeMC2hdurQjQFRlG7WLzLtBrslzBKdkO2', //填写加密用的EncodingAESKey
			'appid'=>'wxab899c981736ec18', //填写高级调用功能的app id, 请在微信开发模式后台查询
			'appsecret'=>'7d18567f174fd042958daa084836114d',
		);
		$userOpenId = M('users')->field('openId')->where('userId = '.$userId)->find();
		$userOpenId = $userOpenId['openId'];
		$weObj = new Wechat($options);
		$weObj->checkAuth();
		// var_dump($access_token); 
		// $data['access_token'] = $access_token;
		$data = array(
			'touser' => $userOpenId,
			// 'omczmwNEdPIjXitzOvGqblw6S8nk',
			'template_id' => 'E7qOJpG-lXRKyXW71--9O-P0-wb6oSSi9phxBy55bX4',
			'url' =>'http://wap.esports666.com/index.php?a=hero&m=index',
			'topcolor'=> '#1f262a',
			'data'=>array(
				'result'=>array(
					'value'=>'恭喜您，英雄猜中奖了！！',
					'color'=>''
					),
				'totalWinMoney'=>array(
					'value'=>'赢得'.$golds.'金币',
					"color"=>"#173177"
					),
				'issueInfo'=>array(
					'value'=>'英雄猜 第 '.$kjqi.' 期',
					"color"=>"#173177"
					),
				'fee'=>array(
					'value'=>''.$tzgolds.'金币',
					"color"=>"#173177"
					),
				'remark'=>array(
					'value'=>'金币已到账，祝您玩的开心',
					"color"=>"#173177"
					)
				)
			);
			$re = $weObj->sendTemplateMessage($data);
			return $re;
			
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


	// 兑换成功 
	// Tm00014
	// @param $money 兑换的钱
	// 		  $golds 兑换用的金币
	// 		  $userId 用户id
	function sendTemplateConvert($money,$golds,$userId,$quannum)
	{
		//$userId = 172;
		//$money = 200;
		//$golds = 22200;
		import("@.ORG.weixin");
		$options = array(
			'token'=>'weixin', //填写你设定的ke
			'encodingaeskey'=>'Sk9YaEQNrTEeMC2hdurQjQFRlG7WLzLtBrslzBKdkO2', //填写加密用的EncodingAESKey
			'appid'=>'wxab899c981736ec18', //填写高级调用功能的app id, 请在微信开发模式后台查询
			'appsecret'=>'7d18567f174fd042958daa084836114d',
		);
		$userOpenId = M('users')->field('openId')->where('userId = '.$userId)->find();
		$userOpenId = $userOpenId['openId'];
		$weObj = new Wechat($options);
		$weObj->checkAuth();
		// var_dump($access_token); 
		// $data['access_token'] = $access_token;
		$data = array(
			'touser' => $userOpenId,
			'template_id' => 'Mn_SlbURZZwKMdz1YQQQiVtUxEHkG2PEdSETyLNeK34',
			'url' =>'http://wap.esports666.com/index.php?a=index&m=index',
			'topcolor'=> '#1f262a',
			'data'=>array(
				'result'=>array(
					'value'=>'您好，您已兑换成功！',
					'color'=>''
					),
				'productType'=>array(
					'value'=>'红包',
					"color"=>"#173177"
					),
				'name'=>array(
					'value'=>$money.'元',
					"color"=>"#173177"
					),
				'certificateNumber'=>array(
					'value'=>''.$quannum.'',
					"color"=>"#173177"
					),
				'number'=>array(
					'value'=>$golds.'金币',
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


	//获取缩略图thumb
	function get_thumb($data){
		
		$thumb_array=explode("/",$data);
		$num=count($thumb_array)-1;
		
		$i=0;
		for($i;$i<$num;$i++){
			$thumb_str.=$thumb_array[$i]."/";
		}
		
		$thumb="./".$thumb_str."/thumb/".$thumb_array[$num]; //得到缩略图地址
		$thumb=str_replace('//','/',$thumb);
		//判断缩略图是否存在
		$pan=is_file($thumb);
		if($pan){
			return $thumb;
		}else{
			return $data;
		}
	}
	//获取缩略图thumb2
	function get_thumb2($data){
		
		$thumb_array=explode("/",$data);
		$num=count($thumb_array)-1;
		
		$i=0;
		for($i;$i<$num;$i++){
			$thumb_str.=$thumb_array[$i]."/";
		}
		
		$thumb="./".$thumb_str."/thumb2/".$thumb_array[$num]; //得到缩略图地址
		$thumb=str_replace('//','/',$thumb);
		//判断缩略图是否存在
		$pan=is_file($thumb);
		if($pan){
			return $thumb;
		}else{
			return $data;
		}
	}
	//获取缩略图thumb3
	function get_thumb3($data){
		
		$thumb_array=explode("/",$data);
		$num=count($thumb_array)-1;
		
		$i=0;
		for($i;$i<$num;$i++){
			$thumb_str.=$thumb_array[$i]."/";
		}
		
		$thumb="./".$thumb_str."/thumb3/".$thumb_array[$num]; //得到缩略图地址
		$thumb=str_replace('//','/',$thumb);
		//判断缩略图是否存在
		$pan=is_file($thumb);
		if($pan){
			return $thumb;
		}else{
			return $data;
		}
	}
	
}
?>