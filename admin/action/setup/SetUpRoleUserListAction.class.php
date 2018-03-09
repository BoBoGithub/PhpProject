<?php
/**
 * 后台管理 - 角色用户查询列表
 * 
 * @package admin
 * @param   addTime 2018-03-06
 * @param   author  ChengBo
 */
class SetUpRoleUserListAction extends AdminAsyncBaseAction {
	/**
	 *  角色用户查询列表
	 *
	 * @param int $page	   当前页码
	 * @param int $pageSize	   每页条数 
	 * @param int $roleId	   角色id
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-06
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$data['page']		= $this->get('page');
		$data['pageSize']  	= $this->get('pageSize');
		$data['roleId']		= $this->get('roleId');

		//实例化管理用户操作Service
		$adminUserService	= AdminUserService::getInstance();

		//查询数据
		$roleUserData		= $adminUserService->getAdminUserListByWhere($data);
		
		//统计总条数
		$userTotalNum		= $adminUserService->getTotalUserNumByWhere($data);

		//设置返回值
		$this->set('list', $roleUserData);
		$this->set('total', $userTotalNum);
	}
}
