<?php
/**
 * @author wangyunjie
 * @date 2014/2/27
 * @brief   问题添加
 * 
 **/
class Submit {
	public static function execute($arr) {
		//  判断回答上的问题是否已经被删除
		$answerService = AnswerService::getInstance();
		//按照提问人UID分表回答表插入
		$tryNum = 0;
		try{
			$ret = $answerService->insertByQuid($arr['id'],$arr['qid'],$arr['auid'],$arr['quid'],$arr['time'],$arr['status'],$arr['title'],$arr['content'],$arr['suggest']);
			if(!$ret){
				return false;
			}
		}catch(Exception $e){
			if(Utils::getErrorCode($e) != CommonConst::COMMON_DB_DUPLICATE_ERROR) {
				return false;
			}
		}

		//按照回答表UID分表回答表插入

		try{
			$ret = $answerService->insertByAuid($arr['id'],$arr['qid'],$arr['auid'],$arr['quid'],$arr['time'],$arr['status'],$arr['title'],$arr['content'],$arr['suggest']);
			if(!$ret){
				return false;
			}
		}catch(Exception $e){
			if(Utils::getErrorCode($e) != CommonConst::COMMON_DB_DUPLICATE_ERROR) {
				return false;
			}
		}

		if($arr['status'] == 1 ){
			//如果是指定的医生回答，更新问题表字段 考虑到前台的显示，将这部分逻辑放入进入队列时先进行处理

			// 如果问题的提问时间在24小时内，给提问用户发送短信
			while (1) {
				$questionService = QuestionService::getInstance(); 
				$questionInfo = $questionService->getQuestionBasicInfo( $arr['qid'] );
				$poorTime = time() - $questionInfo['time'];
				if($poorTime <= 24*60*60){
					#debug发送短信
				}
				break;
			}



			// 发送站内信
			$tryNum = 0;
			while (1) {
				$widgetSapi = new WidgetSapi(CommonConst::SAPI_HOST_WIDGET);
				$touid = $arr['quid'];		
				$module = CommonConst::MODULE_IASK_ID;
				$type = 1;
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
					continue;
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
		
				$data['operate'] = IaskConfig::QUESTION_ANSWER_ADD;

				$moneyService = MoneyService::getInstance();
				$ret = $moneyService -> insertDetailMoney($data);
				if($ret === true){
					break;
				}else{
					CLog::warning('moneyadd error [param '.json_encode($arr).']');
				}
			}
			//如果是首条加日志
			if($questionInfo['answers'] == 0){
				while (1) {
					$data = array();
					$data['qid'] =  $arr['qid'];
					$data['aid'] = $arr['id'];
					$data['uid'] = $arr['auid'];
					$data['date'] = empty($arr['time'])?time():$arr['time'];
					$data['date'] = date('Ymd',$data['date']);
					//如果是首条回答，记录收条回答记录，如果非收条用普通回答记录
					$data['operate'] = IaskConfig::QUESTION_FIRST_ANSWER_ADD;
					$moneyService = MoneyService::getInstance();
					$ret = $moneyService -> insertDetailMoney($data);
					if($ret === true){
						break;
					}else{
						CLog::warning('moneyadd error [param '.json_encode($arr).']');
					}
				}
			}
		}	
		return true;
	}
}
?>
