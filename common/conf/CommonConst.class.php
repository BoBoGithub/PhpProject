<?php
/**
 * passport 通用常量
 *  
 **/
class CommonConst
{
	//module ID
	const MODULE_PASSPORT_ID = 1;
	const MODULE_IASK_ID      = 2;
	const MODULE_WIDGET_ID	= 3;
	const MODULE_ADMIN_ID = 4;
    	const MODULE_SYMPTOM_ID = 5;
	
   	//id generator
	const ID_PASSPORT_USER_ID = 1;
	const ID_PASSPORT_GOODS_ID = 2;
	const ID_PASSPORT_ORDERS_ID = 3;
	const ID_PASSPORT_CURRENCYLOG_ID = 4;

	const ID_IASK_QUESTION_ID = 1;
	const ID_IASK_ANSWER_ID = 2;
	const ID_WIDGET_COMMENT = 1;
	const ID_WIDGET_LIKE 	= 2;
	const ID_WIDGET_GIFT = 3;
	const ID_WIDGET_MES_sys = 4;
	const ID_WIDGET_MES = 5;
	const ID_WIDGET_MES_TAG = 6;

	const ID_ADMIN_USER_ID = 1;
	const ID_ADMIN_ROLE_ID = 2;
	const ID_ADMIN_NODE_ID = 3;


	// page size
	const COMMENT_PAGE_SIZE = 5;


	// messagestatus;
	const MESSAGE_NEW = 0;
	const MESSAGE_READED = 1;

	//HOST
	const ADMIN_HOST = 'http://admin.test.com';
	const STATIC_HOST = "http://static.test.com";
	const FILE_HOST = "http://file.fh21.com";
	const SAPI_HOST_IASK	 = 'http://iask.fh21.com';

	const ERROR_TPL = 'error.tpl';
	
	//后台用户登录Cookie标识
	const ADMIN_USER_LOGIN_KEY = 'AULKEY';

	//后台菜单缓存标识
	const ADMIN_MENU_CACHE_KEY = 'adin_menu_cache_key';

	/**
	 * Common Error No definition.
	 */
	/** success */
	const SUCCESS                     = 0;
	/** uknown error */
	const COMMON_UNKNOWN                  = -1;


	//内部使用，外部无需关心
	const COMMON_API_UNSUPPORTED       = 10000;
	const COMMON_NO_PERMISSION         = 10001;
	const COMMON_SERVICE_INVALID       = 10002;
	const COMMON_DB_ERROR              = 10003;
	const COMMON_PARAM_ERROR           = 10004;
	const COMMON_HTTPMETHOD_ERROR      = 10005;
	const COMMON_REDIS_ERROR           = 10006;
	const COMMON_DB_DUPLICATE_ERROR    = 10007;
	const COMMON_IDGENERATOR_ERROR      = 10008;

	//------------------PASSPORT 模块--------------------
	// 登录接口相关
	const PASSPORT_INVALID_PASSWD       = 10100;
	const PASSPORT_USER_NOT_EXIST       = 10101;
	const PASSPORT_USERORPASSWD_ERROR   = 10102;

	// 注册接口相关
	const PASSPORT_MOBILE_EXIST         = 10200;
	const PASSPORT_EMAIL_EXIST          = 10201;
	const PASSPORT_REG_FAIL             = 10202;

	const PASSPORT_USERNAME_EXIST       = 10203;
	const PASSPORT_USERNAME_ERROR       = 10204;
	const PASSPORT_PASSWORD_ERROR       = 10205;
	const PASSPORT_REPEATPASSWORD_ERROR = 10206;
	const PASSPORT_CODE_ERROR           = 10207;
	const PASSPORT_EMAIL_ERROR          = 10208;
	const PASSPORT_OLDPASSWORD_ERROR    = 10209;
	const PASSPORT_MOBILE_ERROR         = 10210;
	const PASSPORT_MOBILE_STINT         = 10211;//手机验证次数限制

	//------------------ADMIN 模块--------------------
	const ADMIN_LOGIN_ERROR		   	= 11001;
	const ADMIN_LOGIN_USER_NOT_EXIST	= 11002;
	const ADMIN_LOGIN_USER_PWD_ERROR	= 11003;
	const ADMIN_UID_ERROR			= 11004;
	const ADMIN_ROLE_ID_ERROR		= 11005;
	const ADMIN_USER_NOT_LOGIN		= 11006;
	const ADMIN_REAL_NAME_ERROR		= 11007;
	const ADMIN_EMAIL_ERROR			= 11008;
	const ADMIN_USER_NAME_ERROR		= 11009; 
	const ADMIN_USER_PWD_ERROR		= 11010;
	const ADMIN_USER_STATUS_ERROR		= 11011;
	const ADMIN_ROLE_NAME_ERROR		= 11012;
	const ADMIN_ROLE_DESC_ERROR		= 11013;
	const ADMIN_ROLE_STATUS_ERROR		= 11014;
	const ADMIN_MENU_ID_ERROR		= 11015;
	const ADMIN_MENU_PARENT_ID_ERROR	= 11016;
	const ADMIN_MENU_NAME_ERROR		= 11017;
	const ADMIN_MENU_REQUEST_URL_ERROR	= 11018;
	const ADMIN_MENU_STATUS_ERROR		= 11019;


	static $errorDescs = array(
		self::SUCCESS                      => 'success',
		self::COMMON_UNKNOWN               => 'uknown error',

		//内部使用，外部无需关心
		self::COMMON_API_UNSUPPORTED       => 'api not support',
		self::COMMON_NO_PERMISSION         => 'no permission',
		self::COMMON_SERVICE_INVALID       => 'backend service is not available',
		self::COMMON_DB_ERROR              => 'db error',
		self::COMMON_PARAM_ERROR           => 'param error',
		self::COMMON_HTTPMETHOD_ERROR      => 'http method error',
		self::COMMON_REDIS_ERROR           => 'redis error',
		self::COMMON_DB_DUPLICATE_ERROR    => 'db duplicate key',
		self::COMMON_IDGENERATOR_ERROR	   => 'id generator get error',
		//------------------PASSPORT 模块--------------------
		// 登录接口相关
		self::PASSPORT_INVALID_PASSWD      => 'password is incorrect',
		self::PASSPORT_USER_NOT_EXIST      => 'user not exist',
		self::PASSPORT_USERORPASSWD_ERROR  => 'user or password error',

		// 注册接口相关
		self::PASSPORT_MOBILE_EXIST        => 'mobile has already existed',
		self::PASSPORT_EMAIL_EXIST         => 'email has already existed',
		self::PASSPORT_REG_FAIL            => 'reg fail',

		self::PASSPORT_USERNAME_EXIST      => 'username has already existed',
		self::PASSPORT_USERNAME_ERROR      => 'username error',
		self::PASSPORT_PASSWORD_ERROR      => 'password error',
		self::PASSPORT_REPEATPASSWORD_ERROR=> 'repeat password error',
		self::PASSPORT_CODE_ERROR          => 'code error',
		self::PASSPORT_EMAIL_ERROR         => 'email error',
		self::PASSPORT_OLDPASSWORD_ERROR   => 'oldpassword error',
		self::PASSPORT_MOBILE_ERROR        => 'mobile error',
		self::PASSPORT_MOBILE_STINT        => 'mobile num stint',
		
		self::ADMIN_LOGIN_ERROR            => 'admin login error',
		self::ADMIN_LOGIN_USER_NOT_EXIST   => 'admin login user not exist',
		self::ADMIN_LOGIN_USER_PWD_ERROR   => 'admin login pwd error',
		self::ADMIN_UID_ERROR		   => 'admin uid error',
		self::ADMIN_ROLE_ID_ERROR	   => 'admin role id error',
		self::ADMIN_USER_NOT_LOGIN	   => 'admin user not login',
		self::ADMIN_REAL_NAME_ERROR	   => 'admin realName error',
		self::ADMIN_EMAIL_ERROR		   => 'admin email error',
		self::ADMIN_USER_NAME_ERROR	   => 'admin userName error',
		self::ADMIN_USER_PWD_ERROR	   => 'admin password error',
		self::ADMIN_USER_STATUS_ERROR	   => 'admin user status error',
		self::ADMIN_ROLE_NAME_ERROR	   => 'admin role name error',
		self::ADMIN_ROLE_DESC_ERROR	   => 'admin role desc error',
		self::ADMIN_ROLE_STATUS_ERROR	   => 'admin role status error',
		self::ADMIN_MENU_ID_ERROR	   => 'admin menu id error',
		self::ADMIN_MENU_PARENT_ID_ERROR   => 'admin menu parent id error',
		self::ADMIN_MENU_NAME_ERROR	   => 'admin menu name error',
		self::ADMIN_MENU_REQUEST_URL_ERROR => 'admin menu request url error',
		self::ADMIN_MENU_STATUS_ERROR	   => 'admin menu status error',
	);

	public static function getErrorDesc($errno)
	{
		if (isset(self::$errorDescs[$errno])) {
			return self::$errorDescs[$errno];
		} else {
			return 'unknown error';
		}
	}
}
