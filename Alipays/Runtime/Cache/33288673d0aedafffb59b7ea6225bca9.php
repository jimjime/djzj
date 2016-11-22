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
<link href="__PUBLIC__/css/newfooter.css?201608050936" rel="stylesheet" type="text/css" />
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
		<?php }else{ ?>
			<a class="rules" href="<?php echo U('Index/art',array(fid=>1));?>"></a>
		<?php } ?>
		
	</header><?php endif; ?>


<!--主体-->
<div class="wrap">
    <div class="con-layer">
        <h3 style="margin-bottom:20px;">请在右上角菜单中选择在浏览器打开，以完成支付</h3>

    </div>


</div>


	<div class="footerbox">
		<ul>
			<li
				<?php if((ACTION_NAME) == "index"): ?>class="active"<?php endif; ?>
				<?php if((ACTION_NAME) == "indexx"): ?>class="active"<?php endif; ?>
				<?php if((ACTION_NAME) == "readarticles"): ?>class="active"<?php endif; ?>
				<?php if((ACTION_NAME) == "news"): ?>class="active"<?php endif; ?>
			>
				<?php if(($_SESSION["gametypeid"]) == "1"): ?><a class="menu05" href="./index.php?a=index&m=index">竞猜</a>
				<?php else: ?>
					<a class="menu01" href="./index.php?a=index&m=index">竞猜</a><?php endif; ?>
			</li>
			<li
				<?php if((ACTION_NAME) == "hero"): ?>class="active"<?php endif; ?>
			>
				<a class="menu02" href="./index.php?a=hero&m=index">英雄猜</a>
			</li>
			<li
				<?php if((ACTION_NAME) == "indexs"): ?>class="active"<?php endif; ?>
				<?php if((ACTION_NAME) == "ck"): ?>class="active"<?php endif; ?>
			>
				<?php if(($_SESSION["gametypeid"]) == "1"): ?><a class="menu04" href="./index.php?a=roll&m=Active">活动</a>
				<?php else: ?>
					<a class="menu06" href="./index.php?a=roll&m=Active">活动</a><?php endif; ?>
			</li>
			<li
				<?php if((ACTION_NAME) == "mycenter"): ?>class="active"<?php endif; ?>
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
				<a class="menu03" href="./index.php?a=mycenter&m=Index">个人</a>
			</li>
		</ul>
	</div>

<div class="box qiandaocg_box" style="z-index:100;display:none;"></div>
<div class="layer qiandaocg_layer" style="z-index:111;display:none;">
	<div class="layer-img">
	<?php if(($_SESSION["gametypeid"]) == "2"): ?><img src="__PUBLIC__/images/time-day2.png" >
	<?php else: ?>
		<img src="__PUBLIC__/images/time-day.png" ><?php endif; ?>
	</div>
	<div class="con-layer">
        <h3>欢迎来到全民菠菜<?php if(($_SESSION["gametypeid"]) == "2"): ?>DOTA2<?php else: ?>LOL<?php endif; ?>专区</h3>
        <p><?php if(($_SESSION["gametypeid"]) == "2"): ?>DOTA2<?php else: ?>LOL<?php endif; ?>奋战礼包</p>
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tr class="title">
            <td <?php if($new_qdnum>=1 && $new_qdnum<3){ ?> class="day-color" <?php } ?>>登录一天</td>
            <td <?php if($new_qdnum>=3 && $new_qdnum<5){ ?> class="day-color" <?php } ?>>连续三天</td>
            <td <?php if($new_qdnum>=5){ ?> class="day-color" <?php } ?>>连续五天</td>
          </tr>
          <tr>
            <td <?php if($new_qdnum>=1 && $new_qdnum<3){ ?> class="day-color" <?php } ?>>30金币 </td>
            <td <?php if($new_qdnum>=3 && $new_qdnum<5){ ?> class="day-color" <?php } ?>>50金币</td>
            <td <?php if($new_qdnum>=5){ ?> class="day-color" <?php } ?>>70金币</td>
          </tr>
        </table>
    </div>
    <a class="btn-dota qiandaocg_btn" href="javascript:;">领 取</a>
</div>
<div class="box fr_qiandaocg_box" style="z-index:200;display:none;"></div>
<div class="layer fr_qiandaocg_layer" style="z-index:211;display:none;">
	<div class="layer-img">
	<?php if(($_SESSION["gametypeid"]) == "2"): ?><img src="__PUBLIC__/images/first2.png" >
	<?php else: ?>
		<img src="__PUBLIC__/images/first.png" ><?php endif; ?>
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
<div class="tc_box_bg" id="yuebz_box" >
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
function show_yuebz_box(t){
	$("#yuebz_box").show();
	if(t<=1050){
		var yue_y=10;
		var yue_j=1050;
	}else if(t>1050 &&t<=2200){
		var yue_y=20;
		var yue_j=2200;
	}else if(t>2200 &&t<=5500){
		var yue_y=50;
		var yue_j=5500;
	}else if(t>5500 &&t<=11000){
		var yue_y=100;
		var yue_j=11000;
	}else if(t>11000 &&t<=22000){
		var yue_y=200;
		var yue_j=22000;
	}else{
		var yue_y=Math.floor(t/110)+1;
		var yue_j=yue_y*110;
	}
	$("#yuebz_box_y").text(yue_y);
	$("#yuebz_box_j").text(yue_j);
	$("#gotopay_btn").attr("href","./index.php?a=topay&m=index&rmb="+yue_y);
	
}
function hide_yuebz_box(){
	$("#yuebz_box").hide();
}

</script>
<!--余额不足 弹窗 end-->
<!--竞猜成功 弹窗 start-->
<div class="tc_box_bg" id="jccg_box" >
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
			<br />
			投注额：<span id="jccg_box_golds">1</span>金币
		</div>
		<div class="img_icon_btn">
			<a href="javascript:;"  onclick="hide_jccg_box()">继续其他投注</a>
		</div>
	</div>
</div>
<script type="text/javascript">
function show_jccg_box(){
	$("#jccg_box").show();
	
}
function hide_jccg_box(){
	$("#jccg_box").hide();
}

</script>
<!--竞猜成功 弹窗 end-->
<script type="text/javascript">

$(document).ready(function() {
	var qiandaocg=<?php echo ($qiandaocg); ?>;
	if(qiandaocg>=1){
		show_qiandaocg();
		//setInterval(hide_qiandaocg,3000);
	}
	$(".qiandaocg_btn").click(function(event) {
		hide_qiandaocg();
	});
	var fr_qiandaocg=<?php echo ($frqiandaocg); ?>;
	if(fr_qiandaocg>=1){
		show_fr_qiandaocg();
	}
	$(".fr_qiandaocg_btn").click(function(event) {
		hide_fr_qiandaocg();
	});
	
});
function show_qiandaocg(){
	//$(".qiandaocg_layer").css({"display":"block"});
	//$(".qiandaocg_box").css({"display":"block"});
	$(".qiandaocg_layer").show();
	$(".qiandaocg_box").show();
	//$(".qiandaocg_layer").fadeIn("slow");
	//$(".qiandaocg_box").fadeIn("slow");
//	$(".qiandaocg_layer").slideDown("slow");
//	$(".qiandaocg_box").slideDown("slow");
}
function hide_qiandaocg(){
	//$(".qiandaocg_layer").css({"display":"none"});
	//$(".qiandaocg_box").css({"display":"none"});
	$(".qiandaocg_layer").fadeOut("slow");
	$(".qiandaocg_box").fadeOut("slow");
//	$(".qiandaocg_layer").slideUp("slow");
//	$(".qiandaocg_box").slideUp("slow");
}
function show_fr_qiandaocg(){
	$(".fr_qiandaocg_layer").show();
	$(".fr_qiandaocg_box").show();
}
function hide_fr_qiandaocg(){
	$(".fr_qiandaocg_layer").fadeOut("slow");
	$(".fr_qiandaocg_box").fadeOut("slow");
}
</script>

<style type="text/css">
.peilv{font-weight:100;}
.user-about img{border-radius:100%;-moz-border-radius:100%;-webkit-border-radius:100%;}
.peilvactive{color:#e6e6e6 !important;font-weight:100;}
.data_box_value{display:none;}
.submit-btn{border:none;}
.top{margin-top:41px;}
h3 font{color:#fff;}

#jswbox{width:100%;margin:0px auto; background:url(__PUBLIC__/images/bg.jpg) left top no-repeat; background-size:cover; height:260px;}
#jswbox ul{position:relative;}
#jswbox li{position:absolute;width:0;z-index:0;cursor:pointer;overflow:hidden;}
#jswbox li img{width:100%;height:auto;vertical-align:top;float:left;border:0;}
ul.lolhero li{left:450px;top:70px;height:auto;}
ul.dotahero li{left:450px;top:70px;height:auto;}
.header{z-index:99;}
</style>

</body>
</html>