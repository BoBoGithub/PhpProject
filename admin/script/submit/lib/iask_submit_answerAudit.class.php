<?php
/**
 * @author wangyunjie
 * @date 2014/2/27
 * @brief   问题添加
 * 
 **/
class Submit {
	public static function execute($arr) {

		$answerService = AnswerService::getInstance();


		$ret_quid = $answerService->getDetailByQuid($arr['aid'],$arr['quid']);
		$ret_auid = $answerService->getDetailByAuid($arr['aid'],$arr['auid']);
		if((!$ret_quid || $ret_quid['status'] >=0) && (!$ret_auid || $ret_auid['status'] >=0)){
			return true;
		}
		$updateDate = array(
			'id' => $arr['aid'],
			'quid' => $arr['quid'],
			'auid' => $arr['auid'],
			'status' => 1
		);

		// 按照提问人UID 更新数据
		
		$ret = $answerService->editDetailByQuid($arr['aid'],$arr['quid'],$updateDate);

		if(!$ret){
			return false;
		}

		// 按照回答人UID更新数据

		$ret = $answerService->editDetailByAuid($arr['aid'],$arr['auid'],$updateDate);

		if(!$ret){
			return false;
		}

		// 发送站内信
		$tryNum = 0;
		while (1) {
			$widgetSapi = new WidgetSapi(CommonConst::SAPI_HOST_WIDGET);
			$touid = $arr['quid'];		
			$module = CommonConst::MODULE_IASK_ID;
			//debug 和widget确定参数配置
			$type = 3;
			$subject = "您的问题有一条新回答 ".$arr['title'];
			$content = "您的问题'".$arr['content'].' 有新回答';
			$ret = $widgetSapi->sendMessage($touid,$module,$type,$subject,$content);

			if($ret['errno'] === 0 || $tryNum >=20){			
				if($tryNum >= 20){
					CLog::fatal('message send err param[touid:'.$touid.' module:'.CommonConst::MODULE_IASK_ID.' type:'.$type.' subject:'.$subject.']');
				}
				break;
			}else{
				$tryNum++;
				CLog::warning('send message is error param['.json_encode($ret).']');
			}
		}

		//更新用户回答表里面回答数字段
		while (1) {
			$userService = UserService::getInstance();
			$auserinfo = $userService -> getUserInfoByUid($arr['auid']);
			//如果取不到用户的基本信息，while循环重复取
			if(empty($auserinfo)){
				CLog::warning("msg['dont get userinfo'] param[".$arr['auid']."]");
				break;
			}

			$passport_sapi = new PassportSapi(CommonConst::SAPI_HOST_POSSPORT);
			$where = 'answers='.$auserinfo['answers'];
			$uid = $arr['auid'];
			$content = array(
				'answers' => $auserinfo['answers']+1
			);
			$ret = $passport_sapi->updateuserinfo($uid,json_encode($content),$where);
			if($ret['errno'] === 0){
				break;
			}else{
				CLog::warning(json_encode($ret));
			}
		}
		//更新绩效
		$questionSercive = QuestionService::getInstance();
		$questionInfo = $questionSercive -> getQuestionBasicInfo($arr['qid']);
		while (1) {
			$data = array();
			$data['qid'] =  $arr['qid'];
			$data['aid'] = $arr['id'];
			$data['uid'] = $arr['auid'];
			$data['date'] = empty($arr['time'])?time():$arr['time'];
			$data['date'] = date('Ymd',$data['date']);
			//如果是首条回答，记录收条回答记录，如果非收条用普通回答记录
	
			$data['operate'] = IaskConfig::QUESTION_ANSWER_RECOVERED;

			$moneyService = MoneyService::getInstance();
			$ret = $moneyService -> insertDetailMoney($data);
			if($ret === true){
				break;
			}else{
				CLog::warning('moneyadd error [param '.json_encode($arr).']');
			}
		}
		$firstAnswer = $answerService->questionAnswerList($arr['qid'],$arr['quid']);
		//如果是首条加日志
		if(isset($firstAnswer[0]) && $firstAnswer[0]['auid'] == $arr['auid']){
			while (1) {
				$data = array();
				$data['qid'] =  $arr['qid'];
				$data['aid'] = $arr['id'];
				$data['uid'] = $arr['auid'];
				$data['date'] = empty($arr['time'])?time():$arr['time'];
				$data['date'] = date('Ymd',$data['date']);
				//如果是首条回答，记录收条回答记录，如果非收条用普通回答记录
				$data['operate'] = IaskConfig::QUESTION_FIRST_ANSWER_RECOVERED;
				$moneyService = MoneyService::getInstance();
				$ret = $moneyService -> insertDetailMoney($data);
				if($ret === true){
					break;
				}else{
					CLog::warning('moneyadd error [param '.json_encode($arr).']');
				}
			}
		}	
		return true;
	}
}
?>
