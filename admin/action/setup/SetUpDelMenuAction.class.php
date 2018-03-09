<?php
/**
 * 后台管理 - 删除菜单
 * 
 * @package admin
 * @param   addTime 2018-03-07
 * @param   author  ChengBo
 */
class SetUpDelMenuAction extends AdminAsyncBaseAction {
	/**
	 *  后台管理 - 删除菜单
	 *
	 * @param int $menuId	   菜单id
	 *
	 * @param addTIme 2018-03-07
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受更新参数
		$menuId		= $this->get('menuId');

		//修改菜单数据
		$affectNum	= PermitService::getInstance()->delMenuData($menuId);

		//设置返回值
		$this->set('ret', $affectNum);
	}
}
