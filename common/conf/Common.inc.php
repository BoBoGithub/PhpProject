<?php
/**
 * 类库映射
 * Register all classes into phplib's autoloader
 */
define('COMMON_PATH', dirname(__FILE__).'/..');
define('COMMON_ACTION_PATH', COMMON_PATH .'/action');
define('COMMON_MODEL_PATH', COMMON_PATH .'/model');
define('COMMON_SERVICE_PATH', COMMON_PATH .'/service');
define('COMMON_CONF_PATH', COMMON_PATH .'/conf');
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . COMMON_MODEL_PATH. PATH_SEPARATOR . COMMON_SERVICE_PATH);
$g_arrCommonClasses = array(
	'BaseAction'			=> COMMON_ACTION_PATH .'/BaseAction.class.php',
	'TemplateBasedAction'	=> COMMON_ACTION_PATH .'/TemplateBasedAction.class.php',
	'AsyncBaseAction'	    => COMMON_ACTION_PATH .'/AsyncBaseAction.class.php',
	'PageBuilderAction'		=> COMMON_ACTION_PATH .'/PageBuilderAction.class.php',
	'CommonException'		=> COMMON_ACTION_PATH .'/CommonException.class.php',
	'BaseSubmit'			=> COMMON_ACTION_PATH .'/BaseSubmit.class.php',

	'TableService'			=> COMMON_SERVICE_PATH .'/TableService.class.php',
	'UserDao'				=> COMMON_MODEL_PATH .'/UserDao.class.php',
	'UserService'			=> COMMON_SERVICE_PATH .'/UserService.class.php',

	'CommonConst'			=> COMMON_CONF_PATH .'/CommonConst.class.php',
	
);

RegisterMyClasses($g_arrCommonClasses);

