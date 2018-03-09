<?php
/**
 * 后台管理 - 提交新增角色
 * 
 * @package iask
 * @param   addTime 2018-03-08
 * @param   author  ChengBo
 */
class SetUpRolePostAddAction extends AdminAsyncBaseAction {
	/**
	 * 提交新增角色
	 *
	 * @param str $roleName	   角色名称
	 * @param str $roleDesc	   角色描述
	 * @param int $roleStatus  角色状态
	 *
	 * @return int
	 *
	 * @param addTIme 2018-03-08
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$data['roleName']	= $this->get('roleName');
		$data['roleDesc']	= $this->get('roleDesc');
		$data['roleStatus']  	= $this->get('roleStatus');

		//新角色入库
		$insertRet		= PermitService::getInstance()->addRoleInfo($data);

		//设置返回结果
		$this->set('ret', $insertRet);
	}
}
