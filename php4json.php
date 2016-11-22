<?php header("Content-type: text/html; charset=gb2312"); ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <!-- <meta http-equiv="refresh" content="5">-->
</head>
<body>
<?php
//echo '<br>演示内容：采用PHP进行JSON格式采集并分析数据。';
//echo '<br>默认演示为《开彩网》重庆时时彩免费开奖接口';
//echo '<br>付费接口的采集格式与免费接口完全一致';
//echo '<br>如需使用付费接口，请修改采集为对应地址';
//echo '<br>如有其它疑问请访问<a href="http://www.opencai.net/"><b style="color:#f00">www.opencai.net</b></a>';
//echo '<br>';
//$src = 'http://f.apiplus.cn/cqssc-2.json';
//echo '<br>采集地址：'.$src.'<br>';
//$src .= '?_='.time();
//$json = file_get_contents(urldecode($src));
//echo $json;
//$json = json_decode($json);
//echo "<br>".date('Y-m-d H:i:s')."共采集到{$json->rows}行开奖数据：<br>";
//
//for ($i = 0; $i < count($json->data); $i++) {
//	$p = $json ->data[$i]->expect;
//	echo '<br>开奖期号：'.substr($p,0,8).'-'.substr($p,-3,3);
//	echo '<br>开奖号码：'.$json ->data[$i]->opencode;
//	echo '<br>开奖时间：'.$json ->data[$i]->opentime;
//	echo '<br>';
//}

//echo '<br>演示内容：采用PHP进行XML格式采集并分析数据。';
//echo '<br>默认演示为《开彩网》重庆时时彩免费开奖接口';
//echo '<br>付费接口的采集格式与免费接口完全一致';
//echo '<br>如需使用付费接口，请修改采集为对应地址';
//echo '<br>如有其它疑问请访问<a href="http://www.opencai.net/"><b style="color:#f00">www.opencai.net</b></a>';
//echo '<br>';
//$src = 'http://f.apiplus.cn/cqssc.xml';
//echo '<br>采集地址：'.$src.'<br>';
//$src .= '?_='.time();
//$xml = file_get_contents(urldecode($src));
//$xml = simplexml_load_string($xml);
//
//echo "<br>".date('Y-m-d H:i:s')."共采集到{$xml->attributes()->rows}行开奖数据：<br>";
//for ($i = 0; $i < count($xml->row); $i++) {
//	$p = $xml ->row[$i]->attributes()->expect;
//	echo '<br>开奖期号：'.substr($p,0,8).'-'.substr($p,-3,3);
//	echo '<br>开奖号码：'.$xml ->row[$i]->attributes()->opencode;
//	echo '<br>开奖时间：'.$xml ->row[$i]->attributes()->opentime;
//	echo '<br>';
//}
?>


<?php 
//echo "10:00-22:00（72期）10分钟一期，22:00-02:00（48期）5分钟一期";
//$src = 'http://www.cp66607.com/api/cqssc?act=lishikaijianghaoma&limit=100';
//$json = file_get_contents(urldecode($src));
//echo $json;
//$json = json_decode($json);
//$counts=count($json);
//foreach($json as $k=>$v){
//	$kaijiang[$k]['num']=$v->cn1.$v->cn2.$v->cn3.$v->cn4.$v->cn5;
//	$kaijiang[$k]['dayqi']=$v->cissue;
//	$kaijiang[$k]['day']=substr($v->cissue,0,8);
//	$kaijiang[$k]['qi']=substr($v->cissue,-3,3);
//}
//var_dump( $kaijiang);
//var_dump( $counts);
//var_dump( $json);exit;
//$handle = fopen ("http://caipiao.163.com/award/cqssc/", "rb"); 
//$url='http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=1&span=3&r=0.6347607208881527#roll_132'; 
//$html = file_get_contents($url); 
//$info=preg_match_all('/<table[^>]+ class="chart-table">(.*)<\/table>/isU',$html,$m);
//var_dump($m);
//echo $html; 
require('./phpQuery/phpQuery.php');

$aa=10;
if($aa>120){
	$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=1&span=3');  //获取3天内
	$companies = pq('.chart-table')->find('.zdww5')->find("tr");  
	foreach($companies as $company)  
	{  
	   echo pq($company)->find('td.tdbg_1')->text()."<br>";  
	} 
}else if($aa<=120 && $aa>100){
	$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=1&span=1');  //获取今天
	$companies = pq('.chart-table')->find('.zdww5')->find("tr");  
	$i=0;
	foreach($companies as $k=>$company)  
	{  
		$dayqi=pq($company)->find('td.tdbg_1:eq(0)')->text();
		if(!empty($dayqi)){
			$dayqi_arr=explode('-',$dayqi);
			$kj_arr[$i]['day']=$dayqi_arr[0];
			$kj_arr[$i]['qi']=$dayqi_arr[1];
		}
		$num=pq($company)->find('td.tdbg_1:eq(1)')->text();
		if(!empty($num)){
			$kj_arr[$i]['num']=$num;
			$i++;
		}
		//$arr2[$k]=pq($company)->find('td.tdbg_1:eq(1)')->text();
	  // echo pq($company)->find('td.tdbg_1')->text()."<br>";  
	} 
	var_dump($kj_arr);
}else if($aa<=100 && $aa>50){
	$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=0&span=100'); //获取100期 
	$companies = pq('.chart-table')->find('.zdww5')->find("tr");  
	foreach($companies as $company)  
	{  
	   echo pq($company)->find('td.tdbg_1')->text()."<br>";  
	} 
}else if($aa<=50 && $aa>30){
	$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=0&span=50');  //获取50期
	$companies = pq('.chart-table')->find('.zdww5')->find("tr");  
	foreach($companies as $company)  
	{  
	   echo pq($company)->find('td.tdbg_1')->text()."<br>";  
	} 
}else{
	//$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq');  //获取30期
	$doc = phpQuery::newDocumentFile('http://chart.cp.360.cn/zst/ssccq?lotId=255401&chartType=dww5&spanType=0&span=20');  //获取50期
	$companies = pq('.chart-table')->find('.zdww5')->find("tr");  
	$i=0;
	foreach($companies as $k=>$company)  
	{  
		$dayqi=pq($company)->find('td.tdbg_1:eq(0)')->text();
		if(!empty($dayqi)){
			$dayqi_arr=explode('-',$dayqi);
			$kj_arr[$i]['day']='20'.$dayqi_arr[0];
			$kj_arr[$i]['qi']=$dayqi_arr[1];
		}
		$num=pq($company)->find('td.tdbg_1:eq(1)')->text();
		if(!empty($num)){
			$kj_arr[$i]['num']=$num;
			$i++;
		}
		//$arr2[$k]=pq($company)->find('td.tdbg_1:eq(1)')->text();
	  // echo pq($company)->find('td.tdbg_1')->text()."<br>";  
	} 
	krsort($kj_arr);
	var_dump($kj_arr);
}
?>

</body>
</html>