<?php
/**
 * @brief   注册处理脚本
 *  
 **/
define('SCRIPT_PATH', dirname(__FILE__).'/');
define("RUN_MAX_NUM", 100);

require_once(SCRIPT_PATH .'/../../common/env_init.php');
require_once(SCRIPT_PATH."/../../../phplib/fhsdk/WidgetSapi.class.php");

$GLOBALS['LOG'] = array(
	'type'		=> LOG_TYPE,
	'level'		=> LOG_LEVEL,
	'path'		=> APP_PATH .'/common/../script/submit/log',
	'filename'	=> 'iask_submit.log',
);


function help() {
	echo ("Use command [php iask_submit.php]\n");		
}

function execute() {
	global $scriptName;
	CLog::debug("msg[start]");
	$submit_service = SubmitService::getInstance();
	while(1) {
		if(RUN_MAX_NUM == $run_count) {
			CLog::trace("msg[this process run exceed max count, will restart by father process]");
			exit(0);
		}
		$list = $submit_service->getTaskList(CommonConst::MODULE_IASK_ID, CommonConst::TASK_NEW);
		if(is_array($list) && count($list) > 0) {
			foreach($list as $item) {
				$retry_num = 0;
				require_once(SCRIPT_PATH."/lib/iask_submit_".$item['name'].".class.php");
				CLog::trace('msg[start handle submit] name['. $item['name'] .'] content[' .$item['content']. ']');
				while(1) {
					try{
						$ret = Submit::execute(json_decode($item['content'],1));
						if($ret) {
							$submit_service->setTask($item['id'],CommonConst::TASK_SUCC);
							break;
						} else {
							$errmsg = sprintf('msg[submit execute fail] retry[%d]', $retry_num);
							CLog::warning($errmsg);
						}
					}catch(Exception $ex) {
						$errmsg = sprintf('errcode[%s] trace[%s] errmsg[caught exception]', $ex->getMessage(),$ex->__toString());
						CLog::warning($errmsg);
					}
				}
			}
		} else {
			sleep(2);
		}
		$run_count++;
	}
}

$arr_option = getopt("vh");
if(isset($arr_option['h'])) {
	help();
}else {
	execute();
}
?>
