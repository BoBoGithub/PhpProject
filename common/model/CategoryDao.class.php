<?php
/**
 * 分类信息数据操作
 *  
 **/
class CategoryDao {
    private static $instance = NULL;
    private static $mysql = NULL;
    private static $smysql = NULL;

    const CATEGORY_ALL = 0;
    const CATEGORY_FIRET = 1;
    const CATEGORY_SECOND = 2;

	//获取单例
    public function getInstance() {
        if (! isset ( self::$instance )) {
            self::$instance = new CategoryDao ();
        }
        return self::$instance;
    }
    protected function __construct() {
    }



	/**
	 * 获取分类详细信息 
	 * 
	 * @param cid 分类ID
	 * @return array 分类的详细信息
	 */
	public function getDetail($cid,$field = array()){
		$arr = TableService::getDbName(false,NULL);
		$dbname = $arr['db'];
		$this->mysql =  DbWrapper::getInstance($dbname);
		$table_name = "ask_category";
		$field = empty($field)?'*':join(',',$field);
		if(!is_array($cid)){
			$sql = "SELECT " . $field . " FROM " . $table_name . ' WHERE id=%d';
			$arr_ret = $this->mysql->queryFirstRow( $sql , $cid );
		}else{
			$sql = "SELECT " . $field . " FROM " . $table_name . ' WHERE id in('.join(',',$cid).')';
			$arr_ret = $this->mysql->queryAllRows( $sql );
		}
		return $arr_ret;


	}

	/**
	 * 获取分类详细信息及其子级的详细信息
	 *
	 * @param cid 分类ID
	 * @return array 分类详细信息 sonlist 分类下子级的详细信息
	 */
	public function getSonList($cid,$field = array()){

		$arr = TableService::getDbName( false , NULL );
		$dbname = $arr['db'];
		$this->mysql = DbWrapper::getInstance( $dbname );
		$table_name = 'ask_category';
		$field = empty($field)?'*':join(',',$field);
		$sql = 'SELECT '.$field.' FROM ask_category WHERE pid=%d';
		$arr_list = $this->mysql->queryAllRows( $sql , $cid );

		return $arr_list;
	}
        /**
	 * 获取一级分类列表
	 * @return array 一级分类列表详细信息 
	 */
	public function getFirstCategoryList($field , $where){

		$arr = TableService::getDbName( false , NULL );
		$dbname = $arr['db'];
		$this->mysql = DbWrapper::getInstance( $dbname );
		$table_name = 'ask_category';
		$field = empty($field)?'*':join(',',$field);
                
		$sql = 'SELECT '.$field.' FROM ask_category WHERE pid=0 and hide=0 '.$where;
		$arr_list = $this->mysql->queryAllRows( $sql );

		return $arr_list;
	}
        
}






