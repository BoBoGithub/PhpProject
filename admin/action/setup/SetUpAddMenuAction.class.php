<?php
/**
 * 后台管理 - 新增菜单
 * 
 * @package admin
 * @param   addTime 2018-03-07
 * @param   author  ChengBo
 */
class SetUpAddMenuAction extends AdminAsyncBaseAction {
	/**
	 *  后台管理 - 新增菜单
	 *
	 * @param int $parentId	   父级菜单id
	 * @param str $menuName	   菜单名称
	 * @param str $requestUrl  请求url
	 * @param int $menuStatus  菜单状态
	 *
	 * @param addTIme 2018-03-07
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受更新参数
		$data['parentId']	= $this->get('parentId');
		$data['menuName']  	= $this->get('menuName');
		$data['requestUrl']	= $this->get('requestUrl');
		$data['menuStatus']	= $this->get('menuStatus');

		//新增菜单数据
		$insertMenuId		= PermitService::getInstance()->addMenuData($data);

		//设置返回值
		$this->set('ret', $insertMenuId);
	}
}
