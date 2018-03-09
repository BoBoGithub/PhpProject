<?php
/**
 * 追答添加 处理队列
 * 处理队列表中的 和追答 相关的业务
 *
 * @param addTIme 2014-03-04
 * @param author  ChengBo
 */
class Submit {
	public static function execute($arr) {
		//由于 添加队列时 已验证过数据完整性 这里直用dao
		$ChaseDao 	    = ChaseDao::getInstance();

		try{
			$flagState  = $ChaseDao->insertChase($arr);
			if(!$flagState){	
				$errmsg = sprintf('msg[submit execute fail] time[%s] params[%s]', date('Y-m-d H:i:s',time()), json_encode($arr));
				CLog::warning($errmsg);
				return false;
			}
	
			//查找 刚插入的 按quid分表的追问id
			$lastChaseId = $ChaseDao->getLastChaseIdByQuid($arr['qid'], $arr['aid'], $arr['quid']);
			
			
			//初始化问答 异步处理类 由于把 赏金模块迁移到问答里面所以 这个没用了
			//$iaskSapi = new IaskSapi(CommonConst::SAPI_HOST_POSSPORT);
			//$ret 		= $iaskSapi->moneyAdd($param);
			
			//传递参数
			$param['qid'] 	 = $arr['qid'];
			$param['aid']	 = $arr['aid'];
			$param['cid']    = $lastChaseId;
			$param['uid'] 	 = $arr['auid'];
			$param['date']	 = date('Ymd',$arr['time']);
			$param['operate']= IaskConfig::CHASE_ANSWER_ADD;

			//添加追问赏金
			$moneyService 	 = MoneyService::getInstance();
			$ret  			 = $moneyService->insertDetailMoney($param);
			
			if($ret) {
				//发送Message
				$message['touid']   = $arr['quid'];
				$message['type']    = CommonConst::MESSAGE_NEW;
				$message['subject'] = '追问有新回答';
				$message['content'] = '您的追问有了新回答，请注意查收.';
				$widgetSapi 		= new WidgetSapi(CommonConst::SAPI_HOST_WIDGET);
				$retm 				= $widgetSapi->sendMessage($message['touid'], $message['type'], $message['subject'], $message['content']);
				if(!isset($retm['errno']) || $retm['errno'] != CommonConst::SUCCESS) {
					//发送失败
					$errmsg = sprintf('Time:%d chase_add sendMessage fail param:%s',date('Y-m-d',time()),json_encode($message));
					CLog::warning($errmsg);
				}
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
