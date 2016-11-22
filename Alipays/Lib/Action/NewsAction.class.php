<?php
/**
 *
 * @package 
 * @since 1.0
 * @todo 首页、列表页面、详细页
 */
 
class NewsAction extends AllAction{

    function _initialize(){

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
	

	//获取资讯
	//
	function getArticles()
	{
	 	$gid = $_SESSION['gametypeid'];
		$re = M('article')->field('id,title,header,ctime,readtimes')->where('state = 1 and gid = '.$gid)->limit(5)->order('ctime desc')->select();
		foreach ($re as $key => $value) {
			$re[$key]['ctime'] = floor((time()-$value['ctime'])/(24 * 60 * 60));
			if($re[$key]['ctime']>0){
				$re[$key]['ctime']=$re[$key]['ctime']."天前";
			}else{
				$re[$key]['ctime']=floor((time()-$value['ctime'])/(60 * 60));
				if($re[$key]['ctime']>0){
					$re[$key]['ctime']=$re[$key]['ctime']."小时前";
				}else{
					$re[$key]['ctime']=floor((time()-$value['ctime'])/(60));
					if($re[$key]['ctime']>0){
						$re[$key]['ctime']=$re[$key]['ctime']."分钟前";
					}else{
						$re[$key]['ctime']=floor((time()-$value['ctime']))."秒前";
					}
				}
			}
		}
		$json['content'] = $re;
		$json['status'] = 'success';
		return $json;
	}

	// 读取资讯
	function readArticles()
	{
		$id = t(h($_GET['id']));
		$article = M('article');
		$article->where(array('id'=>$id))->setInc('readtimes');
		$re = $article->where(array('id'=>$id))->find();
		$this->assign('article',$re);
		$webtitle=$re['title'];
		$this->assign('webtitle',$webtitle);
		$this->display();
	}
	//首页
	function news(){
		$gametypeid=$_SESSION['gametypeid'];
		
		$webtitle="资讯-全民菠菜";
		$this->assign('webtitle',$webtitle);
		$this->assign('zixun',$this->getArticles());
		$this->display();
	}
    //金币充值 支付宝
    function alipayto(){
        //判断是否微信浏览器
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //var_dump(strpos($user_agent, 'MicroMessenger'));
        if (strpos($user_agent, 'MicroMessenger') === false) {
            $tpnum=$_GET['tpnum'];
            $ddid=$_GET['ddid'];
            $uid=$_GET['uid'];
            $_SESSION['tpnum']=$tpnum;
            $_SESSION['ddid']=$ddid;
            $_SESSION['pay_uid']=$uid;
            $paytag=md5($_SESSION['pay_uid'].$_SESSION['tpnum'].$_SESSION['ddid']);
            $_SESSION['paytag']=$paytag;

            header("Location: http://".$_SERVER['HTTP_HOST']."/alipay/alipayapi.php?tpnum=".$tpnum."&ddid=".$ddid."&uid=".$uid."");
            exit;
        } else {
            $this->display();
        }
        exit;
    }
    function dotopay(){
        $ddid=t(h($_GET['ddid']));
        $tpnum=$_SESSION['tpnum'];
        $uid=$_SESSION['pay_uid'];
        $paytag=$_SESSION['paytag'];
        //$tpnum=1/100;
        if($paytag==md5($uid.$tpnum.$ddid) && !empty($ddid)){
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
                $user_info=M("users")->where("userId=".$uid."")->find();
                //是否首充
                $sf_sc=M("user_golds_record")->where("userId=".$uid." and fromid=4 and changeAmount>=1000")->find();

                $chongzhi['fromid']=4;
                $chongzhi['changeAmount']=$golds;
                $chongzhi['amount']=$user_info['golds']+$chongzhi['changeAmount'];
                $chongzhi['detail']="充值".$tpnum."元";
                $chongzhi['ctime']=time();
                $chongzhi['userId']=$uid;
                $chongzhi['alipay']=1;
                $chongzhi['ddid']=$ddid;
                $chongzhi_res=M("user_golds_record")->add($chongzhi);
                if($s_golds>0){
                    $song['fromid']=5;
                    $song['changeAmount']=$s_golds;
                    $song['amount']=$chongzhi['amount']+$song['changeAmount'];
                    $song['detail']="充值".$tpnum."元 赠送";
                    $song['ctime']=time();
                    $song['userId']=$uid;
                    $song['ddid']=$ddid;
                    $song_res=M("user_golds_record")->add($song);
                }
                if(empty($sf_sc) && $golds>=1000){
                    $sc_jl['fromid']=13;
                    $sc_jl['changeAmount']=200;
                    $sc_jl['amount']=$chongzhi['amount']+$song['changeAmount']+$sc_jl['changeAmount'];
                    $sc_jl['detail']="首充礼包";
                    $sc_jl['ctime']=time();
                    $sc_jl['userId']=$uid;
                    $sc_jl['ddid']=$ddid;
                    $sc_jl_res=M("user_golds_record")->add($sc_jl);
                }

                $user_info['golds']=$user_info['golds']+$golds+$s_golds+$sc_jl['changeAmount'];
                $user_info_res=M("users")->save($user_info);
                $this->sendTemplatePaid($tpnum,$uid);
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
} 