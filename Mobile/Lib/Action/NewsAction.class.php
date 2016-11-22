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
		
		$webtitle="资讯-玩贝电竞";
		$this->assign('webtitle',$webtitle);
		$this->assign('zixun',$this->getArticles());
		$this->display();
	}

}