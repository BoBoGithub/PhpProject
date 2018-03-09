<?php
/**
 * 后台用户登录
 * 
 * @package iask
 * @param   addTime 2018-03-01
 * @param   author  ChengBo
 */
class UserDoLoginAction extends AdminAsyncBaseAction {
	/**
	 * 后台用户登录
	 *
	 * @param int $username	   用户名
	 * @param int $password	   密码
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-01
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受登录参数
		$data['username']	 = $this->get('username');
		$data['password'] 	 = $this->get('password');

		//检查登录
		$flagState		 = AdminUserService::getInstance()->checkUserLogin($data);

		$this->set('isLogin',($flagState ? 1 : 0));
	}
}

