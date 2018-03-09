<?php
/**
 * 生成数据表
 * 
 **/
class TableService {
	private $dbHandlers = array ();

	protected static function getDbNo($hashKey, $subTable, $dbHashFunc) {
		if (false === ($ret = call_user_func ( $dbHashFunc, $hashKey, $subTable ))) {
			throw new CommonException ( CommonConst::EC_CM_DB_ERROR, 'TableService invalid hashKey[' . $hashKey . ']' );
		}
		return $ret;
	}
	
	//获取数据库实例,是否分布
	public static function getDbName($isMaster = false, $hashKey = NULL) {
		$db = "MG_DB";
		$table_suffix = "";
		if($hashKey != NULL) {
			$dbNO = Utils::getHash($hashKey, DbConfig::DB_SPLIT_NUM);
			$db .= "_" . $dbNO;
			$table_suffix = "_" . $dbNO;
		}
		
		//暂无主从
		// if($isMaster) {
			// $db .= "_W";
		// } else {
			// $db .= "_R";
		// }
		
		return array("db" => $db, "table_suffix" => $table_suffix);
	}
	
	//获取数据库实例,是否分布
	public static function getHosDbName() {
		$db = "DB_FH_HOSPITAL";
		$table_suffix = "";
		return array("db" => $db, "table_suffix" => $table_suffix);
	}
	
}

