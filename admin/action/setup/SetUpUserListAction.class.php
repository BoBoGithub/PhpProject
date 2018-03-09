<?php
/**
 * 管理用户列表
 * 
 * @package admin
 * @param   addTime 2018-03-05
 * @param   author  ChengBo
 */
class SetUpUserListAction extends WebBaseAction {
	/**
	 * 管理用户列表
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public function doGet(){
		//设置模板赋值
		$this->setTplName("setup/userList.tpl");
		$this->assign("title","管理用户列表");

		$this->buildPage();
	}
}

