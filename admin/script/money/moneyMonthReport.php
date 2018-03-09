<?php
/**
 * 赏金模块脚本 汇总月报表脚本
 * 
 * @package money
 * @param   addTime 2014-02-28
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
	'filename'	=> 'moreyMonthReport.log',
);


function help() {
	echo ("Use command [php moneyMonthReport.php] start handle\n");		
}

function execute() {
	Timer::start('money_month_report_start');
	
	//取得上一月的时间
	$date         = date("Ym",time());
	$date		  = date('Ym',strtotime($date.'01')-100);
	$moneyService = MoneyService::getInstance();
	
	//统计月报表 直接从报表里面统计 上月每天的数据汇总
	$dayList = $moneyService->getMoneyListByMonth($date);
	if(empty($dayList)){
		CLog::warning('Time:'.date('Y-m-d H:i:s',time()).' Date:'.$date.' 上月医生回答为空.'); 
	}
	
	//更新入库 绩效报表 日报 
	foreach($dayList as $k=>$v){
		$lastInsertId = $moneyService->insertMoneyReport($v);
	}
	
	//更新 医生这个月生效的等级
	updateDoctorLevel();
	
	Timer::end('money_month_report_start');
	//记录汇总日志
	CLog::warning('Time:'.date('Y-m-d H:i:s',time()).' Date:'.$date.' 月报表更新完毕. 用时：'.Timer::calculate('money_month_report_start')); 
}

/**
 *	更新 医生这个月生效的等级 更新到passport_user_info的主表中
 *	注意：这个执行必须在月报表执行完后执行 执行顺序 日报表->月报表->更新本月生效的医生等级	
 *
 *  @param author  ChengBo
 *  @param addTime 2014-03-12
 */
 function updateDoctorLevel(){
	$doctorLevelService = DoctorLevelService::getInstance();
	$doctorLevelService->updateDoctorLevel();
	//记录汇总日志
	CLog::warning('Time:'.date('Y-m-d H:i:s',time()).' 医生等级更新完毕!'); 
 }
 
$arr_option = getopt("vh");
if(isset($arr_option['h'])) {
	help();
}else {
	execute();
}
?>
