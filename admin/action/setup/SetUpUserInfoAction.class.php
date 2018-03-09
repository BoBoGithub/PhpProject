<?php
/**
 * 后台设置-用户信息
 * 
 * @package admin
 * @param   addTime 2018-03-02
 * @param   author  ChengBo
 */
class SetUpUserInfoAction extends WebBaseAction {
	/**
	 * 后台设置-用户信息
	 *
	 * @param addTIme 2018-03-02
	 * @param author  ChengBo
	 */
	public function doGet(){
		//实例化操作管理用户Service
		$adminUserService	= AdminUserService::getInstance();
		
		//查询当前用户的基本信息
		$adminUserInfoData	= $adminUserService->getAdminUserInfoById($this->adminUid);
		
		//调取用户的角色数据
		$userRoleData		= $adminUserService->getUserRoleInfoById($this->adminUserInfo['roleid']);
		
		//模板赋值
		$this->setTplName("setup/SetUpUserInfo.tpl");
		$this->assign('userName', $adminUserInfoData['username']);
		$this->assign('realName', $adminUserInfoData['realname']);
		$this->assign('email', $adminUserInfoData['email']);
		$this->assign('roleName', $userRoleData['rolename']);

		//渲染页面
		$this->buildPage();
	}
}

