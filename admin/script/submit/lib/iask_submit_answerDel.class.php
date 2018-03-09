<?php
/**
 * @author wangyunjie
 * @date 2014/2/27
 * @brief  回答删除处理 
 * 
 **/
class Submit {
	public static function execute($arr) {

		$answerService = AnswerService::getInstance();
		$tryNum = 0;     //重试次数

		//  判断回答的问题状态

		$answerInfo = $answerService->getDetailByQuid($arr['aid'],$arr['quid']);

		if(!$answerInfo || $answerInfo['status'] < 0){
			return true;
		}

		$answerInfo = $answerService->getDetailByAuid($arr['aid'],$arr['auid']);

		if(!$answerInfo || $answerInfo['status'] < 0){

		}


		//更新问题状态，包括删除原因

		$data = array(
			'status' => -1,
			'delreason' => $arr['delreason'],
			'reason' => $arr['reason']
		);


		//更新按照QUID分表数据
		$ret = $answerService->editDetailByquid($arr['aid'],$arr['quid'],$data);
		if(!$ret){
			return false;
		}


		//更新按照AUID分表数据
		$ret = $answerService->editDetailByauid($arr['aid'],$arr['auid'],$data);
		if(!$ret){
			return false;
		}


		//去除问题下的所有回答，判断本条回答是否为第一条回答
		$firstAnswer = $answerService->questionAnswerList($arr['qid'],$arr['quid']);
		if(isset($firstAnswer[0]) && $firstAnswer[0]['auid'] == $arr['auid']){
			//添加首条删除
			$tryNum = 0;
			while (1) {
				//接口调用
				$data = array();
				$data['qid'] =  $arr['qid'];
				$data['aid'] = $arr['aid'];
				$data['uid'] = $arr['auid'];
				$data['date'] = empty($arr['time'])?time():$arr['time'];
				$data['date'] = date('Ymd',$data['date']);
				//如果是首条回答，记录收条回答记录，如果非收条用普通回答记录
				$data['operate'] = IaskConfig::QUESTION_FIRST_ANSWER_DELETE;
				$moneyService = MoneyService::getInstance();
				$ret = $moneyService -> insertDetailMoney($data);
				if($ret){
					break;
				}else{
					$tryNum++;
					CLog::warning('msg[add money for first answer add is fail] param[aid:'.$arr['aid'].' quid:'.$arr['quid'].' auid:'.$arr['auid'].'] trynum['.$tryNum.']');
					if($tryNum>=20){
						break;
					}
				}
			}	
		}

		//添加普通删除
		$tryNum = 0;
		while (1) {
			//接口调用
			$data = array();
			$data['qid'] =  $arr['qid'];
			$data['aid'] = $arr['aid'];
			$data['uid'] = $arr['auid'];
			$data['date'] = empty($arr['time'])?time():$arr['time'];
			$data['date'] = date('Ymd',$data['date']);
			//如果是首条回答，记录收条回答记录，如果非收条用普通回答记录
			$data['operate'] = IaskConfig::QUESTION_ANSWER_DELETE;
			$moneyService = MoneyService::getInstance();
			$ret = $moneyService -> insertDetailMoney($data);
			if($ret){
				break;
			}else{
				$tryNum++;
				CLog::warning('msg[add money for answer add is fail] param[aid:'.$arr['aid'].' quid:'.$arr['quid'].' auid:'.$arr['auid'].'] trynum['.$tryNum.']');
				if($tryNum>=20){
					break;
				}
			}
		}	
		

		
		$tryNum = 0;
		while (1) {
			//更新问题回答数
			$questionService = QuestionService::getInstance();
			$questionInfo = $questionService->getQuestionBasicInfo($arr['qid']);
			if($questionInfo['answers'] == 0){
				CLog::warning('msg[update question answers error answers 0 ] param['.$arr['qid'].']');
			}	
			//准备需要更新的数据
			$data = array();
			$data['answers'] = $questionInfo['answers'] - 1;
			$where = 'answers='.$questionInfo['answers'];
			//debug 等待问题更新方法的修改
			//$ret = $questionService -> updateQuestionBasic($questionInfo['id'],$questionInfo['status'],$questionInfo['title'],$questionInfo['touid'],$questionInfo['sourceid'],$where);
			$ret = true;
			if($ret){
				break;
			}else{
				$tryNum++;
				CLog::warning('msg[updateQuestionAnswers is fail] param[aid:'.$arr['aid'].' quid:'.$arr['quid'].' auid:'.$arr['auid'].'] trynum['.$tryNum.']');
			}

		}


		//更新回答人回答数量
		$tryNum = 0;
		while (1) {
			$userService = UserService::getInstance();
			$auserinfo = $userService -> getUserInfoByUid($arr['auid']);
			//如果取不到用户的基本信息，while循环重复取
			if(empty($auserinfo)){
				CLog::warning("msg['dont get userinfo'] param[".$arr['auid']."]");
				break;
			}

			if($answerInfo['answers'] == 0){
				CLog::warning("msg[dec answers for user error] reason[answers 0] param[quid ".$arr['auid']."]");	
			}

			$passport_sapi = new PassportSapi(CommonConst::SAPI_HOST_POSSPORT);
			$where = 'answers='.$auserinfo['answers'];
			$uid = $arr['auid'];
			$content = array(
				'answers' => $auserinfo['answers'] - 1;
			);
			$ret = $passport_sapi->updateuserinfo($uid,json_encode($content),$where);
			if($ret['errno'] === 0){
				break;
			}else{
				CLog::warning('msg[dec user answers error] reason[db error] param['.json_encode($ret).']');
			}
		}

		//添加追问删除队列
		//debug 等待追问删除方法
		$tryNum = 0;
		while (1) {
			//接口调用
			$ret  = true;
			$data = array();
			$data['id'] = $arr[]
			$chaseService = ChaseService::getInstance();
			$selData = array();
			$selData['uid'] = $arr['auid'];
			$selData['type'] = null;
			$selData['status'] = 0;
			$selData['state'] = 1;
			$selData['page'] = 1;
			$selData['offset'] = 100;
			$chaselist = $chaseService -> getChaseByUid($selData);
			$chaseidsChase = '';
			$chaseidsAnswer = '';
			//将追问追答分开处理
			if(!empty($chaselist)){
				foreach ($chaselist as $key => $chase) {
					if($chase['type'] == 0){
						$chaseidsChase .=','.$chase['id'];
					}else{
						$chaseidsAnswer .=','.$chase['id'];
					}
				}
			}
			$chaseids = trim($chaseids,',');
			//debug 需要将所有的数字变成常量
			if(!empty($chaseids)){
				$chaseData = array();
				$chaseData['qid'] = $arr['qid'];
				$chaseData['aid'] = $arr['aid'];
				$chaseData['quid'] = $arr['quid'];
				$chaseData['auid'] = $arr['auid'];
				$chaseData['content'] = null;
				$chaseData['status'] = 1;
				//删除追问
				$chaseData['id'] = $chaseidsChase;
				$chaseData['type'] = 0;
				$chaseService -> updateChase($chaseData);	
				//删除追答
				$chaseData['id'] = $chaseidsAnswer;
				$chaseData['type'] = 1;
				$chaseService -> updateChase($chaseData);
			}
			
		}

		return true;
	}
}
?>
