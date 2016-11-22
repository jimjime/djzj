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

<style type="text/css">
	.dld_top{width:100%;height:auto;background:#060606;}
	.dld_top_li{width:80%;margin:0 auto;}
	.dld_top_a{width:50%;display:block;float:left;height:auto;text-align:center;}
	.dld_top_a img{width:auto;max-width:100%;}
	.dld_time{width:100%;height:auto;padding-top:2rem;}
	.dld_time_lol{background:url(__PUBLIC__/new/images/dld_lol.jpg) no-repeat;background-size:cover;}
	.dld_time_dota{background:url(__PUBLIC__/new/images/dld_dota.jpg) no-repeat;background-size:cover;}
	.dld_time h1{width:100%;height:3rem;line-height:3rem;font-size:2rem;color:#fff;font-weight: bold;text-align:center;letter-spacing:3px;margin-top:0;}
	.dld_time_box{width:100%;margin:0 auto;text-align:center;}
	.dld_time_box_num{width:6rem;height:8rem;background:url(__PUBLIC__/new/images/dld_time.png) no-repeat;background-size:cover;line-height:7rem;text-align:center;margin-right:1rem;display:inline-block;position:relative;}
	.dld_time_box_num span{font-size:8rem;color:#333333;font-weight:600;}
	.dld_time_box_num:nth-child(2){margin-right:2rem;}
	.dld_time_box_num:nth-child(4){margin-right:0;}
	.dld_time_box_num_h{width:100%;height:100%;position:absolute;left:0;top:0;z-index:2;}
	.dld_time_box_num_h img{width:100%;height:auto;}
	.dld_wyao{width:100%;text-align:center;padding:2rem 0;}
	.dld_wyao a{padding:0.5rem 2rem;font-size:2rem;color:#fff;font-weight:bold;background:#19a2f4;border-radius:2rem;}
	.dld_users{width:100%;height:auto;overflow:hidden;background:#1d2b32;}
	.dld_user{width:11.5rem;height:auto;float:left;padding:1rem;}
	.dld_user_header{width:4rem;height:4rem;border-radius:50%;overflow:hidden;float:left;margin-right:0.5rem;}
	.dld_user_header img{width:100%;height:100%;}
	.dld_user_text{width:5rem;height:2rem;line-height:2rem;font-size:1rem;float:left;color:#c4c4c4;overflow:hidden;}
	.dld_title{width:100%;background:#131c21;height:3rem;line-height:3rem;}
	.dld_title_icon{margin-left:1rem;margin-right:0.5rem;width:1rem;height:1rem;background:#1275a6;border-radius:50%;display:inline-block;}
	.dld_title_big{color:#d3d3d3;font-size:2rem;display:inline-block;}
	.dld_title_small{display:inline-block;float:right;text-align:right;font-size:1rem;}
	.dld_title_small .span1{width:1.2rem;height:1.2rem;display:inline-block;}
	.dld_title_small .span1 img{width:100%;height:100%;margin-top:-0.4rem;}
	.dld_title_small .span2{color:#1275a6;}
	.dld_title_small .span3{color:#d3d3d3;}
	.dld_title_small .span4{color:#be7900;}
	.dld_title_small .span5{color:#d3d3d3;}
	.dld_title_small .span6{color:#d70000;padding-right:1rem;}
	.dld_jchi{width:100%;height:auto;background:#1d2b32;}
	.dld_jchi_ul{width:80%;margin:0 auto;padding:1rem 0;}
	.dld_jchi_li{width:25%;height:auto;padding-bottom:5px;float:left;}
	.dld_jchi_li img{width:95%;border:1px solid #676767;height:auto;}
	.dld_jilu{width:100%;height:auto;}
	.dld_jilu_li{width:100%;height:auto;background:#1d2b32;padding:1.5rem 0;margin-bottom:0.8rem;}
	.dld_jilu_li_l{width:20%;float:left;text-align:right;}
	.dld_jilu_li_l img{width:5rem;height:5rem;border-radius:50%;margin-right:1rem;}
	.dld_jilu_li_r{width:80%;float:left;text-align:right;}
	.dld_jilu_li_r_t{width:100%;height:auto;max-height:4rem;overflow:hidden;text-align:left;line-height:2rem;font-size:1.5rem;}
	.dld_jilu_li_r_t .span1{color:#dbdbdb;}
	.dld_jilu_li_r_t .span2{color:#dbdbdb;}
	.dld_jilu_li_r_t .span3{color:#b41c1c;}
	.dld_jilu_li_r_t .span4{color:#1382b5;float:right;display:inline-block;padding-right:1rem;}
	.dld_jilu_li_r_x{width:100%;height:auto;}
	.dld_jilu_li_r_x span{width:5rem;height:auto;float:left;margin-right:2px;margin-bottom:2px;border:1px solid #676767;}
	.dld_jilu_li_r_x span img{width:100%;height:auto;}
	.img_icon_btn{width:100%;background:#19a2f4;color:#f3f4f5;font-size:2rem; letter-spacing:1px;text-align:center;height:5rem;line-height:5rem;border-radius:0 0 5px 5px;overflow:hidden;}
	.img_icon_btn .img_icon_btn3{width:50%;float:left;background:#0970ae;color:#aeb6ba;}
	.img_icon_btn .img_icon_btn4{width:50%;float:left;background:#28373f;color:#aeb6ba;}
	.dld_tc_t{text-align:center;color:#0970ae;font-size:2rem;line-height:7rem;font-weight:bold;}
	.dld_tc_list{width:90%;margin:0 auto;}
	.dld_tc_list_li{width:31%;margin-right:2%;float:left;margin-bottom:0.5rem;}
	.dld_tc_list_img{width:100%;height:auto;position:relative;}
	.dld_tc_list_img img{width:100%;height:auto;}
	.dld_tc_list_icon{position:absolute;right:-0.4rem;bottom:-0.3rem;z-index:2;width:1.4rem;height:1.4rem;display:none;}
	.dld_tc_list_icon img{width:100%;height:100%;}
	.dld_tc_list_text{font-size:1.5rem;color:#dcdcdc;line-height:2rem;text-align:center;}
	.dld_tc_d{text-align:center;color:#0970ae;font-size:2rem;line-height:2.5rem;padding:1rem 0;}
	.dld_tc_d span{color:#0970ae;}
	.selectedb {position: absolute;left: -6.5%;top: -2.5%;width: 112%;height: auto;}
	.selectedb img {width: 100%;height: auto;}
	.evdlduser {box-sizing: border-box; float: left; position: relative; width: 20%; height: auto; padding: 10px; }
	.evdlduser img { width: 100%; height:auto; border-radius: 50%; }
</style>
<!--主体-->
<div class="wrap">
	<!--
	<div class="dld_top">
		<div class="dld_top_li">
			<a href="<?php echo U('Index/hero');?>" class="dld_top_a">
				<img src="__PUBLIC__/new/images/yxc.jpg">
			</a>
			<a href="<?php echo U('Index/dld');?>" class="dld_top_a">
				<img src="__PUBLIC__/new/images/dld_c.jpg">
			</a>
			<div class="clear"></div>
		</div>

	</div>
	-->
	<div class="dld_time <?php if(($_SESSION["gametypeid"]) == "1"): ?>dld_time_lol <?php else: ?> dld_time_dota<?php endif; ?> ">
		<h1>倒计时</h1>
		<div class="dld_time_box">
			<div class="dld_time_box_num">
				<span class="fen1">0</span>
				<div class="dld_time_box_num_h">
					<img src="__PUBLIC__/new/images/dld_time2.png">
				</div>
			</div>
			<div class="dld_time_box_num">
				<span class="fen2">1</span>
				<div class="dld_time_box_num_h">
					<img src="__PUBLIC__/new/images/dld_time2.png">
				</div>
			</div>
			<div class="dld_time_box_num">
				<span class="miao1">0</span>
				<div class="dld_time_box_num_h">
					<img src="__PUBLIC__/new/images/dld_time2.png">
				</div>
			</div>
			<div class="dld_time_box_num">
				<span class="miao2">0</span>
				<div class="dld_time_box_num_h">
					<img src="__PUBLIC__/new/images/dld_time2.png">
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="dld_wyao">
			<a href="javascript:;" onclick="show_dld_tz()">我要乱斗</a>
		</div>

	</div>
	<div class="dld_users">
		<?php if(is_array($nowdld_users)): $i = 0; $__LIST__ = $nowdld_users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$u): $mod = ($i % 2 );++$i;?><div class="evdlduser dld_user_id<?php echo ($u["userId"]); ?>" data-uid="<?php echo ($u["userId"]); ?>">
				<?php if(empty($u["header"])): ?><img src="__PUBLIC__/images/user-inco.png">
				<?php else: ?>
					<img src="<?php echo ($u["header"]); ?>"><?php endif; ?>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>

		<div class="clear"></div>
	</div>
	<div class="dld_title">
		<div class="dld_title_icon"></div>
		<div class="dld_title_big">
			决斗场
		</div>
		<div class="dld_title_small">
			<span class="span1">
				<img src="__PUBLIC__/new/images/ren.png">
			</span>
			<span class="span2"><?php echo ($nowdld["rennum"]); ?></span>
			<span class="span3">部队数：</span>
			<span class="span4"><?php echo ($nowdld["bnum"]); ?></span>
			<span class="span5">战斗力总值：</span>
			<span class="span7"><?php echo ($nowdld["my_dld_jine"]); ?></span>
			/
			<span class="span6"><?php echo ($nowdld["zjine"]); ?></span>
		</div>
	</div>
	<div class="dld_jchi">
		<div class="dld_jchi_ul">
			<?php if(is_array($nowdld_users)): $i = 0; $__LIST__ = $nowdld_users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$u): $mod = ($i % 2 );++$i; if(is_array($u["dldimgs"])): $i = 0; $__LIST__ = $u["dldimgs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$i): $mod = ($i % 2 );++$i;?><div class="dld_jchi_li">
						<img src="<?php echo ($i["img"]); ?>">
					</div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>

			<div class="clear"></div>
		</div>

	</div>
	<div class="dld_title">
		<div class="dld_title_icon"></div>
		<div class="dld_title_big" style="display:inline;">
			历史中奖记录
		</div>

	</div>
	<div class="dld_jilu">
		<?php if(is_array($old_dld)): $i = 0; $__LIST__ = $old_dld;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?><div class="dld_jilu_li">
				<div class="dld_jilu_li_l">
					<?php if(empty($o["users"]["header"])): ?><img src="__PUBLIC__/images/user-inco.png">
					<?php else: ?>
						<img src="<?php echo ($o["users"]["header"]); ?>"><?php endif; ?>
				</div>
				<div class="dld_jilu_li_r">
					<div class="dld_jilu_li_r_t">
						<span class="span1"><?php echo ($o["users"]["name"]); ?></span>
					<span class="span2">
						以<span class="span3"><?php echo ($o["gailv"]); ?>%</span>的概率赢得了<span class="span3"><?php echo ($o["yjine"]); ?></span>金币
					</span>
						<span class="span4"><?php echo ($o["qi"]); ?>期</span>
					</div>
					<div class="dld_jilu_li_r_x">
						<?php if(is_array($o["jilu_img"])): $i = 0; $__LIST__ = $o["jilu_img"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$j): $mod = ($i % 2 );++$i;?><span>
								<img src="<?php echo ($j["img"]); ?>" >
							</span><?php endforeach; endif; else: echo "" ;endif; ?>

						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>

	</div>
	<div class="cp_more" >
		<input type="hidden" value="1" id="nowpage" />
		<a href="javascript:;" class="more_btn">点击查看更多</a>
	</div>
	<script type="text/javascript">
		$(".more_btn").click(function(){
			var nowpage=$("#nowpage").val();
			$.post("<?php echo U('Index/more_dld_jilu');?>", {nowpage:nowpage}, function(v){
				$(".dld_jilu").append(v.ccc);
				$("#nowpage").val(v.nextpage);
			},'JSON');
		})
	</script>
</div>
<!--弹窗 start-->
<div class="tc_box_bg" id="dld_tz"  >
	<div class="tc_box" style="margin-top:0;top:0;max-height:98%;overflow:auto;">

		<div class="dld_tc_t">
			选择你要投入的战斗力
		</div>
		<div class="dld_tc_list">
			<?php if(is_array($dld_b)): $i = 0; $__LIST__ = $dld_b;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$b): $mod = ($i % 2 );++$i;?><div class="dld_tc_list_li" data-id="<?php echo ($b["id"]); ?>" data-jb="<?php echo ($b["jine"]); ?>">
					<div class="dld_tc_list_img">
						<img src="<?php echo ($b["img"]); ?>">
						<div class="dld_tc_list_icon">
							<img src="__PUBLIC__/new/images/icon_check.png">
						</div>
					</div>
					<div class="dld_tc_list_text">
						<?php echo ($b["name"]); ?><br>
						<?php echo ($b["jine"]); ?>金币
					</div>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="clear"></div>
		</div>
		<div class="dld_tc_d">
			参与<span class="zb">0</span>支部队，共计<span class="jb">0</span>金币，预计中奖概率<span class="gl">0</span>
		</div>
		<div class="img_icon_btn">
			<input type="hidden" style="color:#000;" id="tz_b_ids" value="" >
			<a href="javascript:;" class="img_icon_btn3" onclick="to_dld_tz()">确认投注</a>
			<a href="javascript:;" onclick="hide_dld_tz()" class="img_icon_btn4">取消</a>
			<div class="clear"></div>
		</div>
	</div>
</div>
<!--弹窗 end-->
<script type="text/javascript">
	function to_dld_tz(){
		var zb_obj=$(".dld_tc_d").find(".zb");
		var zb_num=zb_obj.text();
		var jb_obj=$(".dld_tc_d").find(".jb");
		var jb_num=jb_obj.text();
		var tz_b_ids=$("#tz_b_ids").val();
		var yi_b_num=$(".dld_title_small").find(".span4").text();
		var zong_b_num=Number(zb_num)+Number(yi_b_num);
		if(zong_b_num>10){
			alert("部队总数最多10支");
			return false;
		}
		if(zb_num<=0){
			alert("部队数最少一支");
			return false;
		}
		if(zb_num>3){
			alert("部队数最多三支");
			return false;
		}
		if(jb_num<=0){
			alert("部队数最少一支");
			return false;
		}
		$.post("<?php echo U('Index/dld_tz');?>", {tz_b_ids:tz_b_ids}, function(v){
			if(v.jieguo=="cg"){
				$(".header").find("span.header-r").html("余额："+v.user_yue+"");
				$(".nowmoney").html("<label>当前余额 ：</label>"+v.user_yue+"金币");
				$("#tz_b_ids").val("");
				$(".dld_jchi_ul").prepend(v.tz_imgs);
				var dld_user_id=$(".dld_users").find(".dld_user_id"+v.uid).attr("data-uid");
				if(dld_user_id== v.uid){
					//var dld_user_id_jine_obj=$(".dld_users").find(".dld_user_id"+v.uid).find(".dld_user_jine");
					//var new_dld_user_id_jine=Number(dld_user_id_jine_obj.text())+Number(v.tz_jine);
					//dld_user_id_jine_obj.text(new_dld_user_id_jine);
				}else{
					//$(".dld_users").prepend("<div class='dld_user dld_user_id"+v.uid+"' data-uid='"+v.uid+"'><div class='dld_user_header'><img src='"+v.user_header+"'></div><div class='dld_user_text'>"+v.user_name+"</div><div class='dld_user_text dld_user_jine'>"+v.tz_jine+"</div><div class='clear'></div></div>");
					//$(".dld_users").prepend("<div class='evdlduser dld_user_id"+v.uid+"' data-uid='"+v.uid+"'><img src='"+v.user_header+"'></div>");

				}
				//if(v.shengyumiao>=0){
				//	intDiff=v.shengyumiao;
				//	if(intDiff==0){
				//		dld_kj();
				//	}else if(intDiff>0){
				//		timer(intDiff);
				//	}
				//}
				$(".dld_title_small").find(".span2").text(v.rennum);
				$(".dld_title_small").find(".span4").text(v.bnum);
				$(".dld_title_small").find(".span6").text(v.zjine);

				hide_dld_tz();
				$(".dld_tc_list_icon").hide();
				var zb_obj=$(".dld_tc_d").find(".zb");
				var jb_obj=$(".dld_tc_d").find(".jb");
				var gl_obj=$(".dld_tc_d").find(".gl");
				zb_obj.text(0);
				jb_obj.text(0);
				gl_obj.text(0);
				$("#tz_b_ids").val("");


			}else if(v.jieguo=="yuebuzu"){
				var jinbi_cha= v.tz_jine-v.user_yue;
				show_yuebz_box(jinbi_cha);
			}else if(v.jieguo=="sb"){
				alert("投注失败");
			}else if(v.jieguo=="zb11"){
				alert("总部队数不能大于10支");
			}else if(v.jieguo=="zb1"){
				alert("部队数最少一支");
			}else if(v.jieguo=="zb3"){
				alert("部队数最多三支");
			}
			if(v.shifounew==1){
				window.location.reload();
			}
			//$(this).addClass("hint-in1");

		},'JSON');
	}
	function show_dld_tz(){
		$("#dld_tz").show();
	}
	function hide_dld_tz(){
		$("#dld_tz").hide();
	}
	$(function(){
		$(".dld_tc_list_li").click(function(){
			var icon_obj=$(this).find(".dld_tc_list_icon");
			var icon_zt=icon_obj.css("display");
			var zb_obj=$(".dld_tc_d").find(".zb");
			var zb_num=zb_obj.text();
			var jb_obj=$(".dld_tc_d").find(".jb");
			var jb_num=jb_obj.text();
			var gl_obj=$(".dld_tc_d").find(".gl");
			var gl_num=gl_obj.text();
			var b_jb=$(this).attr("data-jb");
			var b_id=$(this).attr("data-id");
			var zjine=$(".dld_title_small").find(".span6").text();
			var tz_b_ids_obj=$("#tz_b_ids");
			var tz_b_ids_val=tz_b_ids_obj.val();
			var tz_b_ids_arr=tz_b_ids_val.split(",");

			if(icon_zt=="none"){
				var yi_b_num=$(".dld_title_small").find(".span4").text();
				var zong_b_num=Number(zb_num)+Number(yi_b_num);
				if(zong_b_num>10){
					alert("部队总数最多10支");
					return false;
				}
				if(zb_num>=3){
					alert("部队最多只能选3支");
				}else{
					icon_obj.show();
					var zb_newnum=Number(zb_num)+1;
					zb_obj.text(zb_newnum);
					var jb_newnum=Number(jb_num)+Number(b_jb);
					jb_obj.text(jb_newnum);
					var gl_newnum=Math.floor((jb_newnum/(Number(zjine)+Number(jb_newnum)))*100*100)/100+"%";
					gl_obj.text(gl_newnum);


					tz_b_ids_arr.push(b_id);
					if($.inArray("",tz_b_ids_arr)>=0){
						tz_b_ids_arr.splice($.inArray("",tz_b_ids_arr),1);
					}
					var newtz_b_ids_val=tz_b_ids_arr.join(",");
					tz_b_ids_obj.val(newtz_b_ids_val);
				}

			}else{
				icon_obj.hide();
				var zb_newnum=Number(zb_num)-1;
				zb_obj.text(zb_newnum);
				var jb_newnum=Number(jb_num)-Number(b_jb);
				jb_obj.text(jb_newnum);
				var gl_newnum=Math.floor((jb_newnum/(Number(zjine)+Number(jb_newnum)))*100*100)/100+"%";
				gl_obj.text(gl_newnum);

				tz_b_ids_arr.splice($.inArray(b_id,tz_b_ids_arr),1);
				var newtz_b_ids_val=tz_b_ids_arr.join(",");
				tz_b_ids_obj.val(newtz_b_ids_val);
			}

		});
	});

	//获取最新奖池图片
	var updatea=null;
	function get_dld_jc(){

		updatea=setInterval(function(){
			var now_jc_num=$("div.dld_jchi_ul").find("div.dld_jchi_li").length;
			var nowdldqi=$(".dld_jilu .dld_jilu_li").first().find(".dld_jilu_li_r_t").find(".span4").text();
			nowdldqi=nowdldqi.substring(0,nowdldqi.length-1);

			var nowdld_users_ids="";

			$("div.dld_users").find("div.evdlduser").each(function(){
				nowdld_users_ids=nowdld_users_ids+","+$(this).attr("data-uid");
			});

			$.post("<?php echo U('Index/get_dld_jc');?>", {now_jc_num:now_jc_num,nowdldqi:nowdldqi,nowdld_users_ids:nowdld_users_ids}, function(v){

				$(".dld_jchi_ul").html(v.moreimg);
				$(".dld_title_small").find(".span2").text(v.rennum);
				$(".dld_title_small").find(".span4").text(v.bnum);
				$(".dld_title_small").find(".span6").text(v.zjine);
				$(".dld_title_small").find(".span7").text(v.my_dld_jine);
				$(".dld_users").prepend(v.moreuser);
				if(v.shengyumiao>=0){
					var begindjs=$("#begindjs").val();
					if(begindjs<=0){
						intDiff=v.shengyumiao;
						timer(intDiff);
						$("#begindjs").val(1);
					}

				}else{
					clearInterval(timerxh);
				}
				if(v.more_dld_jilus!=undefined){
					$(".dld_jilu").prepend(v.more_dld_jilus);
					//$("input#nowdldqi").val(v.nowdldqi);
					//alert(v.qi1);
					//alert(v.more_dld_jilus);
				}
			},'JSON');
		}, 2000);
	}
	$(function(){
		get_dld_jc();
	});
	function clear_users(){
		$(".evdlduser").remove();
	}
	function dld_kj(){
		$.post("<?php echo U('Index/ajax_dld_kj');?>", {}, function(v){
			if(v.yikai!=1){
				$(".dld_jilu").prepend(v.kj_html);
				$("#begindjs").val(0);
				$(".selectedb").remove();
				$(".dld_user_id"+v.uid).prepend('<div class="selectedb"><img src="./Public/Mobile/new/images/dld/sec.png"></div>');
				begin_check_user=1;
			}
			//clearInterval(timerxh);
			//timerxh=null;
			//$("#begindjs").val(0);
			//$('.fen1').text(0);
			//$('.fen2').text(1);
			//$('.miao1').text(0);
			//$('.miao2').text(0);
			//$(".dld_time_box_num").find("span").css("color","#333333");
//
			//setTimeout("clear_users()",4900);
			//setTimeout("get_dld_jc()",5000);

		},'JSON');
		clearInterval(timerxh);
		timerxh=null;
		$("#begindjs").val(0);
		$('.fen1').text(0);
		$('.fen2').text(1);
		$('.miao1').text(0);
		$('.miao2').text(0);
		$(".dld_time_box_num").find("span").css("color","#333333");

		setTimeout("clear_users()",4900);
		setTimeout("get_dld_jc()",5000);

	}
</script>
<script type="text/javascript">
	var timerxh = null;
	var ranselec=null;
	var begin_check_user=1;
	var intDiff = parseInt(<?php echo ($nowdld["shengyumiao"]); ?>);//倒计时总秒数量
	function timer(intDiff){
		timerxh=setInterval(function(){
			if(intDiff==0){
				$('.fen1').text(0);
				$('.fen2').text(1);
				$('.miao1').text(0);
				$('.miao2').text(0);
				$(".dld_time_box_num").find("span").css("color","#333333");
				dld_kj();
				clearInterval(updatea);
			}
			var hour=0,
					minute=0,
					second=0;//时间默认值
			var miao1=0;
			var miao2=0;
			if(intDiff > 0){
				hour = Math.floor(intDiff / (60 * 60));
				minute = Math.floor(intDiff / 60) - (hour * 60);
				second = Math.floor(intDiff) - (hour * 60 * 60) - (minute * 60);
			}
			if (minute <= 9) minute = minute;
			if (second <= 9){
				miao1 = 0;
				miao2 = second;
			}else{
				miao1=Math.floor(second/10);
				miao2=Math.floor(second%10);
			}
			if(minute>0){
				$('.fen1').text(0);
				$('.fen2').text(minute);
				$('.miao1').text(miao1);
				$('.miao2').text(miao2);
			}else{
				$('.fen1').text(0);
				$('.fen2').text(0);
				$('.miao1').text(miao1);
				$('.miao2').text(miao2);
			}
			if(intDiff<=10 && intDiff>0){
				$(".dld_time_box_num").find("span").css("color","#b41c1c");
			}

			if(intDiff<=1){
				clearInterval(ranselec);
			}
			if(intDiff<=9 && intDiff>1){
				if(begin_check_user==1){
					check_user();
					begin_check_user=0;
				}

			}
			if(intDiff<=0){
				$('.fen1').text(0);
				$('.fen2').text(1);
				$('.miao1').text(0);
				$('.miao2').text(0);
				$(".dld_time_box_num").find("span").css("color","#333333");
				//dld_kj();
			}

			intDiff--;
		}, 1000);
	}
	function check_user(){
		if(ranselec==null){
			ranselec=setInterval(function(){
				var i=$("div.dld_users div.evdlduser").length;
				//alert(i);
				$(".selectedb").remove();
				var randomnum=Math.floor(Math.random()*i);
				console.log(randomnum);
				$(".dld_users div:eq("+randomnum+")").prepend('<div class="selectedb"><img src="./Public/Mobile/new/images/dld/sec.png"></div>');
			}, 100);
		}

	}
	$(function(){
		if(intDiff==0){
			$('.fen1').text(0);
			$('.fen2').text(1);
			$('.miao1').text(0);
			$('.miao2').text(0);
			$(".dld_time_box_num").find("span").css("color","#333333");
			dld_kj();
		}else if(intDiff>0){
			clearInterval(timerxh);
			timer(intDiff);
		}

	});
</script>

<input type="hidden" id="begindjs" value="0">
<input type="hidden" id="nowdldqi" value="<?php echo ($nowdld["qi"]); ?>">
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