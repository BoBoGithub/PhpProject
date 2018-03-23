<?php
/**
 * @brief admin test lib
 *  
 **/
class Admin {
	/* http request method */
	const http_get = 'GET';
	const http_put = 'PUT';
	const http_post = 'POST';
	const http_delete = 'DELETE';

	/* user authorize using access-token */
	private $access_token = '';
	/* user authorize using cookie */
	private $cookie = '';
	/* return error */
	private $error_code = 0;
	private $error_message = '';
	/* setting */
	private $debug_mode = false;
	private $use_ssl = false;
	private $app_id = 0;

	public $response_header;
	public $response_code;
	public function __construct() {
	}

	/**
	 ** generate url, and send get/put/post/delete request to server
	 ** @param string $requesttype, such as list create etc.
	 ** @param string $method, get/put/post/delete
	 ** @param array $opt, including opt['url_opts'] and opt['req_opts']
	 ** @return array
	 **/
	private function authenticate($requesttype, $method, $opt = NULL,$header = array()) {
		/* generate full request url, including hostname and querystring */
		$url_opts = array (
			'request_type' => $requesttype, 
		);
		if (is_array($opt) && isset($opt['url_opts'])) {
			$url_opts = array_merge_recursive($url_opts, $opt['url_opts']);
		}
		$url = $this->format_url($url_opts);

		/* set request option, including method, header and body */
		$req_opts = array (
			'method' => $method,
			'header' => $header,
		);
		if (!empty($this->cookie)){
			$req_opts['header']['cookie'] = $this->cookie;
		}
		if (is_array($opt) && isset($opt["req_opts"])) {
			$req_opts = array_merge_recursive($req_opts, $opt["req_opts"]);
		}        
		$request = new RequestCore($url);
		$this->set_request($request, $req_opts);

		/* send request */
		$request->send_request();
		$response = new ResponseCore($request->get_response_header(), $request->get_response_body(), $request->get_response_code());
		/* get the response header and the code */
		$this->response_header = $response->header;
		$this->response_code = $response->status;
		/* handle general error */
		$json_body = $response->body;
		$arr_body = json_decode($json_body, true);
		if (is_array($arr_body)) {
			return $arr_body;
		} else {
			$this->error_code = 0;
			$this->error_message = '';
			return $response->body;
		}
	}

	/**
	 * @brief get response header
	 */	 
	public function get_response_header() {
		return $this->response_header;
	}

	/**
	 *@brief get response code
	 */

	public function get_response_code() {		
		return $this->response_code;
	}

	/**
	 ** generate url
	 ** @param array $opt, url_opts
	 ** @return string
	 **/
	private function format_url($opt) {
		global $_EVN;
		if (!isset($opt['request_type'])) {
			throw new Exception('miss netdisk request type in ' . __FUNCTION__);
		}

		$url = "";
		$url .= $this->use_ssl ? 'https://' : 'http://' . $_ENV['url']."/";
		$url .= $opt['request_type'];

		if (!empty($this->access_token)) {
			$opt['query_string']['access_token'] = $this->access_token;
		} 

		if (isset($opt ['query_string'])) {
			$url .= '?';

			/* append query string in the end of request url */
			$query_string = "";
			if(is_array($opt['query_string'])) {
				foreach ( $opt ['query_string'] as $key => $value ) {
					$query_string .= '&' . $key . '=' . rawurlencode($value);
				}
			} else {
				$query_string .= '&' . $opt['query_string'];
			}
			/* remove the first '&' */
			$query_string = substr($query_string, 1, strlen($query_string) - 1);

			$url .= $query_string;
		}
		return $url;
	}

	/**
	 ** set request option
	 ** @param object $request, RequestCore
	 ** @param array $opt, req_opts
	 ** @return (none)
	 **/
	private function set_request($request, $opt) {
		/* method */
		if (!isset($opt['method'])) {
			throw new Exception('miss request method in ' . __FUNCTION__);
		}
		$method = $opt['method'];
		$request->set_method($method);

		/* header */
		$headers = array();
		if (isset($opt['header'])) {
			if (!is_array($opt['header'])) {
				throw new Exception('parameter header is not a array in ' . __FUNCTION__);
			}
			$headers = $opt['header'];
		}
		foreach($headers as $key => $value) {
			$request->add_header($key, $value);
		}

		/* body */
		/* 1) content */
		if (isset($opt['body'])) {
			$request->set_body($opt['body']);
		}

		/* 2) upload file */
		if (isset($opt['upload_file'])) {
			if (!file_exists($opt['upload_file'])) {
				throw new Exception('the file does not exist : ' . $opt['upload_file'] . ' in ' . __FUNCTION__);
			}
			$request->set_read_file($opt['upload_file']);
		}

		/* 3) follow */
		if (isset($opt['follow']) && $opt['follow'] == true) {
			$request->follow = true;
		} 

		/* 4) write file */
		if (isset($opt['write_file'])) {
			$request->set_write_file($opt['write_file']);
		}

		/* 5) debug mode */
		//$reuqest->debug_mode = $this->debug_mode;

		/* 6) just for test*/
		if (isset($opt['cookie_file'])) {
			$request->set_cookie_file($opt['cookie_file']);
		}
	}

	/**
	 ** clear error
	 **/
	private function clear_error() {
		$this->error_code = 0;
		$this->error_message = '';
	}

	//登录
	public function login($param) {
		$this->clear_error();
		$method = self::http_post;
		$requesttype = 'user/dologin';
		$opts = array();
		if($param) {
			$opt['req_opts']['body'] = json_encode($param);
		}
		$header = array("Content-type"=>"application/json");
		try {
			$ret = $this->authenticate($requesttype, $method, $opt,$header);
			return $ret;
		} catch (Exception $ex) {
			$this->error_code = -1;
			$this->error_message = $ex->getMessage();
			return false;
		}
	}
}
?>

