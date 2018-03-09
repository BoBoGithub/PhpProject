<?php
/**
 * 追答修改 处理队列
 * 处理队列表中的 和追答修改 相关的业务
 *
 * @param addTime 2014-03-04
 * @param author  ChengBo
 */
class Submit {
	public static function execute($arr) {
		//由于 添加队列时 已验证过数据完整性 这里直用dao
		$ChaseDao 	   = ChaseDao::getInstance();
		try{
			$flagState = $ChaseDao->updateChase($arr);
			if(!$flagState){	
				$errmsg = sprintf('msg[submit execute fail] time[%s] params[%s]', date('Y-m-d H:i:s',time()), json_encode($arr));
				CLog::warning($errmsg);
				return false;
			}
			
			//更新赏金 异步处理类 由于把 赏金模块迁移到问答里面所以 这个没用了
			//$iaskSapi = new IaskSapi(CommonConst::SAPI_HOST_POSSPORT);
			//$ret = $iaskSapi->moneyAdd($param);
			
			//传递参数
			$param['qid'] 	 = $arr['qid'];
			$param['aid']	 = $arr['aid'];
			$param['cid']    = $arr['ids'];
			$param['uid'] 	 = $arr['auid'];
			$param['date']	 = date('Ymd',$arr['time']);
			// 8为删除状态 13为追问恢复状态
			$param['operate']= $arr['update']['status'] == IaskConfig::CHASE_STATUS_DEL ? IaskConfig::CHASE_ANSWER_DELETE : IaskConfig::CHASE_ANSWER_RECOVERED;

			//扣除追问删除赏金
			$moneyService 	 = MoneyService::getInstance();
			$ret  			 = $moneyService->insertDetailMoney($param);
			
			if($ret) {
				//发送站内信
				
				//发送短信通知
				
			}else{
				$errmsg = sprintf('Time:%d chase_add insertDetailMoneyFail param:%s',date('Y-m-d',time()),json_encode($param));
				CLog::warning($errmsg);
			}
		}catch(Exception $ex) {
			if(Utils::getErrorCode($ex) != CommonConst::COMMON_DB_DUPLICATE_ERROR) {
				return false;
			}
		}
		return true;
	}
}
?>
