<?php
/**
 * @brief 环境初始化
 *  
 **/
define('IS_DEBUG', false);
define('APP_NAME', 'admin');
define('APP_PATH', dirname(__FILE__).'/..');
define('APP_CONF_PATH', APP_PATH . '/conf');

require_once(APP_PATH .'/../common/conf/PhplibConfig.inc.php');
require_once(APP_PATH .'/../common/conf/Common.inc.php');

$appIncludePath = APP_PATH .'/action/'. PATH_SEPARATOR .
APP_PATH .'/action/setup/' . PATH_SEPARATOR .
APP_PATH .'/model/' . PATH_SEPARATOR .
APP_PATH .'/service/' . PATH_SEPARATOR .
APP_PATH .'/common/' . PATH_SEPARATOR .
APP_PATH .'/common/model/' . PATH_SEPARATOR .
APP_CONF_PATH .'/' . PATH_SEPARATOR;
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $appIncludePath);

require_once ('AdminConfig.class.php');
if ((defined('IS_DEBUG') && IS_DEBUG)) {
	ini_set('display_errors', 1);
}

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

$GLOBALS['LOG'] = array(
	'type'		=> LOG_TYPE,
	'level'		=> LOG_LEVEL,
	'path'		=> (LOG_TYPE == 'LOCAL_LOG') ? LOCAL_LOG_PATH : 'log',
	'filename'	=> 'admin.log.'.date("Ymd"),
);
