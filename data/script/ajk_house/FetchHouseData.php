<?php
/**
 * 抓取房屋数据
 * 
 * @package data
 * @param   addTime 2018-03-19
 * @param   author  ChengBo
 */
define('SCRIPT_PATH', dirname(__FILE__).'/');
require_once(SCRIPT_PATH .'/../../common/env_init.php');
define("RUN_MAX_NUM",100);

$GLOBALS['LOG'] = array(
	'type'		=> LOG_TYPE,
	'level'		=> LOG_LEVEL,
	'path'		=> APP_PATH .'/common/../script/ajk_house/log',
	'filename'	=> 'FetchAjkHouseData.log',
);


function help() {
	echo ("Use command [php FetchHouseData.php.php] start handle\n");		
}

//具体处理方法 
function execute() {
	Timer::start('FetchAjkHouseData');

	//抓取昨天前发布的数据 - 奥北公元
	FetchDataService::getInstance()->fetchAjkHouseData(1);
	
	//抓取昨天前发布的数据 - 瀚唐小区
	FetchDataService::getInstance()->fetchAjkHouseData(2);
	
	Timer::end('FetchAjkHouseData');

	//记录汇总日志
	CLog::warning('Time:'.date('Y-m-d H:i:s',time()).' Date:'.$date.' 数据抓取完毕. 用时：'.Timer::calculate('FetchAjkHouseData')); 
}

$arr_option = getopt("vh");
if(isset($arr_option['h'])) {
	help();
}else {
	execute();
}
?>
