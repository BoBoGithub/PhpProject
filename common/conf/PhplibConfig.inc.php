<?php
/**
 * lib config
 *  
 **/
define('PROCESS_START_TIME', microtime(true)*1000);

define('LOG_TYPE', 'LOCAL_LOG');    
define('LOG_LEVEL', 0x15);	

define('HTDOCS_PATH', dirname(__FILE__) .'/../..');
define('LOCAL_LOG_PATH', HTDOCS_PATH .'/logs');

define('PUBLIC_PATH', HTDOCS_PATH .'/libs');
define('PUBLIC_CONF_PATH', HTDOCS_PATH .'/common/conf');

//smarty配置
define('TEMPLATE_PATH', HTDOCS_PATH .'/resources');
define('SMARTY_TEMPLATE_DIR', TEMPLATE_PATH .'/templates');
define('SMARTY_COMPILE_DIR', TEMPLATE_PATH .'/templates_c');
define('SMARTY_CONFIG_DIR', TEMPLATE_PATH .'/config');
define('SMARTY_CACHE_DIR', TEMPLATE_PATH .'/cache');
define('SMARTY_PLUGIN_DIR', TEMPLATE_PATH .'/plugins');
define('SMARTY_LEFT_DELIMITER', '{{');
define('SMARTY_RIGHT_DELIMITER', '}}');

class PublicLibManager
{
	private $arrClasses;

	private static $instance;

	public static function getInstance()
	{
		if (!(self::$instance instanceof self)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		$this->arrClasses = array(
		'Action'			=> PUBLIC_PATH .'/framework/Action.class.php',
		'ActionChain'			=> PUBLIC_PATH .'/framework/ActionChain.class.php',
		'ActionController'		=> PUBLIC_PATH .'/framework/ActionController.class.php',
		'ActionControllerConfig'	=> PUBLIC_PATH .'/framework/ActionControllerConfig.class.php',
		'Context'			=> PUBLIC_PATH .'/framework/Context.class.php',
		'Application'			=> PUBLIC_PATH .'/framework/Application.class.php',
		
		'CLog'				=> PUBLIC_PATH .'/log/CLog.class.php',
		'Smarty'			=> PUBLIC_PATH .'/smarty/Smarty.class.php',
		
		'RedisWrapper'			=> PUBLIC_PATH .'/redis/RedisWrapper.class.php',
		'RedisConfig'			=> PUBLIC_CONF_PATH .'/RedisConfig.class.php',
		'Db'				=> PUBLIC_PATH .'/db/Db.class.php',
		'DbWrapper'			=> PUBLIC_PATH .'/db/DbWrapper.class.php',
		'DbConfig'			=> PUBLIC_CONF_PATH .'/DbConfig.class.php',

		'Utils'				=> PUBLIC_PATH .'/utils/Utils.class.php',
		'ResourceFactory'		=> PUBLIC_PATH .'/utils/ResourceFactory.class.php',
		'RequestCore'			=> PUBLIC_PATH .'/utils/RequestCore.class.php',

		'Timer'				=> PUBLIC_PATH .'/utils/Timer.class.php',
		'BaseSapi'      		=> PUBLIC_PATH .'/sdk/BaseSapi.class.php',		
		'DataSapi'      		=> PUBLIC_PATH .'/sdk/DataSapi.class.php',		
		);
	}

	public function getPublicClassNames()
	{
		return $this->arrClasses;
	}

	public function RegisterMyClassName($className, $classPath)
	{
		$this->arrClasses[$className] = $classPath;
	}

	public function RegisterMyClasses(array $classes)
	{
		$this->arrClasses = array_merge($this->arrClasses, $classes);
	}
}

/**
 * Register user defined class into phplib's autoloader
 * @param string $className	Name of user defined class
 * @param string $classPath	File path of user defined class
 */
function RegisterMyClassName($className, $classPath)
{
	$PublicClassName = PublicLibManager::getInstance();
	$PublicClassName->RegisterMyClassName($className, $classPath);
}

/**
 * Register User defined classes into phplib's autoloader
 * @param array $classes	Class infos, use format: array(classname => class file path, ...)
 */
function RegisterMyClasses(array $classes)
{
	$PublicClassName = PublicLibManager::getInstance();
	$PublicClassName->RegisterMyClasses($classes);
}

function PublicLibAutoLoader($className)
{
	$PublicClassName = PublicLibManager::getInstance();
	$arrPublicClassName = $PublicClassName->getPublicClassNames();
	if (array_key_exists($className, $arrPublicClassName)) {
		require_once($arrPublicClassName[$className]);
	} else {
		// Avoid to explictly require Smarty as it is a so big file
		$class = strtolower($className);
    	if (!strncmp($class, 'smarty', 6)) {
    		return;
    	}
    	
    	$classFile = $className .'.class.php';
    	include_once($classFile);
    	// No autoloader should be registered after Public.conf.php is required,
    	// as the autoloaders will be run in the order they are defined.
    	// To avoid this limit, we could traverse the include path to check the
    	// existence of file and include it if exist, instead of directly include,
    	// example:
    	// <code>
    	// $include_path = explode(':', get_include_path());
    	// foreach ($include_path as $path_dir) {
    	// 		$real_path = rtrim($path_dir, '/') . '/' .$classFile;
    	//		if (file_exists($real_path)) {
    	//			include_once($real_path);
    	//		}
    	//	}
	}
}

spl_autoload_register('PublicLibAutoLoader');
