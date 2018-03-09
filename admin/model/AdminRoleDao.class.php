<?php
/**
 * 管理用户 操作角色数据Dao类
 * 
 * @package money
 * @param   addTime 2018-03-02
 * @param   author  ChengBo
 */
class AdminRoleDao {
    private static $instance = NULL;

	//获取单例
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new AdminRoleDao();
        }
        return self::$instance;
    }
    protected function __construct(){}
	
	/**
	 *	定位数据库连接
	 *  @param $master  true主库 false从库
	 *  @param $balance 取模负载
	 *
	 *	@return mysql resource 
	 */
	private function getDbResource($master = false, $balance = NULL){
		if($balance != NULL && empty($balance)) return false;
		
		//定位数据库连接 链接从库
		$arr 	 	 = TableService::getDbName($master, $balance);
		$dbname 	 = $arr['db'];
		$resource    = DbWrapper::getInstance($dbname);

		return $resource;
	}
	
	/**
	 * 查询指定角色id的数据
	 *
	 * @param int $id
	 *
	 * @param addTime 2018-03-02
	 * @param author  ChengBo
	 */
	public function getRoleInfoByid($id){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role';
		
		//调去数据
		$sql		= 'SELECT * FROM '.$tableName.' WHERE roleid=%d AND status IN(0, 1)';
		$roleData	= $mysqlHandle->queryFirstRow($sql, $id);

		return $roleData;
	}
	
	/**
	 * 获取所有角色数据
	 *
	 * @param['page']	int 当前页码
	 * @param['pageSize'] 	int 每页条数
	 * @param['status']	arr 角色状态 
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo 
	 */
	public function getRoleListData($param){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role';

		//设置页面偏移量
		$offset		= ($param['page'] - 1) * $param['pageSize'];
		
		//调去数据
		$sql		= 'SELECT * FROM '.$tableName.' WHERE status IN('.implode(',', $param['status']).') ORDER BY roleid DESC LIMIT '.$offset.','.$param['pageSize'];
		
		//调取数据
		$roleData	= $mysqlHandle->queryAllRows($sql);

		return $roleData;
	}

	/**
	 * 汇总角色总条数
	 *
	 * @param['status'] arr 角色状态 
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function countRoleNum($status){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role';

		//设置查询SQL
		$sql 		= "SELECT COUNT(1) AS num FROM ".$tableName.' WHERE status IN('.implode(',', $status).')';

		//查询数据
		$totalNumData = $mysqlHandler->queryFirstRow($sql);

		return $totalNumData['num'];
	}

	/**
	 * 新增角色数据
	 *
	 * @param['roleName'] 	str 角色名称
	 * @param['roleDesc'] 	str 角色描述
	 * @param['roleStatus'] int 角色状态
	 *
	 * return int 
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo 
	 */
	public function addRoleInfo($addParam){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role';

		//设置入库参数
		$insertParam['rolename'] = addslashes($addParam['roleName']);
		$insertParam['roledesc'] = addslashes($addParam['roleDesc']);
		$insertParam['status']	 = addslashes($addParam['roleStatus']);

		//入库
		$insertStatus = $mysqlHandler->insert($insertParam, $tableName);
		if($insertStatus === false){
			//记录插入失败日志
			CLog::fatal("AdminRoleDao addRoleInfo insert failure param[".var_export($insertParam, true)."]");
			return 0;
		}

		//返回插入的id
		return $mysqlHandler->getLastInsertID();
	}

	/**
	 *  管理角色编辑
	 *
	 * @param int $param['roleId']     角色id
	 * @param str $param['roleName']   角色名称
	 * @param str $param['roleDesc']   角色描述
	 * @param int $param['roleStatus'] 角色状态
	 *
	 * @return int
	 *
	 * @param addTIme 2018-03-06
	 * @param author  ChengBo
	 */
	public function updRoleInfoById($updParam){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role';

		//设置更新sql
		$updSql 	= 'UPDATE '.$tableName.' SET ';
		$updSql	       .= 'rolename="'.addslashes($updParam['roleName']).'",';
		$updSql        .= 'roledesc="'.addslashes($updParam['roleDesc']).'",';
		$updSql        .= 'status='.$updParam['roleStatus'];
		$updSql        .= ' WHERE roleid='.$updParam['roleId'];

		//更新数据
		$execStatus	= $mysqlHandler->doUpdateQuery($updSql);

		//返回影响行数
		return $mysqlHandler->getAffectedRows();
	}

	/**
	 * 删除指定角色
	 *
	 * @param int $roleId 
	 *
	 * return int
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function delRoleInfoById($roleId){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin_role';
	
		//设置更新sql
		$updSql		= 'UPDATE '.$tableName.' SET status=-1 WHERE roleid='.$roleId;

		//执行sql
		$execStatus	= $mysqlHandler->doUpdateQuery($updSql);

		//返回影响行数
		return $mysqlHandler->getAffectedRows();
	}

}
