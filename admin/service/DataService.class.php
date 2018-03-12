<?php
/**
 * 后台管理 - 数据分析相关Service类
 * 
 * @param   addTime 2018-03-12
 * @param   author  ChengBo
 */
class DataService{
	private static $instance = NULL;
	
	//单例模式
	public static function getInstance(){
		if (!isset(self::$instance)){
			self::$instance = new DataService();
		}
		return self::$instance;
	}
	
	protected function __construct(){}

	/**
	 * 调取安居客房屋数据
	 *
	 * @param int $data['page']	当前页码
	 * @param int $data['pageSize'] 每页条数
	 * @param str $data['siteName']	小区名称
	 * @param str $data['totalSize']房屋总面积
	 * @param str $data['userName']	业务员名称
	 *
	 * @param return array()
	 *
	 * @param addTime 2018-03-12
	 * @param author ChengBo
	 */
	public function getAjkHouseListByWhere($param = array()){
		//调取数据
		$houseData = DataSapi::getInstance()->getHouseDataByWhere($param);
		
		return empty($houseData['houseData']) ? array() : $houseData['houseData'];
	}

	/**
	 * 汇总安居客房屋的总条数
	 *
	 * @param str $data['siteName']	小区名称
	 * @param str $data['totalSize']房屋总面积
	 * @param str $data['userName']	业务员名称
	 *
	 * return int 
	 *
	 * @param addTime 2018-03-12
	 * @param author ChengBo
	 */
	public function countAjkHouseNumByWhere($param = array()){
		//调取数据
		$countHouseData = DataSapi::getInstance()->countHouseDataByWhere($param);
		
		//处理返回结果
		return Utils::check_int($countHouseData['num']) ? $countHouseData['num'] : 0;
	}
}
