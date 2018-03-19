<?php
/**
 * 抓取房屋数据
 * 
 * @package data
 * @param   addTime 2018-03-19
 * @param   author  ChengBo
 */
class DataTaskFetchHouseData{
	//处理任务方法
    public function execute($time = 0){
		Timer::start('DataTaskFetchHouseData');

		//抓取昨天前发布的数据 - 瀚唐小区
		FetchDataService::getInstance()->fetchAjkHouseData(2);

		//抓取昨天前发布的数据 - 奥北公元
		FetchDataService::getInstance()->fetchAjkHouseData(1);
		
		Timer::end('DataTaskFetchHouseData');

		//记录汇总日志
		CLog::warning('Time:'.date('Y-m-d H:i:s',time()).' Date:'.$date.' 数据抓取完毕. 用时：'.Timer::calculate('FetchAjkHouseData')); 
	}
}
?>
