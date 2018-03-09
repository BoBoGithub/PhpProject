<?php
/**
 * 后台欢迎页
 * 
 * @package admin
 * @param   addTime 2018-03-02
 * @param   author  ChengBo
 */
class MainWelcomeAction extends WebBaseAction {
	/**
	 * 后台欢迎页
	 *
	 * @param addTIme 2018-03-02
	 * @param author  ChengBo
	 */
	public function doGet(){
		//模板赋值
		$this->setTplName("mainWelcome.tpl");
		$this->assign("title","欢迎页面");

		$this->buildPage();
	}
}

