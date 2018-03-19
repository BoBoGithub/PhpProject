<?php
/**
 * 提交数据操作类
 *  
 **/
class SubmitDao {
	private static $instance = NULL;
	private static $mysql = NULL;
	private static $smysql = NULL;

	//获取单例
	public static function getInstance() {
		if(!isset(self::$instance)){
			self::$instance = new SubmitDao();
		}
		return self::$instance;
	}

	protected function __construct() {}

	/**
	 * @brief 添加任务
	 * @demo 
		$task = array();
		$task[] = array("module" => CommonConst::MODULE_PASSPORT_ID,"name" => "test","content" => "haha");
		$task[] = array("module" => CommonConst::MODULE_PASSPORT_ID,"name" => "test","content" => "haha2");
		$task_service->addTask($task);
	 */
	public function addTask($task_arr) {
		$task_num = count($task_arr);
		if(!is_array($task_arr) || $task_num == 0) 
		{
			return false;
		}

		$arr = TableService::getDbName(true, NULL);
		$dbname = $arr['db'];
		$this->mysql = DbWrapper::getInstance($dbname);
		$table_name = "common_task_list";

		$sql = 'INSERT INTO ' . $table_name . '(module,name,content,status,ctime) values';
		$i = 1;
		foreach($task_arr as $task) {
			if(isset($task['module']) && isset($task['name']) && isset($task['content'])) {
				$sql .= " (" . $task['module'] . ",'" .mysql_escape_string($task['name']). "','" .mysql_escape_string($task['content']). "'," .CommonConst::TASK_NEW. "," .time(). ")";
				if($i < $task_num) {
					$sql .= ",";
				}
			} else {
				return false;
			}
			$i++;
		}

		$ret = $this->mysql->doUpdateQuery ( $sql );

		if ($ret === false) {
			return false;
		} else {
			return $this->mysql->getLastInsertID();
		}
	}

	public function getTaskList($module, $status) {
		//TODO 优化下边的状态的事物查询更新操作
		return false;
		$arr = TableService::getDbName(true, NULL);
		$dbname = $arr['db'];
		$this->mysql = DbWrapper::getInstance($dbname);
		$table_name = "common_task_list";
		$sql = 'SELECT * FROM ' . $table_name . ' WHERE module = %s and status=%d for update';
		$update_sql = 'update ' . $table_name . ' set status=%d WHERE module = %s and status=%d';
		$this->mysql->autoCommit(false);
		$this->mysql->startTransaction();
		$arr_res = $this->mysql->queryAllRows( $sql, $module, $status);
		if($arr_res) {
			$this->mysql->doUpdateQuery( $update_sql, CommonConst::TASK_DOING ,$module, $status);
		}
		$ret = $this->mysql->commit();
		if (! $arr_res || !$ret) {
			return false;
		}
		return $arr_res;
	}

	public function setTask($taskid, $status) {
		$arr = TableService::getDbName(true, NULL);
		$dbname = $arr['db'];
		$this->mysql = DbWrapper::getInstance($dbname);
		$table_name = "common_task_list";
		if($status == CommonConst::TASK_SUCC) {
			$sql = 'update ' . $table_name . ' set status=%d,ftime=%d WHERE id = %d';
			$ret = $this->mysql->doUpdateQuery($sql, $status, time(), $taskid);
		} else {
			$sql = 'update ' . $table_name . ' set status=%d WHERE id = %d';
			$ret = $this->mysql->doUpdateQuery($sql, $status, $taskid);
		}
		return $ret;
	}
}
