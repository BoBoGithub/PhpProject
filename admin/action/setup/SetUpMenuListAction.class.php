<?php
/**
 * 管理菜单列表
 * 
 * @package admin
 * @param   addTime 2018-03-07
 * @param   author  ChengBo
 */
class SetUpMenuListAction extends WebBaseAction {
	/**
	 * 管理菜单列表
	 *
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	public function doGet(){
		//获取菜单数据
		$menuListData = PermitService::getInstance()->getMenuTree();

		//设置模板赋值
		$this->setTplName("setup/MenuList.tpl");
		$this->assign('menuListData', $menuListData);

		//渲染页面
		$this->buildPage();
	}
}
