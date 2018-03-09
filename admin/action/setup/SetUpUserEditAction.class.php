<?php
/**
 * 管理用户列表 - 修改用户信息
 * 
 * @package admin
 * @param   addTime 2018-03-05
 * @param   author  ChengBo
 */
class SetUpUserEditAction extends WebBaseAction {
	/**
	 * 管理用户列表 - 修改用户信息
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public function doGet(){

		//接收参数
		$adminUid = $this->get('adminUid');

		//实例化操作管理用户Service
		$adminUserService = AdminUserService::getInstance();

		//查询指定用户的信息
		$adminUserInfo = $adminUserService->getAdminUserInfoById($adminUid);


		//获取角色数据
		$roleListData = PermitService::getInstance()->getRoleListData();

		//提取当前的角色数据
		$currRoleData = array();
		if(!empty($adminUserInfo['roleid'])){
			foreach($roleListData as $k=>$v){
				if($v['roleid'] == $adminUserInfo['roleid']){
					$currRoleData = $v;break;
				}
			}
		}

		//设置模板赋值
		$this->setTplName("setup/UserEdit.tpl");
		$this->assign('adminUserInfo', $adminUserInfo);
		$this->assign('roleListData', $roleListData);
		$this->assign('currRoleData', $currRoleData);

		//渲染模板
		$this->buildPage();
	}
}

