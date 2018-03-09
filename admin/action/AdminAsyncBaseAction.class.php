<?php
/**
 * @brief 返回json数据的操作应继承此类
 *  
 **/
class AdminAsyncBaseAction extends BaseAction {
	protected $logid = 0;
	protected $outputs = array();
    	public $m_request = array();
	
	//后台管理用户uid
	protected $adminUid	= 0;
	protected $adminUserInfo= array();
	
	public function initial($initObject) {
		parent::initial($initObject);
		$this->logid = CLog::logId();
		set_error_handler(array($this,'errorHandler'));
		set_exception_handler(array($this,'exceptionHandler'));
		
	        $request = array();
       		if(!empty($_POST)){
            		$request = array_merge($_POST, $_GET);
        	}elseif(!empty($_GET)){
            		$request = $_GET;
        	}
        
        	if(!empty($request)){
            		$this->__trim_request_deep($request);
            		$this->m_request = $request;
        	}
        
        	$json_request = $this->getJsonBody();
        	$this->m_request = array_merge($this->m_request, $json_request);

		//检查是否是登录页面
		if($this->actionClassName != "UserDoLoginAction"){
			//检查登录状态
			$adminUserData = AdminUserService::getInstance()->checkUserLoginStatus();
			if(empty($adminUserData)){
				throw new Exception(CommonConst::ADMIN_USER_NOT_LOGIN." not login access action[".$this->actionClassName."]");
			}

			//设置登陆用户UID
			$this->adminUid		= $adminUserData['uid'];
			$this->adminUserInfo	= $adminUserData;
			
			//检查是否有访问权限
			$vistPermit = PermitService::getInstance()->checkRolePriv($adminUserData['roleid'], $this->action_params['url']);
			if(!$vistPermit){
				throw new Exception(CommonConst::COMMON_NO_PERMISSION.' no permission access');	
			}
		}
		
		if("GET" == $_SERVER['REQUEST_METHOD']){
			$this->doGet();
		}elseif("POST" == $_SERVER['REQUEST_METHOD']){
			$this->doPost();
		}else{
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

	protected function outputResponse(){
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
		if ((defined('IS_DEBUG') && IS_DEBUG)) {
			unset($error[4]);
			echo "<pre>\n";
			print_r($error);
			echo "\n</pre>";
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
		if ((defined('IS_DEBUG') && IS_DEBUG)) {
			echo "<pre>\n";
			print_r($ex->__toString());
			echo "\n</pre>";
		}
		$this->outputResponse();
		exit;
	}

    //将整个body 作为json
    private function getJsonBody() {
        if ($_SERVER ['CONTENT_TYPE'] == 'application/json') {
            $json_request = file_get_contents ( 'php://input' );
            $json_request = json_decode ( $json_request, true );
        } else {
            $json_request = $this->m_request;
        }
        
        if (isset($_REQUEST['XDEBUG'])){
            Log::debug ( "XDEBUG, json_request : $json_request" );
        }
        
        if (empty ( $json_request )) {
            return array ();
        }
        if (NULL == $json_request) {
            Log::trace ( "json_decode get NULL!! " );
            throw new Exception ( Conf_Error::ERROR_PARAM_MALFORMED_JSON );
        }
        return $json_request;
    }
    
    private function __trim_request_deep(&$value) {
        if (is_array ( $value )) {
            foreach ( $value as &$v ) {
                $this->__trim_request_deep ( $v );
            }
        } else {
            $value = trim ( $value );
        }
        return true;
    }



    public function get($strVarName) {
        if (empty ( $strVarName )) {
            return NULL;
        }
        if (isset ( $this->m_request [$strVarName] )) {
            return $this->m_request [$strVarName];
        } else {
            return NULL;
        }
	}
}
