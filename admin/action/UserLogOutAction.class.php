<?php
/**
 * 后台用户退出页
 * 
 * @package admin
 * @param   addTime 2018-03-09
 * @param   author  ChengBo
 */
class UserLogOutAction extends WebBaseAction {
	/**
	 * 后台用户退出页
	 *
	 * @param addTIme 2018-03-09
	 * @param author  ChengBo
	 */
	public function doGet(){
		//退出登陆
		AdminUserService::getInstance()->adminUserLogOut($this->adminUserInfo['uid']);

		//跳转首页
		$this->redirectAndExit('/user/login');
	}
	
}

