<?php
/**
 * 主页ACTION
 * 
 * @package admin
 * @param   addTime 2018-02-28
 * @param   author  ChengBo
 */
class IndexAction extends WebBaseAction {
	/**
	 * 主页ACTION
	 *
	 * @param addTIme 2018-02-28
	 * @param author  ChengBo
	 */
	public function doGet(){
		//设置用户拥有的一级菜单变量
		$userBigMenuList = $this->getUserBigMenuList($this->adminUserInfo['roleid'], $this->adminUserInfo['uid']);

		//设置模板
		$this->setTplName("mainIndex.tpl");
		$this->assign('userInfo', $this->adminUserInfo);
		$this->assign('userBigMenuList', $userBigMenuList);

		//渲染页面 
		$this->buildPage();
	}

	/**
	 * 设置用户拥有的一级菜单变量
	 *
	 * @param int $roleId   角色id
	 * @param int $adminUid 用户uid
	 *
	 * @param addTime 2018-03-08
	 * @param addTime ChengBo
	 */
	public function getUserBigMenuList($roleId = 0, $adminUid = 0){
		//设置用户拥有的一级菜单变量
		$userBigMenuList = array();
						
		//调取一级菜单数据
		$bigMenuList = PermitService::getInstance()->getAdminMenuByPid(0);
							
		//调取当前角色下的菜单数据
		$menuPriveList = PermitService::getInstance()->getAdminRolePrivByRoleId($roleId);
										
		//提取用户的一级菜单 超级管理有有全部权限
		if(!empty($bigMenuList) && (!empty($menuPriveList) || $adminUid == 1)){
			foreach($bigMenuList as $adminMenu){
				if($adminMenu['status'] ==  0 && ($adminUid == 1 || in_array($adminMenu['url'], $menuPriveList))){
					$userBigMenuList[] = $adminMenu;
				}
			}
		}
					
		return $userBigMenuList;
	}


	/**
	 * 调取用户数据
	 *
	 * @param int uid 用户uid
	 *
	 * @param addTime 2018-03-01
	 * @param author  ChengBo
	 */
	 public function getUserInfoByUid($uid = 0){
		 //实例化用户Service
		 $userData = UserService::getInstance()->getUserInfoByUid($uid);
		 
		 return $userData;
	 }
}

