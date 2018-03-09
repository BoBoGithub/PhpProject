<?php
/**
 * 管理用户列表 - 新增用户
 * 
 * @package admin
 * @param   addTime 2018-03-05
 * @param   author  ChengBo
 */
class SetUpUserAddAction extends WebBaseAction {
	/**
	 * 管理用户列表 - 新增用户
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public function doGet(){

		//获取用户角色数据
		$userRoleData = PermitService::getInstance()->getRoleListData();

		//设置模板赋值
		$this->setTplName("setup/userAdd.tpl");
		$this->assign('userRoleData', $userRoleData);

		//渲染模板
		$this->buildPage();
	}
}

