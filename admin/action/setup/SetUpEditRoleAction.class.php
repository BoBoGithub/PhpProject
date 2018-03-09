<?php
/**
 * 后台管理 - 管理角色编辑
 * 
 * @package admin
 * @param   addTime 2018-03-06
 * @param   author  ChengBo
 */
class SetUpEditRoleAction extends AdminAsyncBaseAction {
	/**
	 *  管理角色编辑
	 *
	 * @param int $roleId     角色id
	 * @param str $roleName	  角色名称
	 * @param str $roleDesc   角色描述
	 * @param int $roleStatus 角色状态
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-06
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$data['roleId']		= $this->get('roleId');
		$data['roleName']  	= $this->get('roleName');
		$data['roleDesc']	= $this->get('roleDesc');
		$data['roleStatus']	= $this->get('roleStatus');

		//查询数据
		$updRowNum		= PermitService::getInstance()->updRoleInfoById($data);

		//设置返回值
		$this->set('ret', $updRowNum);
	}
}
