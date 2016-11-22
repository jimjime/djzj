<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 登陆 注册
 */
 
class InchannelAction extends AllAction{

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
	function pan_quan($data){ //php跳转判断
		$pan=in_array($data,$_SESSION['admin_quan']);
        if(!$pan){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
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
	//渠道列表
	function invite_channel(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('user_invite_channel')->order("user_invite_channelId asc")->select();
		foreach($list as $k=>$v){
			$list[$k]['qr_img']="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($v['qr_ticket'])."";
		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	function invite_channel_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$id=t(h($_POST['id']));
		if(empty($id)){
			$add['name']=t(h($_POST['name']));
			$add['ctime']=time();
			if(empty($add['name'])){
				$this->error('请输入名称');
			}
			$res=M("user_invite_channel")->add($add);
			if($res){
				import("@.ORG.weixin");		 
				$options = array(
					'token'=>'weixin', //填写你设定的ke
					'encodingaeskey'=>'Sk9YaEQNrTEeMC2hdurQjQFRlG7WLzLtBrslzBKdkO2', //填写加密用的EncodingAESKey
					'appid'=>'wxab899c981736ec18', //填写高级调用功能的app id, 请在微信开发模式后台查询
					'appsecret'=>'7d18567f174fd042958daa084836114d'
				);
				$weObj = new Wechat($options);
				$ress=$weObj->checkAuth();
				$wx_token['token']=$ress;
				$wx_token['ctime']=time();
	
				$urls = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$wx_token['token']."";//POST指向的链接      
				$datas = array(      
					'action_name'=>'QR_LIMIT_STR_SCENE',     
					'action_info'=>array(
						'scene'=>array(
							'scene_str'=>"c=".$res.""
						)
					)     
				);    
				$datas = json_encode($datas,true);
		
				list($returnCode, $returnContent)=$this->http_post_json($urls, $datas); 
	
				$returnContent = json_decode($returnContent); 
				$qudao_qr['qr_ticket']=$returnContent->ticket;
	
				$urltu="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($qudao_qr['qr_ticket'])."";
	
				$qudao_qr['qr_url']=$returnContent->url;
				$qudao_qr['user_invite_channelId']=$res;
				$save_qudao_qr=M("user_invite_channel")->save($qudao_qr);
				
				if($save_qudao_qr){
					$this->success("添加成功！");
				}else{
					$this->error("添加失败！");
				}
			}else{
				$this->error("添加失败！");
			}
		}else{
			$add['user_invite_channelId']=$id;
			$add['name']=t(h($_POST['name']));
			$add['stime']=time();
			if(empty($add['name'])){
				$this->error('请输入名称');
			}
			$res=M("user_invite_channel")->save($add);
			if($res){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
		 
	
} 
