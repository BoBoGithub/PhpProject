<?php
/**
 * 管理用户 操作菜单数据Dao类
 * 
 * @package iask
 * @param   addTime 2018-03-07
 * @param   author  ChengBo
 */
class AdminMenuDao {
    private static $instance = NULL;

	//获取单例
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new AdminMenuDao();
        }
        return self::$instance;
    }
    protected function __construct(){}
	
	/**
	 *  定位数据库连接
	 *
	 *  @param $master  true主库 false从库
	 *  @param $balance 取模负载
	 *
	 *  @return mysql resource 
	 */
	private function getDbResource($master = false, $balance = NULL){
		if($balance != NULL && empty($balance)) return false;
		
		//定位数据库连接 链接从库
		$arr 	 = TableService::getDbName($master, $balance);
		$dbname  = $arr['db'];
		$resource= DbWrapper::getInstance($dbname);

		return $resource;
	}
	
	/**
	 * 查询菜单数据
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	public function getMenuList(){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_menu';
		
		//调去数据
		$sql		= 'SELECT * FROM '.$tableName.' WHERE status IN(0, 1)';
		$menuData	= $mysqlHandle->queryAllRows($sql);

		return $menuData;
	}

	/**
	 * 获取指定id的菜单数据
	 *
	 * @param int $menuId 菜单id
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	public function getMenuDataById($menuId){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_menu';
		
		//调去数据
		$sql		= 'SELECT * FROM '.$tableName.' WHERE id=%d';
		$menuData	= $mysqlHandle->queryFirstRow($sql, $menuId);

		return $menuData;
	}

	/**
	 * 新增菜单数据
	 *
	 * @param['parentId'] 	int 父级菜单id
	 * @param['menuName'] 	str 菜单名称
	 * @param['requestUrl'] str 请求地址
	 * @param['menuStatus']	int 菜单状态
	 * 
	 * return int
	 *
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	public function addMenuData($param = array()){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_menu';

		//设置入库参数
		$insParam['name']	= addslashes($param['menuName']);
		$insParam['parentid']	= intval($param['parentId']);
		$insParam['url']	= addslashes($param['requestUrl']);
		$insParam['status']	= intval($param['menuStatus']);

		//数据入库
		$insStatus = $mysqlHandle->insert($insParam, $tableName);

		//返回插入的id
		return $mysqlHandle->getLastInsertID();
	}
	
	/**
	 * 更新菜单数据
	 *
	 * @param int $menuId	   菜单id
	 * @param str $parentId	   父级菜单id
	 * @param str $menuName	   菜单名称 
	 * @param str $requestUrl  请求地址
	 * @param int $menuStatus  菜单状态
	 *
	 * @param addTIme 2018-03-07
	 * @param author  ChengBo
	 */
	public function updMenuData($param){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_menu';

		//设置更新sql
		$sql = 'UPDATE '.$tableName.' SET parentid=%d, name=%s,url=%s,status=%d WHERE id=%d';

		//执行更新
		$exeState = $mysqlHandle->doUpdateQuery($sql, $param['parentId'], $param['menuName'], $param['requestUrl'], $param['menuStatus'], $param['menuId']);

		//返回影响行数
		return $mysqlHandle->getAffectedRows();
	}

	/**
	 * 删除指定菜单数据
	 * 
	 * @param int $menuId 菜单id
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public function delMenuData($menuId = 0){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_menu';

		//设置更新sql
		$sql = 'UPDATE '.$tableName.' SET status=-1 WHERE id=%d';

		//执行更新
		$exeState = $mysqlHandle->doUpdateQuery($sql, $menuId);

		//返回影响行数
		return $mysqlHandle->getAffectedRows();

	}

}
