<?php
/**
 * 后台管理 - 提交新增管理用户
 * 
 * @package iask
 * @param   addTime 2018-03-05
 * @param   author  ChengBo
 */
class SetUpUserPostAddAction extends AdminAsyncBaseAction {
	/**
	 * 提交新增管理用户
	 *
	 * @param str $userName	   用户名
	 * @param str $password	   密码
	 * @param str $realName	   真实姓名
	 * @param int $roleId	   所属角色id
	 *
	 * @return Boolean
	 *
	 * @param addTIme 2018-03-05
	 * @param author  ChengBo
	 */
	public function doPost() {		
		//接受更新参数
		$data['userName']	= $this->get('userName');
		$data['password']	= $this->get('password');
		$data['realName']  	= $this->get('realName');
		$data['roleId']		= $this->get('roleId');

		//新用户入库
		$insertRet		= AdminUserService::getInstance()->addAdminUserInfo($data);

		//设置返回结果
		$this->set('ret', $insertRet);
	}
}
