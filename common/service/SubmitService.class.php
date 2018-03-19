<?php
/**
 * 提交服务类
 *  
 **/
class SubmitService {
	private static $instance = NULL;

	//获取单例
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			self::$instance = new SubmitService ();
		}
		return self::$instance;
	}

	protected function __construct() {
	}

	//添加任务
	public function addTask($task_arr) {
		$submit_dao = SubmitDao::getInstance ();
		return $submit_dao->addTask($task_arr);
	}

	//获取任务列表
    public function getTaskList($module, $status) {
		$submit_dao = SubmitDao::getInstance();
		return  $submit_dao->getTaskList($module, $status);
	}

	//设置任务状态
    public function setTask($taskid, $status) {
		$submit_dao = SubmitDao::getInstance ();
		return  $submit_dao->setTask($taskid, $status);
	}
}
