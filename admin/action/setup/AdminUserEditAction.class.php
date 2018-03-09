<?php
/**
 * 后台管理 - 提交编辑管理用户
 * 
 * @package iask
 * @param   addTime 2018-03-02
 * @param   author  ChengBo
 */
class AdminUserEditAction extends AdminAsyncBaseAction {
	/**
	 * 提交编辑管理用户
	 *
	 * @param int $username	   用户名
	 * @param int $password	   密码
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-02
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$data['adminUid']	= $this->adminUid;
		$data['realName']	= $this->get('realName');
		$data['email'] 	 	= $this->get('email');

		//更新数据
		$updState		= AdminUserService::getInstance()->updAdminUserInfoByUid($data);

		$this->set('updRet',($uppState ? 1 : 0));
	}
}
