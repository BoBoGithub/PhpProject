<?php
/**
 * @brief passport配置
 *  
 **/

//url 映射
ActionControllerConfig::$config = array(
	'hash_mapping' => array(
		'/' 			=> array('IndexAction'),
		'/user/login'		=> array('UserLoginAction'),
		'/user/dologin'		=> array('UserDoLoginAction'),
		'/setup/user/logout'	=> array('UserLogOutAction'),
		'/main/welcome'		=> array('MainWelcomeAction'),
		'/setup/user/info'	=> array('SetUpUserInfoAction'),
		'/setup/user/edit/info' => array('AdminUserEditAction'),
		'/setup/user/list'	=> array('SetUpUserListAction'),
		'/setup/get/user/list'	=> array('SetUpGetUserListAction'),
		'/setup/user/add'	=> array('SetUpUserAddAction'),
		'/setup/user/post/add'	=> array('SetUpUserPostAddAction'),
		'/setup/del/user'	=> array('SetUpUpdUserStatusAction'),
		'/setup/user/edit'	=> array('SetUpUserEditAction'),
		'/setup/user/post/edit'	=> array('SetUpUserPostEditAction'),

		//角色列表
		'/setup/role/list'	=> array('SetUpRoleListAction'),
		'/setup/get/role/list'	=> array('SetUpGetRoleListAction'),
		'/setup/role/add'	=> array('SetUpRoleAddAction'),
		'/setup/role/post/add'	=> array('SetUpRolePostAddAction'),
		'/setup/role/edit'	=> array('SetUpRoleEditAction'),
		'/setup/edit/role'	=> array('SetUpEditRoleAction'),
		'/setup/del/role'	=> array('SetUpDelRoleAction'),
		'/setup/role/user'	=> array('SetUpRoleUserAction'),
		'/setup/role/user/list'	=> array('SetUpRoleUserListAction'),
		'/setup/role/permit'	=> array('SetUpRolePermitAction'),
		'/setup/set/role/permit'=> array('SetUpSetRolePermitAction'),

		//菜单列表
		'/setup/menu/list'	=> array('SetUpMenuListAction'),
		'/setup/menu/add'	=> array('SetUpMenuAddAction'),
		'/setup/add/menu'	=> array('SetUpAddMenuAction'),
		'/setup/menu/edit'	=> array('SetUpMenuEditAction'),
		'/setup/edit/menu'	=> array('SetUpEditMenuAction'),
		'/setup/del/menu'	=> array('SetUpDelMenuAction'),
		'/setup/get/sub/menu'	=> array('SetUpGetSubMenuAction'),

		
	),
	'prefix_mapping'=>array(
		'/user-dd'=>array('TestAction'),
	),
	'regex_mapping'=>array(
		"/\/q-([0-9]+).html/"=>array('TestAction'),
	),
);


class AdminConfig {
	public static $tableNum = 2;
	
	//passport默认过期时间:1个月,不记住登录状态下
	public static $expireIntervalNotRmb = 2592000;
	//passport默认过期时间:6个月,记住登录状态下
	public static $expireIntervalRmb = 15552000;
	//passport更新上次在线时间的时间间隔:1个小时
	public static $updateInterval = 3600;
	//passport自动延长的时间长度:1个小时
	public static $autoExtendTime = 3600;
	
	//passport的cookie加密私钥
	public static $passportKey = 'BaiduPcsId!*';
	
	//多点登录的次数
	public static $maxLoginCount = 5;

}

