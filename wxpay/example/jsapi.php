<?php 
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
session_start(); 
if($_SESSION['user_id']<=0){
	header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=loginbyweixin&m=User");
	exit;
}
$paycg_tag=md5($_SESSION['user_id'].$_SESSION['tpnum']);
//$paycg_tag=md5($_SESSION['user_id']."0.01");
//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
//function printf_info($data)
//{
//    foreach($data as $key=>$value){
//        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
//    }
//}

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("充值");
$input->SetAttach("test");
$input->SetOut_trade_no($_SESSION['ddid']);
$input->SetTotal_fee($_SESSION['tpnum']);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("充值");
$input->SetNotify_url("http://wap.esports666.com/index.php?a=dotopay&m=index");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();



//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付-玩贝电竞</title>
	<link href="/Public/Mobile/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="/Public/Mobile/css/style.css" rel="stylesheet" type="text/css" />
	<!--弹出层-->
	<link rel="stylesheet" type="text/css" href="/Public/Mobile/css/alert.css" />
	<script type="text/javascript" src="/Public/Mobile/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript">
	</script>
</head>
<body style="text-align:center;background:#1d2b32;padding-top:100px;font-size:20px;color:#e6e6e6;">
	<input type="hidden" name="paycg_tag" id="paycg_tag" value="<?php echo $paycg_tag; ?>" />
    <br/>
    <font><b>充值金额为<span style="color:#19a2f4;font-size:30px"><?php echo ($_SESSION['tpnum']/100); ?></span>元</b></font><br/><br/>
	<div align="center">
		<button style="width:210px; height:50px; border-radius: 15px;background-color:#19a2f4; border:0px #19a2f4 solid; cursor: pointer;  color:white;  font-size:20px;" type="button" onClick="callpay()" >确认支付</button>
	</div>
	<div align="center">
		<div style="width:90%;font-size:1.5rem;padding-top:3rem;">
		为了金币及时到账，请在支付成功后，点击右上角"完成"，并等待页面加载完毕。如有金币未到账情况，请在QQ群联系客服MM。
		</div>
	</div>
	<div class="box" style=" display:none;"></div>
	<div class="layer" style=" display:none;">
		<div class="con-layer">
			<P style=" font-size:18px; padding:20px 0; margin:0">充值成功</P>
		</div>
		<div class="bnt-con"><a class="hint-in3" href="javascript:void(0);">确  定</a></div>
	</div>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
				if(res.err_msg=="get_brand_wcpay_request:ok"){
					//alert("充值成功");
					var paycg_tag=$("#paycg_tag").val();
//					var payurl="http://wap.esports666.com/index.php?a=dotopay&m=index&paytag="+paycg_tag+"";
//					window.location.href=payurl;
					$(".layer").css({"display":"block"});
					$(".box").css({"display":"block"});
					$(".hint-in3").attr("href","http://wap.esports666.com/index.php?a=dotopay&m=index&paytag="+paycg_tag+"&ddid=<?php echo $_SESSION['ddid']; ?>");
					window.location.href="http://wap.esports666.com/index.php?a=dotopay&m=index&paytag="+paycg_tag+"&ddid=<?php echo $_SESSION['ddid']; ?>";
				}else{
					alert("充值失败");
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
</body>
</html>