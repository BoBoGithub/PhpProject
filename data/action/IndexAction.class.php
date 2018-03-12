<?php
/**
 * 主页ACTION
 * 
 * @package data
 * @param   addTime 2018-03-12
 * @param   author  ChengBo
 */
class IndexAction extends WebBaseAction {
	/**
	 * 主页ACTION
	 *
	 * @param addTIme 2018-02-12
	 * @param author  ChengBo
	 */
	public function doGet(){
		//设置模板
		$this->setTplName("Index.tpl");

		//渲染页面 
		$this->buildPage();
	}
}

