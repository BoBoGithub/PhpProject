<?php
/**
 * Sapi基类
 *
 * @param addTime 2017-02-12
 * @param author   ChengBo
 */
class BaseSapi {
	/* The user identity */
	const APP_ID 	 	= 'inner';
	const APP_SECRET 	= '24f6fecd587e53aaa2b67c03dbf093cb';
	
	/* default entrance for pcs server */
	const default_entrance = '/';
	
	/* http request method */
    	const http_get 		= 'GET';
    	const http_put 		= 'PUT';
    	const http_post 	= 'POST';
    	const http_delete	= 'DELETE';
	
    	/* default hostname for pcs server */
    	protected $default_hostname;
	protected $host_name = '';
	protected $json_body = '';
	/* return error */
	protected $error_code = 0;
	protected $error_message = '';
	/* setting */
	protected $debug_mode = false;
	
	/**
	 * construct function
	 *
	 * @param array $auth
	 *        	Authorization type, such as token/cookie/user_id
	 * @return (none)
	 */
	public function __construct($host = NULL) {
		if(empty($host)) {
			$this->host_name = $this->default_hostname;
		} else {
			$this->host_name = $host;
		}
	}
	
	/**
	 * generate url, and send head/get/put/post/delete request to pcs server
	 *
	 * @param string $method
	 *        	method, get/put/post/delete
	 * @param array $opt
	 *        	request argument, including 'url_opts' and 'req_opts'
	 * @return array response
	 */
	protected function authenticate($method, $opt = NULL, $timeout = 10) {
		$url = $this->format_url($opt['url_opts']);
		
		// 获取请求参数
		$query_string = !empty($opt['url_opts']['query_string']) ? $opt['url_opts']['query_string'] : array();
		$query_body   = !empty($opt['req_opts']['body'])&&is_string($opt['req_opts']['body']) ? json_decode($opt['req_opts']['body'], true) : array();
		$params = array_merge($query_string, $query_body);
		
		// 生成token
		$token = $this->generate_token($params, self::APP_SECRET);
		
		// 将appid和token拼接到url后面
		$default_query_params = array('token' => $token,);
		if(!isset($opt['url_opts']['query_string']['appid'])){
			$default_query_params['appid'] = self::APP_ID;
		}
		$query_params = http_build_query($default_query_params); 
		if(strpos($url, '?') !== false) {
			$url .= '&' . $query_params;
		} else {
			$url .= '?' . $query_params;
		}

		/* set request option, including method, header and body */
		$req_opts = array ('method' => $method);
		if(!empty($this->cookie)) {
			$req_opts['header']['cookie'] = $this->cookie;
		}

		//匹配 header头
		if (is_array($opt) && isset($opt["req_header"]) && !empty($opt['req_header'])) {
			$req_opts['header'] = $opt["req_header"];
		}
		if (is_array($opt) && isset($opt["req_opts"])) {
			$req_opts = array_merge_recursive($req_opts, $opt["req_opts"]);
        	}

		//construct request
		$request = new RequestCore($url);
		$this->set_request ( $request, $req_opts);
        	$request->set_timeout($timeout);

		/* send request */
		$request->send_request ();
		$response = new ResponseCore($request->get_response_header(), $request->get_response_body (), $request->get_response_code () );
		//print_r($response);

		/* handle general error */
		$this->json_body = rawurldecode ( $response->body );
		$arr_body = json_decode ( $response->body, true );
		//print_r($arr_body);
		if (is_array ( $arr_body )) {
			if (isset ( $arr_body ['error_code'] )) {
				$this->error_code = $arr_body ['error_code'];
				$this->error_message = $arr_body ['error_msg'];
				return false;
			}
			
			return $arr_body;
		} else {
			$this->error_code = - 1;
			$this->error_message = $response->body;
			return false;
		}
	}
	
	/**
	 * generate http url address
	 *
	 * @param array $opt
	 *        	URL argument
	 * @return string URL address
	 */
	protected function format_url($opt) {
		$url = $this->host_name;
		$url .= self::default_entrance;
		$url .= trim($opt['action'],'/');
		
		if (isset ( $opt ['query_string'] )) {
			$url .= '?';
			
			/* append query string in the end of request url */
			$query_string = "";
			if (is_array ( $opt ['query_string'] )) {
				foreach ( $opt ['query_string'] as $key => $value ) {
					$query_string .= '&' . $key . '=' . rawurlencode ( $value );
				}
			} else {
				$query_string .= '&' . $opt ['query_string'];
			}
			/* remove the first '&' */
			$query_string = substr ( $query_string, 1, strlen ( $query_string ) - 1 );

			$url .= $query_string;
		}
		
		return $url;
	}
	
	/**
	 * set request option
	 *
	 * @param object $request
	 *        	Http Request
	 * @param array $opt
	 *        	Request argument
	 * @return (none)
	 */
	protected function set_request($request, $opt) {
		/* method */
		if (! isset ( $opt ['method'] )) {
			throw new Exception ( 'miss request method in ' . __FUNCTION__ );
		}
		$method = $opt ['method'];
		$request->set_method ( $method );
		
		/* header */
		$headers = array ();
		if (isset ( $opt ['header'] )) {
			if (! is_array ( $opt ['header'] )) {
				throw new Exception ( 'parameter header is not a array in ' . __FUNCTION__ );
			}
			$headers = $opt ['header'];
		}
		foreach ( $headers as $key => $value ) {
			$request->add_header ( $key, $value );
		}
		
		/* body */
		/* 1) content */
		if (isset ( $opt ['body'] )) {
			$request->set_body ( $opt ['body'] );
		}
		
		/* 2) upload file */
		if (isset ( $opt ['upload_file'] )) {
			if (! file_exists ( $opt ['upload_file'] )) {
				throw new Exception ( 'the file does not exist : ' . $opt ['upload_file'] . ' in ' . __FUNCTION__ );
			}
			$request->set_read_file ( $opt ['upload_file'] );
		}
		
		/* 3) follow */
		if (isset ( $opt ['follow'] ) && $opt ['follow'] == true) {
			$request->follow = true;
		}
		
		/* 4) write file */
		if (isset ( $opt ['write_file'] )) {
			$request->set_write_file ( $opt ['write_file'] );
		}
		
		/* 5) debug mode */
		$request->debug_mode = $this->debug_mode;
	}
	
	/**
	 * check path, must not empty and use '/' as prefix character
	 *
	 * @param string $path
	 *        	File or directory path
	 * @return bool If failed, return false; otherwise return true
	 */
	protected function check_path($path) {
		if (isset ( $path ) && ! empty ( $path ) && '/' == substr ( $path, 0, 1 )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * clear error code and error mesage
	 *
	 * @param
	 *        	(none)
	 * @return (none)
	 */
	protected function clear_error() {
		$this->error_code = 0;
		$this->error_message = '';
	}
	
	/**
	 * get error code
	 *
	 * @param
	 *        	(none)
	 * @return int Error Code
	 */
	public function get_error_code() {
		return $this->error_code;
	}
	
	/**
	 * get error message
	 *
	 * @param
	 *        	(none)
	 * @return string Error Message
	 */
	public function get_error_message() {
		return $this->error_message;
	}
	
	/**
	 * set debug mode
	 *
	 * @param bool $debugmode
	 *        	If true, start debug mode; otherwise stop debug mode
	 * @return (none)
	 */
	public function set_debug_mode($debugmode) {
		$this->clear_error ();
		$this->debug_mode = $debugmode;
	}
	
	/**
	 * 生成token
	 * 
	 * @param  array  $params 要计算的参数数组
	 * @param  string $secret 密钥 
	 * @return string token
	 */
	protected function generate_token($params, $secret) {
		$args = array();
		ksort($params);
		foreach($params as $k => $v) {
			$v = is_null($v) ? '' : (is_array($v) ? @join(",", $v) : $v);
			$args[] = @($k . '=' . $v);
		}
		return sha1(md5(join('#', $args)) . '@' . $secret);
	}
}
