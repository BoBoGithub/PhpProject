<?php
/**
 * Data脚本任务处理 按时间执行 适合定时任务脚本
 *  
 * @param addTime 2018-03-19
 * @param author  ChengBo
 */

// 定义执行脚本路径, 必须
define('SCRIPT_PATH', dirname(__FILE__));

//引入初始化文件
require_once SCRIPT_PATH . '/../common/env_init.php';

//设置当天时间 Y-m-d H:i:s
$time		= time();
$currYear	= date('Y');
$currMonth	= intval(date('m'));
$currDay 	= intval(date('d'));
$currHour	= intval(date('H'));
$currMin	= intval(date('i'));

//每天早上8点20分执行 采集前一天的数据
if($currHour == 8 && $currMin == 35){
	//引入操作脚本文件
	require_once SCRIPT_PATH . '/lib/DataTaskFetchHouseData.class.php';
	(new DataTaskFetchHouseData)->execute($time);
}


?>
