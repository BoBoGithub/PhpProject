<?php
/**
 * 自增id生成操作类
 *  
 **/
class IdGeneratorDao {
    private static $instance = NULL;
    private static $mysql = NULL;
    private static $smysql = NULL;

	//获取单例
    public function getInstance() {
        if (! isset ( self::$instance )) {
            self::$instance = new IdGeneratorDao ();
        }
        return self::$instance;
    }

    protected function __construct() {
    }

	//获取自增id
	public function getId($module, $gen_id) {
		$arr = TableService::getDbName(true, NULL);
		$dbname = $arr['db'];
        $this->mysql = DbWrapper::getInstance($dbname);
        $table_name = "common_id_generator";

		$sql = 'update ' .$table_name. ' set  num=last_insert_id(num+1) where module=%d and id=%d;';
        $ret = $this->mysql->doUpdateQuery ( $sql, $module, $gen_id);

        if ($ret === false) {
            return false;
		} else {
			return $this->mysql->getLastInsertID();
		}
    }
}
