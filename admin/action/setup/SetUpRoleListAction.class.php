<?php
/**
 * 管理角色列表
 * 
 * @package admin
 * @param   addTime 2018-03-06
 * @param   author  ChengBo
 */
class SetUpRoleListAction extends WebBaseAction {
	/**
	 * 管理角色列表
	 *
	 * @param addTime 2018-03-06
	 * @param author  ChengBo
	 */
	public function doGet(){
		//设置模板赋值
		$this->setTplName("setup/RoleList.tpl");

		//模板渲染
		$this->buildPage();
	}
}

