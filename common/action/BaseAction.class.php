<?php
/**
 * action基础类
 *  
 **/
class BaseAction extends Action
{
	/**
	 * Property key name for context data.
	 * 
	 * @var string
	 */
	const CONTEXT_PARAMS = 'context_params';

	/**
	 * Action data, which is only used in current Action instance.
	 * 
	 * @var mixed
	 */
	protected $action_params;

	/**
	 * Context data passed between Actions, only available when execute() method be called.
	 * 
	 * @var array
	 */
	protected $context_params;

	/**
	 * Charset of http response for current request.
	 * 
	 * @var string
	 */
	protected $charset = 'utf-8';

	/**
	 * array_merge($_GET, $_POST)
	 *  
	 * @var array
	 */	
	protected $requests = array();

	/**
	 * Whether is a POST request
	 * 
	 * @var bool
	 */
	protected $is_post = false;

	/**
	 * Whether we are under debug mode or not.
	 * 
	 * @var bool
	 */
	protected $is_debug = false;

	/**
	 * Initialize current action
	 * 
	 * @param mix $initObject Params for current action
	 * @return bool
	 */
	public function initial($initObject)
	{
		$this->action_params = $initObject;

		if (empty($this->action_params['charset'])) {
			$this->charset = defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8';
		} else {
			$this->charset = $this->action_params['charset'];
		}

		if (defined('IS_DEBUG') && IS_DEBUG && intval($_GET['test']) === 1) {
			$this->is_debug = true;
		}

		$this->is_post = (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0);

		$this->requests = array_merge($_GET, $_POST);

		return true;
	}

	protected function get($strVarName) {
		if (empty ( $strVarName )) {
			return NULL;
		}

		if (isset ( $this->requests[$strVarName] )) {
			return $this->requests[$strVarName];
		} else {
			return NULL;
		}
	}

	/**
	 * @param Context $context	Bingo Context instance
	 * @param array $actionParams	Params for current Action
	 * @return bool
	 */
	public function execute(Context $context, array $actionParams = array())
	{
		$this->context_params = $context->getProperty(self::CONTEXT_PARAMS);
		if (!$this->context_params) {
			$this->context_params = array();
		}

		$ret = false;
		try{
			$this->preExecute();
			$ret = $this->doExecute();
		}catch(Exception $e){
			CLog::warning('class[%s] msg[Exception caught when execute] errmsg[%s] line[%d] file[%s]',get_class($this),$e->getMessage(), $e->getLine(), $e->getFile());
			$this -> setErr($e->getCode(), $e->getMessage());
			trigger_error('Exception caught when execute ['. get_class($this).'] errmsg: '.$e->getMessage() . ' line ' . $e->getLine() . ' file ' . $e->getFile());
		}

		$context->setProperty(self::CONTEXT_PARAMS, $this->context_params);

		return $ret;
	}

	/**
	 * Before Excute Action do something
	 *
	 * @return bool
	 */
	protected function preExecute(){
		return true;
	}
	
	/**
	 * Actions extends from BaseAction should override the doPost() or doGet()
	 * interface, or directly override this interface, or directly override
	 * the execute() interface.
	 * 
	 * @return bool
	 */
	protected function doExecute()
	{
		switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
		case 'POST':
			return $this->doPost();

		case 'PUT':
			return $this->doPut();

		case 'DELETE':
			return $this->doDelete();

		default:
			return $this->doGet();
		}
	}

	/**
	 * Actions extends from BaseAction and is designed for PUT request should
	 * override this interface, or directly override the doExecute() interface.
	 * 
	 * @return bool
	 */
	protected function doPut()
	{
		return true;
	}

	/**
	 * Actions extends from BaseAction and is designed for POST request should
	 * override this interface, or directly override the doExecute() interface.
	 * 
	 * @return bool
	 */
	protected function doPost()
	{
		return true;
	}

	/**
	 * Actions extends from BaseAction and is designed for DELETE request should
	 * override this interface, or directly override the doExecute() interface.
	 * 
	 * @return bool
	 */
	protected function doDelete()
	{
		return true;
	}

	/**
	 * Actions extends from BaseAction and is designed for GET request should
	 * implement this interface, or directly implement the doExecute() interface.
	 * 
	 * @return bool
	 */
	protected function doGet()
	{
		return true;
	}

	/**
	 * Actions extend from BaseAction should override this interface when
	 * need some customized processing for csrf attack.
	 */
	protected function doCsrfAttackPrevention()
	{
		$this->output404Page();
	}

	/**
	 * @param int $errno
	 * @param string $errmsg
	 */
	protected function setErr($errno, $errmsg = '')
	{
		$this->context_params['errno'] = $errno;
		$this->context_params['errmsg'] = $errmsg;
	}

	/**
	 * Get last error no.
	 * 
	 * @return int
	 */
	protected function errno()
	{
		return isset($this->context_params['errno']) ?
			intval($this->context_params['errno']) : 0;
	}

	/**
	 * Get last error message.
	 * 
	 * @return string
	 */
	protected function errmsg()
	{
		return isset($this->context_params['errmsg']) ?
			$this->context_params['errmsg'] : '';
	}

	/**
	 * Set Cache-Control and Expires header for response to use browser cache.
	 * 
	 * @param int $timeout Seconds to timeout
	 * @return void
	 */
	protected function setBrowserCache($timeout)
	{
		header('Cache-Control: max-age=' . $timeout);
		header('Expires: ' . gmdate('D, d M Y H:i:s', $timeout + time()) . ' GMT');
	}

	/**
	 * Set Cache-Control and Pragma header to avoid browser cache
	 * 
	 * @return void
	 */
	protected function setNoBrowserCache()
	{
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');	
	}

	/**
	 * Set Content Type header for response
	 * example:
	 * <code>
	 * 	$this->setContentType('text/html');
	 * </code>
	 * 
	 * @param string $mime_type Content type value, no need to specify charset
	 * @return void
	 */
	protected function setContentType($mime_type)
	{
		header("Content-Type: $mime_type;charset=$this->charset");
	}

	/**
	 * Get refer url.
	 * 
	 * @return string
	 */
	protected function getRefer()
	{
		return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	}

	/**
	 * Build query string.
	 * 
	 * @param array $params
	 * @return string
	 */
	protected function buildQueryString(array $params)
	{
		foreach ($params as $key => $val) {
			if (!$val) {
				unset($params[$key]);
			}
		}
		return http_build_query($params);
	}

	/**
	 * Whether is a https request or not
	 * 
	 * @return bool
	 */
	protected function isHttpsRequest()
	{
		$scheme = 'http';
		if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
			$scheme = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
		} elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$scheme = 'https';
		}
		return ($scheme == 'https');
	}

	/**
	 * Redirect to the related url in https scheme if it's a http get request,
	 * or directly exit if it's a http post request.
	 * 
	 * @return void
	 */
	protected function forceToHttps()
	{
		if (!$this->isHttpsRequest()) {
			if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
				CLog::warning('msg[exit as it should be https]');
				exit();
			} else {
				CLog::debug('msg[redirect to use https]');
				$this->redirectAndExit('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}
		}
	}

	/**
	 * Output the response for async request.
	 * 
	 * @param mixed $value
	 * @return void
	 */
	protected function outputAsyncResponse($value)
	{
		$json = json_encode($value);

		$callback = isset($_GET['callback']) ? preg_replace('/[^\w\._()]/', '', $_GET['callback']) : '';

		if ($callback) {
			$this->setContentType('text/javascript');
			echo "$callback($json)";
		} else {
			$this->setContentType('application/json');
			echo $json;
		}
	}

	/**
	 * Output 404 error page.
	 */
	protected function output404Page()
	{
		header('HTTP/1.1 404 Not Found');
		exit();
	}

	/**
	 * Redirect to the specified URL and exit the php process
	 * 
	 * @param string $url
	 * @return void
	 */
	protected function redirectAndExit($url)
	{
		header("Location: $url");
		exit();	
	}
	/**
	 * 接受kookie信息，如果saltkey值不为空进行解密
	 * 查询session是否存在，
	 * 如果当前时间减活跃时间大于1小时， 那么更新session过期时间
	 * 
	 * @param string $cookie
	 * @return array $uid | false
	 */
	protected function checkLoginByCookie() {
		$cookie=$_COOKIE;
		if(!isset($cookie) || empty($cookie['saltkey'])){
			return false;
		}

		$uid = Utils::authcode($cookie['saltkey'], 'DECODE', PassportConfig::$passportKey);
		if(!isset($uid) || empty($uid) || $uid<=0){
			return false;
		}

		$user_service = UserService::getInstance();
		$ret = $user_service->getSession($uid);
		if(empty($ret) ||  !is_array($ret)){
			return false;
		}
		if(time()-$ret['activeTime'] > PassportConfig::$sessionupdateInterval){
			//echo time()-$ret['activeTime'];
			$ret['activeTime'] = time();
			$user_service->setSession($uid,$ret,$ret['addtTime']);
		}
		//print_r($user_service->getSession($uid));
		return array('uid'=>$uid);

	}

}
