<include file="Public:header_active"/>

<!--主体 start-->
<div class="mainbox">
	<!--头部 start-->
	<div class="headerbox">
		酷暑送皮肤	
	</div>
	<div class="messagebox">
		<div class="messagebox_li">
			<span class="span1">
				中奖提示:
			</span>
			<span class="span2">
				{$zjts_info}
			</span>
		</div>
	</div>
	<!--头部 end-->
	<!--转盘 start-->
	<div class="panbg">
		<div class="pan" data-pan="1">
			<div class="pifu199" data-pifu="1">
				<img src="__PUBLIC__/new/images/pifu199s1.png" />
			</div>
			<div class="pifu3" data-pifu="1">
				<img src="__PUBLIC__/new/images/pifu3s1.png" />
			</div>
			<div class="pifu69" data-pifu="1">
				<img src="__PUBLIC__/new/images/pifu69s1.png" />
			</div>
			<div class="panzi">
				<img src="__PUBLIC__/new/images/panzibg.png" />
			</div>
			<div class="panline" data-pan="1">
				<img src="__PUBLIC__/new/images/pan1.png" />
			</div>
			<div class="panbtn">
				<img src="__PUBLIC__/new/images/panbtn.png" id="lotteryBtn" />
				<a href="javascript:;" id="panbtn_a"></a>
			</div>
		</div>
		<empty name="islc">
			<div class="lcbtn">
				<a href="javascript:;" onclick="show_tc_slchou()">十连抽<span class="jinbi">（888金币）</span></a>
			</div>
		</empty>
		<div class="invbtn">
			<a href="{:U('Index/myqr')}" >招募战友<span class="jinbi">（+88金币）</span></a>
		</div>
	</div>
	<div class="pan_info">
		<div class="pan_info_box">
			<div class="pan_info_wxts" style="color:#DDD000;">
				兑换流程：点击确认兑换后请加Q群：528279088，私戳兑换管理员
			</div>
			<div class="pan_info_suipian">
				<span class="pifu69num">
					69元皮肤碎片:<font class="pifu69nums">{$pifu69count}</font>
				</span>
				<span class="pifu199num">
					199元皮肤碎片:<font class="pifu199nums">{$pifu199count}</font>
				</span>
				<a class="tojpck" href="{:U('Active/ck')}">
					奖品仓库>
				</a>
				<div class="clear"></div>
			</div>
			<div class="pan_info_wxts">
				温馨提示：<br />
				1、登录全民菠菜微信号即可获得118金币，成功邀请战友将获得88金币，即可抽奖一次。<br />
				2、10个199碎片即可兑换199元任意皮肤，5个69碎片，即可兑换69元任意皮肤。<br />
				3、活动时间：2016年7月22日11：00-8月21日11：00。<br />
				4、皮肤碎片请在2016年8月21日11：00之前使用，过期将自动失效。<br />
				5、活动专属QQ群：528279088。<br />
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(function(){
		//setTimeout("exchange_pan1()",50);
		exchange_pan();
		getzjinfo();
		exchange_pifu199();
		setTimeout("exchange_pifu69()",1000);
		setTimeout("exchange_pifu3()",2000);

//		$.post("{:U('Active/slcChareg')}", { }, function(v){
//			if(v.status=="yes"){
//				$('.lcbtn').children().attr("onclick",null);
//			}else{
//				
//			}
//		},'JSON');
		
	});
//	function rotate_panbtn(){
//		$('#panbtn_img').rotate();
//	}
	
	function getzjinfo(){
		window.setInterval(function(){
			$.post("{:U('Active/getzjinfo_ajax')}", { }, function(v){
				
				$(".messagebox_li").append("<br><span class='span1'>中奖提示: </span><span class='span2'>"+v+"</span>");
				var css_top=$(".messagebox_li").css("top");
				var css_height=$(".messagebox_li").css("line-height");
				//alert(css_height);
				var new_css_top=Number(css_top.substr(0,(css_top.length-2)))-Number(css_height.substr(0,(css_height.length-2)));
				//alert((css_top.substring(0,(css_top.length-3))));
				$(".messagebox_li").animate({"top":""+new_css_top+"px"});
			});
		},2000);
	}
	function exchange_pan(){
		window.setInterval(function(){
			var panid=$(".panline").attr("data-pan");
			if(panid==1){
				$(".panline img").attr("src","./Public/Mobile/new/images/pan2.png");
				$(".panline").attr("data-pan",2);
			}else{
				$(".panline img").attr("src","./Public/Mobile/new/images/pan1.png");
				$(".panline").attr("data-pan",1);
			}
		},250);
	}
	function exchange_pifu199(){
		window.setInterval(function(){
			var pifu199=$(".pifu199").attr("data-pifu");
			if(pifu199<=4){
				pifu199=Number(pifu199)+1;
				$(".pifu199 img").attr("src","./Public/Mobile/new/images/pifu199s"+pifu199+".png");
				$(".pifu199").attr("data-pifu",pifu199);
			}else{
				$(".pifu199 img").attr("src","./Public/Mobile/new/images/pifu199s1.png");
				$(".pifu199").attr("data-pifu",1);
			}
		},3000);
	}
	function exchange_pifu69(){
		window.setInterval(function(){
			var pifu69=$(".pifu69").attr("data-pifu");
			if(pifu69<=4){
				pifu69=Number(pifu69)+1;
				$(".pifu69 img").attr("src","./Public/Mobile/new/images/pifu69s"+pifu69+".png");
				$(".pifu69").attr("data-pifu",pifu69);
			}else{
				$(".pifu69 img").attr("src","./Public/Mobile/new/images/pifu69s1.png");
				$(".pifu69").attr("data-pifu",1);
			}
		},3000);
	}
	function exchange_pifu3(){
		window.setInterval(function(){
			var pifu3=$(".pifu3").attr("data-pifu");
			if(pifu3<=4){
				pifu3=Number(pifu3)+1;
				$(".pifu3 img").attr("src","./Public/Mobile/new/images/pifu3s"+pifu3+".png");
				$(".pifu3").attr("data-pifu",pifu3);
			}else{
				$(".pifu3 img").attr("src","./Public/Mobile/new/images/pifu3s1.png");
				$(".pifu3").attr("data-pifu",1);
			}
		},3000);
	}
	</script>
	<!--转盘 end-->
</div>
<!--主体 end-->
<!--弹窗 start-->
<div class="tc_box_bg" id="yuebuzu" >
	<div class="tc_box">
		<div class="tc_box_close" onclick="hide_yuebz()">
			<img src="__PUBLIC__/new/images/close.png" />
		</div>
		<div class="img_icon">
			<img class="sad_img" src="__PUBLIC__/new/images/sad.png" />
		</div>
		<div class="img_icon_text">
			余额不足
		</div>
		<div class="img_icon_btn">
			<a href="{:U('Index/myqr')}" class="img_icon_btn1">招募战友</a>
			<a href="{:U('Index/topay')}" class="img_icon_btn2">充 值</a>
			<div class="clear"></div>
		</div>
	</div>
</div>
<!--弹窗 end-->
<!--弹窗 start-->
<div class="tc_box_bg" id="zhongjiang" >
	<div class="tc_box">
		<div class="zj_box_bg">
			<div class="zj_box_bg_img">
				<img src="__PUBLIC__/new/images/zhongjiang_bg.png" />
			</div>
			<div class="zj_box_jp_img" id="zj_box_jp_img">
				<img src="" />
			</div>
			<div class="zj_box_jp_text">
				 <p>获得:</p>
				 <p id="zj_box_jp_text"></p>
			</div>
		</div>
		<div class="img_icon_btn" id="jp_btn_div">
			<a href="javascript:;" class="img_icon_btn_all" id="jp_lq_btn" onclick="hide_zhongjiang()">领 取</a>
			<a href="javascript:;" class="img_icon_btn_all" id="again_cj_js" style="display:none;" >再来一次</a>
		</div>
	</div>
</div>
<script type="text/javascript">
function hide_zhongjiang(){
	$("#zhongjiang").hide();
}
</script>
<!--弹窗 end-->
<!--抽奖js start-->
<script type="text/javascript">
$(function(){
	var rotateFunc = function(awards,angle,texts,imgurl){  //awards:奖项，angle:奖项对应的角度
		$('#lotteryBtn').stopRotate();
		$("#lotteryBtn").rotate({
			angle:0, 
			duration: 5000, 
			animateTo: angle+1440, //angle是图片上各奖项对应的角度，1440是我要让指针旋转4圈。所以最后的结束的角度就是这样子^^
			callback:function(){
				if(awards==8){
					var pifu199nums=$(".pifu199nums").text();
					var pifu199nums_new=Number(pifu199nums)+1;
					$(".pifu199nums").text(pifu199nums_new);
				}
				if(awards==7){
					var pifu69nums=$(".pifu69nums").text();
					var pifu69nums_new=Number(pifu69nums)+1;
					$(".pifu69nums").text(pifu69nums_new);
				}
				$("#zhongjiang").show();
				$("#panbtn_a").show();
			}
		}); 
	};
	
	$("#panbtn_a").rotate({
	   bind: 
		 { 
			click: function(){
					$("#panbtn_a").hide();
					$.post("{:U('Active/lottery')}", { }, function(v){
						if(v.jg=="yuebuzu"){
							$("#yuebuzu").show();
						}else{
							// v.id=8;
							if (v.id == 1) {
								var angle = 270;
								$("#zj_box_jp_text").text("6金币");
								$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jinbi6.png");
								$("#jp_lq_btn").show();
								$("#again_cj_js").hide();
							}
							if (v.id == 2) { 
								var angle = 135;
								$("#zj_box_jp_text").text("66金币");
								$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jinbi66.png");
								$("#jp_lq_btn").show();
								$("#again_cj_js").hide();
							}
							if (v.id == 3) { 
								var angle = 90;
								$("#zj_box_jp_text").text("666金币");
								$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jinbi666.png");
								$("#jp_lq_btn").show();
								$("#again_cj_js").hide();
							}
							if (v.id == 4) { 
								var angle = 225; 
								$("#zj_box_jp_text").text("再来一次");
								$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jp_again.png");
								$("#jp_lq_btn").hide();
								$("#again_cj_js").show();
							}
							if (v.id == 5) { 
								var angle = 0;   
								$("#zj_box_jp_text").text("100点卷");
								$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/dianquan.png");
								$("#jp_lq_btn").show();
								$("#again_cj_js").hide();
							}
							if (v.id == 6) { 
								var angle = 180;
								$("#zj_box_jp_text").text("限时皮肤");
								$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jp_pifu3.jpg");
								$("#jp_lq_btn").show();
								$("#again_cj_js").hide();
							}
							if (v.id == 7) { 
								var angle = 45;
								$("#zj_box_jp_text").text("69元皮肤碎片");
								$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jp_pifu69.jpg");
								$("#jp_lq_btn").show();
								$("#again_cj_js").hide();
							}
							if (v.id == 8) { 
								var angle = 315;
								$("#zj_box_jp_text").text("199元皮肤碎片");
								$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jp_pifu199.jpg");
								$("#jp_lq_btn").show();
								$("#again_cj_js").hide();
							}
							rotateFunc(v.id,angle,' ');
						}
					},'JSON');
				
			}
		 } 
	   
	});
	$("#again_cj_js").on("click",function(){
		$("#zhongjiang").hide();
		$("#panbtn_a").hide();
		$.post("{:U('Active/lottery')}", { }, function(v){
			if(v.jg=="yuebuzu"){
				$("#yuebuzu").show();
			}else{
				// v.id=8;
				if (v.id == 1) {
					var angle = 270;
					$("#zj_box_jp_text").text("6金币");
					$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jinbi6.png");
					$("#jp_lq_btn").show();
					$("#again_cj_js").hide();
				}
				if (v.id == 2) { 
					var angle = 135;
					$("#zj_box_jp_text").text("66金币");
					$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jinbi66.png");
					$("#jp_lq_btn").show();
					$("#again_cj_js").hide();
				}
				if (v.id == 3) { 
					var angle = 90;
					$("#zj_box_jp_text").text("666金币");
					$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jinbi666.png");
					$("#jp_lq_btn").show();
					$("#again_cj_js").hide();
				}
				if (v.id == 4) { 
					var angle = 225; 
					$("#zj_box_jp_text").text("再来一次");
					$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jp_again.png");
					$("#jp_lq_btn").hide();
					$("#again_cj_js").show();
				}
				if (v.id == 5) { 
					var angle = 0;   
					$("#zj_box_jp_text").text("100点卷");
					$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/dianquan.png");
					$("#jp_lq_btn").show();
					$("#again_cj_js").hide();
				}
				if (v.id == 6) { 
					var angle = 180;
					$("#zj_box_jp_text").text("限时皮肤");
					$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jp_pifu3.jpg");
					$("#jp_lq_btn").show();
					$("#again_cj_js").hide();
				}
				if (v.id == 7) { 
					var angle = 45;
					$("#zj_box_jp_text").text("69元皮肤碎片");
					$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jp_pifu69.jpg");
					$("#jp_lq_btn").show();
					$("#again_cj_js").hide();
				}
				if (v.id == 8) { 
					var angle = 315;
					$("#zj_box_jp_text").text("199元皮肤碎片");
					$("#zj_box_jp_img img").attr("src","./Public/Mobile/new/images/jp_pifu199.jpg");
					$("#jp_lq_btn").show();
					$("#again_cj_js").hide();
				}
				rotateFunc(v.id,angle,' ');
			}
		},'JSON');
			
	});
	
})
</script>
<!--抽奖js end-->

<!--十连抽弹窗 start-->
<div class="tc_box_bg" id="tc_slchou" >
	<div class="tc_box" style="width:80%;left:10%;top:10px;margin-top:0;">
		<div class="tc_slchou_title">
			十连抽结果
		</div>
		<div class="tc_slchou_box">
		
			
			<div class="clear"></div>
		</div>
		<div class="img_icon_btn">
			<a href="javascript:;" class="img_icon_btn_all" onclick="hide_tc_slchou()">领 取</a>
		</div>
	</div>
</div>
<script type="text/javascript">
function hide_tc_slchou(){
	$("#tc_slchou").hide();
	// $('.lcbtn').hide();
	//$('.lcbtn').children().attr("onclick",null);
}
function show_tc_slchou(){
	$.post("{:U('Active/decLottery')}", { }, function(v){
		if(v.jg=="yuebuzu"){
			$("#yuebuzu").show();
		}else if(v.jg=="cjg"){
			
		}else{
			$(".tc_slchou_box").html(v.jieguo+"<div class='clear'></div>");
			$("#tc_slchou").show();
			$(".img_icon_btn").show();
			$(".img_icon_btn_all").show();
			$(".lcbtn").remove();
			
		}
	},'JSON');

	
}
function hide_yuebz() {
	$("#panbtn_a").show();	
	$('#yuebuzu').hide();
}
</script>
<!--十连抽弹窗 end-->


<include file="Public:footer_active"/>
