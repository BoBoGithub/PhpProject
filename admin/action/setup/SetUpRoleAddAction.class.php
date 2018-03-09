<?php
/**
 * 管理角色列表 - 新增角色
 * 
 * @package admin
 * @param   addTime 2018-03-06
 * @param   author  ChengBo
 */
class SetUpRoleAddAction extends WebBaseAction {
	/**
	 * 管理角色列表 - 新增角色
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function doGet(){
		//设置模板赋值
		$this->setTplName("setup/RoleAdd.tpl");

		//渲染页面
		$this->buildPage();
	}
}

