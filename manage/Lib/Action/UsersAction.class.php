<?php
 
class UsersAction extends AllAction{

	/**
	 * ------------------------------
	 * 权限判断
	 * ------------------------------
	 */
	function pan_quan_ajax($data){  //ajax判断
		$gid=$_SESSION['gid'];
		$group=M("admin_group")->where("id=".$gid."")->find();
		$quanxian=explode(',',$group['quanxian']);
		$pan=in_array($data,$quanxian);
        if($pan){
			return true;
		}else{
			return false;
		}
	}
	
	// 赠送用户碎片数
	function users_edit_pieces(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$userId=t(h($_POST['userId']));
		if(empty($userId)){

		}else{
			$userInfo = M('active_user_info');
			$piecesNum = t(h($_POST['piecesNum']));
			$re = $userInfo->where('userId = '.$userId)->save(array('givePieces'=>$piecesNum));
			if ($re) {
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 

	 //用户列表
	function users(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$uname=t(h($_POST['keywords']));
		if(empty($uname)){
			import("@.ORG.Page");	
			$count	= M('users')->order("userId desc")->count();
			$Page	= New Page($count,30);
			$show	= $Page->show();
			$list=M('users')->order("userId desc")->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->assign('page', $show);
		}else{
			$list=M('users')->where("name like '%".$uname."%'")->order("userId desc")->select();
		}
		foreach($list as $k=>$v){
			if($v['invite_by_channelId']>0){
				$qudao=M("user_invite_channel")->where("user_invite_channelId=".$v['invite_by_channelId']."")->find();
				$list[$k]['qudao']=$qudao['name'];
			}
			if($v['invite_by_userId']>0){
				$qudao=M("users")->where("userId=".$v['invite_by_userId']."")->find();
				$list[$k]['qudao']=$qudao['name'];
			}
			if($v['invite_by_userId']<=0 && $v['invite_by_channelId']<=0){
				$list[$k]['qudao']="未知";
			}
			
			$list[$k]['zhaomu']=M("users")->where("invite_by_userId=".$v['userId']."")->order("userId desc")->select();
			$list[$k]['liushui']=M("user_golds_record")->where("userId=".$v['userId']."")->order("ctime desc,user_golds_recordId asc")->select();
			
		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	function users_edit(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$userId=t(h($_POST['userId']));
		if(empty($userId)){

		}else{
			$add['userId']=$userId;
			$jiuusers=M("users")->where("userId=".$userId."")->find();
			
			$jinbishu=t(h($_POST['jinbishu']));
			$jiujinbi=$jiuusers['golds'];
			$newjinbi=$jiujinbi+$jinbishu;
			$add['golds']=$newjinbi;

			$res=M("users")->save($add);
			if($res){
				$jilu['userId']=$userId;
				$jilu['ctime']=time();
				$jilu['detail']=t(h($_POST['beizhu']));
				$jilu['changeAmount']=$jinbishu;
				$jilu['amount']=$newjinbi;
				$jilu['adminid']=$_SESSION['manage_id'];
				$jilu['fromid']=7;
				M("user_golds_record")->add($jilu);
				
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	} 
	 //用户列表
	function allusers(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$uname=t(h($_POST['keywords']));
		if(empty($uname)){
			$list=M('users')->order("userId desc")->select();
		}else{
			$list=M('users')->where("name like '%".$uname."%'")->order("userId desc")->select();
		}
		foreach($list as $k=>$v){
			if($v['invite_by_channelId']>0){
				$qudao=M("user_invite_channel")->where("user_invite_channelId=".$v['invite_by_channelId']."")->find();
				$list[$k]['qudao']=$qudao['name'];
			}
			if($v['invite_by_userId']>0){
				$qudao=M("users")->where("userId=".$v['invite_by_userId']."")->find();
				$list[$k]['qudao']=$qudao['name'];
			}
			if($v['invite_by_userId']<=0 && $v['invite_by_channelId']<=0){
				$list[$k]['qudao']="未知";
			}
			
			$list[$k]['zhaomu']=M("users")->where("invite_by_userId=".$v['userId']."")->order("userId desc")->select();
			$list[$k]['liushui']=M("user_golds_record")->where("userId=".$v['userId']."")->order("ctime desc,user_golds_recordId asc")->select();
			
		}
		$this->assign('list', $list);
		
		$this->display();
	} 
	function users_changetime(){
		if(empty($_SESSION['manage_id'])){
			$this->assign('jumpUrl', U('User/login'));
			$this->error('请先登录');
		}
		$list=M('users')->where("ctime<=0")->order("userId desc")->select();
		foreach($list as $k=>$v){
			$ctime=strtotime($v['first_login_time']);
			$changetime['ctime']=$ctime;
			$lasttime=strtotime($v['last_login_time']);
			$changetime['lasttime']=$lasttime;
			$changetime['userId']=$v['userId'];
			$changetime['openId']=$v['openId'];
			if($ctime>0){
				$save=M("users")->save($changetime);
			}
			var_dump($changetime['ctime']);
		}
		echo 1;
		
		
	} 
	function exportExcel()
	{
		import("@.ORG.PHPExcel");		 
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
					->setLastModifiedBy("Maarten Balliauw")
					->setTitle("Office 2007 XLSX Test Document")
					->setSubject("Office 2007 XLSX Test Document")
					->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
					->setKeywords("office 2007 openxml php")
					->setCategory("Test result file");
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', '用户ID')
					->setCellValue('B1', '昵称')
					->setCellValue('C1', '金币余额')
					->setCellValue('D1', '加入时间')
					->setCellValue('E1', '最后登录时间')
					->setCellValue('F1', '来源渠道')
					->setCellValue('G1', '招募战友')
					->setCellValue('H1', '主播猜次数')
					->setCellValue('I1', '英雄猜次数')
					->setCellValue('J1', '大转盘次数')
					->setCellValue('K1', '用户头像');
		$re = M('users')->order("userId desc")->select();
		foreach ($re as $key => $value) {
			$k = $key + 2;

			$inviteFrom = '未知';
			$inviteInfo = '未知'; 
			if(intval($value['invite_by_channelId'])>0){
				$qudao = M("user_invite_channel")->where("user_invite_channelId = ".$value['invite_by_channelId']."")->find();
				$inviteFrom = $qudao['name'];
			}
			if(intval($value['invite_by_userId'])>0){
				$qudao = M("users")->where("userId = ".$value['invite_by_userId']."")->find();
				$inviteInfo = $qudao['name'];
			}

			$zhubocaiNum = M('user_golds_record')->where('fromid = 2 and userId ='.$value['userId'])->count();

			$heroNum = M('user_golds_record')->where('fromid = 3 and userId = '.$value['userId'])->count();

			$zhuanpanNum = M('user_golds_record')->where('fromid = 11 and userId = '.$value['userId'])->count()+M('user_golds_record')->where('fromid = 12 and userId = '.$value['userId'])->count();


			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$k, $value['userId'])
						->setCellValue('B'.$k, str_replace("=","等号",(strval($value['name']))))
						->setCellValue('C'.$k, floor($value['golds']))
						->setCellValue('D'.$k, date("Y-m-d",$value['ctime']))
						->setCellValue('E'.$k, date("Y-m-d",$value['lastqdtime']))
						->setCellValue('F'.$k, strval($inviteFrom))
						->setCellValue('G'.$k, str_replace("=","等号",strval($inviteInfo)))
						->setCellValue('H'.$k, $zhubocaiNum)
						->setCellValue('I'.$k, $heroNum)
						->setCellValue('J'.$k, $zhuanpanNum)
						->setCellValue('K'.$k, $value['header']);
			}

			$zhubocaiNum = 0;$heroNum = 0;$zhuanpanNum = 0;
			
		//设置单元格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);


      
		//合并单元格
        // $objPHPExcel->getActiveSheet()->mergeCells('A2:A'.$kaa);
        // $objPHPExcel->getActiveSheet()->mergeCells('B2:B'.$kaa);
		//设置居中
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// //所有垂直居中
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// //设置自动换行
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setWrapText(true);
		// // Rename worksheet
		// $objPHPExcel->getActiveSheet()->setTitle('Simple');
		// // Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		//$user = iconv('utf-8','gb2312',$user);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.'所有用户列表'.date('YmdHis',time()).'.xls');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
		exit;

			
	}
	function exportExcel2()
	{
		import("@.ORG.PHPExcel");		 
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
					->setLastModifiedBy("Maarten Balliauw")
					->setTitle("Office 2007 XLSX Test Document")
					->setSubject("Office 2007 XLSX Test Document")
					->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
					->setKeywords("office 2007 openxml php")
					->setCategory("Test result file");
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', '用户ID')
					->setCellValue('B1', '昵称')
					->setCellValue('C1', '金币余额')
					->setCellValue('D1', '加入时间')
					->setCellValue('E1', '最后登录时间')
					->setCellValue('F1', '来源渠道')
					->setCellValue('G1', '招募战友')
					->setCellValue('H1', '主播猜次数')
					->setCellValue('I1', '英雄猜次数')
					->setCellValue('J1', '大转盘次数')
					->setCellValue('K1', '充值数额(金币)')
					->setCellValue('L1', '兑换皮肤')
					->setCellValue('M1', '兑换点券');

		$re = M('users')->order("userId desc")->where('invite_by_channelId = 18')->select();
		foreach ($re as $key => $value) {
			$k = $key + 2;

			$inviteFrom = '未知';
			$inviteInfo = '未知'; 
			if(intval($value['invite_by_channelId'])>0){
				$qudao = M("user_invite_channel")->where("user_invite_channelId = ".$value['invite_by_channelId']."")->find();
				$inviteFrom = $qudao['name'];
			}
			if(intval($value['invite_by_userId'])>0){
				$qudao = M("users")->where("userId = ".$value['invite_by_userId']."")->find();
				$inviteInfo = $qudao['name'];
			}
			//主播猜次数
			$zhubocaiNum = M('user_golds_record')->where('fromid = 2 and userId ='.$value['userId'])->count();
			//英雄猜次数
			$heroNum = M('user_golds_record')->where('fromid = 3 and userId = '.$value['userId'])->count();
			//转盘次数
			$zhuanpanNum = M('user_golds_record')->where('fromid = 11 and userId = '.$value['userId'])->count()+M('user_golds_record')->where('fromid = 12 and userId = '.$value['userId'])->count();
			//充值数额
			$payNum = M('user_golds_record')->where('fromid = 4 and userId = '.$value['userId'])->select();
			$paySum = 0;
			foreach ($payNum as $paykey => $payvalue) {
				$paySum += floor($payvalue['changeAmount']);
			}
			//兑换皮肤
			$skinNum = M('active_record')->where('prizeId = 6 and userId = '.$value['userId'])->count();

			//兑换点卷 
			$ticNum = M('active_record')->where('prizeId = 5 and userId = '.$value['userId'])->count();

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$k, $value['userId'])
						->setCellValue('B'.$k, str_replace("=","等号",(strval($value['name']))))
						->setCellValue('C'.$k, floor($value['golds']))
						->setCellValue('D'.$k, date("Y-m-d H:i:s",$value['ctime']))
						->setCellValue('E'.$k, date("Y-m-d H:i:s",$value['lastqdtime']))
						->setCellValue('F'.$k, strval($inviteFrom))
						->setCellValue('G'.$k, str_replace("=","等号",strval($inviteInfo)))
						->setCellValue('H'.$k, $zhubocaiNum)
						->setCellValue('I'.$k, $heroNum)
						->setCellValue('J'.$k, $zhuanpanNum)
						->setCellValue('K'.$k, $paySum)
						->setCellValue('L'.$k, $skinNum)
						->setCellValue('M'.$k, $ticNum);
			}

			$zhubocaiNum = 0;$heroNum = 0;$zhuanpanNum = 0;
			
		//设置单元格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);


      
		//合并单元格
        // $objPHPExcel->getActiveSheet()->mergeCells('A2:A'.$kaa);
        // $objPHPExcel->getActiveSheet()->mergeCells('B2:B'.$kaa);
		//设置居中
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// //所有垂直居中
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// //设置自动换行
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setWrapText(true);
		// // Rename worksheet
		// $objPHPExcel->getActiveSheet()->setTitle('Simple');
		// // Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		//$user = iconv('utf-8','gb2312',$user);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.'所有用户列表'.date('YmdHis',time()).'.xls');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
		exit;

			
	}
	  
	function exportExcel3()
	{

		import("@.ORG.PHPExcel");		 
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
					->setLastModifiedBy("Maarten Balliauw")
					->setTitle("Office 2007 XLSX Test Document")
					->setSubject("Office 2007 XLSX Test Document")
					->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
					->setKeywords("office 2007 openxml php")
					->setCategory("Test result file");
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', '用户ID')
					->setCellValue('B1', '昵称')
					->setCellValue('C1', '充值数额')
					->setCellValue('D1', '兑换皮肤')
					->setCellValue('E1', '兑换点卷')
					->setCellValue('F1', '兑换RMB');

		$re = M('users')->field('userId,name')->select();
	
		foreach ($re as $key => $value) {

			$k = $key + 2;

			//充值记录
			$payNum = M('user_golds_record')->field('changeAmount')->where('fromid = 4 and userId = '.$value['userId'])->select();

			$paySum = 0;
			foreach ($payNum as $paykey => $payvalue) {
				$paySum += floor($payvalue['changeAmount']);
			}
			//兑换皮肤
			$skinNum = M('active_record')->where('prizeId = 6 and userId = '.$value['userId'])->count();

			//兑换点卷 
			$ticNum = M('active_record')->where('prizeId = 5 and userId = '.$value['userId'])->count();

			//兑换RMB
			$txSum = 0;
			$txNum = M('user_golds_record')->field('changeAmount')->where('txstate = 2 and userId = '.$value['userId'])->select();

			foreach ($txNum as $txkey => $txvalue) {
				$txSum += intval($txvalue['changeAmount']);
			}

		
			

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$k, $value['userId'])
						->setCellValue('B'.$k, str_replace('=','等号',$value['name']))
						->setCellValue('C'.$k, $paySum)
						->setCellValue('D'.$k, $skinNum)
						->setCellValue('E'.$k, $ticNum)
						->setCellValue('F'.$k, $txSum);
						
			}

			
			
		//设置单元格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        

      
		// 合并单元格
  //       $objPHPExcel->getActiveSheet()->mergeCells('A2:A'.$kaa);
  //       $objPHPExcel->getActiveSheet()->mergeCells('B2:B'.$kaa);
		// // 设置居中
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// //所有垂直居中
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// //设置自动换行
  //       $objPHPExcel->getActiveSheet()->getStyle('A1:F'.$k)->getAlignment()->setWrapText(true);
		// // Rename worksheet
		// $objPHPExcel->getActiveSheet()->setTitle('Simple');
		// // Set active sheet index to the first sheet, so Excel opens this as the first sheet
		// $objPHPExcel->setActiveSheetIndex(0);
		// $user = iconv('utf-8','gb2312',$user);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.'所有用户列表'.date('YmdHis',time()).'.xls');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
		exit;

			
	}
	  

	  function getTxMessage()
	  {
	  	$re = M('user_golds_record')->where('txstate = 1')->select();
	  	if ($re) {
	  		echo(1);
	  	}else{
	  		echo(2);
	  	}
	  }
	 
	
} 
