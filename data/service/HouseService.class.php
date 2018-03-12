<?php
/**
 * 数据中心 - 房屋相关Service类
 * 
 * @param   addTime 2018-03-12
 * @param   author  ChengBo
 */
class HouseService{
	private static $instance = NULL;
	
	//单例模式
	public static function getInstance(){
		if (!isset(self::$instance)){
			self::$instance = new HouseService();
		}
		return self::$instance;
	}
	
	protected function __construct(){}

	/**
	 * 获取安居客房屋数据数据
	 *
	 * @param int $param['page']		当前页码
	 * @param int $param['pageSize'] 	每页条数
	 * @param str $param['siteName']	小区名称
	 * @param str $param['totalSize']   	房屋总面积
	 * @param str $param['userName']   	业务员名称
	 *
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-12
	 * @param author  ChengBo 
	 */
	public function getAjkHouseByWhere($param = array()){
		//设置查询条件 简单过滤一下参数
		$data['page']		= Utils::check_int($param['page'], 1) ? $param['page'] : 1;
		$data['pageSize']	= Utils::check_int($param['pageSize'], 1)? $param['pageSize'] : 10;
		$data['siteName']	= Utils::check_string($param['siteName']) ? $param['siteName'] : '';
		$data['totalSize']	= Utils::check_string($param['totalSize']) ? $param['totalSize'] : '';
		$data['userName']	= Utils::check_string($param['userName']) ? $param['userName'] : '';

		//查询数据
		return AjkHouseDao::getInstance()->getAjkHouseByWhere($data);
	}

	/**
	 * 汇总安居客房屋数据数据
	 *
	 * @param str $param['siteName']	小区名称
	 * @param str $param['totalSize']   	房屋总面积
	 * @param str $param['userName']   	业务员名称
	 *
	 *
	 * return int
	 *
	 * @param addTime 2018-03-12
	 * @param author  ChengBo 
	 */
	public function countAjkHouseByWhere($param = array()){
		//设置查询条件 简单过滤一下参数
		$data['siteName']	= Utils::check_string($param['siteName']) ? $param['siteName'] : '';
		$data['totalSize']	= Utils::check_string($param['totalSize']) ? $param['totalSize'] : '';
		$data['userName']	= Utils::check_string($param['userName']) ? $param['userName'] : '';

		//汇总数据
		return AjkHouseDao::getInstance()->countAjkHouseByWhere($data);
	}

}
