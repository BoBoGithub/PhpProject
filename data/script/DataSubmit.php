<?php
/**
 * Data脚本任务处理入库
 *  
 * @param addTime 2018-03-19
 * @param author   ChengBo
 */

// 定义执行脚本路径, 必须
define('SCRIPT_PATH', dirname(__FILE__));

//引入初始化文件
require_once SCRIPT_PATH . '/../common/env_init.php';

/**
 * DataSubmit 继承BaseSubmit
 * 
 * @param addTime 2018-03-19
 * @param author   ChengBo
 */
class DataSubmit extends BaseSubmit {
	/**
	 * 设置module_id
	 * 
	 * 必须要实现的抽象方法, 设置子类自己的module_id
	 * 
	 * @param addTime 2018-03-19
	 * @param author   ChengBo
	 */
	public function setModuleId() {
		$this->module_id = CommonConst::MODULE_DATA_ID;
	}

}

// 执行脚本
(new DataSubmit)->run();