<?php
/**
 * 后台管理 - 编辑菜单
 * 
 * @package admin
 * @param   addTime 2018-03-07
 * @param   author  ChengBo
 */
class SetUpEditMenuAction extends AdminAsyncBaseAction {
	/**
	 *  后台管理 - 编辑菜单
	 *
	 * @param int $menuId	   菜单id
	 * @param str $parentId	   父级菜单id
	 * @param str $menuName	   菜单名称 
	 * @param str $requestUrl  请求地址
	 * @param int $menuStatus  菜单状态
	 *
	 * @param addTIme 2018-03-07
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受更新参数
		$data['menuId']		= $this->get('menuId');
		$data['parentId']	= $this->get('parentId');
		$data['menuName']  	= $this->get('menuName');
		$data['requestUrl']	= $this->get('requestUrl');
		$data['menuStatus']	= $this->get('menuStatus');

		//修改菜单数据
		$affectNum		= PermitService::getInstance()->updMenuData($data);

		//设置返回值
		$this->set('ret', $affectNum);
	}
}
