<?php
/**
 * redis包装类
 *  
 **/
class RedisWrapper extends Redis
{
	/**
	 * RedisWrapper instance array
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * Get RedisWrapper instance for the specified key
	 * 
	 * @param string $key	redis key
	 * @return Redis
	 */
	public static function getInstance($key = 1, $cluster = 'cache')
	{
		if(!$key || !isset(RedisConfig::$RedisServer[$cluster])) {
			return NULL;
		}
		$num =  count(RedisConfig::$RedisServer[$cluster]);
		if($num == 0) {
			return NULL;
		}
		if($num == 1) {
			$index = 0;
		} else {
			$index =  Utils::getHash($key,count(RedisConfig::$RedisServer[$cluster]));
		}
		if (!isset(self::$instances[$cluster][$index]) || empty(self::$instances[$cluster][$index])) {
			self::$instances[$cluster][$index] = self::createInstance($index, $cluster);
		}
		return self::$instances[$cluster][$index];
	}

	protected static function createInstance($index, $cluster)
	{	
		if (!isset(RedisConfig::$RedisServer[$cluster][$index])) {
			return false;
		}
		$RedisWrapper = new RedisWrapper();
		$totalServerNum = count(RedisConfig::$RedisServer[$cluster][$index]);
		$ip = RedisConfig::$RedisServer[$cluster][$index]['ip'];
		$port = RedisConfig::$RedisServer[$cluster][$index]['port'];
		try {
			$RedisWrapper->connect($ip, $port, RedisConfig::CONNECTION_TIMEOUT);
		} catch (Exception $e) {
			CLog::fatal('module[RedisWrapper] ip[%s] port[%d] msg[connect failed]', $ip, $port);
			return false;
		}
		
		return $RedisWrapper;
	}
}
