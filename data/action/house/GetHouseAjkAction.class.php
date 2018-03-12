<?php
/**
 * 查询房屋数据
 * 
 * @package data
 * @param   addTime 2018-03-12
 * @param   author  ChengBo
 */
class GetHouseAjkAction extends AsyncAction {
	/**
	 * 获取房屋数据
	 *
	 * @param int $page	   当前页码
	 * @param int $pageSize	   每页条数
	 * @param str $siteName	   小区名称
	 * @param str $totalSize   房屋总面积
	 * @param str $userName	   业务员名称
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-12
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受登录参数
		$data['page']		 = $this->get('page');
		$data['pageSize'] 	 = $this->get('pageSize');
		$data['siteName']	 = $this->get('siteName');
		$data['totalSize']	 = $this->get('totalSize');
		$data['userName']	 = $this->get('userName');

		//查询数据
		$houseData		 = HouseService::getInstance()->getAjkHouseByWhere($data);

		$this->set('houseData', $houseData);
	}
}

