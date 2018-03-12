<?php
/**
 * data配置
 * url 映射
 */
ActionControllerConfig::$config = array(
	'hash_mapping' => array(
		'/' 			=> array('IndexAction'),
		'/get/house/ajk'	=> array('GetHouseAjkAction'),
		'/count/house/ajk'	=> array('CountHouseAjkAction'),

		
	),
	'prefix_mapping'=>array(
		'/user-dd'=>array('TestAction'),
	),
	'regex_mapping'=>array(
		"/\/q-([0-9]+).html/"=>array('TestAction'),
	),
);


class DataConfig {
	public static $tableNum = 2;
	
	//passport默认过期时间:1个月,不记住登录状态下
	public static $expireIntervalNotRmb = 2592000;
	//passport默认过期时间:6个月,记住登录状态下
	public static $expireIntervalRmb = 15552000;
	//passport更新上次在线时间的时间间隔:1个小时
	public static $updateInterval = 3600;
	//passport自动延长的时间长度:1个小时
	public static $autoExtendTime = 3600;
	
	//passport的cookie加密私钥
	public static $passportKey = 'BaiduPcsId!*';
	
	//多点登录的次数
	public static $maxLoginCount = 5;

}

