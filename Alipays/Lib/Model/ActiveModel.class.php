<?php
/**
 * @package 
 * @since 1.0
 * @todo 活动需要用到的函数
 */
 
class ActiveModel extends Model{
	
	//常亮定义
	protected $userId;

	protected $time;

	protected $prizeId;

	protected $userInfo;

	protected $packageInfo;

	//保存抽奖记录
	public function insertIntoRecord($userId,$prizeId)
	{
		try{
			M('active_record')->add(
				array(
					'userId'=>$userId,
					'prizeId'=>$prizeId,
					'lotteryTime'=>time()
					)
				);
			return 1;
		}catch(Exception $e){
			throw $e;
		}
	}

	//更新用户信息
	public function updateUserActiveInfo($userId,$userInfo)
	{
		$re = M('active_user_info')->where(" userId = ".$userId."")->select();
		if (@is_null($re)) {
			M('active_user_info')->add($userInfo);
		}else{
			M('active_user_info')->where(" userId = ".$userId."")->save($userInfo);
		}
	}

	//添加到包裹数据
	public function addPackInfo($userId,$prizeId)
	{
		$count = intval($this->getMaxPackageNum($userId));
		$count++;
		$packageInfo = array(
				'userId'=>$userId,
				'prizeId'=>$prizeId,
				'position'=>$count,
				'status'=>1
				);
		$re = M('active_user_package')->add($packageInfo);
		return $re;

	}

	//获取物品数量
	public function getCountById($userId,$prizeId)
	{
		if(!$count = M('active_user_package')->where("prizeId = ".$prizeId." and userId = ".$userId."")->select()) {
			return 0;
		}else{
			return $count;
		}
	}

	//获取抽奖记录
	public function getCount($userId,$prizeId)
	{
		$count = M('active_record')->where("prizeId = ".$prizeId." and userId = ".$userId."")->count();
		return $count;
	}

	//获取碎片数量（从user_info表)
	// @return array[smallPiecesTimes,bigPiecesTimes]
	public function getPiecesNumFromUserInfo($userId)
	{
		$re = M('active_user_info')->field('smallPiecesTimes,bigPiecesTimes')->where("userId = ".$userId."")->find();
		if (@is_null($re)) {
			$re['smallPiecesTimes'] = 0;
			$re['bigPiecesTimes'] = 0;
		}
		return $re;
	}

	// 获取碎片数量（从record表）
	// @param 
	public function getPiecesNumFromRecord($userId)
	{
		$count1 = M('active_record')->where('userId = '.$userId.' and prizeId = 7 ')->count();
		$count2 = M('active_record')->where('userId = '.$userId.' and prizeId = 8 ')->count();
		$re['smallPiecesTimes'] = $count1;
		$re['bigPiecesTimes'] = $count2;
		return $re;
	}

	// 存入碎片数量
	// @input array[smallPiecesTimes.bigPiecesTimes]
	public function updatePiecesNum($userId,$piecesNumArr)
	{
		$re =  M('active_user_info')->where("userId = ".$userId."")->save($piecesNumArr);
		return $re;
	}

	//获取最大包裹排序
	public function getMaxPackageNum($userId)
	{		
		if(!$maxNum = M('active_user_package')->query("SELECT MAX(position) FROM djzj_active_user_package where userId = ".$userId."")){
			return 0;
		}else{
			return $maxNum[0]['MAX(position)'];
		}

	}


	//修改金币
	// @param changeNum为正则加 负则减
	public function coinCalc($userId,$changeNum)
	{
		$result = M('users')->where("userId =".$userId."")->field('golds')->find();
		$coinNum = floatval($result['golds']);
		$coinNum += $changeNum;
		$userInfoArr = array('golds'=>$coinNum);
		M('users')->where('userId = '.$userId)->save($userInfoArr);
		// var_dump($userInfoArr);
		return $coinNum;
	}

	//记录金币流水
	public function addGoldsRecord($userId,$detailId,$changeNum,$coinNum)
	{
		if ($detailId == 11) {
			$detail = '转盘活动单次抽奖';
		}

		if ($detailId == 12) {
			$detail = '转盘抽奖活动十连抽';
		}
		$coinLogArr = array(
			'fromid'=>$detailId,
			'amount'=>$coinNum,
			'changeAmount'=>$changeNum,
			'detail'=>$detail,
			'ctime'=>time(),
			'userId'=>$userId
			);
		$re = M('user_golds_record')->add($coinLogArr);
		return $re;
	}

	//金币操作总函数
	public function doCoin($userId,$changeNum,$detailId)
	{
		$coinNum = $this->coinCalc($userId,$changeNum);
		$re = $this->addGoldsRecord($userId,$detailId,$changeNum,$coinNum);
		return $re;
	}

	// 判断余额
	// @param output 0-余额不足 1-余额足够
	public function yue($userId,$coinNum)
	{
		$result = M('users')->where("userId = ".$userId."")->field('golds')->find();
		$yue = floor($result['golds']);
		// return $yue;
		if ($yue < $coinNum) {
			return 0;
		}else{
			return 1;
		}

	}

	// 获取用户转盘活动的金币流水

//	public function getUserFinalGold($userId)
//	{
//		$sum1 = 0;
//		$re = M('user_golds_record')->field('changeAmount')->where('(fromid = 11 or fromid = 12) and changeAmount > 0 and userId = '.$userId)->select();
//		foreach ($re as $key => $value) {
//			$sum1+=$value['changeAmount'];
//		}
//		$sum2 = 0;
//		$re = M('user_golds_record')->field('changeAmount')->where('(fromid = 11 or fromid = 12) and changeAmount < 0 and userId = '.$userId)->select();
//		foreach ($re as $key => $value) {
//			$sum2+=$value['changeAmount'];
//		}
//		return $sum1+$sum2;
//	}


	public function showinfo($userInfo)
	{			
		var_dump($userInfo);
	}
	
}