<?php
/**
 * 用户信息数据操作
 *  
 **/
class UserDao {
    private static $instance = NULL;
    private static $mysql = NULL;
    private static $smysql = NULL;

	//获取单例
    public static function getInstance() {
        if (! isset ( self::$instance )) {
            self::$instance = new UserDao();
        }
        return self::$instance;
    }
    protected function __construct() {
    }

    /**
     * 按UID查找用户基本信息
     * @return Array | false
     */
    public function getUserInfoByUid($uid) {
		$arr = TableService::getDbName(false, NULL);
		$dbname = $arr['db'];
        $mysqlLink = DbWrapper::getInstance($dbname);
        $table_name = "mg_admin";
        $sql = 'SELECT * FROM ' . $table_name . ' WHERE uid = %d';
        $arr_res = $mysqlLink->queryFirstRow($sql, $uid);
        if (! $arr_res) {
			return false;
		}
        return $arr_res;
    }

    /**
     * 按UID查找用户扩展信息
     * @return Array | false
     *
     */
    public function getUserMoreInfoByUid($uid) {
        $arr = TableService::getDbName(false, $uid);
        $dbname = $arr['db'];
        $table_suffix = $arr['table_suffix'];
        $this->mysql = DbWrapper::getInstance($dbname);
        $table_name = "passport_user_moreinfo" . $table_suffix;
        $sql = 'SELECT * FROM ' . $table_name . ' WHERE uid = %d';
        $arr_res = $this->mysql->queryFirstRow ( $sql, $uid );
        if (! $arr_res) {
            return false;
        }
        return $arr_res;
    }    
    /**
     * 查找用户列表
     * 
     * @return Array | false
     *
     * @param $userstatus 用户类型  
     *
     */
    public function getList( $where='', $page=0, $pageSize=10,$order='uid desc') {
        $arr = TableService::getDbName(false, null);
        $dbname = $arr['db'];
        $table_suffix = $arr['table_suffix'];
        $this->mysql = DbWrapper::getInstance($dbname);
        $table_name = "passport_user_info" . $table_suffix;
        $sql = 'SELECT * FROM ' . $table_name . ' WHERE 1=1 '.$where.' order by '.$order.' limit '.$page.','.$pageSize;
        $arr_res = $this->mysql->queryAllRows ( $sql);
        if (! $arr_res) {
            return false;
        }
        return $arr_res;
    }      
    /**
     * 按照条件查找用户
     * 只返回一条数据
     * @return Array | false
     *
     * @param $userstatus 用户类型  
     *   
     */
    public function getOne($where) {

        $arr = TableService::getDbName(false, null);
        $dbname = $arr['db'];
        $table_suffix = $arr['table_suffix'];
        $this->mysql = DbWrapper::getInstance($dbname);
        $table_name = "passport_user_info" . $table_suffix;
        $sql = 'SELECT * FROM ' . $table_name . ' WHERE 1=1 and '.$where;
        $arr_res = $this->mysql->queryFirstRow ( $sql);
        if (! $arr_res) {
            return false;
        }
        return $arr_res;
    }    
    /**
     * 地区省份列表
     * 
     * @return Array | false
     * 
     */
    public function getAreaList($pid) {
		$arr = TableService::getDbName(false, null);
		$dbname = $arr['db'];
		$table_suffix = $arr['table_suffix'];
        $this->mysql = DbWrapper::getInstance($dbname);
        $table_name = "passport_user_area" . $table_suffix;
        $sql = 'SELECT * FROM ' . $table_name . ' WHERE style = 0 and  parentid = %d ';
        $arr_res = $this->mysql->queryAllRows ( $sql, $pid);
        if (! $arr_res) {
			return false;
		}
        return $arr_res;
    }




}
