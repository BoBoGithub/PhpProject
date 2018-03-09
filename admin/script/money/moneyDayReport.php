<?php
/**
 * 赏金模块脚本 汇总日报表脚本
 * 
 * @package money
 * @param   addTime 2014-02-26
 * @param   author  ChengBo
 * @copyright feihua
 */
define('SCRIPT_PATH', dirname(__FILE__).'/');
require_once(SCRIPT_PATH .'/../../common/env_init.php');
define("RUN_MAX_NUM",100);

$GLOBALS['LOG'] = array(
	'type'		=> LOG_TYPE,
	'level'		=> LOG_LEVEL,
	'path'		=> APP_PATH .'/common/../script/money/log',
	'filename'	=> 'moreyDayReport.log',
);


function help() {
	echo ("Use command [php passport_money_day_report.php] start handle\n");		
}

function execute() {
	Timer::start('money_day_report_start');
	$date         = date("Ymd",strtotime("-1 day"));
	$moneyService = MoneyService::getInstance();

	//循环分库的数据 统计绩效报表 for循环必须从1开始 分库是empty判断
	for($i = 1;$i <= DbConfig::DB_SPLIT_NUM;$i++){
		$dayList = $moneyService->getMoneyListByDay($i, $date);
		if(empty($dayList)){continue;}
		
		//更新入库 绩效报表 日报 
		foreach($dayList as $k=>$v){
			$lastInsertId = $moneyService->insertMoneyReport($v);
		}
	}
	
	Timer::end('money_day_report_start');
	//记录汇总日志
	CLog::warning('Time:'.date('Y-m-d H:i:s',time()).' Date:'.$date.' 日报表更新完毕. 用时：'.Timer::calculate('money_day_report_start')); 
}

$arr_option = getopt("vh");
if(isset($arr_option['h'])) {
	help();
}else {
	execute();
}
?>
