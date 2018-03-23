<?php 
/**
 * redis配置类
 *  
 **/
class RedisConfig 
{
	const CONNECTION_TIMEOUT = 1;


	public static $RedisServer = array(
		'cache' => array(
			array('ip' => '172.16.1.208', 'port' => 6379),	
			array('ip' => '172.16.1.208', 'port' => 6379),	
		),
		'submit' => array(
			array('ip' => '172.16.1.208', 'port' => 6381),	
		),
	);

}
