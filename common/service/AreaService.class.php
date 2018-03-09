<?php
class AreaService{
	private static $configFile = 'areaConfig.php';
	private static $areaConfig;
	
	private static function loadConfig(){
		if(!isset(self::$areaConfig)){
			self::$areaConfig = include COMMON_CONF_PATH . '/'. self::$configFile;
		}
		return self::$areaConfig;
	}
	
	public static function getHotArea(){
		$config = self::loadConfig();
		return $config['hotArea'];
	}
	
	/**
	 * 按照区域读取
	 * @param int $area	某一区域的id: 0=>华北地区....
	 */
	public static function getArea($areaId = NULL){
		$config = self::loadConfig();
		if($areaId !== NULL && array_key_exists($areaId, $config['area'])){
			return $config['area'][$areaId];
		}
		return $config['area'];
	}
	
	/**
	 * 取省或省下城市列表
	 * @param int $cityId 省id：id=6，取河北省的二级城市
	 */
	public static function getCityList($cityId = NULL){
		$config = self::loadConfig();
		if($cityId !== NULL && array_key_exists($cityId,$config['city'])){
			return $config['city'][$cityId];
		}
		return $config['province'];
	}
}