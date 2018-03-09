<?php
/**
 * 后台管理 - 更新管理用户状态
 * 
 * @package admin
 * @param   addTime 2018-03-05
 * @param   author  ChengBo
 */
class SetUpUpdUserStatusAction extends AdminAsyncBaseAction {
	/**
	 * 更新管理用户
	 *
	 * @param int $adminUid	   用户名uid
	 * @param int $status	   用户状态
	 *
	 * @return Boolean
	 *
	 * @param addTIme 2018-03-05
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$updParam['adminUid']	= $this->get('adminUid');
		$updParam['status']		= $this->get('status');

		//新用户入库
		$updStatus		= AdminUserService::getInstance()->updAdminUserStatusByUid($updParam);

		//设置返回结果
		$this->set('ret', $updStatus);
	}
}
