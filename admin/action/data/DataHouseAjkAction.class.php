<?php
/**
 * 安居客数据列表
 * 
 * @package admin
 * @param   addTime 2018-03-12
 * @param   author  ChengBo
 */
class DataHouseAjkAction extends WebBaseAction {
	/**
	 * 安居客数据列表
	 *
	 * @param addTIme 2018-03-12
	 * @param author  ChengBo
	 */
	public function doGet(){
		//设置模板
		$this->setTplName("data/HouseAjkList.tpl");

		//渲染页面 
		$this->buildPage();
	}
}

