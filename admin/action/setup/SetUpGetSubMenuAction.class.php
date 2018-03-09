<?php
/**
 * 后台管理 - 获取子菜单数据
 * 
 * @package admin
 * @param   addTime 2018-03-08
 * @param   author  ChengBo
 */
class SetUpGetSubMenuAction extends AdminAsyncBaseAction {
	/**
	 *  获取子菜单数据
	 *
	 * @param int $pid 父级菜单id
	 *
	 * @return array
	 *
	 * @param addTIme 2018-03-08
	 * @param author  ChengBo
	 */
	public function doPost() {
		//接受更新参数
		$pid	= $this->get('pid');

		if($pid != 0){
			//提取角色id
			$roleId	 	= $this->adminUserInfo['roleid'];
			$adminUid	= $this->adminUserInfo['uid'];
			
			//实例化操作权限Serivce
			$permitService = PermitService::getInstance();

			//调取当前角色下的菜单数据
			$menuPriveList = $permitService->getAdminRolePrivByRoleId($roleId);
			
			//调取二级菜单数据
			$menuList = $permitService->getAdminMenuByPid($pid);
			if(!empty($menuList)){
				//提取二级菜单
				foreach($menuList as $adminMenu){
					//只提去显示状态的菜单
					if($adminMenu['status'] != 0 || ($adminUid != 1 && !in_array($adminMenu['url'], $menuPriveList))){
						continue;
					}
					
					//实例化三级Map
					$thirdMenuList = array();
					
					//调取三级菜单
					$thirdMenuDataList = $permitService->getAdminMenuByPid($adminMenu['id']);
					if(!empty($thirdMenuDataList)){
						//提取三级菜单
						foreach($thirdMenuDataList as $thirdAdminMenu){
							//只提去显示状态的菜单
							if($thirdAdminMenu['status'] == 0 && ($adminUid == 1 || in_array($thirdAdminMenu['url'], $menuPriveList))){
								//实例化三级Map
								$thirdMenuMap = array();
								
								//设置三级菜单数据
								$thirdMenuMap['name']	= $thirdAdminMenu['name'];
								$thirdMenuMap['url']	= $thirdAdminMenu['url'];
								
								//追加到返回列表中
								$thirdMenuList[] = $thirdMenuMap;
							}
						}
					}
					
					//实例化二级Map
					$secondMenuMap = array();
					
					//设置二级菜单名称
					$secondMenuMap['name']	= $adminMenu['name'];
					$secondMenuMap['child'] = $thirdMenuList;
					
					//追加到返回List中
					$subMenuList[] = $secondMenuMap;
				}
			}
		}
		
		//设置返回结果
		$this->set("subMenuList", $subMenuList);
	}
}
