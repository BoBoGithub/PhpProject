<?php
/**
 * 管理用户 操作权限数据Dao类
 * 
 * @package admin
 * @param   addTime 2018-03-08
 * @param   author  ChengBo
 */
class AdminRolePrivDao {
    private static $instance = NULL;

	//获取单例
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new AdminRolePrivDao();
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
	 * 获取指定id的角色的权限数据
	 *
	 * @param int $menuId 菜单id
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-08
	 * @param author  ChengBo
	 */
	public function getAdminRolePrivByRoleId($roleId){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role_priv';
		
		//调去数据
		$sql		= 'SELECT * FROM '.$tableName.' WHERE roleid=%d';
		$rolePrivData	= $mysqlHandle->queryAllRows($sql, $roleId);

		return $rolePrivData;
	}

	/**
	 * 删除指定角色的权限数据
	 *
	 * @param int $roleId 角色id
	 *
	 * @param addTime 2018-03-08
	 * @param author  ChengBo
	 */
	public function delPrivByRoleId($roleId){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role_priv';
		
		//调去数据
		$sql		= 'DELETE FROM '.$tableName.' WHERE roleid=%d';
		$delStatus	= $mysqlHandle->doUpdateQuery($sql, $roleId);

		//返回影响行数
		return $mysqlHandle->getAffectedRows();
	}

	/**
	 * 新增菜单数据
	 *
	 * @param arr $menuIds 菜单ids
	 * 
	 * return int
	 *
	 * @param addTime 2018-03-08
	 * @param author  ChengBo
	 */
	public function batchInsertRolePriv($menuIds){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role_priv';

		//循环插入数据
		foreach($menuIds as $k=>$v){
			//设置入库参数
			$insertParam['roleid']	= $v['roleId'];
			$insertParam['url']	= $v['url'];

			//数据入库
			$mysqlHandle->insert($insertParam, $tableName);
		}

		return true;
	}
}
