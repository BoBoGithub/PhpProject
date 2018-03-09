<?php
/**
 * 后台管理 - 管理角色删除
 * 
 * @package admin
 * @param   addTime 2018-03-06
 * @param   author  ChengBo
 */
class SetUpDelRoleAction extends AdminAsyncBaseAction {
	/**
	 *  管理角色删除
	 *
	 * @param int $roleId     角色id
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-06
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受更新参数
		$roleId		= $this->get('roleId');

		//查询数据
		$updRowNum	= PermitService::getInstance()->delRoleInfoById($roleId);


		//设置返回值
		$this->set('ret', $updRowNum);
	}
}
