<?php
/**
 * 管理后台-添加菜单
 * 
 * @package admin
 * @param   addTime 2018-03-07
 * @param   author  ChengBo
 */
class SetUpMenuAddAction extends WebBaseAction {
	/**
	 * 管理后台=添加菜单
	 *
	 * @param addTime 2018-03-07
	 * @param author  ChengBo
	 */
	public function doGet(){
		//接收参数
		$menuId = $this->get('menuId');

		//调取当前菜单数据
		$menuData	= PermitService::getInstance()->getMenuDataById($menuId);

		//调取菜单列表数据
		$menuListData	= PermitService::getInstance()->getMenuTree();

		//设置模板赋值
		$this->setTplName("setup/MenuAdd.tpl");
		$this->assign('menuData', $menuData);
		$this->assign('menuId', $menuId);
		$this->assign('menuListData', $menuListData);

		//渲染页面
		$this->buildPage();
	}
}
