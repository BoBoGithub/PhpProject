<?php
/**
 * 后台管理 - 权限相关Service类
 * 
 * @param   addTime 2018-03-05
 * @param   author  ChengBo
 */
class PermitService{
	private static $instance = NULL;
	
	//单例模式
	public static function getInstance(){
		if (!isset(self::$instance)){
			self::$instance = new PermitService();
		}
		return self::$instance;
	}
	
	protected function __construct(){}

	/**
	 * 获取所有角色数据
	 *
	 * @param['page']	int 当前页码
	 * @param['pageSize'] 	int 每页条数
	 *
	 * return array()
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo 
	 */
	public function getRoleListData($param = array()){
		//设置查询页码
		$data['page']		= Utils::check_int($param['page'], 1) ? $param['page'] : 1;
		$data['pageSize']	= Utils::check_int($param['pageSize'], 1)? $param['pageSize'] : 10;

		//设置角色状态
		$data['status']		= array(0, 1);
		
		//查询数据
		return AdminRoleDao::getInstance()->getRoleListData($data);
	}

	/**
	 * 汇总角色总条数
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function getTotalRoleNum(){
		//设置角色状态
		$status = array(0, 1);

		//调取角色总条数
		$totalNum = AdminRoleDao::getInstance()->countRoleNum($status);

		return $totalNum;
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
	public function addRoleInfo($addParam = array()){
		//检查角色名称
		if(!Utils::check_string($addParam['roleName'])){
			throw new Exception(CommonConst::ADMIN_ROLE_NAME_ERROR." addRoleInfo roleName error");
		}

		//检查角色描述
		if(!Utils::check_string($addParam['roleDesc'])){
			throw new Exception(CommonConst::ADMIN_ROLE_DESC_ERROR." addRoleInfo roleDesc error");
		}

		//检查角色状态
		if(!Utils::check_int($addParam['roleStatus']) || !in_array($addParam['roleStatus'], array(0, 1))){
			throw new Exception(CommonConst::ADMIN_ROLE_STATUS_ERROR." addRoleInfo roleStatus error");
		}

		//角色入库
		$insertId = AdminRoleDao::getInstance()->addRoleInfo($addParam);

		return $insertId;
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
	public function updRoleInfoById($updParam = array()){
		//检查角色id参数
		if(!Utils::check_int($updParam['roleId'])){
			throw new Exception(CommonConst::ADMIN_ROLE_ID_ERROR." updRoleInfoById roleId error");
		}

		//检查角色名称
		if(!Utils::check_string($updParam['roleName'])){
			throw new Exception(CommonConst::ADMIN_ROLE_NAME_ERROR.' updRoleInfoById role name error');
		}

		//检查角色描述
		if(!Utils::check_string($updParam['roleDesc'])){
			throw new Exception(CommonConst::ADMIN_ROLE_DESC_ERROR.' updRoleInfoById role desc error');
		}

		//检查角色状态
		if(!Utils::check_int($updParam['roleStatus']) || !in_array($updParam['roleStatus'], array(-1, 0, 1))){
			throw new Exception(CommonConst::ADMIN_ROLE_STATUS_ERROR.' updRoleInfoById role status error');
		}

		//更新角色数据
		$updRowNum = AdminRoleDao::getInstance()->updRoleInfoById($updParam);

		return $updRowNum;
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
	public function delRoleInfoById($roleId = 0){
		//检查角色id参数
		if(!Utils::check_int($roleId)){
			throw new Exception(CommonConst::ADMIN_ROLE_ID_ERROR." delRoleInfoById roleId error");
		}

		//删除数据
		$updRowNum = AdminRoleDao::getInstance()->delRoleInfoById($roleId);

		return $updRowNum;
	}

	/**
	 * 获取树形结构的菜单列表数据
	 * 
	 * @return array()
	 * 
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	public function getMenuTree(){
		//
		$menuList = array();

		//获取菜单整理后的数据
		$this->getChildMenuList(0, $menuList, "");
				
		//返回处理结果
		return $menuList;
	}
	
	/**
	 * 调取菜单的子菜单数据
	 * 
	 * @param pid
	 *
	 * @return array()
	 * 
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	private function getChildMenuList($pid, &$menuList, $spacer){
		//调取菜单数据
		$menuListData = $this->getAdminMenuData();
		if(count($menuListData) <= 0){
			return ;
		}

		//设置层级前缀
		$spacer = ($pid == 0 ? "" : $spacer."　│");
		
		//设施菜单个数
		$total = count($menuList);
		
		//提取子菜带数据
		for($i = 0; $i < count($menuListData); $i++){
			if($pid == $menuListData[$i]['parentid']){
				//实例化菜单
				$menuMap = array();

				//设置菜单值
				$menuMap['id']		= $menuListData[$i]['id'];
				$menuMap['name']	= $spacer."　├─".$menuListData[$i]['name'];
				$menuMap['parentid']	= $menuListData[$i]['parentid'];
				$menuMap['url']		= $menuListData[$i]['url'];
				
				//设置权限时页面上使用
				$menuMap['pnode']	= $menuListData[$i]['parentid'] == 0 ? "" : "class='child-of-node-".$menuListData[$i]['parentid']."'";
				$menuMap['level']	= $this->getMenuLevel($menuListData[$i]['id'], 0);
				
				//追加子菜单
				$menuList[]		= $menuMap;
				
				//处理子菜单
				$this->getChildMenuList($menuListData[$i]['id'], $menuList, $spacer);
			}
		}
		
		//替换最后一个结尾前置标签 
		if($total < count($menuList)){
			$menuList[count($menuList)-1]['name'] = str_replace('├─', '└─', $menuList[count($menuList)-1]['name']);
		}
	}
	
	/**
	 * 获取指定菜单的层级数
	 * 
	 * @param menuId
	 * @param level
	 *
	 * @return int
	 * 
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	private function getMenuLevel($menuId, $level){
		//调取菜单数据
		$menuListData = $this->getAdminMenuData();
		if(count($menuListData) <= 0){
			return $level;
		}
		
		//循环菜单查找
		for($i=0; $i< count($menuListData);$i++){
			if($menuId == $menuListData[$i]['id']){
				if($menuListData[$i]['parentid'] == 0){
					return $level;
				}

				return $this->getMenuLevel($menuListData[$i]['parentid'], ++$level);
			}
		}
		
		return $level;
	}
	
	/**
	 * 调取菜单数据
	 * 
	 * @return
	 * 
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	public function getAdminMenuData(){
		//实例化Redis缓存实例
		$redisCache = RedisWrapper::getInstance();

		//从缓存中调取数据
		$menuListCacheData = $redisCache->get(CommonConst::ADMIN_MENU_CACHE_KEY);
		
		//检查是否存在缓存
		if(!empty($menuListCacheData)){
			return json_decode($menuListCacheData, true);
		}
		
		//调取菜单数据列表
		$menuListData = AdminMenuDao::getInstance()->getMenuList();
		
		//设置缓存
		$redisCache->set(CommonConst::ADMIN_MENU_CACHE_KEY, json_encode($menuListData), 86400*30);
		
		//返回菜单数据
		return $menuListData;
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
	public function getMenuDataById($menuId = 0){
		//检查参数
		if(!Utils::check_int($menuId)){
			throw new Exception(CommonConst::ADMIN_MENU_ID_ERROR.' getMenuDataById menuId error');
		}

		//查询数据
		$menuData = AdminMenuDao::getInstance()->getMenuDataById($menuId);

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
		//检查父级id
		if(!Utils::check_int($param['parentId'])){
			throw new Exception(CommonConst::ADMIN_MENU_PID_ERROR.' addMenuData parentId error');
		}

		//检查菜单名称
		if(!Utils::check_string($param['menuName'])){
			throw new Exception(CommonConst::ADMIN_MENU_NAME_ERROR.' addMenuData menuName error');
		}

		//检查请求地址
		if(!Utils::check_string($param['requestUrl'])){
			throw new Exception(CommonConst::ADMIN_MENU_REQUEST_URL_ERROR.' addMenuData requestUrl error');
		}

		//检查菜单状态
		if(!Utils::check_int($param['menuStatus']) || !in_array($param['menuStatus'], array(0, 1))){
			throw new Exception(CommonConst::ADMIN_MENU_STATUS_ERROR.' addMenuData status error');
		}

		//新增菜单数据
		$menuId = AdminMenuDao::getInstance()->addMenuData($param);

		//清楚菜单缓存
		if($menuId){
			//从缓存中删除数据
			RedisWrapper::getInstance()->del(CommonConst::ADMIN_MENU_CACHE_KEY);
		}

		return $menuId;
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
	public function updMenuData($param = array()){
		//检查菜单id参数
		if(!Utils::check_int($param['menuId'])){
			throw new Exception(CommonConst::ADMIN_MENU_ID_ERROR.' updMenuData id error');
		}

		//检查菜单父级id
		if(!Utils::check_int($param['parentId'])){
			throw new Exception(CommonConst::ADMIN_MENU_PARENT_ID_ERROR.' updMenuData parentId error');
		}

		//检查菜单名称
		if(!Utils::check_string($param['menuName'])){
			throw new Exception(CommonConst::ADMIN_MENU_NAME_ERROR.' addMenuData menuName error');
		}

		//检查请求地址
		if(!Utils::check_string($param['requestUrl'])){
			throw new Exception(CommonConst::ADMIN_MENU_REQUEST_URL_ERROR.' addMenuData requestUrl error');
		}

		//检查菜单状态
		if(!Utils::check_int($param['menuStatus']) || !in_array($param['menuStatus'], array(0, 1))){
			throw new Exception(CommonConst::ADMIN_MENU_STATUS_ERROR.' addMenuData status error');
		}

		//更新菜单数据
		$affectNum = AdminMenuDao::getInstance()->updMenuData($param);

		//更新菜单缓存 
		if($affectNum){
			//从缓存中删除数据
			RedisWrapper::getInstance()->del(CommonConst::ADMIN_MENU_CACHE_KEY);
		}

		return $affectNum;
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
		//检查菜单id
		if(!Utils::check_int($menuId)){
			throw new Exception(CommonConst::ADMIN_MENU_ID_ERROR.' delMenuData id error');
		}

		//更新菜单为删除状态
		$affectNum = AdminMenuDao::getInstance()->delMenuData($menuId);

		//更新菜单缓存 
		if($affectNum){
			//从缓存中删除数据
			RedisWrapper::getInstance()->del(CommonConst::ADMIN_MENU_CACHE_KEY);
		}

		return $affectNum;
	}

	/**
	 * 根据角色id查询权限数据
	 * 
	 * @param $roleId
	 *
	 * return array()
	 * 
	 * @param addTime 2018-03-08
	 * @param author  ChengBo
	 */
	public function getAdminRolePrivByRoleId($roleId){
		//检查角色id参数
		if(!Utils::check_int($roleId)){
			throw new Exception(CommonConst::ADMIN_ROLE_ID_ERROR." getAdminRolePrivByRoleId roleId error");
		}
		
		//实例化Redis缓存实例
		$redisCache = RedisWrapper::getInstance();

		//从缓存中调取数据
		$privList = $redisCache->get(CommonConstant.ADMIN_ROLE_PRIV_CACHE_KEY.':'.$roleId);
		if(!empty($privList)){
			return json_decode($privList, true);
		}
		
		//设置返回变量
		$privLists = array();
		
		//查询角色数据
		$rolePrivList = AdminRolePrivDao::getInstance()->getAdminRolePrivByRoleId($roleId);
		if(!is_array($rolePrivList) || empty($rolePrivList)){
			return $privLists;
		}
	
		//提取权限url
		for($i=0; $i<count($rolePrivList);$i++){
			$privLists[] = $rolePrivList[$i]['url'];
		}
		
		//设置角色的权限缓存
		$redisCache->set(CommonConstant.ADMIN_ROLE_PRIV_CACHE_KEY.':'.$roleId, json_encode($privLists), 86400*20);
		
		return $privLists;
	}

	/**
	 * 设置角色权限
	 *
	 * @param['roleId']  int 角色id
	 * @param['menuIds'] arr 菜单ids
	 *
	 * return int
	 * 
	 * @param addTime 2018-03-08
	 * @param author  ChengBo
	 */
	public function setAdminRolePriv($data = array()){
		//检查角色id参数
		if(!Utils::check_int($data['roleId'])){
			throw new Exception(CommonConst::ADMIN_ROLE_ID_ERROR." setAdminRolePriv roleId error");
		}

		//检查菜单id
		if(empty($data['menuIds']) || !is_array($data['menuIds'])){
			throw new Exception(CommonConst::ADMIN_MENU_ID_ERROR.' setAdminRolePriv menuId error');
		}

		//循环挨个检查
		foreach($data['menuIds'] as $k=>$v){
			if(!Utils::check_int($v)){
				throw new Exception(CommonConst::ADMIN_MENU_ID_ERROR.' setAdminRolePriv menuIds error');
			}
		}

		//查找指定菜单id的菜单数据
		$menuList = $this->getAddAdminRoPrivByMenuIds($data['roleId'], $data['menuIds']);
				
		//删除当前的角色权限
		AdminRolePrivDao::getInstance()->delPrivByRoleId($data['roleId']);

		//权限批量入库
		AdminRolePrivDao::getInstance()->batchInsertRolePriv($menuList);

		//删除角色权限缓存
		RedisWrapper::getInstance()->del(CommonConstant.ADMIN_ROLE_PRIV_CACHE_KEY.':'.$data['roleId']);

		return true;
	}

	/**
	 * 获取指定菜单id的菜单数据
	 * 
	 * @param $roleId  int
	 * @param $menuIds arr
	 * 
	 * @param addTime 2018-03-08
	 * @param author  ChengBo
	 */
	private function getAddAdminRoPrivByMenuIds($roleId = 0, $menuIds = array()){
		//实例化返回结果
		$privList = array();
		if(empty($menuIds) || !Utils::check_int($roleId)){
			return $privList;
		}
		
		//获取菜单数据
		$menuListData = $this->getAdminMenuData();

		//提取菜单数据
		foreach($menuListData as $k=>$v){
			if(in_array($v['id'], $menuIds)){
				//实例化权限操作Bean
				$tmpPriv = array();
				
				//设置入库参数
				$tmpPriv['roleId']	= $roleId;
				$tmpPriv['url']		= $v['url'];
				
				//追加到返回结果中
				$privList[] = $tmpPriv;
			}
		}
		
		return $privList;
	}

	/**
	 * 查询指定pid的菜单数据
	 * 
	 * @param pid
	 *
	 * return array
	 * 
	 * @param addTime 2018-03-08
	 * @param author  ChengBo
	 */
	public function getAdminMenuByPid($pid = 0){
		//设置返回结果
		$menuList = array();
		
		//调取菜单数据
		$menuListData = $this->getAdminMenuData();
		if(empty($menuListData)){
			return $menuList;
		}
		
		//遍历菜单提取菜单数据
		foreach($menuListData as $adminMenu){
			if($adminMenu['parentid'] == $pid){
				$menuList[] = $adminMenu;
			}
		}
		
		return $menuList;
	}

	/**
	 * 检查是否有访问权限
	 *
	 * @param int $roleId 角色id
	 * @param str $url    请求url
	 * 
	 * return Boolean
	 *
	 * @param addTime 2018-03-09
	 * @param author  ChengBo 
	 */
	public function checkRolePriv($roleId = 0, $url = ''){
		//检查参数
		if(!Utils::check_int($roleId) || !Utils::check_string($url)){
			return false;
		}

		//检查是否时超级管理员
		if($roleId == 1){
			return true;
		}

		//调取指定角色的权限数据
		$rolePrivData = $this->getAdminRolePrivByRoleId($roleId);
		
		//返回是否有访问权限
		return in_array($url, $rolePrivData);
	}
}
