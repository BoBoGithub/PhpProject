<?php
/**
 * 后台管理用户Service类
 * 
 * @param   addTime 2018-03-01
 * @param   author  ChengBo
 */
class AdminUserService{
	private static $instance = NULL;
	
	//单例模式
	public static function getInstance(){
		if (!isset(self::$instance)){
			self::$instance = new AdminUserService();
		}
		return self::$instance;
	}
	
	protected function __construct(){}

	/**
	 * 管理用户登录操作
	 *
	 * @param addTIme 2018-03-01
	 * @param author  ChengBo
	 */
	public function checkUserLogin($loginInfo = array()){
		//参数检查
		if(!Utils::check_string($loginInfo['username']) || !Utils::check_string($loginInfo['password'])){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR."  param(username or password) error");
		}

		//调取用户数据
		$adminUserData = AdminUserDao::getInstance()->getAdminUserInfoByName($loginInfo['username']);
		if(empty($adminUserData)){
			throw new Exception(CommonConst::ADMIN_LOGIN_USER_NOT_EXIST."  adminuser not exixts[".$loginInfo['username']."]");
		}
		
		//检查密码是否正确
		if($adminUserData['password'] != md5(md5($loginInfo['password']).$adminUserData['encrypt'])){
			throw new Exception(CommonConst::ADMIN_LOGIN_USER_PWD_ERROR." admin user login pwd error");
		}
		
		//设置加密信息
		$userInfo['uid']	= $adminUserData['uid'];
		$userInfo['uname']	= $adminUserData['username'];

		//设置登录Cookie标识
		setcookie(CommonConst::ADMIN_USER_LOGIN_KEY, Utils::authcode(json_encode($userInfo), 'ENCODE'), (time()+86400), '/', 'admin.test.com', ($_SERVER['SERVER_PORT'] == '443' ? 1 : 0), true);
		
		//设置登陆时间
		$adminUserData['loginTime']	= time();
		$adminUserData['activeTime']	= time();

		//设置用户缓存 缓存一天
		RedisWrapper::getInstance()->set(CommonConst::ADMIN_USER_LOGIN_KEY.':'.$adminUserData['uid'], json_encode($adminUserData), 86400);
		
		return true;
	}

	/**
	 * 用户退出操作
	 *
	 * @param int $uid 登陆用户id
	 * 
	 * @param addTime 2018-03-09
	 * @param author  ChengBo
	 */
	public function adminUserLogOut($uid = 0){
		//清楚用户缓存
		RedisWrapper::getInstance()->del(CommonConst::ADMIN_USER_LOGIN_KEY.':'.$uid);

		//清楚登陆cookie
		setcookie(CommonConst::ADMIN_USER_LOGIN_KEY, '', (time()-86400), '/', 'admin.test.com', ($_SERVER['SERVER_PORT'] == '443' ? 1 : 0), true);

		return true;
	}

	/**
	 * 检查用户登录状态
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-02
	 * @param author  ChengBo
	 */
	public function checkUserLoginStatus(){
		 //提取cookie中的值
		 if(empty($_COOKIE[CommonConst::ADMIN_USER_LOGIN_KEY])){
			 return array();
		 }
		 
		 //解密用户信息
		 $userInfo = Utils::authcode($_COOKIE[CommonConst::ADMIN_USER_LOGIN_KEY], 'DECODE');
		 
		 //提取用户信息
		 $userInfoData = json_decode($userInfo, true);
		 if(!Utils::check_int($userInfoData['uid'])){
		 	return array();
		 }

		 //从缓存中调取数据
		 $adminUserInfoCache = RedisWrapper::getInstance()->get(CommonConst::ADMIN_USER_LOGIN_KEY.':'.$userInfoData['uid']);
		 if(empty($adminUserInfoCache)){
		 	return array();
		 }

		 //解析缓存数据
		 $adminUserData = json_decode($adminUserInfoCache, true);

		 //检查登陆状态是否快过期
		 if(time()-$adminUserData['activeTime'] > 3600){
			 //延长活跃时间
			 $adminUserData['activeTime'] = time();

			 //更新缓存
			 RedisWrapper::getInstance()->set(CommonConst::ADMIN_USER_LOGIN_KEY.':'.$adminUserData['uid'], json_encode($adminUserData), 86400);
		 }
		 
		 //返回管理用户信息
		 return $adminUserData;
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
	 public function getAdminUserInfoById($adminUid = 0){
		  //检查用户uid参数
		  if(!Utils::check_int($adminUid)){
			  throw new Exception(CommonConst::ADMIN_UID_ERROR." getAdminUserInfoById admin user uid error");
		  }
		  
		  //调取管理用户数据
		  $adminUserData = AdminUserDao::getInstance()->getAdminUserInfoById($adminUid);
		  
		  return $adminUserData;
	 }

	/**
	 * 调取指定角色id的数据
	 *
	 * @param int $id
	 *
	 * @param addTime 2018-03-02
	 * @param author  ChengBo
	 */
	public function getUserRoleInfoById($id = 0){
		//检查用户uid参数
		if(!Utils::check_int($id)){
			throw new Exception(CommonConst::ADMIN_ROLE_ID_ERROR." getUserRoleInfoById admin role id error");
		}
		
		//调取角色数据
		$userRoleInfo = AdminRoleDao::getInstance()->getRoleInfoByid($id);
		
		return $userRoleInfo;
	}

	/**
	 * 更新指定管理用户的信息
	 *
	 * @param['adminUid'] int 要更新的用户uid
	 * @param['realName'] str 用户的真实姓名
	 * @param['email']    str 用户邮箱地址
	 *
	 * return int
	 *
	 * @param addTime 2018-03-02
	 * @param author  ChengBo
	 */
	public function updAdminUserInfoByUid($updParam = array()){
		//检查管理用户uid
		if(!Utils::check_int($updParam['adminUid'])){
			throw new Exception(CommonConst::ADMIN_UID_ERROR." updAdminUserInfoByUid adminUid error");
		}

		//检查更新的用户名
		if(!Utils::check_string($updParam['realName'])){
			throw new Exception(CommonConst::ADMIN_REAL_NAME_ERROR." updAdminUserInfoByUid realName error");
		}

		//检查更新的邮箱
		if(!Utils::check_email($updParam['email'])){
			throw new Exception(CommonConst::ADMIN_EMAIL_ERROR." updAdminUserInfoByUid email error");
		}

		//更新数据库
		$updStatus = AdminUserDao::getInstance()->updAdminUserInfoByUid($updParam);

		return $updStatus;
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
	public function getAdminUserList($param = array()){
		//检查管理员用户名
		if(isset($param['userName']) && Utils::check_string($param['userName'])){
			$data['userName'] = $param['userName'];
		}

		//设置分页偏移量
		$data['page'] 		= Utils::check_int($param['page'], 1) ? intval($param['page']) : 1;
		$data['pageSize']	= Utils::check_int($param['pageSize'], 1) ? intval($param['pageSize']) : 10;

		//调取数据
		$userListData 		= AdminUserDao::getInstance()->getAdminUserList($data);

		return $userListData;
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
		//检查查询参数
		$userName = Utils::check_string($userName) ? $userName : '';

		//汇总总条数数据
		$totalNum = AdminUserDao::getInstance()->getTotalUserNumByUName($userName);

		return $totalNum;
	}

	/**
	 * 提交新增管理用户
	 *
	 * @param str $userName	   用户名
	 * @param str $password	   密码
	 * @param str $realName	   真实姓名
	 * @param int $roleId	   所属角色id
	 *
	 * @return Boolean
	 *
	 * @param addTIme 2018-03-05
	 * @param author  ChengBo
	 */
	public function addAdminUserInfo($param = array()){
		//检查用户名
		if(!Utils::check_string($param['userName'])){
			throw new Exception(CommonConst::ADMIN_USER_NAME_ERROR." addAdminUserInfo userName param error");
		}

		//检查密码
		if(!Utils::check_string($param['password'])){
			throw new Exception(CommonConst::ADMIN_USER_PWD_ERROR." addAdminUserInfo password param error");
		}

		//检查真实姓名
		if(!Utils::check_string($param['realName'])){
			throw new Exception(CommonConst::ADMIN_REAL_NAME_ERROR." addAdminUserInfo realName param error");
		}

		//检查角色
		if(!Utils::check_int($param['roleId'])){
			throw new Exception(CommonConst::ADMIN_ROLE_ID_ERROR." addAdminUserInfo roleId param error");
		}

		//设置新用户入库参数
		$param['email']		= $param['userName'].'@migang.com';
		$param['saltStr']	= Utils::getRandomString(6);
		$param['password']	= md5(md5($param['password']).$param['saltStr']);
		$param['ctime']		= time();

		//新用户入库
		$uid	= AdminUserDao::getInstance()->addAdminUser($param);

		return $uid;
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
		//检查更新的用户uid
		if(!Utils::check_int($updParam['adminUid'])){
			throw new Exception(CommonConst::ADMIN_UID_ERROR." updAdminUserStatusByUid adminUid error");
		}

		//检查用户状态
		if(!Utils::check_int($updParam['status'], -1) || !in_array($updParam['status'], array(-1, 0, 1))){
			throw new Exception(CommonConst::ADMIN_USER_STATUS_ERROR." updAdminUserStatusByUid status param error");
		}

		//更新用户状态
		$updRet = AdminUserDao::getInstance()->updAdminUserStatusByUid($updParam);

		return $updRet;
	}

	/**
	 * 更新管理用户信息
	 *
	 * @param['adminUid']	int 管理用户uid
	 * @param['roleId']	int 管理用户角色id
	 * @param['userName']	str 用户名
	 * @param['realName']   str 真实姓名
	 * @param['password']   str 登陆密码
	 *
	 * return int
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function updAdminUserInfo($updParam = array()){
		//设置更新参数变量
		$dataParam = array();

		//检查管理用户uid 
		if(!Utils::check_int($updParam['adminUid'])){
			throw new Exception(CommonConst::ADMIN_UID_ERROR." updAdminUserInfo adminUid error");
		}else{
			$dataParam['adminUid'] = $updParam['adminUid'];
		}
		
		//检查角色id
		if(Utils::check_int($updParam['roleId'])){
			$dataParam['roleId'] = $updParam['roleId'];
		}

		//检查用户名
		if(Utils::check_string($updParam['userName'])){
			$dataParam['userName'] = $updParam['userName'];
		}

		//检查真实姓名
		if(Utils::check_string($updParam['realName'])){
			$dataParam['realName'] = $updParam['realName'];
		}

		//检查修改密码
		if(Utils::check_string($updParam['password'])){
			$dataParam['encrypt']	= Utils::getRandomString(6);
			$dataParam['password']	= md5(md5($updParam['password']).$dataParam['encrypt']);
		}

		//检查更新参数
		if(count($dataParam) <= 1){
			throw new Exception(CommonConst::COMMON_PARAM_ERROR." updAdminUserInfo upd param error ");
		}

		//更新数据
		$updStatus = AdminUserDao::getInstance()->updAdminUserInfo($dataParam);

		return $updStatus;
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
	public function getAdminUserListByWhere($param = array()){
		//设置查询条件 角色id
		if(Utils::check_int($param['roleId'])){
			$selectParam['roleId'] = $param['roleId'];
		}

		//设置用户名查询
		if(Utils::check_string($param['userName'])){
			$selectParam['userName'] = $param['userName'];
		}

		//设置当前页数
		$selectParam['page']	= Utils::check_int($param['page']) ? intval($param['page']) : 1;
		$selectParam['pageSize']= Utils::check_int($param['pageSize']) ? $param['pageSize']: 10;

		//调取管理用户数据
		$adminUserData = AdminUserDao::getInstance()->getAdminUserListByWhere($selectParam);

		return $adminUserData;
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
	public function getTotalUserNumByWhere($param = array()){
		//设置查询条件 角色id
		if(Utils::check_int($param['roleId'])){
			$selectParam['roleId'] = $param['roleId'];
		}

		//设置用户名查询
		if(Utils::check_string($param['userName'])){
			$selectParam['userName'] = $param['userName'];
		}

		//调取管理用户总条数
		$totalNum = AdminUserDao::getInstance()->getTotalUserNumByWhere($selectParam);

		return $totalNum;
	}
}
