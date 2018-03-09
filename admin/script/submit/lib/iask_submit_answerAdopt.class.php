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

		$questionService = QuestionService::getInstance();

		$questionService->updateQuestionBasic($arr['qid'],array('isadopt'=>1));

		$answerService -> editDetailByQuid($arr['aid'],$arr['quid'],array('adopt'=>time()));

		$answerService -> editDetailByAuid($arr['aid'],$arr['auid'],array('adopt' => time()));

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
			$data['operate'] = IaskConfig::QUESTION_ACCEPTED_ANSWER_ADD;
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
		return true;

	}
}
?>
