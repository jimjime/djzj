<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo ($webtitle); ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
<meta name="description" content="">
<meta name="author" content="">
<meta name="screen-orientation" content="portrait">
<meta name="x5-orientation" content="portrait">
<link href="__PUBLIC__/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/css/style.css?201608091347" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/css/newfooter.css?201610050936" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<!--弹出层-->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/alert.css" />
<!--验证信息 js+css -->
<link rel="stylesheet" href="__PUBLIC__/bootstrap-3.3.5-dist/css/bootstrapValidator.css"/>
<script type="text/javascript" src="__PUBLIC__/bootstrap-3.3.5-dist/js/bootstrapValidator.js"></script>
<!--微信js-->
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>

<body>
<?php if(!empty($bigmess)): ?><style type="text/css">
	.bigmess{width:98%;padding-left:1%;padding-right:1%;height:auto;position:fixed;left:0;top:0;z-index:99999999;background:#1c2930;color:#fff;}
	.bigmess_span{width:90%;height:auto;display:block;line-height:2rem;font-size:1.3rem;}
	.bigmess_a{width:10%;height:auto;display:block;position:absolute;right:0;top:0.4rem;z-index:2;text-align:center;}
	.bigmess_a img{width:60%;}
	</style>
	<div class="bigmess">
		<span class="bigmess_span">
		<?php echo ($bigmess["name"]); ?>
		</span>
		<a href="javascript:;" class="bigmess_a" onClick="hide_bigmess(<?php echo ($bigmess["id"]); ?>)">
			<img src="__PUBLIC__/new/images/close.png" />
		</a>
	</div>
	<script type="text/javascript">
	function hide_bigmess(t){
		$.post("<?php echo U('Index/hidebigmess');?>", {mid:t}, function(v){
			$(".bigmess").hide();
		});
	}
	</script><?php endif; ?>
<!--顶部导航-->
<?php if((ACTION_NAME) == "myqr"): else: ?>
	<header class="header">
		<span class="fl">今日竞猜：<?php echo ($thisdayzong_ren); ?>人，<?php echo ($thisdayzong_jinbi); ?>金币</span>
		<span class="header-r fr">余额：<?php echo ($user_yue); ?></span>
		<?php if(ACTION_NAME=="hero"){ ?>
			<a class="rules" href="<?php echo U('Index/art',array(fid=>4));?>"></a>
		<?php }else if(ACTION_NAME=="recom" || ACTION_NAME=="myqr" || ACTION_NAME=="recoms"){ ?>
			<a class="rules" href="<?php echo U('Index/art',array(aid=>5));?>"></a>
		<?php }else if(ACTION_NAME=="art" && $_GET['aid']==5){ ?>
			<a class="rules" href="<?php echo U('Index/art',array(aid=>5));?>"></a>
		<?php }else if(ACTION_NAME=="dld"){ ?>
			<a class="rules" href="<?php echo U('Index/art',array(aid=>9));?>"></a>
		<?php }else{ ?>
			<a class="rules" href="<?php echo U('Index/art',array(fid=>1));?>"></a>
		<?php } ?>

	</header><?php endif; ?>


<style>
	.nav{width:100%;}
	.jingcai_nav{width:50%;height:auto;display:block;float:left;}
	.jingcai_nav_pic{width:100%;}
	.zixun_nav{width:50%;height:auto;display:block;float:left;}
	.zixun_nav_pic{width:100%;}

	.banner{width:100%;height:auto;position:relative;}
	.banner_img{width:100%;}
	.banner_detail{width:100%;position:absolute;z-index:2;bottom:0.5rem;right:0;}
	.banner_detail p{color: #fff;font-size:1.6rem;text-align:right;padding-right:1rem;}
	.banner_info{width:100%;height:1.5rem;text-align:right;}
	.banner_pubtime{float:right;padding-right:1rem;}
	.pubtime_clock{display:block;float:right;}
	.pubtime_clock img{height:1.4rem;padding-right:1rem;}
	.pubtime_detail{display:block;float:right;}
	.pubtime_detail p{color:#fff;font-size:1rem;line-height:2rem;}
	.banner_readtimes{float:right;padding-right:1rem;}
	.readtimes_eye{display:block;float:left;}
	.readtimes_eye img{height:1.4rem;padding-right:1rem;}
	.readtimes_detail{height:2rem;display:block;float:left;}
	.readtimes_detail p{color:#fff;font-size:1rem;line-height:2rem;}
	
	.content{width:100%; background:#131c21;}
	.content_item{width:100%;background:#1c2930;height:8rem;margin-top:0.8rem;}
	.item_pic{width:35%;display:block;float:left;height:8rem;overflow:hidden;}
	.item_pic img{width:100%;height:100%;}
	.item_intro{display:block;float:left;width:65%;padding:0 0;}
	.item_intro_title{padding-left:1rem;padding-top:0.8rem;padding-right:0.5rem;}


	.item_info{width:96%;height:1.5rem;padding-left:1rem;padding-top:0.8rem;}
	.item_pubtime{width:50%;float:left;padding-right:1rem;}
	.item_pubtime_detail p{line-height:2rem;}
	.item_pubtime_clock{display:block;float:left;padding-right:1rem;}
	.item_pubtime_clock img{height:1.4rem;}
	.item_detail{display:block;float:left;}
	.item_detail p{font-size:1rem;line-height:2rem;}
	.item_readtimes{width:50%;float:right;}
	.item_readtimes_eye{display:block;float:right;}
	.item_readtimes_eye img{height:1.4rem;padding-right:1rem;}
	.item_readtimes_detail{height:2rem;display:block;float:right;word-wrap:break-word;}
	.item_readtimes_detail p{font-size:1rem;line-height:2rem;}
</style>

<!--主体-->
<div class="wrap">
	<!-- 竞猜/咨询导航栏 -->
	<div class="nav">
		<div class="jingcai_nav">
			<a href="<?php echo U('Index/index');?>">
				<img class="jingcai_nav_pic" src="__PUBLIC__/new/images/zixun/jincai-active.png" alt="">
			</a>
		</div>
		<div class="zixun_nav" >
			<a href="<?php echo U('News/news');?>">
				<img class="zixun_nav_pic" src="__PUBLIC__/new/images/zixun/zixun2.png" alt="">
			</a>
		</div>
	</div>

	<div class="jingcai">
		<!--主播 开始-->
		<div class="title1">
			<h3 class="toptitle">主播竞猜</h3>
		</div>
		<?php if(!empty($list_zhubos)): ?><div class = "zhubopic">
				<?php if(is_array($list_zhubos)): $i = 0; $__LIST__ = $list_zhubos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$z): $mod = ($i % 2 );++$i;?><div class="zhubopic_li">
						<a href="<?php echo U('Index/indexx',array(gameid=>$z[gameid]));?>">
							<div class="zhubopic_li_img_box">
								<div class="zhubopic_li_icon">
									<img src="<?php echo ($z["pt"]["avatar"]); ?>" />
								</div>
								<img src="<?php echo ($z["avatar"]); ?>" class="zhubopic_li_img" />
								<div class="zhubopic_li_name">
									<?php echo ($z["name"]); ?>
								</div>
							</div>
						</a>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
				<div class="clear"></div>
			</div>
		<?php else: ?>
			<div class="zhubo_nopic">
				<?php if(($_SESSION["gametypeid"]) == "1"): ?><img src="__PUBLIC__/new/images/jc/lolno.jpg" />
				<?php else: ?>
					<img src="__PUBLIC__/new/images/jc/dotano.jpg"  /><?php endif; ?>
				<div class="clear"></div>
			</div><?php endif; ?>
		<!--主播 结束-->
		<!--比赛 开始-->
		<div  class = "title2">
			<h3 class="toptitle">赛事竞猜</h3>
		</div>

		<div class="raceBet">
			
			<?php if(is_array($list_game)): $i = 0; $__LIST__ = $list_game;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><a href="javascript:;" onclick="show_pan(<?php echo ($l["gameId"]); ?>,this)">
					<div class="bet1">
						<div class="logo1">
							<img src="<?php echo ($l["team1"]["avatar"]); ?>" alt="<?php echo ($l["team1"]["name"]); ?>">
							<div class="tname">
								<?php echo ($l["team1"]["name"]); ?>
							</div>
						</div>	
						<div class="logomid">
							<div class="logomid_li1 logomid_li1_<?php echo ($l["gameId"]); ?>">
								<?php $kaishitime=$l['start_time']; if(time()<=$kaishitime){ $shengyutime=$kaishitime-time(); $shengyuhour=floor($shengyutime / (60 * 60)); $shengyuminute = floor($shengyutime / 60) - ($shengyuhour * 60); $shengyusecond = floor($shengyutime) - ($shengyuhour * 60 * 60) - ($shengyuminute * 60); if ($shengyuminute <= 9) $shengyuminute = '0'.$shengyuminute; if ($shengyusecond <= 9) $shengyusecond = '0'.$shengyusecond; if($shengyuhour>0){ $shengyutext=$shengyuhour."小时后"; }else{ if($shengyuminute>0){ $shengyutext=$shengyuminute."分钟后"; }else{ $shengyutext="比赛中"; } } }else{ $shengyutext="比赛中"; } ?>
								<?php if(($l["cstate"]) == "1"): echo ($shengyutext); endif; ?> 
								<?php if(($l["cstate"]) == "2"): ?>比赛中<?php endif; ?> 
								<?php if(($l["cstate"]) == "1"): ?><script type="text/javascript">
									
									var intDiff<?php echo ($l["gameId"]); ?> = parseInt(<?php echo ($shengyutime); ?>);//倒计时总秒数量
									function timer<?php echo ($l["gameId"]); ?>(intDiff<?php echo ($l["gameId"]); ?>){
										window.setInterval(function(){
										var hour=0,
											minute=0,
											second=0;//时间默认值		
										if(intDiff<?php echo ($l["gameId"]); ?> > 0){
											hour<?php echo ($l["gameId"]); ?> = Math.floor(intDiff<?php echo ($l["gameId"]); ?> / (60 * 60));
											minute<?php echo ($l["gameId"]); ?> = Math.floor(intDiff<?php echo ($l["gameId"]); ?> / 60) - (hour<?php echo ($l["gameId"]); ?> * 60);
											second<?php echo ($l["gameId"]); ?> = Math.floor(intDiff<?php echo ($l["gameId"]); ?>) - (hour<?php echo ($l["gameId"]); ?> * 60 * 60) - (minute<?php echo ($l["gameId"]); ?> * 60);
										}else{
											$(".logomid_li1_<?php echo ($l["gameId"]); ?>").text("比赛中");
										}
										if (minute<?php echo ($l["gameId"]); ?> <= 9) minute<?php echo ($l["gameId"]); ?> = '0' + minute<?php echo ($l["gameId"]); ?>;
										if (second<?php echo ($l["gameId"]); ?> <= 9) second<?php echo ($l["gameId"]); ?> = '0' + second<?php echo ($l["gameId"]); ?>;
										if(hour<?php echo ($l["gameId"]); ?>>0){
											$(".logomid_li1_<?php echo ($l["gameId"]); ?>").text(hour<?php echo ($l["gameId"]); ?>+"小时后");
										}else{
											if(minute<?php echo ($l["gameId"]); ?>>0){
												$(".logomid_li1_<?php echo ($l["gameId"]); ?>").text(minute<?php echo ($l["gameId"]); ?>+"分钟后");
											}else{
												//$(".logomid_li1_<?php echo ($l["gameId"]); ?>").text(second<?php echo ($l["gameId"]); ?>+"秒后");
												$(".logomid_li1_<?php echo ($l["gameId"]); ?>").text("比赛中");
											}
										}
										intDiff<?php echo ($l["gameId"]); ?>--;
										}, 60000);
									} 
									$(function(){
										timer<?php echo ($l["gameId"]); ?>(intDiff<?php echo ($l["gameId"]); ?>);
									});	
									</script><?php endif; ?>
							</div>
							<div class="logomid_li2">
								<?php if(($l["cstate"]) == "1"): ?><span class="logomid_li2_zt jczzt "><?php echo ($l["cstatename"]); ?></span><?php endif; ?>
								<?php if(($l["cstate"]) == "2"): ?><span class="logomid_li2_zt yfpzt "><?php echo ($l["cstatename"]); ?></span><?php endif; ?>
								<?php if(($l["cstate"]) == "3"): ?><span class="logomid_li2_bf"><?php echo ($l["bifen"]); ?></span><?php endif; ?>
	
							</div>
							<div class="logomid_li3">
							<?php echo ($l["name"]); ?>
							</div>
						</div>
						<div class="logo2">
							<img src="<?php echo ($l["team2"]["avatar"]); ?>" alt="<?php echo ($l["team2"]["name"]); ?>">
							<div class="tname">
								<?php echo ($l["team2"]["name"]); ?>
							</div>
						</div>
						
						<div class="clear"></div>
					</div>
				</a>
				<div class="bet_pan bet_pan<?php echo ($l["gameId"]); ?>">
					<!--二级盘 循环 开始-->
					<?php if(is_array($l["gamejus"])): $i = 0; $__LIST__ = $l["gamejus"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$j): $mod = ($i % 2 );++$i;?><div class="bet_pan_li">
							<a href="javascript:;" class="bet_pan_li_a_win <?php if(($j["cstate"]) == "1"): ?>btn-win<?php endif; ?>" data-id="<?php echo ($j["id"]); ?>"></a>
							<a href="javascript:;" class="bet_pan_li_a_fu <?php if(($j["cstate"]) == "1"): ?>btn-fu<?php endif; ?>" data-id="<?php echo ($j["id"]); ?>"></a>
							<div class="bet_pan_peilv">
								<?php if(($j["wincheck"]) == "1"): ?><div class="bet_pan_peilv_win win_icon1">
									胜
									</div><?php endif; ?>
								<span class="bet_pan_peilv1_<?php echo ($j["id"]); ?>"><?php echo ($j["peilv1"]); ?></span><br /><?php echo ($j["check1"]); ?>
							</div>
							<div class="bet_pan_zt">
								<div class="bet_pan_zt_li  <?php if(($j["cstate"]) == "1"): ?>jczzt<?php endif; ?>  <?php if(($j["cstate"]) == "2"): ?>yfpzt<?php endif; ?>  <?php if(($j["cstate"]) == "3"): ?>yjszt<?php endif; ?>  <?php if(($j["cstate"]) == "4"): ?>yjszt<?php endif; ?> ">
									<div class="bet_pan_zt_li_span1">
									<?php echo ($j["cname"]); ?>
									</div>
									<div class="bet_pan_zt_li_span2">
									<?php echo ($j["cstatename"]); ?>
									</div>
								</div>
							</div>
							<div class="bet_pan_peilv">
								<?php if(($j["wincheck"]) == "2"): ?><div class="bet_pan_peilv_win win_icon2">
									胜
									</div><?php endif; ?>
								<span class="bet_pan_peilv2_<?php echo ($j["id"]); ?>"><?php echo ($j["peilv2"]); ?></span><br /><?php echo ($j["check2"]); ?>
							</div>
							<div class="clear"></div>
						</div>
						<div class="data_box_value pdataid<?php echo ($j["id"]); ?>" data-cktime="<?php echo (date('H:i:s',$j["ktime"])); ?>" data-ccname="<?php echo ($j["cname"]); ?>" data-ccheck1="<?php echo ($j["check1"]); ?>"  data-ccheck2="<?php echo ($j["check2"]); ?>" data-cpeilv1="<?php echo ($j["peilv1"]); ?>" data-cpeilv2="<?php echo ($j["peilv2"]); ?>" data-uyue="<?php echo ($user_yue); ?>"></div><?php endforeach; endif; else: echo "" ;endif; ?>
					<!--二级盘 循环 结束-->
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
			
		</div>
		<!--已结算比赛 开始-->
		<div class="raceBet" id="game_jie_more_box">
			
			<?php if(is_array($list_game_jie)): $i = 0; $__LIST__ = $list_game_jie;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$e): $mod = ($i % 2 );++$i;?><a href="javascript:;" onclick="show_pan(<?php echo ($e["gameId"]); ?>,this)">
					<div class="bet1">
						<div class="logo1">
							<img src="<?php echo ($e["team1"]["avatar"]); ?>" alt="<?php echo ($e["team1"]["name"]); ?>">
							<div class="tname">
								<?php echo ($e["team1"]["name"]); ?>
							</div>
						</div>	
						<div class="logomid">
							<div class="logomid_li2">
								<span class="logomid_li2_bf" ><?php echo ($e["bifen"]); ?></span>
							</div>
							<div class="logomid_li3">
							<?php echo ($e["name"]); ?>
							</div>
						</div>
						<div class="logo2">
							<img src="<?php echo ($e["team2"]["avatar"]); ?>" alt="<?php echo ($e["team2"]["name"]); ?>">
							<div class="tname">
								<?php echo ($e["team2"]["name"]); ?>
							</div>
						</div>
						
						<div class="clear"></div>
					</div>
				</a>
				<div class="bet_pan bet_pan<?php echo ($e["gameId"]); ?>">
					<!--二级盘 循环 开始-->
					<?php if(is_array($e["gamejus"])): $i = 0; $__LIST__ = $e["gamejus"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$j): $mod = ($i % 2 );++$i;?><div class="bet_pan_li">
							<div class="bet_pan_peilv">
								<?php if(($j["wincheck"]) == "1"): ?><div class="bet_pan_peilv_win win_icon1">
									胜
									</div><?php endif; ?>
								<span class="bet_pan_peilv1_<?php echo ($j["id"]); ?>"><?php echo ($j["peilv1"]); ?></span><br /><?php echo ($j["check1"]); ?>
							</div>
							<div class="bet_pan_zt">
								<div class="bet_pan_zt_li  <?php if(($j["cstate"]) == "1"): ?>jczzt<?php endif; ?>  <?php if(($j["cstate"]) == "2"): ?>yfpzt<?php endif; ?>  <?php if(($j["cstate"]) == "3"): ?>yjszt<?php endif; ?>  <?php if(($j["cstate"]) == "4"): ?>yjszt<?php endif; ?> ">
									<div class="bet_pan_zt_li_span1">
									<?php echo ($j["cname"]); ?>
									</div>
									<div class="bet_pan_zt_li_span2">
									<?php echo ($j["cstatename"]); ?>
									</div>
								</div>
							</div>
							<div class="bet_pan_peilv">
								<?php if(($j["wincheck"]) == "2"): ?><div class="bet_pan_peilv_win win_icon2">
									胜
									</div><?php endif; ?>
								<span class="bet_pan_peilv2_<?php echo ($j["id"]); ?>"><?php echo ($j["peilv2"]); ?></span><br /><?php echo ($j["check2"]); ?>
							</div>
							<div class="clear"></div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
					<!--二级盘 循环 结束-->
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
			
		</div>
		<div class="cp_more" >
			<input type="hidden" value="1" id="nowpage" />
			<a href="javascript:;" class="more_btn">点击查看更多</a>
		</div>
		<script type="text/javascript">
			$(".more_btn").click(function(){
				var nowpage=$("#nowpage").val();
				$.post("<?php echo U('Index/more_game_jie');?>", {nowpage:nowpage}, function(v){
					$("#game_jie_more_box").append(v.ccc);
					$("#nowpage").val(v.nextpage);

				},'JSON');
				
			})
		</script>
		<!--已结算比赛 结束-->
		<!--比赛 结束-->
	</div>


   
</div>


<div class="box jctz_box"></div>
<div class="layer jctz_layer">
	<div class="con-layer">
        <h4 class="pcname"></h4>
        <span class="pxiang"></span>
        <div class="form-group">
            <div class="input-box">
             <label>竞猜金额 | </label><input type="number" class="form-control" id="addgoldnum" name="goldname" placeholder="最低投注1金币" />
			 <input type="hidden" id="jctz_id" />
            </div>
        </div>
        <div class="money maybemoney" data-cpeilv=""><label>可能奖金 ：</label>0.0金币</div>
        <div class="money nowmoney"><label>当前余额 ：</label>0.0金币</div>
	</div>
    <div class="bnt-con"><a class="hint-in1 hint" data-checkid="" href="javascript:;">确  定</a><a class="hint-in2 cancel" href="javascript:;">取消</a></div>
</div>
<div class="box jccg_box"></div>
<div class="layer jccg_layer">
	<div class="con-layer">
        <P style=" font-size:18px; padding:20px 0; margin:0">竞猜成功</P>
	</div>
    <div class="bnt-con"><a class="hint-in3" href="javascript:;">确  定</a></div>
</div>

<script type="text/javascript">

$(document).ready(function($){
	// $.post("<?php echo U('Index/getArticles');?>",{}, function(v){
	// 	console.log(v);
	// 	console.log(v['content']['0']);
	// 	$('.banner').css('background','url("'+v['content'][0]['header']+'")');
	// 	$('.banner').css('background-size','cover');
	// 	$('#banner_title').text(v['content'][0]['title']);
	// 	$('#banner_pubtime').text(v['content'][0]['addtime']);
	// 	$('#banner_readtimes').text(v['content'][0]['readtimes']);
	// },'JSON');


	$(".btn-win").click(function() {
		var pid=$(this).attr("data-id");
		var ccname=$(".pdataid"+pid).attr("data-ccname");
		var cktime=$(".pdataid"+pid).attr("data-cktime");
		var databc=$(".pdataid"+pid).attr("data-bc");
		var ccheck1=$(".pdataid"+pid).attr("data-ccheck1");
		var ccheck2=$(".pdataid"+pid).attr("data-ccheck2");
		var cpeilv1=$(".pdataid"+pid).attr("data-cpeilv1");
		var cpeilv2=$(".pdataid"+pid).attr("data-cpeilv2");
		var uyue=$(".pdataid"+pid).attr("data-uyue");
		
		$(".jctz_layer").find("input#jctz_id").val(pid);
		$(".jctz_layer").find("h4.pcname").text(ccname+" "+cktime);
		
		$(".jctz_layer").find("span.pxiang").text("( "+ccheck1+" "+cpeilv1+" )");
		$(".jctz_layer").find("div.maybemoney").html("<label>可能奖金 ：</label>0.0金币");
		$(".jctz_layer").find("div.maybemoney").attr("data-cpeilv",cpeilv1);
		$(".jctz_layer").find("div.nowmoney").html("<label>当前余额 ：</label>"+uyue+"金币");
		$(".jctz_layer").find("a.hint-in1").attr("data-checkid",1);
		$(".jctz_layer").find("a.hint-in1").attr("data-checkname",ccheck1);

		$(".jctz_layer").css({"display":"block"});
		$(".jctz_box").css({"display":"block"});
	});
	$(".btn-fu").click(function() {
		var pid=$(this).attr("data-id");
		var ccname=$(".pdataid"+pid).attr("data-ccname");
		var cktime=$(".pdataid"+pid).attr("data-cktime");
		var databc=$(".pdataid"+pid).attr("data-bc");
		var ccheck1=$(".pdataid"+pid).attr("data-ccheck1");
		var ccheck2=$(".pdataid"+pid).attr("data-ccheck2");
		var cpeilv1=$(".pdataid"+pid).attr("data-cpeilv1");
		var cpeilv2=$(".pdataid"+pid).attr("data-cpeilv2");
		var uyue=$(".pdataid"+pid).attr("data-uyue");
		
		$(".jctz_layer").find("input#jctz_id").val(pid);
		$(".jctz_layer").find("h4.pcname").text(ccname+" "+cktime);
		
		$(".jctz_layer").find("span.pxiang").text("( "+ccheck2+" "+cpeilv2+" )");
		$(".jctz_layer").find("div.maybemoney").html("<label>可能奖金 ：</label>0.0金币");
		$(".jctz_layer").find("div.maybemoney").attr("data-cpeilv",cpeilv2);
		$(".jctz_layer").find("div.nowmoney").html("<label>当前余额 ：</label>"+uyue+"金币");
		$(".jctz_layer").find("a.hint-in1").attr("data-checkid",2);
		$(".jctz_layer").find("a.hint-in1").attr("data-checkname",ccheck2);

		$(".jctz_layer").css({"display":"block"});
		$(".jctz_box").css({"display":"block"});
	});
	$('#addgoldnum').bind('input propertychange', function() {
	
		var addgoldnum=Number($(this).val());
		var cpeilv=Number($(".layer").find("div.maybemoney").attr("data-cpeilv"));
		if(addgoldnum>0){
			var maybemoney=(addgoldnum*cpeilv).toFixed(2);
		}else{
			var maybemoney=0.0;
		}
		$(".jctz_layer").find("div.maybemoney").html("<label>可能奖金 ：</label>"+maybemoney+"金币");		
	});

	$(".hint-in2").click(function(event) {
		$(".jctz_layer").css({"display":"none"});
		$(".jctz_box").css({"display":"none"});
	});
	
	$(".hint-in3").click(function(event) {
		$(".jccg_layer").css({"display":"none"});
		$(".jccg_box").css({"display":"none"});
	});

	$(".hint-in1").click(function(event) {
		var id=$(".jctz_layer").find("input#jctz_id").val();
		var addgoldnum=$(".jctz_layer").find("input#addgoldnum").val();
		var checkid=$(this).attr("data-checkid");
		var checkname=$(this).attr("data-checkname");
		$.post("<?php echo U('Index/jctz');?>", {id:id,addgoldnum:addgoldnum,checkid:checkid}, function(v){
			if(v.jieguo=="tzcg"){
				$(".header").find("span.fl").html("今日竞猜："+v.ren+"人，"+v.jinbi+"金币");
				$(".header").find("span.header-r").html("余额："+v.user_yue+"");
				
				$(".bet_pan_peilv1_"+v.pid).text(v.peilv1);
				$(".bet_pan_peilv2_"+v.pid).text(v.peilv2);
				
				$("#jccg_box_xuan").text(checkname);
				if(checkid==1){
					$("#jccg_box_peilv").text(v.peilv1);
				}else{
					$("#jccg_box_peilv").text(v.peilv2);
				}
				$("#jccg_box_golds").text(addgoldnum);

				$("div.pdataid"+v.pid).attr("data-cpeilv1",v.peilv1);
				$("div.pdataid"+v.pid).attr("data-cpeilv2",v.peilv2);
				$("div.pdataid"+v.pid).attr("data-uyue",v.user_yue);
				
				$(".jctz_layer").css({"display":"none"});
				$(".jctz_box").css({"display":"none"});
				
				show_jccg_box();
				
			}else if(v.jieguo=="tzsb"){
				alert("竞猜失败");
			}else if(v.jieguo=="nostart"){
				alert("竞猜未开始");
			}else if(v.jieguo=="yiend"){
				alert("竞猜已结束");
			}else if(v.jieguo=="fengpan"){
				alert("此竞猜封盘中");
			}else if(v.jieguo=="jiesuan"){
				alert("此竞猜结算中");
			}else if(v.jieguo=="yuebuzu"){
				var jinbi_cha=addgoldnum-v.user_yue;
				show_yuebz_box(jinbi_cha);
			}else if(v.jieguo=="no1"){
				alert("投注金额不能小于1金币");
			}
					   
		},'JSON');
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#form').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        //group: '.form-group',
        fields: {            
            goldname: {
                validators: {
                    notEmpty: {
                        message: '金币不能是空的'
                    },
                }
            },
           
			
	     }
    });
});
</script>
<script type="text/javascript">
function show_pan(t,obj){
	$(".bet_pan").slideUp();
	var display_zt=$(".bet_pan"+t).css("display");
	if(display_zt=="none"){
		$(".bet_pan"+t).slideDown();
	}
	//$(".bet_pan"+t).slideDown();
	//$(obj).attr("onclick","hide_pan("+t+",this)");
}
function hide_pan(t,obj){
	$(".bet_pan"+t).slideUp();
	$(obj).attr("onclick","show_pan("+t+",this)");
}




// 资讯
function show_jingcai() {
	$('.jingcai_nav_pic').attr('src','__PUBLIC__/new/images/zixun/jincai-active.png');
	$('.zixun_nav_pic').attr('src','__PUBLIC__/new/images/zixun/zixun2.png');
	$('.jingcai').show();
	$('.zixun').hide();
}

function show_zixun() {
	$('.jingcai_nav_pic').attr('src','__PUBLIC__/new/images/zixun/jincai.png');
	$('.zixun_nav_pic').attr('src','__PUBLIC__/new/images/zixun/zixun-active.png');
	$('.jingcai').hide();
	$('.zixun').show();
}
</script>

<div class="footerbox">
    <ul>
        <li <?php if((ACTION_NAME) == "games"): ?>class="active"<?php endif; ?>
        <?php if((ACTION_NAME) == "lolteams"): ?>class="active"<?php endif; ?>
        <?php if((ACTION_NAME) == "cfunding"): ?>class="active"<?php endif; ?>
        <?php if((ACTION_NAME) == "lolrace"): ?>class="active"<?php endif; ?>
        <?php if((ACTION_NAME) == "sologames"): ?>class="active"<?php endif; ?>
        <?php if((ACTION_NAME) == "teamlist"): ?>class="active"<?php endif; ?>
        <?php if((ACTION_NAME) == "teamsindex"): ?>class="active"<?php endif; ?>
        <?php if((ACTION_NAME) == "soloindex"): ?>class="active"<?php endif; ?>
            >
            <a class="menu07" href="<?php echo U('Index/games');?>">赛事</a>
        </li>
        <li <?php if((ACTION_NAME) == "index"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "indexx"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "readarticles"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "news"): ?>class="active"<?php endif; ?>
            >
            <?php if(($_SESSION["gametypeid"]) == "1"): ?><a class="menu05" href="<?php echo U('Index/index');?>">竞猜</a>
                <?php else: ?>
                <a class="menu01" href="<?php echo U('Index/index');?>">竞猜</a><?php endif; ?>
        </li>
        <li <?php if((ACTION_NAME) == "hero"): ?>class="active"<?php endif; ?>
            >
            <a class="menu02" href="<?php echo U('Index/hero');?>">英雄猜</a>
        </li>
        <li <?php if((ACTION_NAME) == "dld"): ?>class="active"<?php endif; ?>

            >
            <?php if(($_SESSION["gametypeid"]) == "1"): ?><a class="menu04" href="<?php echo U('Index/dld');?>">大乱斗</a>
                <?php else: ?>
                <a class="menu06" href="<?php echo U('Index/dld');?>">大乱斗</a><?php endif; ?>
        </li>
        <li <?php if((ACTION_NAME) == "mycenter"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "art"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "chtype"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "mygolds"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "myquiz"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "myqr"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "tobuy"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "tobuygo"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "topay"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "recom"): ?>class="active"<?php endif; ?>
            <?php if((ACTION_NAME) == "recoms"): ?>class="active"<?php endif; ?>
            >
            <a class="menu03" href="<?php echo U('Index/mycenter');?>">个人</a>
        </li>
    </ul>
</div>
<div class="box qiandaocg_box" style="z-index:100;display:none;"></div>
<div class="layer qiandaocg_layer" style="z-index:111;display:none;">
    <div class="layer-img">
        <?php if(($_SESSION["gametypeid"]) == "2"): ?><img src="__PUBLIC__/images/time-day2.png">
            <?php else: ?>
            <img src="__PUBLIC__/images/time-day.png"><?php endif; ?>
    </div>
    <div class="con-layer">
        <h3>欢迎来到全民菠菜<?php if(($_SESSION["gametypeid"]) == "2"): ?>DOTA2<?php else: ?>LOL<?php endif; ?>专区</h3>
        <p>
            <?php if(($_SESSION["gametypeid"]) == "2"): ?>DOTA2
                <?php else: ?>LOL<?php endif; ?>奋战礼包</p>
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
            <tr class="title">
                <td <?php if($new_qdnum>=1 && $new_qdnum <3){ ?> class="day-color"
                        <?php } ?>>登录一天</td>
                <td <?php if($new_qdnum>=3 && $new_qdnum <5){ ?> class="day-color"
                        <?php } ?>>连续三天</td>
                <td <?php if($new_qdnum>=5){ ?> class="day-color"
                    <?php } ?>>连续五天</td>
            </tr>
            <tr>
                <td <?php if($new_qdnum>=1 && $new_qdnum <3){ ?> class="day-color"
                        <?php } ?>>30金币 </td>
                <td <?php if($new_qdnum>=3 && $new_qdnum <5){ ?> class="day-color"
                        <?php } ?>>50金币</td>
                <td <?php if($new_qdnum>=5){ ?> class="day-color"
                    <?php } ?>>70金币</td>
            </tr>
        </table>
    </div>
    <a class="btn-dota qiandaocg_btn" href="javascript:;">领 取</a>
</div>
<div class="box fr_qiandaocg_box" style="z-index:200;display:none;"></div>
<div class="layer fr_qiandaocg_layer" style="z-index:211;display:none;">
    <div class="layer-img">
        <?php if(($_SESSION["gametypeid"]) == "2"): ?><img src="__PUBLIC__/images/first2.png">
            <?php else: ?>
            <img src="__PUBLIC__/images/first.png"><?php endif; ?>
    </div>
    <div class="con-layer">
        <h3 style="margin-bottom:20px;">全民菠菜新手大礼包</h3>
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
            <tr class="title">
                <td style="color:#e6e6e6;">88金币</td>
            </tr>
        </table>
    </div>
    <a class="btn-dota fr_qiandaocg_btn" href="javascript:;">领 取</a>
</div>
<!--余额不足 弹窗 start-->
<div class="tc_box_bg" id="yuebz_box">
    <div class="tc_box">
        <div class="tc_box_close" onclick="hide_yuebz_box()">
            <img src="__PUBLIC__/new/images/close.png" />
        </div>
        <div class="img_icon">
            <img class="sad_img" src="__PUBLIC__/new/images/sad.png" />
        </div>
        <div class="img_icon_text">
            您的金币余额不足，您想以<span id="yuebz_box_y">10</span>元购买<span id="yuebz_box_j">1050</span>金币参与竞猜吗？
        </div>
        <div class="img_icon_btn">
            <a href="<?php echo U('Index/myqr');?>" class="img_icon_btn1">招募战友</a>
            <a href="<?php echo U('Index/topay',array(rmb=>10));?>" class="img_icon_btn2" id="gotopay_btn">确定购买</a>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
function show_yuebz_box(t) {
    $("#yuebz_box").show();
    if (t <= 1050) {
        var yue_y = 10;
        var yue_j = 1050;
    } else if (t > 1050 && t <= 2200) {
        var yue_y = 20;
        var yue_j = 2200;
    } else if (t > 2200 && t <= 5500) {
        var yue_y = 50;
        var yue_j = 5500;
    } else if (t > 5500 && t <= 11000) {
        var yue_y = 100;
        var yue_j = 11000;
    } else if (t > 11000 && t <= 22000) {
        var yue_y = 200;
        var yue_j = 22000;
    } else {
        var yue_y = Math.floor(t / 110) + 1;
        var yue_j = yue_y * 110;
    }
    $("#yuebz_box_y").text(yue_y);
    $("#yuebz_box_j").text(yue_j);
    $("#gotopay_btn").attr("href", "./index.php?a=topay&m=index&rmb=" + yue_y);

}

function hide_yuebz_box() {
    $("#yuebz_box").hide();
}
</script>
<!--余额不足 弹窗 end-->
<!--竞猜成功 弹窗 start-->
<div class="tc_box_bg" id="jccg_box">
    <div class="tc_box">
        <div class="tc_box_close" onclick="hide_jccg_box()">
            <img src="__PUBLIC__/new/images/close.png" />
        </div>
        <div class="img_icon">
            <img src="__PUBLIC__/new/images/jc/cg.png" class="jccg_icon" />
            <span class="jccg_text">竞猜成功</span>
        </div>
        <div class="img_icon_text2">
            您已成功投注<span id="jccg_box_xuan">1</span>（<span id="jccg_box_peilv">1</span>赔率动态变化，以最后封盘前赔率为准）
            <br />
            <br /> 投注额：
            <span id="jccg_box_golds">1</span>金币
        </div>
        <div class="img_icon_btn">
            <a href="javascript:;" onclick="hide_jccg_box()">继续其他投注</a>
        </div>
    </div>
</div>
<script type="text/javascript">
function show_jccg_box() {
    $("#jccg_box").show();

}

function hide_jccg_box() {
    $("#jccg_box").hide();
}
</script>
<!--竞猜成功 弹窗 end-->
<script type="text/javascript">
$(document).ready(function() {
    var qiandaocg = {
        $qiandaocg
    };
    if (qiandaocg >= 1) {
        show_qiandaocg();
        //setInterval(hide_qiandaocg,3000);
    }
    $(".qiandaocg_btn").click(function(event) {
        hide_qiandaocg();
    });
    var fr_qiandaocg = {
        $frqiandaocg
    };
    if (fr_qiandaocg >= 1) {
        show_fr_qiandaocg();
    }
    $(".fr_qiandaocg_btn").click(function(event) {
        hide_fr_qiandaocg();
    });

});

function show_qiandaocg() {
    //$(".qiandaocg_layer").css({"display":"block"});
    //$(".qiandaocg_box").css({"display":"block"});
    $(".qiandaocg_layer").show();
    $(".qiandaocg_box").show();
    //$(".qiandaocg_layer").fadeIn("slow");
    //$(".qiandaocg_box").fadeIn("slow");
    //	$(".qiandaocg_layer").slideDown("slow");
    //	$(".qiandaocg_box").slideDown("slow");
}

function hide_qiandaocg() {
    //$(".qiandaocg_layer").css({"display":"none"});
    //$(".qiandaocg_box").css({"display":"none"});
    $(".qiandaocg_layer").fadeOut("slow");
    $(".qiandaocg_box").fadeOut("slow");
    //	$(".qiandaocg_layer").slideUp("slow");
    //	$(".qiandaocg_box").slideUp("slow");
}

function show_fr_qiandaocg() {
    $(".fr_qiandaocg_layer").show();
    $(".fr_qiandaocg_box").show();
}

function hide_fr_qiandaocg() {
    $(".fr_qiandaocg_layer").fadeOut("slow");
    $(".fr_qiandaocg_box").fadeOut("slow");
}
</script>
<style type="text/css">
.peilv {
    font-weight: 100;
}

.user-about img {
    border-radius: 100%;
    -moz-border-radius: 100%;
    -webkit-border-radius: 100%;
}

.peilvactive {
    color: #e6e6e6 !important;
    font-weight: 100;
}

.data_box_value {
    display: none;
}

.submit-btn {
    border: none;
}

.top {
    margin-top: 41px;
}

h3 font {
    color: #fff;
}

#jswbox {
    width: 100%;
    margin: 0px auto;
    background: url(__PUBLIC__/images/bg.jpg) left top no-repeat;
    background-size: cover;
    height: 260px;
}

#jswbox ul {
    position: relative;
}

#jswbox li {
    position: absolute;
    width: 0;
    z-index: 0;
    cursor: pointer;
    overflow: hidden;
}

#jswbox li img {
    width: 100%;
    height: auto;
    vertical-align: top;
    float: left;
    border: 0;
}

ul.lolhero li {
    left: 450px;
    top: 70px;
    height: auto;
}

ul.dotahero li {
    left: 450px;
    top: 70px;
    height: auto;
}

.header {
    z-index: 99;
}
</style>
</body>

</html>