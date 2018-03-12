<?php
/**
 * 获取安居客的房屋数据列表
 * 
 * @package admin
 * @param   addTime 2018-03-12
 * @param   author  ChengBo
 */
class GetHouseAjkListAction extends AdminAsyncBaseAction {
	/**
	 * 获取安居客的房屋数据列表
	 *
	 * @param int $page	   当前页码
	 * @param int $pageSize	   每页条数
	 * @param str $siteName	   小区名称
	 * @param str $totalSize   房屋总面积
	 * @param str $useName	   业务员名称
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-12
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受登录参数
		$data['page']		= $this->get('page');
		$data['pageSize'] 	= $this->get('pageSize');
		$data['siteName']	= $this->get('siteName');
		$data['totalSize']	= $this->get('totalSize');
		$data['userName']	= $this->get('userName');
		
		//检查房屋数据
		$houseData		= DataService::getInstance()->getAjkHouseListByWhere($data);
		if(!empty($houseData)){
			foreach($houseData as $k=>$v){
				$houseData[$k]['send_time'] = date('Y-m-d', $v['send_time']);
			}
		}

		//汇总房屋总条数
		$totalNum		= DataService::getInstance()->countAjkHouseNumByWhere($data);

		//设置返回值
		$this->set('list', $houseData);
		$this->set('total', $totalNum);
	}
}

