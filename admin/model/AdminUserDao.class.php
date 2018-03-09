<?php
/**
 * 管理用户 操作数据库rDao类
 * 
 * @package money
 * @param   addTime 2018-03-01
 * @param   author  ChengBo
 */
class AdminUserDao {
    private static $instance = NULL;

	//获取单例
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new AdminUserDao();
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
	 * 按用户名查询管理用户数据
	 * 
	 * @param string $userName 登录用户名
	 *
	 * @return Array
	 *
	 * @param addTIme 2018-03-01
	 * @param author  ChengBo
	 */
	public function getAdminUserInfoByName($userName = ''){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin';
		
		//调去数据
		$sql		= 'SELECT * FROM '.$tableName.' WHERE username=%s AND status=0';
		$userData	= $mysqlHandle->queryFirstRow($sql, $userName);

		return $userData;
	}
	
	 /**
	  * 调取指定uid的管理用户数据
	  *
	  * @param int $adminUid
	  *
	  * return array()
	  *
	  * @param addTime 2018-03-02
	  * @param author  ChengBo
	  */
	  public function getAdminUserInfoById($adminUid){
		//定位数据库连接
		$mysqlHandle = $this->getDbResource(false, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin';
		
		//调去数据
		$sql		= 'SELECT * FROM '.$tableName.' WHERE uid=%d AND status=0';
		$userData	= $mysqlHandle->queryFirstRow($sql, $adminUid);

		return $userData;
	  }
	
	/**
	 * 更新 指定UID的信息 
	 *
	 * @param int $param[adminUid']
	 * @param str $param['realName']
	 * @param str $param['email']
	 *
	 * @return affectRows
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public function updAdminUserInfoByUid($updParam){
		//定位数据库连接
		$this->mysql = $this->getDbResource(true, NULL);
		
		//定位数据表
		$tableName = 'mg_admin';
		
		$updSql    = 'UPDATE '.$tableName.' SET realname=%s, email=%s WHERE uid=%d';
		$exeState  = $this->mysql->doUpdateQuery($updSql, $updParam['realName'], $updParam['email'], $updParam['adminUid']);

		return $this->mysql->getAffectedRows();
	}

	/**
	 * 获取管理用户数据列表
	 *
	 * @param int $param['page'] 	 当前页码数
	 * @param int $param['pageSize'] 每页条数
	 * @param str $param['userName'] 管理用户名
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public function getAdminUserList($data){
		//定位数据库连接
		$mysqlHandler = $this->getDbResource(true, NULL);
		
		//定位数据表
		$tableName = 'mg_admin';

		//设置查询条件
		$where	   = Utils::check_string($data['userName']) ? ' AND username="'.addslashes($data['userName']).'"' : '';

		//设置页码偏移量
		$offset	   = ($data['page'] - 1)*$data['pageSize'];

		//设置查询SQL
		$sql = "SELECT * FROM ".$tableName." WHERE status IN(0, 1) ".$where." ORDER BY uid DESC LIMIT ".$offset.",".$data['pageSize'];

		//查询数据
		return $mysqlHandler->queryAllRows($sql);
	}

	/**
	 * 统计管理用户总条数
	 *
	 * @param str $userName 管理用户名
	 *
	 * return int 
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public function getTotalUserNumByUName($userName = ''){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(true, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin';

		//设置查询条件
		$where		= Utils::check_string($userName) ? ' AND username="'.addslashes($userName).'" ' : '';

		//设置查询SQL
		$sql		= 'SELECT COUNT(1) AS num FROM '.$tableName.' WHERE status IN(0, 1) '.$where;

		//查询数据
		$totalNum 	= $mysqlHandler->queryFirstRow($sql);

		return $totalNum['num'];
	}

	/**
	 * 提交新增管理用户
	 *
	 * @param str $param['userName']   用户名
	 * @param str $param['password']   密码
	 * @param str $param['realName']   真实姓名
	 * @param int $param['roleId']	   所属角色id
	 *
	 * @return int
	 *
	 * @param addTIme 2018-03-05
	 * @param author  ChengBo
	 */
	public function addAdminUser($param){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(true, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin';

		//设置入库参数
		$insParam['username'] = $param['userName'];
		$insParam['password'] = $param['password'];
		$insParam['realname'] = $param['realName'];
		$insParam['roleid']   = $param['roleId'];
		$insParam['email']    = $param['email'];
		$insParam['encrypt']  = $param['saltStr'];
		$insParam['ctime']    = $param['ctime'];

		//新增数据入库
		$ret	=  $mysqlHandler->insert($insParam, $tableName);
		if($ret === false){
			CLog::fatal('msg[update insert mg_admin] param[aid:'.$auid.' sql:'.sprintf($sql, $tableName).']');
			return 0;
		}

		//返回插入的id
		return $mysqlHandler->getLastInsertID();
	}

	/**
	 * 更新管理用户
	 *
	 * @param int $updParam['adminUid'] 用户名uid
	 * @param int $updParam['status']   用户状态
	 *
	 * @return Boolean
	 *
	 * @param addTIme 2018-03-05
	 * @param author  ChengBo
	 */
	public function updAdminUserStatusByUid($updParam){
		//定位数据库连接
		$mysqlHandler = $this->getDbResource(true, NULL);
		
		//定位数据表
		$tableName = 'mg_admin';
		
		$updSql    = 'UPDATE '.$tableName.' SET status=%d WHERE uid=%d';
		$exeState  = $mysqlHandler->doUpdateQuery($updSql, $updParam['status'], $updParam['adminUid']);

		return $mysqlHandler->getAffectedRows();
	}

	/**
	 * 更新管理用户信息
	 *
	 * @param['adminUid']	int 管理用户uid
	 * @param['roleId']	int 管理用户角色id
	 * @param['userName']	str 用户名
	 * @param['realName']   str 真实姓名
	 * @param['password']   str 登陆密码
	 * @param['encrypt']	str 混淆码
	 *
	 * return int
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function updAdminUserInfo($updParam){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(true, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin';
		
		$updSql    	= 'UPDATE '.$tableName.' SET ';
		$updSql	       .= empty($updParam['roleId']) ? '' : ' roleid='.$updParam['roleId'].',';
		$updSql        .= empty($updParam['userName']) ? '' : 'username="'.addslashes($updParam['userName']).'",';
		$updSql        .= empty($updParam['realName']) ? '' : 'realname="'.addslashes($updParam['realName']).'",';
		$updSql        .= empty($updParam['encrypt']) ? '': 'password="'.addslashes($updParam['password']).'",encrypt="'.addslashes($updParam['encrypt']).'",';
		$updSql         = trim($updSql, ',').' WHERE uid='.$updParam['adminUid'];
		
		//执行sql
		$exeState	= $mysqlHandler->doUpdateQuery($updSql);

		//返回影响行数
		return $mysqlHandler->getAffectedRows();
	}

	/**
	 * 按条件查询管理用户数据
	 *
	 * @param['page'] 	int 当前页码
	 * @param['pageSize']	int 每页条数
	 * @param['roleId']	int 所属角色id
	 * @param['userName']	str 管理用户名
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function getAdminUserListByWhere($param){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(true, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin';

		//设置查询条件
		$where = empty($param['roleId']) ? '': ' roleid='.$param['roleId'].' AND';
		$where.= empty($param['userName']) ? '': ' username="'.addslashes($param['userName']).'" AND';
		$where = empty($where) ? '' : ' WHERE '.trim($where, 'AND');

		//设置页面偏移
		$offset = ($param['page']-1)*$param['pageSize'];
		
		//设置查询sql
		$sql 	= 'SELECT * FROM '.$tableName.' '.$where.' ORDER BY uid DESC LIMIT '.$offset.','.$param['pageSize'];
	
		//执行sql语句
		return $mysqlHandler->queryAllRows($sql);
	}

	/**
	 * 按条件查询管理用户数据
	 *
	 * @param['roleId']	int 所属角色id
	 * @param['userName']	str 管理用户名
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function getTotalUserNumByWhere($param){
		//定位数据库连接
		$mysqlHandler	= $this->getDbResource(true, NULL);
		
		//定位数据表
		$tableName	= 'mg_admin';

		//设置查询条件
		$where = empty($param['roleId']) ? '': ' roleid='.$param['roleId'].' AND';
		$where.= empty($param['userName']) ? '': ' username="'.addslashes($param['userName']).'" AND';
		$where = empty($where) ? '' : ' WHERE '.trim($where, 'AND');

		//设置查询sql
		$sql 	= 'SELECT COUNT(1) AS num FROM '.$tableName.' '.$where;
	
		//执行sql语句
		$totalNum = $mysqlHandler->queryFirstRow($sql);

		return $totalNum['num'];
	}
}
