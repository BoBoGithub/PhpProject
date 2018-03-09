<?php
/**
 * 后台管理 - 管理用户查询列表
 * 
 * @package admin
 * @param   addTime 2018-03-05
 * @param   author  ChengBo
 */
class SetUpGetUserListAction extends AdminAsyncBaseAction {
	/**
	 *  管理用户查询列表
	 *
	 * @param int $page	   当前页码
	 * @param int $pageSize	   每页条数 
	 * @param str $userName	   管理用户名称
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-05
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$data['page']		= $this->get('page');
		$data['pageSize']  	= $this->get('pageSize');
		$data['userName']	= $this->get('userName');

		//实例化管理用户操作Service
		$adminUserService	= AdminUserService::getInstance();

		//查询数据
		$userListData		= $adminUserService->getAdminUserList($data);
		if(!empty($userListData)){
			foreach($userListData as $k=>$v){
				$userListData[$k]['ctime'] = date('Y-m-d H:s', $v['ctime']);
			}
		}

		//统计总条数
		$userTotalNum		= $adminUserService->getTotalUserNumByUName($data['userName']);

		//设置返回值
		$this->set('list', $userListData);
		$this->set('total', $userTotalNum);
	}
}
