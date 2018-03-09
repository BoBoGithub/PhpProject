<?php
/**
 * 后台管理 - 管理角色查询列表
 * 
 * @package admin
 * @param   addTime 2018-03-06
 * @param   author  ChengBo
 */
class SetUpGetRoleListAction extends AdminAsyncBaseAction {
	/**
	 *  管理角色查询列表
	 *
	 * @param int $page	   当前页码
	 * @param int $pageSize	   每页条数 
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

		//查询数据
		$roleListData		= PermitService::getInstance()->getRoleListData($data);

		//统计总条数
		$roleTotalNum		= PermitService::getInstance()->getTotalRoleNum();

		//设置返回值
		$this->set('list', $roleListData);
		$this->set('total', $roleTotalNum);
	}
}
