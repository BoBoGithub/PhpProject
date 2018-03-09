<?php
/**
 * 后台管理 - 提交修改管理用户
 * 
 * @package admin
 * @param   addTime 2018-03-05
 * @param   author  ChengBo
 */
class SetUpUserPostEditAction extends AdminAsyncBaseAction {
	/**
	 * 提交修改管理用户
	 *
	 * @param int $adminUid   用户uid
	 * @param int $roleId	  角色id
	 * @param str $userName	  用户名
	 * @param str $realName	  真实姓名
	 * @param str $password	  密码
	 *
	 * @return Boolean
	 *
	 * @param addTIme 2018-03-05
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$data['adminUid']	= $this->get('adminUid');
		$data['password']	= $this->get('password');
		$data['realName']  	= $this->get('realName');
		$data['roleId']		= $this->get('roleId');
		$data['userName']	= $this->get('userName');

		//新用户入库
		$insertRet		= AdminUserService::getInstance()->updAdminUserInfo($data);

		//设置返回结果
		$this->set('ret', $insertRet);
	}
}
