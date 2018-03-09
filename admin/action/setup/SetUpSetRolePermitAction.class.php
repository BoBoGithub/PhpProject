<?php
/**
 * 后台管理 - 设置角色权限
 * 
 * @package admin
 * @param   addTime 2018-03-08
 * @param   author  ChengBo
 */
class SetUpSetRolePermitAction extends AdminAsyncBaseAction {
	/**
	 *  设置角色权限
	 *
	 * @param int $roleId	 角色id
	 * @param arr $menuIds   菜单ids
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-08
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$data['roleId']		= $this->get('roleId');
		$data['menuIds']  	= $this->get('menuIds');

		//设置角色权限
		$setRet = PermitService::getInstance()->setAdminRolePriv($data);

		//设置返回值
		$this->set('ret', ($setRet ? 1 : 0));
	}
}
