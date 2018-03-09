<?php
/**
 * db配置类
 *  
 **/
class DbConfig
{
	const DB_SPLIT_NUM = 2;
	//mysql错误码
	const DB_DUPLICATE_KEY = 1062;
	/**
	 * 数据库连接失败时的总重试次数(包括失败、超时等)
	 * @var int
	 */
	const RETRY_TIMES = 3;

	/**
	 * 单个机房数据库连接失败时的重试次数(包括失败、超时等)
	 * @var int
	 */
	const RETRY_TIMES_PER_IDC = 2;

	/**
	 * MySQL 链接超时时间（秒）
	 * 用于设置 MYSQLI_OPT_CONNECT_TIMEOUT
	 * @var int
	 */
	const CONNECTION_TIMEOUT = 1;

	/**
	 * Db集群的机器列表、访问集群时所用的用户名、密码、端口号、失败重试次数
	 * @var array
	 */
	static $arrDbServer = array(
		"MG_DB" => array(	
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3306,
				'host' => '127.0.0.1',
				'db' => 'MG_DB',
			),
		),
		"DB_MG_ADMIN_0_W" => array(	
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3306,
				'host' => '127.0.0.1',
				'db' => 'MG_DB_0',
			),
		),
		"DB_MG_ADMIN_1_W" => array(	
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3306,
				'host' => '192.168.10.111',
				'db' => 'MG_DB_1',
			),
		),
		"DB_MG_ADMIN_R" => array(	
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3307,
				'host' => '192.168.10.111',
				'db' => 'MG_DB',
			),
		),
		"MG_DB_ADMIN_0_R" => array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3307,
				'host' => '192.168.10.111',
				'db' => 'MG_DB_0',
			),
		),
		"MG_DB_ADMIN_1_R" => array(	
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3307,
				'host' => '192.168.10.111',
				'db' => 'MG_DB_1',
			),
		),
		'DB_MG_DRUG' => array(
			array(
				'username' => 'root',
				'password' => '123456',
				'port' => 3306,
				'host' => '192.168.10.234',
				'db' => 'MG_DB',
			),
		),

	);
}
