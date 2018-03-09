<?php
/**
 * 管理后台-修改菜单
 * 
 * @package admin
 * @param   addTime 2018-03-07
 * @param   author  ChengBo
 */
class SetUpMenuEditAction extends WebBaseAction {
	/**
	 * 管理后台=修改菜单
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

		//提取父级菜单数据
		$parentMenu['name'] = '作为一级菜单';
		$parentMenu['id']   = 0;
		if(!empty($menuData['parentid'])){
			foreach($menuListData as $k=>$v){
				if($menuData['parentid'] == $v['id']){
					$parentMenu = $v;
					break;
				}
			}
		}

		//设置模板赋值
		$this->setTplName("setup/MenuEdit.tpl");
		$this->assign('menuData', $menuData);
		$this->assign('menuId', $menuId);
		$this->assign('menuListData', $menuListData);
		$this->assign('parentMenu', $parentMenu);

		//渲染页面
		$this->buildPage();
	}
}
