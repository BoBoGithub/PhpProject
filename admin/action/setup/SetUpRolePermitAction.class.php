<?php
/**
 * 管理角色列表 - 角色权限设置
 * 
 * @package admin
 * @param   addTime 2018-03-08
 * @param   author  ChengBo
 */
class SetUpRolePermitAction extends WebBaseAction {
	/**
	 * 管理角色列表-角色权限设置
	 *
	 * @param addTime 2018-03-08
	 * @param author  ChengBo
	 */
	public function doGet(){
		//接收角色id参数
		$roleId = $this->get('roleId');

		//调取当前角色的权限
		$privList = PermitService::getInstance()->getAdminRolePrivByRoleId($roleId);

		//调取登陆角色的权限
		$adminPrivList = PermitService::getInstance()->getAdminRolePrivByRoleId($this->adminUserInfo['roleid']);

		//调取菜单数据
		$menuListData = PermitService::getInstance()->getMenuTree();

		//处理菜单选中状态
		if(!empty($privList) || !empty($adminPrivList)){
			foreach($menuListData as $k=>$v){
				if(in_array($v['url'], $adminPrivList) || $this->adminUserInfo['roleid'] == 1){
					$menuListData[$k]['checked'] = in_array($v['url'], $privList) ? 'checked' : '';
				}else{
					$menuListData[$k]['checked'] = 'disabled';
				}
				//$menuListData[$k]['checked'] = in_array($v['url'], $privList) ? 'checked' : ($this->adminUserInfo['roleid'] == 1 ? '' : 'disabled');
			}
		}

		//设置模板赋值
		$this->setTplName("setup/RolePermit.tpl");
		$this->assign('menuListData', $menuListData);
		$this->assign('roleId', $roleId);

		//渲染模板
		$this->buildPage();
	}
}

