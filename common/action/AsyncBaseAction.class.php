<?php
/**
 * 返回json数据的操作应继承此类 
 **/
class AsyncBaseAction extends BaseAction {
	protected $logid = 0;
	protected $outputs = array();
    	public $m_request = array();
	
	public function initial($initObject){
		parent::initial($initObject);
		$this->logid = CLog::logId();
		set_error_handler(array($this,'errorHandler'));
		set_exception_handler(array($this,'exceptionHandler'));

        	$json_request = $this->getJsonBody();
        	$this->m_request = array_merge($this->m_request, $json_request);

		if("GET" == $_SERVER['REQUEST_METHOD']) {
			$this->doGet();
		}elseif ("POST" == $_SERVER['REQUEST_METHOD']) {
			$this->doPost();
		}else {
			throw new Exception(CommonConst::COMMON_HTTPMETHOD_ERROR." http_method[" . $_SERVER['REQUEST_METHOD'] . "] not allowed to use this api");
		}
        
		return true;
	}
	
	public function execute(Context $context, array $actionParams = array()) {
		$this->context_params = $context->getProperty(self::CONTEXT_PARAMS);
		if (!$this->context_params) {
			$this->context_params = array();
		}
		$this->requests = array_merge($_GET, $_POST);
		$this->set('logid', $this->logid);
        $this->set('errno', 0);
        $this->set('errmsg','success');
		$this->outputResponse();
	}
	
	public function doGet() {
		throw new Exception(CommonConst::COMMON_HTTPMETHOD_ERROR." http_method[" . $_SERVER['REQUEST_METHOD'] . "] not allowed to use this api");
	}
	
	public function doPost() {
		throw new Exception(CommonConst::COMMON_HTTPMETHOD_ERROR." http_method[" . $_SERVER['REQUEST_METHOD'] . "] not allowed to use this api");
	}

	protected function outputResponse()	{
		$this->outputAsyncResponse($this->outputs);
	}
	
	protected function set($key, $value = null) {
		$this->outputs [$key] = $value;
	}
	
	protected function _errorHandler($errno, $errstr, $errfile, $errline) {
		if (!($errno & error_reporting())) {
			return false;
		} elseif ($errno === E_USER_NOTICE) {
			CLog::trace('errno[%d] errmsg[%s] file[%s] line[%d] msg[caught trace]', $errno, $errstr, $errfile, $errline);
			return false;
		} elseif ($errno === E_STRICT) {
			return false;
		} else {
			restore_error_handler();
			CLog::fatal('errno[%d] errmsg[%s] file[%s] line[%d] msg[caught error]', $errno, $errstr, $errfile, $errline);
			return true;
		}
	}

	public function errorHandler($errno, $errstr, $errfile, $errline)	{
		$error = func_get_args();
		if (false === $this->_errorHandler($errno, $errstr, $errfile, $errline)) { 
			return;
		}
		header('HTTP/1.1 200 OK');
		$this->set("logid", $this->logid);
		$this->set("errno", CommonConst::COMMON_SERVICE_INVALID);
		$this->set("errmsg", CommonConst::$errorDescs[CommonConst::COMMON_SERVICE_INVALID]);
		$this->outputResponse();
		exit;
	}
	
	protected function _exceptionHandler($ex)	{
		restore_exception_handler();
		$errcode = Utils::getErrorCode($ex);
			
		header("HTTP/1.1 200 OK");
		$this->set("logid", $this->logid);
		$this->set("errno", $errcode);
		$this->set("errmsg", CommonConst::$errorDescs[$errcode]);
		CLog::warning('errcode[%s] trace[%s] msg[Caught exception]', $errcode, $ex->__toString());
	}

	public function exceptionHandler($ex) {
		$this->_exceptionHandler($ex);
		$this->outputResponse();
		exit;
	}

    //将整个body 作为json
    private function getJsonBody() {
        if ($_SERVER ['CONTENT_TYPE'] == 'application/json'){
            $json_request = file_get_contents('php://input');
            $json_request = json_decode($json_request, true);
        } else {
            $json_request = $this->m_request;
        }
        
        if(isset($_REQUEST['XDEBUG'])){
            CLog::debug("XDEBUG, json_request : $json_request");
        }
        
        if(empty($json_request)){
            return array();
        }
        if (NULL == $json_request){
            CLog::trace ( "json_decode get NULL!! " );
            throw new Exception ( Conf_Error::ERROR_PARAM_MALFORMED_JSON );
        }
        return $json_request;
    }
}
