<?php
/**
 * 后台管理 - 修改角色页面
 * 
 * @package admin
 * @param   addTime 2018-03-06
 * @param   author  ChengBo
 */
class SetUpRoleEditAction extends WebBaseAction {
	/**
	 * 后台管理 - 修改角色页面
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function doGet(){
		//接收角色参数
		$roleId = $this->get('roleId');

		//调取当前角色的数据
		$roleData = AdminUserService::getInstance()->getUserRoleInfoById($roleId);

		//设置模板赋值
		$this->setTplName("setup/RoleEdit.tpl");
		$this->assign('roleData', $roleData);

		//渲染页面
		$this->buildPage();
	}
}

