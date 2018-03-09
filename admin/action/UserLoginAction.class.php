<?php
/**
 * 后台用户登录页
 * 
 * @package admin
 * @param   addTime 2018-03-01
 * @param   author  ChengBo
 */
class UserLoginAction extends WebBaseAction {
	/**
	 * 后台用户登录页
	 *
	 * @param addTIme 2018-03-01
	 * @param author  ChengBo
	 */
	public function doGet(){
		//检查是否登陆
		if(!empty($this->adminUserInfo)){
			$this->redirectAndExit('/');
		}
		
		//设置页面模板并赋值
		$this->setTplName("userLogin.tpl");

		//渲染页面
		$this->buildPage();
	}
}

