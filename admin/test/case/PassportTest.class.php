<?php
/**
 * @brief  测试基类
 *  
 **/
if(!defined('TEST_ROOT')){
	define('TEST_ROOT',substr(dirname(__FILE__), 0, -2));
}
$includePath = "";
ini_set('include_path', ini_get('include_path') . ':' . '/usr/share/php/PHPUnit:/usr/share/doc/php5-common/PEAR/PHPUnit');

require_once 'PHPUnit/Framework.php';
require_once TEST_ROOT.'/../conf/config.php';


class PassportTest extends PHPUnit_Framework_TestCase{
}
