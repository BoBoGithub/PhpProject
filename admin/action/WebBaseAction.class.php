<?php
/**
 * @brief 要用模板的应继承此类
 *  
 **/
class WebBaseAction extends TemplateBasedAction
{
	//后台管理用户uid
	protected $adminUid		= 0;
	protected $adminUserInfo	= array();

	//检查登录权限
	protected function preExecute(){
		//检查登录状态
		$adminUserData = AdminUserService::getInstance()->checkUserLoginStatus();
		
		//设置登陆用户UID
		$this->adminUid		= !empty($adminUserData['uid']) ? $adminUserData['uid']: '';
		$this->adminUserInfo	= !empty($adminUserData) ? $adminUserData : array();

		//检查是否是登录或权限
		if(!in_array($this->actionClassName, array("UserLoginAction", "UserLogOutAction"))){
			//检查登录状态
			if(empty($adminUserData)){
				$this->redirectAndExit('/user/login');
			}

			//检查是否有访问权限
			$vistPermit = PermitService::getInstance()->checkRolePriv($adminUserData['roleid'], $this->action_params['url']);
			if(!$vistPermit){
				echo '<div style="padding:20px;">无权限访问</div>';EXIT;
			}
		}
	}
	
	//渲染模板
    	protected function buildPage() {
        	// Assign the common template var
		$this->assign('STATIC_HOST', CommonConst::STATIC_HOST);
		$this->assign('ADMIN_HOST', CommonConst::ADMIN_HOST);
		
		$this->display($this->getTplName());
    	}

}
