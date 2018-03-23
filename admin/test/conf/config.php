<?php
define('TEST_LIB_PATH', dirname(__FILE__));
define('CODE_CONF_PATH', dirname(__FILE__).'/..');

ini_set('include_path', ini_get('include_path') . ':' . CODE_CONF_PATH.'/lib');
ini_set('include_path', ini_get('include_path') . ':' . CODE_CONF_PATH.'/../../common/conf');

spl_autoload_register('autoload');

function autoload($class)
{
	@require_once($class.'.class.php');
}
require_once(dirname(__FILE__).'/../../../libs/utils/RequestCore.class.php');

$_ENV['url']   = 'admin.test.com';
$_ENV['username']   = 'test110';
$_ENV['password']   = '123456';
?>

