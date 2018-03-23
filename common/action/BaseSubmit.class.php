<?php
/**
 * BaseSubmit 任务队列抽象基类
 *
 * @param addTime 2018-03-19
 * @param author    ChengBo
 */
abstract class BaseSubmit {
	/**
	 * 最大执行次数
	 * 
	 * @var integer
	 */
	const RUN_MAX_NUM = 100;
	
	/**
	 * 模块ID
	 * 
	 * @var integer
	 */
	protected $module_id;
	
	/**
	 * 脚本名, 用于help提示, 不应该改动
	 * 
	 * @var string
	 */
	protected $script_name;
	
	/**
	 * 脚本前缀, 用于调用lib目录下的子脚本, 一般不需要改动
	 * 
	 * @var string
	 */
	protected $script_prefix;
	
	/**
	 * 日志文件名, 用于日志记录, 一般不需要改动
	 * 
	 * @var string
	 */
	protected $log_filename;
	
	protected $not_retry_scripts = array();
	
	/**
	 * 构造函数
	 * 
	 * 子类如果重写该方法, 必须先调用该方法
	 */
	public function __construct() {
		$this->setModuleId();		// 设置module_id
		$this->setScriptName();		// 设置script_name
		$this->setScriptPrefix();	// 设置script_prefix
		$this->setLogFileName();	// 设置log_filename
	}
	
	/**
	 * 设置module_id
	 *
	 * 抽象方法, 子类必须实现该方法设置自己的module_id
	 */
	abstract public function setModuleId();
	
	/**
	 * 设置script_name
	 *
	 * 默认情况下, script_name的值是当前脚本名(包括.php)
	 */
	public function setScriptName() {
		global $argv;
		$this->script_name = basename($argv[0]);
	}
	
	/**
	 * 设置script_prefix
	 *
	 * 默认情况下, script_prefix的值是当前脚本名去掉.php的部分
	 */
	public function setScriptPrefix() {
		$this->script_prefix = substr($this->script_name, 0, stripos($this->script_name, '.'));
	}
	
	/**
	 * 设置log_filename
	 *
	 * 默认情况下, log_filename的值是script_prefix + '.log'
	 */
	public function setLogFileName() {
		$this->log_filename  = $this->script_prefix . '.log';
	}
	
	/**
	 * 打印使用帮助信息
	 * 
	 */
	public function help() {
		echo 'Use command [php ' . $this->script_name . ' status]' . "\n";
	}
	
	/**
	 * 执行主体
	 * 
	 * @param string $status
	 */
	public function execute($status) {
		//脚本启动日志
		CLog::debug(date('Y-m-d H:i:s').' one thread task restart  ', 'script_submit_message_'.date("Y-m-d").'.log');
		
		//设置执行参数
		global $argv;
        	$total 		= isset($argv[1]) ? $argv[1] : NULL;
        	$current	= isset($argv[2]) ? $argv[2] : NULL;
		$runCount 	= 0;
		
		while(1) {
			//设置结束条件
			if($runCount >= BaseSubmit::RUN_MAX_NUM) {
				//一个进程执行完毕
				CLog::debug(date('Y-m-d H:i:s').' one thread task end ', 'script_submit_message_'.date("Y-m-d").'.log');
				exit();
			}

			//调取任务表数据新任务数据
			$taskList = SubmitService::getInstance()->getTaskList($this->module_id, CommonConst::TASK_NEW, $total, $current);

			//处理任务
			if(is_array($taskList) && count($taskList) > 0) {
				foreach($taskList as $item) {
					//设置脚本路径
					$scriptFile = SCRIPT_PATH . '/lib/' . $this->script_prefix . '_' . $item['name'] . '.class.php';
					if(!file_exists($scriptFile)){
						//更新任务状态为失败 
						SubmitService::getInstance()->setTask($item['id'], CommonConst::TASK_FAIL);
						
						//记录日志
						CLog::debug(date('Y-m-d H:i:s').' file not exists '.$item['name'], 'script_submit_message_'.date("Y-m-d").'.log');
						
						continue;
					}

					//引入脚本路径
					require_once(SCRIPT_PATH . '/lib/' . $this->script_prefix . '_' . $item['name'] . '.class.php');

					//设置脚本处理类
					$class = ucfirst($item['name']) . 'Submit';

					//这里设置循环 考虑以后重试的问题
					while(1) {
						try{
							//解析/检查参数
							$content = json_decode($item['content'], true);
							if(!is_array($content)) {
								//参数错误更新队列为失败
								SubmitService::getInstance()->setTask($item['id'], CommonConst::TASK_FAIL);break;
							}

							//检查是否存在处理方法
							if(method_exists($class, 'execute')){
								//执行脚本
								$execResult = $class::execute($content, $item['ctime']);
							}else{
								//记录日志 没有处理的方法
								CLog::debug(date('Y-m-d H:i:s').' taskName:'.$item['name'].' not have deal function ', 'script_submit_message_'.date("Y-m-d").'.log');
								
								//如果没有处理方法 直接更新为失败
								$execResult = false;
							}
							
							//处理队列状态
							if($execResult){
								//更新队列为执行成功
								$updExecResult = SubmitService::getInstance()->setTask($item['id'], CommonConst::TASK_SUCC);
								if($updExecResult){
									//记录完成的日志
									CLog::debug(date('Y-m-d H:i:s').' itemID:'.$item['id'].' task finish ', 'script_submit_message_'.date("Y-m-d").'.log');
								}else{
									//记录更新失败日志
									CLog::debug(date('Y-m-d H:i:s').' itemID:'.$item['id'].' set status failue ', 'script_submit_message_'.date("Y-m-d").'.log');
								}
							}else{
								//更新队列为失败状态
								$updExecResult = SubmitService::getInstance()->setTask($item['id'], CommonConst::TASK_FAIL);
								if($updExecResult){
									//记录对流处理失败日志
									CLog::debug(date('Y-m-d H:i:s').' itemID:'.$item['id'].' task exec failure ', 'script_submit_message_'.date("Y-m-d").'.log');
								}else{
									//记录更新状态失败日志
									CLog::debug(date('Y-m-d H:i:s').' itemID:'.$item['id'].' task exec status failure ', 'script_submit_message_'.date("Y-m-d").'.log');
								}
							}
						}catch(Exception $ex){
							//程序异常更新队列状态
							$updEexcptionRet = SubmitService::getInstance()->setTask($item['id'], CommonConst::TASK_FAIL);
							
							//记录失败日志
							CLog::debug(date('Y-m-d H:i:s').' itemID:'.$item['id'].' task exec exception result:'.var_export($updEexcptionRet, true), 'script_submit_message_'.date("Y-m-d").'.log');
						}
						
						//结束当前的执行
						break;
					}
				}
			} else {
				//没有任务空闲3s
				//CLog::debug(date('Y-m-d H:i:s').' task free sleep 3s execNum:'.$runCount, 'script_submit_message_'.date("Y-m-d").'.log');
				sleep(3);
			}
			
			//执行次数累加
			$runCount++;
		}
	}
	
	/**
	 * 执行脚本
	 * 
	 */
	public function run() {
		$arr_option = getopt('vhe');
		if(isset($arr_option['h'])) {
			$this->help();exit;
		}
		if(isset($arr_option['v'])){
			echo 'script version 0.1'.PHP_EOL;exit;
		}
		
		if(isset($arr_option['e'])) {
			$status = 'error';
		} else {
			$status = 'new';
		}
		$this->execute($status);
	}
}

?>
