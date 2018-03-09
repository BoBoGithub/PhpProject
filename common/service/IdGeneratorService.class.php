<?php
/**
 * 自增id生成服务类
 *  
 **/
class IdGeneratorService {
	private static $instance = NULL;

	//获取单例
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			self::$instance = new IdGeneratorService ();
		}
		return self::$instance;
	}

	protected function __construct() {
	}

	//获取自增id
	public function getId($module, $gen_id) {
		$idgenerator_dao = IdGeneratorDao::getInstance ();
		return $idgenerator_dao->getId($module, $gen_id);
	}
}
