<?php
/**
 * 数据模块对外接口
 *
 * @param addTime 2018-03-12
 * @param author  ChengBo
 */
class DataSapi extends BaseSapi {
	//设置单例变量
	private static $instance = NULL;

	//设置请求HOST地址
	protected $default_hostname = 'http://data.test.com';

	//设置单例
	public static function getInstance(){
		if(!isset(self::$instance)){
			self::$instance = new DataSapi();
		}
		return self::$instance;
	}

	/**
	 * 查询房屋数据
	 *
	 * @param int $param['page']		查询页码
	 * @param int $param['pageSize']	每页条数
	 * @param str $param['siteName']	小区名称
	 * @param str $param['totalSize']	房屋总面积
	 * @param str $param['userName']	业务员名称
 	 *
	 * @param addTime 2018-03-12
	 * @param author  ChengBo
	 */
	 public function getHouseDataByWhere($param = array()){
		//清空日志
		$this->clear_error();
		
		//匹配参数
		$opt['req_header']		= array("Content-type"=>"application/json");
		$opt['url_opts']['action'] 	= '/get/house/ajk';
		$opt['req_opts']['body']   	= json_encode($param);
		try {
			$ret = $this->authenticate(self::http_post, $opt);
			return $ret;
		} catch (Exception $ex) {
			$this->error_code = -2;
			$this->error_message = $ex->getMessage();
			return false;
		}
	}

	/**
	 * 统计房屋数据
	 *
	 * @param str $param['siteName']	小区名称
	 * @param str $param['totalSize']	房屋总面积
	 * @param str $param['userName']	业务员名称
 	 *
	 * @param addTime 2018-03-12
	 * @param author  ChengBo
	 */
	 public function countHouseDataByWhere($param = array()){
		//清空日志
		$this->clear_error();
		
		//匹配参数
		$opt['req_header']		= array("Content-type"=>"application/json");
		$opt['url_opts']['action'] 	= '/count/house/ajk';
		$opt['req_opts']['body']   	= json_encode($param);
		try {
			$ret = $this->authenticate(self::http_post, $opt);
			return $ret;
		} catch (Exception $ex) {
			$this->error_code = -2;
			$this->error_message = $ex->getMessage();
			return false;
		}
	}

}
?>
