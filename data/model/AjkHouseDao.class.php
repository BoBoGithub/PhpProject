<?php
/**
 * 数据中心 操作安居客房屋数据Dao类
 * 
 * @package data
 * @param   addTime 2018-03-12
 * @param   author  ChengBo
 */
class AjkHouseDao {
    private static $instance = NULL;

    //获取单例
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new AjkHouseDao();
        }
        return self::$instance;
    }
    protected function __construct(){}
	
	/**
	 *  定位数据库连接
	 *  @param $master  true主库 false从库
	 *  @param $balance 取模负载
	 *
	 *  @return mysql resource 
	 */
	private function getDbResource($master = false, $balance = NULL){
		if($balance != NULL && empty($balance)) return false;
		
		//定位数据库连接 链接从库
		$arr		= TableService::getDbName($master, $balance);
		$dbname		= $arr['db'];
		$resource	= DbWrapper::getInstance($dbname);

		return $resource;
	}
	
	/**
	 * 查询安居客的房屋数据
	 *
	 * @param int $param['page']		当前页码
	 * @param int $param['pageSize'] 	每页条数
	 * @param str $param['siteName']	小区名称
	 * @param str $param['totalSize']   	房屋总面积
	 * @param str $param['userName']   	业务员名称
	 *
	 *
	 * @param addTime 2018-03-12
	 * @param author  ChengBo
	 */
	public function getAjkHouseByWhere($param){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'ajk_house';

		//设置查询条件
		$where 		= empty($param['siteName']) ? '' : ' site_name="'.addslashes($param['siteName']).'" AND ';
		$where	       .= empty($param['totalSize']) ? '' : ' total_size="'.addslashes($param['totalSize']).'" AND ';
		$where	       .= empty($param['userName']) ? '' : ' user_name="'.addslashes($param['userName']).'" AND ';
		$where		= empty($where) ? '' : ' WHERE '.trim($where, 'AND ');

		//设置偏移量
		$offset		= ($param['page'] - 1) * $param['pageSize'];

		//调去数据
		$sql		= 'SELECT * FROM '.$tableName.' '.$where.' ORDER BY id DESC LIMIT '.$offset.','.$param['pageSize'];
		
		//执行sql
		$roleData	= $mysqlHandle->queryAllRows($sql); 

		return $roleData;
	}
	
	/**
	 * 汇总安居客的房屋数据
	 *
	 * @param str $param['siteName']	小区名称
	 * @param str $param['totalSize']   	房屋总面积
	 * @param str $param['userName']   	业务员名称
	 *
	 * @param addTime 2018-03-12
	 * @param author  ChengBo
	 */
	public function countAjkHouseByWhere($param){
		//定位数据库连接
		$mysqlHandle	= $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'ajk_house';

		//设置查询条件
		$where 		= empty($param['siteName']) ? '' : ' site_name="'.addslashes($param['siteName']).'" AND ';
		$where	       .= empty($param['totalSize']) ? '' : ' total_size="'.addslashes($param['totalSize']).'" AND ';
		$where	       .= empty($param['userName']) ? '' : ' user_name="'.addslashes($param['userName']).'" AND ';
		$where		= empty($where) ? '' : ' WHERE '.trim($where, 'AND ');

		//调去数据
		$sql		= 'SELECT COUNT(1) AS num FROM '.$tableName.' '.$where;
		
		//执行sql
		$countData	= $mysqlHandle->queryFirstRow($sql); 
		if($countData == false){
			//记录执行sql失败操作
			CLog::fatal(" countAjkHouseByWhere execute sql failure sql : ". $sql);
		}

		return $countData['num'];
	}


}
