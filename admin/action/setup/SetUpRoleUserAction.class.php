<?php
/**
 * 管理角色下的用户列表
 * 
 * @package admin
 * @param   addTime 2018-03-06
 * @param   author  ChengBo
 */
class SetUpRoleUserAction extends WebBaseAction {
	/**
	 * 管理角色下用户列表
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function doGet(){
		//接收参数
		$roleId = $this->get('roleId');

		//设置模板赋值
		$this->setTplName("setup/RoleUser.tpl");
		$this->assign('roleId', intval($roleId));

		//渲染页面
		$this->buildPage();
	}
}

