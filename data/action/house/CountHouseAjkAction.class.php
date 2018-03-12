<?php
/**
 * 汇总房屋数据
 * 
 * @package data
 * @param   addTime 2018-03-12
 * @param   author  ChengBo
 */
class CountHouseAjkAction extends AsyncAction {
	/**
	 * 汇总房屋数据
	 *
	 * @param str $siteName	   小区名称
	 * @param str $totalSize   房屋总面积
	 * @param str $userName	   业务员名称
	 *
	 * @return int
	 *
	 * @param addTIme 2018-03-12
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受登录参数
		$data['siteName']	 = $this->get('siteName');
		$data['totalSize']	 = $this->get('totalSize');
		$data['userName']	 = $this->get('userName');

		//汇总数据
		$countHouseData		 = HouseService::getInstance()->countAjkHouseByWhere($data);

		//设置返回值
		$this->set('num', $countHouseData);
	}
}

