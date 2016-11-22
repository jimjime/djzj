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

<!--主体-->
<style type="text/css">
/*	.lolhero li:nth-child(3){
       transition: all 0.8s linear;
	}*/
</style>
<div class="wrap rules-ab Hero">
    <style type="text/css">
        .dld_top{width:100%;height:auto;background:#060606;}
        .dld_top_li{width:80%;margin:0 auto;}
        .dld_top_a{width:50%;display:block;float:left;height:auto;text-align:center;}
        .dld_top_a img{width:auto;max-width:100%;}
    </style>
    <!--
    <div class="dld_top">
        <div class="dld_top_li">
            <a href="<?php echo U('Index/hero');?>" class="dld_top_a">
                <img src="__PUBLIC__/new/images/yxc_c.jpg">
            </a>
            <a href="<?php echo U('Index/dld');?>" class="dld_top_a">
                <img src="__PUBLIC__/new/images/dld.jpg">
            </a>
            <div class="clear"></div>
        </div>

    </div>
    -->
    <div id="jswbox">
        <h3>距离第<font id="sy_cha_ci"><?php echo ($now_tou["now_cha_cishu"]); ?></font>期幸运英雄出现还有<font id="sy_time_hour"></font><font id="sy_time_minute"></font><font id="sy_time_second"></font></h3>
        <script type="text/javascript">
        var intDiff = parseInt(<?php echo ($now_tou["shengyumiao"]); ?>); //倒计时总秒数量
        function timer(intDiff) {
            window.setInterval(function() {
                if (intDiff <= 0) {
                    var sy_cha_ci = $("#sy_cha_ci").text();
                    var sy_cha_ci_new = Number(Number(sy_cha_ci) + Number(1));
                    $("#sy_cha_ci").text(sy_cha_ci_new);
                    if (sy_cha_ci_new == 25) {
                        intDiff = parseInt(28800);
                    } else if (sy_cha_ci_new > 25 && sy_cha_ci_new <= 96) {
                        intDiff = parseInt(600);
                    } else {
                        intDiff = parseInt(300);
                    }
                    $('#sy_time_hour').show();
                    $('#sy_time_minute').show();
                }
                var hour = 0,
                    minute = 0,
                    second = 0; //时间默认值		
                if (intDiff > 0) {
                    hour = Math.floor(intDiff / (60 * 60));
                    minute = Math.floor(intDiff / 60) - (hour * 60);
                    second = Math.floor(intDiff) - (hour * 60 * 60) - (minute * 60);
                } else {

                }
                if (minute <= 9) minute = '0' + minute;
                if (second <= 9) second = '0' + second;
                if (hour > 0) {
                    $('#sy_time_hour').html('' + hour + '小时');
                    $('#sy_time_minute').html('' + minute + '分');
                    $('#sy_time_second').html('' + second + '秒');
                } else {
                    $('#sy_time_hour').hide();
                    if (minute > 0) {
                        $('#sy_time_minute').html('' + minute + '分');
                        $('#sy_time_second').html('' + second + '秒');
                    } else {
                        $('#sy_time_minute').hide();
                        $('#sy_time_second').html('' + second + '秒');
                    }
                }
                intDiff--;
            }, 1000);
        }
        $(function() {
            timer(intDiff);
        });
        </script>
        <?php if(($_SESSION["gametypeid"]) == "1"): ?><ul class="hero_himg_ul lolhero">
                <li><img src="__PUBLIC__/lol80/1.jpg"></li>
                <li><img src="__PUBLIC__/lol80/2.jpg"></li>
                <li><img src="__PUBLIC__/lol80/3.jpg"></li>
                <li><img src="__PUBLIC__/lol80/4.jpg"></li>
                <li><img src="__PUBLIC__/lol80/5.jpg"></li>
                <li><img src="__PUBLIC__/lol80/6.jpg"></li>
                <li><img src="__PUBLIC__/lol80/7.jpg"></li>
                <li><img src="__PUBLIC__/lol80/8.jpg"></li>
                <li><img src="__PUBLIC__/lol80/9.jpg"></li>
                <li><img src="__PUBLIC__/lol80/10.jpg"></li>
            </ul>
            <script type="text/javascript">
            function lolhero(heronum) {
            	var picnum=2
                window.setInterval(function() {
                    var w_width = $(window).width();
                    var c_left = Math.floor(w_width / 15);
                    var c_top = c_left * 2;
                    $("#jswbox").height((c_left * 7));
                    if (heronum >= 2) {
                        var heronumfu = Number(heronum) - Number(2);
                        $(".lolhero li:eq(" + heronumfu + ")").css({
                            'left': "-" + c_left + "px",
                            'width': '' + (2 * c_left) + 'px',
                            'top': '' + c_top + 'px',
                            'z-index': '0',
                            'list-style-type': 'none'
                        });
                    }
                    if (heronum >= 1) {
                        var heronumfu = Number(heronum) - Number(1);
                        $(".lolhero li:eq(" + heronumfu + ")").css({
                            'left': "-" + c_left + "px",
                            'width': '' + (2 * c_left) + 'px',
                            'top': '' + c_top + 'px',
                            'z-index': '1',
                            'list-style-type': 'none'
                        });
                    }
                    $(".lolhero li:eq(" + heronum + ")").css({
                        'left': '' + (1 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '2',
                        'list-style-type': 'none'
                    });
                    var heronum1 = Number(heronum) + Number(1);
                    $(".lolhero li:eq(" + heronum1 + ")").css({
                        'left': '' + (2.5 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '3',
                        'list-style-type': 'none'
                    });
                    var heronum2 = Number(heronum) + Number(2);
                    $(".lolhero li:eq(" + heronum2 + ")").css({
                        'left': '' + (4 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '4',
                        'list-style-type': 'none'
                    });
                    var heronum3 = Number(heronum) + Number(3);
                    $(".lolhero li:eq(" + heronum3 + ")").css({
                        'left': '' + (5.5 * c_left) + 'px',
                        'width': '' + (4 * c_left) + 'px',
                        'top': '' + (c_top / 2) + 'px',
                        'z-index': '10',
                        'list-style-type': 'none'
                    });
                    var heronum4 = Number(heronum) + Number(4);
                    $(".lolhero li:eq(" + heronum4 + ")").css({
                        'left': '' + (9 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '9',
                        'list-style-type': 'none'
                    });
                    var heronum5 = Number(heronum) + Number(5);
                    $(".lolhero li:eq(" + heronum5 + ")").css({
                        'left': '' + (10.5 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '8',
                        'list-style-type': 'none'
                    });
                    var heronum6 = Number(heronum) + Number(6);
                    $(".lolhero li:eq(" + heronum6 + ")").css({
                        'left': '' + (12 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '7',
                        'list-style-type': 'none'
                    });
                    var heronum7 = Number(heronum) + Number(7);
                    $(".lolhero li:eq(" + heronum7 + ")").css({
                        'left': '' + (13.5 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '6',
                        'list-style-type': 'none'
                    });
                    var heronum8 = Number(heronum) + Number(8);
                    $(".lolhero li:eq(" + heronum8 + ")").css({
                        'left': '' + (15 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '5',
                        'list-style-type': 'none'
                    });
                    if(heronum>=1){
                    	picnum1=picnum;
                        for(var i=0;i<9;i++){
                        	
                        	// console.log(picnum1);
                        	console.log(i);
                        	$(".lolhero li:eq("+i+") img").attr('src','./Public/Mobile/lol80/'+picnum1+'.jpg');
                        	picnum1++;
                        }
                    }
                    // 当图片摆放达到上限的时候，自动从1开始继续轮番切换
                    if(picnum<121){
                    picnum++;
                    }
                    else{
                    	picnum=1;
                    }
                    console.log(picnum)
                }, 200);
            }
            $(function() {
                var heronum = 1;
                lolhero(heronum);
            });
            </script><?php endif; ?>
        <?php if(($_SESSION["gametypeid"]) == "2"): ?><ul class="hero_himg_ul dotahero">
                <li><img src="__PUBLIC__/dota80/1.jpg"></li>
                <li><img src="__PUBLIC__/dota80/2.jpg"></li>
                <li><img src="__PUBLIC__/dota80/3.jpg"></li>
                <li><img src="__PUBLIC__/dota80/4.jpg"></li>
                <li><img src="__PUBLIC__/dota80/5.jpg"></li>
                <li><img src="__PUBLIC__/dota80/6.jpg"></li>
                <li><img src="__PUBLIC__/dota80/7.jpg"></li>
                <li><img src="__PUBLIC__/dota80/8.jpg"></li>
                <li><img src="__PUBLIC__/dota80/9.jpg"></li>
                <li><img src="__PUBLIC__/dota80/10.jpg"></li>
            </ul>
             <script type="text/javascript">
            function dotahero(heronum) {
                var picnum=1;
                window.setInterval(function() {
                    var w_width = $(window).width();
                    var c_left = Math.floor(w_width / 15);
                    var c_top = c_left * 2;
                    $("#jswbox").height((c_left * 7));
                    if (heronum >= 2) {
                        var heronumfu = Number(heronum) - Number(2);
                        $(".dotahero li:eq(" + heronumfu + ")").css({
                            'left': "-" + c_left + "px",
                            'width': '0px',
                            'top': '' + c_top + 'px',
                            'z-index': '1',
                            'list-style-type': 'none'
                        });
                    }
                    if (heronum >= 1) {
                        var heronumfu = Number(heronum) - Number(1);
                        $(".dotahero li:eq(" + heronumfu + ")").css({
                            'left': "-" + c_left + "px",
                            'width': '' + (2 * c_left) + 'px',
                            'top': '' + c_top + 'px',
                            'z-index': '1',
                            'list-style-type': 'none'
                        });
                    }
                    $(".dotahero li:eq(" + heronum + ")").css({
                        'left': '' + (1 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '2',
                        'list-style-type': 'none'
                    });
                    var heronum1 = Number(heronum) + Number(1);
                    $(".dotahero li:eq(" + heronum1 + ")").css({
                        'left': '' + (2.5 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '3',
                        'list-style-type': 'none'
                    });
                    var heronum2 = Number(heronum) + Number(2);
                    $(".dotahero li:eq(" + heronum2 + ")").css({
                        'left': '' + (4 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '4',
                        'list-style-type': 'none'
                    });
                    var heronum3 = Number(heronum) + Number(3);
                    $(".dotahero li:eq(" + heronum3 + ")").css({
                        'left': '' + (5.5 * c_left) + 'px',
                        'width': '' + (4 * c_left) + 'px',
                        'top': '' + (c_top / 2) + 'px',
                        'z-index': '10',
                        'list-style-type': 'none'
                    });
                    var heronum4 = Number(heronum) + Number(4);
                    $(".dotahero li:eq(" + heronum4 + ")").css({
                        'left': '' + (9 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '9',
                        'list-style-type': 'none'
                    });
                    var heronum5 = Number(heronum) + Number(5);
                    $(".dotahero li:eq(" + heronum5 + ")").css({
                        'left': '' + (10.5 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '8',
                        'list-style-type': 'none'
                    });
                    var heronum6 = Number(heronum) + Number(6);
                    $(".dotahero li:eq(" + heronum6 + ")").css({
                        'left': '' + (12 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '7',
                        'list-style-type': 'none'
                    });
                    var heronum7 = Number(heronum) + Number(7);
                    $(".dotahero li:eq(" + heronum7 + ")").css({
                        'left': '' + (13.5 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '6',
                        'list-style-type': 'none'
                    });
                    var heronum8 = Number(heronum) + Number(8);
                    $(".dotahero li:eq(" + heronum8 + ")").css({
                        'left': '' + (15 * c_left) + 'px',
                        'width': '' + (2 * c_left) + 'px',
                        'top': '' + c_top + 'px',
                        'z-index': '5',
                        'list-style-type': 'none'
                    });
                    if(heronum>=1){
                        picnum1=picnum;
                        for(var i=0;i<9;i++){
                            
                            // console.log(picnum1);
                            console.log(i);
                            $(".dotahero li:eq("+i+") img").attr('src','./Public/Mobile/dota80/'+picnum1+'.jpg');
                            picnum1++;
                        }
                    }
                    // 当图片摆放达到上限的时候，自动从1开始继续轮番切换
                    if(picnum<104){
                    picnum++;
                    }
                    else{
                        picnum=1;
                    }
                    console.log(picnum)

                }, 200);
            }
            $(function() {
                var heronum = 1;
                dotahero(heronum);
            });
            </script><?php endif; ?>
    </div>
    <div class="hero-con">
        <h3>本期幸运英雄是？</h3>
        <?php if(is_array($roulette_property_type)): $i = 0; $__LIST__ = $roulette_property_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><h4><?php echo ($r["name"]); ?></h4>
            <div class="row">
                <?php if(($r["num"]) == "2"): if(is_array($r["roulette_property"])): $i = 0; $__LIST__ = $r["roulette_property"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?><a class="col-md-6 check_btn_yxc" data-checksid="<?php echo ($p["roulette_propertyId"]); ?>" data-checkspeilv="<?php echo ($p["peilv"]); ?>" data-checksname="<?php echo ($p["name"]); ?>"><?php echo ($p["name"]); ?>（<?php echo ($p["peilv"]); ?>）</a><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php else: ?>
                    <?php if(is_array($r["roulette_property"])): $i = 0; $__LIST__ = $r["roulette_property"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?><a class="col-md-4 check_btn_yxc" data-checksid="<?php echo ($p["roulette_propertyId"]); ?>" data-checkspeilv="<?php echo ($p["peilv"]); ?>" data-checksname="<?php echo ($p["name"]); ?>"><?php echo ($p["name"]); ?>（<?php echo ($p["peilv"]); ?>）</a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
        <a class="btn-hero" href="javascript:;">立即投注</a>
    </div>
    <div class="write-down">
        <p class="declare" style="text-align:center;">开奖结果完全取自时时彩，100%公平公正公开</p>
        <h3>今日开奖记录</h3>
        <p>
            <label>时时彩期数</label>
            <label>开奖号码</label>幸运英雄</p>
        <p>
            <label>第<?php echo ($daikaijiangqi); ?>期：</label>等待时时彩开奖结果(约10s-60s延迟)</p>
        <?php if(is_array($roulette_log)): $i = 0; $__LIST__ = $roulette_log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><p>
                <label>第<?php echo ($l["qi"]); ?>期：</label>
                <label><?php echo ($l["num"]); ?></label><?php echo ($l["hero_info"]); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<div class="box yxctz_box"></div>
<div class="layer yxctz_layer">
    <div class="con-layer">
        <h4 class="pcname">英雄猜</h4>
        <span class="pxiang">投注：</span>
        <div class="form-group">
            <div class="input-box">
                <label>竞猜金额 | </label>
                <input type="number" class="form-control" id="addgoldnum" name="goldname" placeholder="最低投注1金币" />
            </div>
        </div>
        <div class="money maybemoney" data-cpeilv="">
            <label>可能奖金 ：</label>0.0金币</div>
        <div class="money nowmoney">
            <label>当前余额 ：</label><?php echo ($user_yue); ?>金币</div>
    </div>
    <div class="bnt-con"><a class="hint-in1 hint" href="javascript:;">确  定</a><a class="hint-in2 cancel" href="javascript:;">取消</a></div>
</div>
<div class="box yxccg_box"></div>
<div class="layer yxccg_layer">
    <div class="con-layer">
        <P style=" font-size:18px; padding:20px 0; margin:0">投注成功</P>
    </div>
    <div class="bnt-con"><a class="hint-in3" href="javascript:;">确  定</a></div>
</div>
<div style="display:none;" id="all_peilv_box">
    <?php if(is_array($all_peilv)): $i = 0; $__LIST__ = $all_peilv;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?><input type="hidden" name="all_peilv_num(<?php echo ($a["num"]); ?>)" value="<?php echo ($a["peilv"]); ?>" /><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<input type="hidden" name="checkspeilv" value="" />
<input type="hidden" name="checksids" value="" />
<input type="hidden" name="checksnames" value="" />
<script type="text/javascript">

$("a.check_btn_yxc").on("click", function() {
    //$(this).removeClass("check_btn_yxc");
    var hasclass_ha = $(this).hasClass("hero-active");
    if (hasclass_ha >= 1) {
        $(this).parent().find("a.hero-active").removeClass("hero-active");
    } else {
        var this_checksid = $(this).attr("data-checksid");
        if (this_checksid == 12) {
            $(this).parent().parent().find("a.hero-active").removeClass("hero-active");
            $(this).addClass("hero-active");
        } else {
            var checksids_jiu = "";
            $("a.hero-active").each(function() {
                checksids_jiu = checksids_jiu + "," + $(this).attr("data-checksid");
                checksids_jiu = checksids_jiu.substr(1);
            });
            var checksids_jiu_arr = checksids_jiu.split(",");
            var inarray12id = $.inArray("12", checksids_jiu_arr);
            if (inarray12id < 0) {
                $(this).parent().find("a.hero-active").removeClass("hero-active");
                $(this).addClass("hero-active");
            } else {
                if (this_checksid == 10 || this_checksid == 11) {
                    $(this).parent().find("a.hero-active").removeClass("hero-active");
                    $(this).addClass("hero-active");
                }
            }

        }
    }

    var checksids = "";
    var checksnames = "";
    //var checkspeilv="1";
    $("a.hero-active").each(function() {

        checksids = checksids + "," + $(this).attr("data-checksid");
        //checkspeilv=checkspeilv*$(this).attr("data-checkspeilv");
        checksnames = checksnames + "," + $(this).attr("data-checksname");
    });
    checksids = checksids.substr(1);
    checksnames = checksnames.substr(1);
    //checkspeilv=Math.floor(checkspeilv*100)/100;
    var checkspeilv = $("div#all_peilv_box").find("input[name='all_peilv_num(" + checksids + ")']").val();

    var checksids_arr = checksids.split(",");
    var checksids_length = checksids_arr.length;

    $("input[name='checksids']").val(checksids);
    $("input[name='checksnames']").val(checksnames);
    $("input[name='checkspeilv']").val(checkspeilv);
    if (checksids == "") {
        $("a.btn-hero").text("立即投注");
    } else {
        if (checksids_length >= 2) {
            $("a.btn-hero").text("立即投注 组合赔率" + checkspeilv + "");
        } else if (checksids_length == 1) {
            $("a.btn-hero").text("立即投注 赔率" + checkspeilv + "");
        } else {
            $("a.btn-hero").text("立即投注");
        }
    }
    //alert(checksids);
});
$("a.btn-hero").click(function() {

    var checksids = $("input[name='checksids']").val();
    var checkspeilv = $("input[name='checkspeilv']").val();
    var checksnames = $("input[name='checksnames']").val();
    if (checksids == "") {
        alert("请选择投注项");
    } else if (checkspeilv == "") {
        alert("请选择投注项");
    } else if (checksnames == "") {
        alert("请选择投注项");
    } else {
        $(".pxiang").text("投注：" + checksnames + "(赔率：" + checkspeilv + ")");

        $(".yxctz_layer").css({
            "display": "block"
        });
        $(".yxctz_box").css({
            "display": "block"
        });
    }
});
$(".hint-in2").click(function(event) {
    $(".yxctz_layer").css({
        "display": "none"
    });
    $(".yxctz_box").css({
        "display": "none"
    });
});

$(".hint-in3").click(function(event) {
    $(".yxccg_layer").css({
        "display": "none"
    });
    $(".yxccg_box").css({
        "display": "none"
    });
});
$('#addgoldnum').bind('input propertychange', function() {

    var addgoldnum = Number($(this).val());
    var cpeilv = $("input[name='checkspeilv']").val();
    if (addgoldnum > 0) {
        var maybemoney = (addgoldnum * cpeilv).toFixed(2);
    } else {
        var maybemoney = 0.0;
    }
    $(".yxctz_layer").find("div.maybemoney").html("<label>可能奖金 ：</label>" + maybemoney + "金币");
});

function takeToken(){
    var token="1";
    //获取令牌
    $.ajax({
        type: "POST",
        url: "<?php echo U('Index/setFormCode');?>",
        datatype: "json",
        beforeSend: function () {
            $("#msg").html("logining");
        },
        success: function (data) {
          token=2
        },
        //调用执行后调用的函数
        complete: function () {
            token=3
        },
        error: function () {
            alert("获取令牌发生错误")
            window.location.reload();
        }
    });
   return  token;
}
$(".hint-in1").click(function() {
    $(this).removeClass("hint-in1");
    var checksids = $("input[name='checksids']").val();
    var goldname = $("input[name='goldname']").val();
   // var token=takeToken();
   // alert(token);exit;
    $.ajax({
        type: "POST",
        url: "<?php echo U('Index/hero_tz');?>",
        data: {checksids:checksids,goldname:goldname},
        //返回数据的格式"  xml", "html", "script", "json", "jsonp", "text".
        datatype: "json",
        //在请求之前调用的函数
        beforeSend: function () {
            $("#msg").html("logining");
        },
        //成功返回之后调用的函数
        success: function (data) {
            var v=eval('('+data+')');
            if (v.jieguo == "cg") {
                $(".header").find("span.fl").html("今日竞猜：" + v.ren + "人，" + v.jinbi + "金币");
                $(".header").find("span.header-r").html("余额：" + v.uyue + "");

                $(".nowmoney").html("<label>当前余额 ：</label>" + v.uyue + "金币");

                $(".btn-hero").text("立即投注");
                $(".pxiang").text("投注：");
                $("#addgoldnum").val("");
                $(".maybemoney").html("<label>可能奖金 ：</label>0.0金币");
                $("a.hero-active").each(function() {
                    $(this).removeClass("hero-active");
                });
                $("input[name='checksids']").val("");
                $("input[name='checksnames']").val("");
                $("input[name='checkspeilv']").val("");

                $(".yxctz_layer").css({
                    "display": "none"
                });
                $(".yxctz_box").css({
                    "display": "none"
                });

                $(".yxccg_layer").css({
                    "display": "block"
                });
                $(".yxccg_box").css({
                    "display": "block"
                });

            } else if (v.jieguo == "yuebuzu") {
                var jinbi_cha = goldname - v.user_yue;
                show_yuebz_box(jinbi_cha);
            } else if (v.jieguo == "no1") {
                alert("投注金额不能小于1金币");
            } else if (v.jieguo == "sb") {
                alert("投注失败");
            } else if (v.jieguo == "nocheck") {
                alert("请选择投注项");
            } else if (v.jieguo == "rsnomore") {
                alert("肉山不可选择其余属性");
            }
            $(this).addClass("hint-in1");
            // $("#msg").html(decodeURI(data));
        },
        //调用出错执行的函数
        error: function () {
            //请求出错处理
            alert("发生错误")
            window.location.reload();
        }
    });
});_
/*
$(".hint-in1").click(function() {

    $(this).removeClass("hint-in1");
    var checksids = $("input[name='checksids']").val();
    var goldname = $("input[name='goldname']").val();

    $.post("<?php echo U('Index/hero_tz');?>", {
        checksids: checksids,
        goldname: goldname
    },function(v) {
        alert(12)
        if (v.jieguo == "cg") {
            $(".header").find("span.fl").html("今日竞猜：" + v.ren + "人，" + v.jinbi + "金币");
            $(".header").find("span.header-r").html("余额：" + v.uyue + "");

            $(".nowmoney").html("<label>当前余额 ：</label>" + v.uyue + "金币");

            $(".btn-hero").text("立即投注");
            $(".pxiang").text("投注：");
            $("#addgoldnum").val("");
            $(".maybemoney").html("<label>可能奖金 ：</label>0.0金币");
            $("a.hero-active").each(function() {
                $(this).removeClass("hero-active");
            });
            $("input[name='checksids']").val("");
            $("input[name='checksnames']").val("");
            $("input[name='checkspeilv']").val("");

            $(".yxctz_layer").css({
                "display": "none"
            });
            $(".yxctz_box").css({
                "display": "none"
            });

            $(".yxccg_layer").css({
                "display": "block"
            });
            $(".yxccg_box").css({
                "display": "block"
            });

        } else if (v.jieguo == "yuebuzu") {
            var jinbi_cha = goldname - v.user_yue;
            show_yuebz_box(jinbi_cha);
        } else if (v.jieguo == "no1") {
            alert("投注金额不能小于1金币");
        } else if (v.jieguo == "sb") {
            alert("投注失败");
        } else if (v.jieguo == "nocheck") {
            alert("请选择投注项");
        } else if (v.jieguo == "rsnomore") {
            alert("肉山不可选择其余属性");
        }
        $(this).addClass("hint-in1");

    }, 'JSON');
});
*/
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