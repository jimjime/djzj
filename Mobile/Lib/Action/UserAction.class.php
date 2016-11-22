<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 登陆 注册
 */
 
class UserAction extends AllAction{

    function _initialize(){
				

	}
	function getIP() /*获取客户端IP*/  
	{  
		if (@$_SERVER["HTTP_X_FORWARDED_FOR"])  
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
		else if (@$_SERVER["HTTP_CLIENT_IP"])  
		$ip = $_SERVER["HTTP_CLIENT_IP"];  
		else if (@$_SERVER["REMOTE_ADDR"])  
		$ip = $_SERVER["REMOTE_ADDR"];  
		else if (@getenv("HTTP_X_FORWARDED_FOR"))  
		$ip = getenv("HTTP_X_FORWARDED_FOR");  
		else if (@getenv("HTTP_CLIENT_IP"))  
		$ip = getenv("HTTP_CLIENT_IP");  
		else if (@getenv("REMOTE_ADDR"))  
		$ip = getenv("REMOTE_ADDR");  
		else  
		$ip = "Unknown";  
		return $ip;  
	}    
	  
	//微信登录相关
    function loginbyweixin(){
		if($_SESSION['user_id']>0){
			$this->assign('jumpUrl',U('Index/index'));
			$this->error("已登录！");
		}else{
		
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			if (strpos($user_agent, 'MicroMessenger') === false) {
				// 非微信浏览器禁止浏览
				echo "请在微信端打开";exit;
			} else {
			
				$appid = "wxab899c981736ec18";

				$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=http%3a%2f%2f".$_SERVER['HTTP_HOST']."%2findex.php%3fa%3dwx_login%26m%3duser&response_type=code&scope=snsapi_userinfo&state=type";

				header("Location: ".$url."");
				
				exit;
					
			}

			$this->assign('jumpUrl', U('Index/index'));
			$this->error("授权错误！");
			
			
		}
    }
	function get_user_info($openid)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$this->access_token."&openid=".$openid."&lang=zh_CN";
        $res = $this->https_request($url);
        return json_decode($res, true);
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
	function wx_login(){//微信登录
		
		$appid = "wxab899c981736ec18";
    	$appsecret = "7d18567f174fd042958daa084836114d";
		
//		$this->lasttime = $_SESSION['wx_lasttime'];
//        $this->access_token = "nRZvVpDU7LxcSi7GnG2LrUcmKbAECzRf0NyDBwKlng4nMPf88d34pkzdNcvhqm4clidLGAS18cN1RTSK60p49zIZY4aO13sF-eqsCs0xjlbad-lKVskk8T7gALQ5dIrgXbQQ_TAesSasjJ210vIqTQ";
//
//        if (time() > ($this->lasttime + 7200)){
//            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
//            $res = $this->https_request($url);
//            $result = json_decode($res, true);
//            
//            $this->access_token = $result["access_token"];
//			$_SESSION['wx_lasttime']=time();
//            $this->lasttime = time();
//        }
		if (isset($_GET['code'])){
			$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code";
			$res = $this->https_request($url);
			$res=(json_decode($res, true));
			
			$this->access_token = $res["access_token"];
			
			$row=$this->get_user_info($res['openid']);
			
			if ($row['openid']) {
				//这里写上逻辑,存入cookie,数据库等操作
				$user['openId']=$row['openid'];
				$user['name']=$row['nickname'];
				$user['header']=$row['headimgurl'];
				//var_dump($row);exit;
				//$user['unionid']=$row['unionid'];
				
//				$user_info['sex']=$row['sex'];
//				$user_info['area']=$row['province'].$row['city'];
				
				$user_jiu=M('users')->where("openId='".$user['openId']."'")->find();
				
				if(!empty($user_jiu)){
					$user_jiu['lasttime']=time();
					$user_jiu['name']=$row['nickname'];
					$user_jiu['header']=$row['headimgurl'];
					$user_new=M('users')->save($user_jiu);
					$_SESSION['user_id']=$user_jiu['userId'];
				}else{
					$user['ctime']=time();
					$user['lasttime']=time();
					$user_new=M('users')->add($user);
					$_SESSION['user_id']=$user_new;
				}
					
				//$gametypeid=$_SESSION['gametypeid'];		
//				if($_SERVER['HTTP_REFERER']=="http://".$_SERVER['HTTP_HOST']."/index.php?a=index&m=Index&gtypeid=1" || $_SERVER['HTTP_REFERER']=="http://".$_SERVER['HTTP_HOST']."/index.php?a=index&m=Index&gtypeid=2" || $_SERVER['HTTP_REFERER']=="http://".$_SERVER['HTTP_HOST']."/index.php?a=mycenter&m=Index"){
//					$lyurl=$_SERVER['HTTP_REFERER'];	
//					
//				}else{
//					$lyurl=$_SESSION['lyurl'];	
//				}
				
				$lyurl=$_SESSION['lyurl'];
//				if($_SESSION['user_id']==774){
//					var_dump($_SESSION);exit;
//				}
				header("Location: ".$lyurl."");
				exit;
			
			}else{
				$this->error('授权出错,请重新授权!');
			}

		}else{
			
		}
		
	}
	 
	
} 
