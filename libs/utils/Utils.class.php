<?php
/**
 * @brief 通用方法类
 *  
 **/
class Utils
{
	/**
	 * check if the first arg starts with the second arg
	 *
	 * @param string $str		the string to search in
	 * @param string $needle	the string to be searched
	 * @return bool	true or false
	 * @author zhujt
	 **/
	public static function starts_with($str, $needle)
	{
		$pos = stripos($str, $needle);
		return $pos === 0;
	}

	/**
	 * check if the first arg ends with the second arg
	 *
	 * @param string $str		the string to search in
	 * @param string $needle	the string to be searched
	 * @return bool	true or false
	 * @author zhujt
	 **/
	public static function ends_with($str, $needle)
	{
		$pos = stripos($str, $needle);
		if( $pos === false ) {
			return false;
		}
		return ($pos + strlen($needle) == strlen($str));
	}

	/**
	 * undoes any magic quote slashing from an array, like the $_GET, $_POST, $_COOKIE
	 *
	 * @param array	$val	Array to be noslashing
	 * @return array The array with all of the values in it noslashed
	 * @author zhujt
	 **/
	public static function noslashes_recursive($val)
	{
		if (get_magic_quotes_gpc()) {
			$val = self::stripslashes_recursive($val);
		}
		return $val;
	}

	public static function stripslashes_recursive($var)
	{
		if (is_array($var)) {
			return array_map(array('Utils', 'stripslashes_recursive'), $var);
		} elseif (is_object($var)) {
			$rvar = null;
			foreach ($var as $key => $val) {
				$rvar->{$key} = self::stripslashes_recursive($val);
			}
			return $rvar;
		} elseif (is_string($var)) {
			return stripslashes($var);
		} else {
			return $var;
		}
	}

	/**
	 * Convert string or array to requested character encoding
	 *
	 * @param mix $var	variable to be converted
	 * @param string $in_charset	The input charset.
	 * @param string $out_charset	The output charset
	 * @return mix	The array with all of the values in it noslashed
	 * @see http://cn2.php.net/manual/en/function.iconv.php
	 * @author zhujt
	 **/
	public static function iconv_recursive($var, $in_charset = 'UTF-8', $out_charset = 'GBK')
	{
		if (is_array($var)) {
			$rvar = array();
			foreach ($var as $key => $val) {
				$rvar[$key] = self::iconv_recursive($val, $in_charset, $out_charset);
			}
			return $rvar;
		} elseif (is_object($var)) {
			$rvar = null;
			foreach ($var as $key => $val) {
				$rvar->{$key} = self::iconv_recursive($val, $in_charset, $out_charset);
			}
			return $rvar;
		} elseif (is_string($var)) {
			return iconv($in_charset, $out_charset, $var);
		} else {
			return $var;
		}
	}

	/**
	 * Check if the text is gbk encoding
	 *
	 * @param string $str	text to be check
	 * @return bool
	 * @author zhujt
	 **/
	public static function is_gbk($str)
	{
		return preg_match('%^(?:[\x81-\xFE]([\x40-\x7E]|[\x80-\xFE]))*$%xs', $str);
	}

	/**
	 * Check if the text is utf8 encoding
	 *
	 * @param string $str	text to be check
	 * @return bool Returns true if input string is utf8, or false otherwise
	 * @author zhujt
	 **/
	public static function is_utf8($str)
	{
		return preg_match('%^(?:[\x09\x0A\x0D\x20-\x7E]'.	// ASCII
			'| [\xC2-\xDF][\x80-\xBF]'.				//non-overlong 2-byte
			'| \xE0[\xA0-\xBF][\x80-\xBF]'.			//excluding overlongs
			'| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'.	//straight 3-byte
			'| \xED[\x80-\x9F][\x80-\xBF]'.			//excluding surrogates
			'| \xF0[\x90-\xBF][\x80-\xBF]{2}'.		//planes 1-3
			'| [\xF1-\xF3][\x80-\xBF]{3}'.			//planes 4-15
			'| \xF4[\x80-\x8F][\x80-\xBF]{2}'.		//plane 16
			')*$%xs', $str);
	}

	public static function txt2html($text)
	{
		return htmlspecialchars($text, ENT_QUOTES, 'GB2312');
	}

	/**
	 * Escapes text to make it safe to display in html.
	 * FE may use it in Javascript, we also escape the QUOTES
	 *
	 * @param string $str	text to be escaped
	 * @return string	escaped string in gbk
	 * @author zhujt
	 **/
	public static function escape_html_entities($str)
	{
		return htmlspecialchars($str, ENT_QUOTES, 'GB2312');
	}

	/**
	 * Escapes text to make it safe to use with Javascript
	 *
	 * It is usable as, e.g.:
	 *  echo '<script>alert(\'begin'.escape_js_quotes($mid_part).'end\');</script>';
	 * OR
	 *  echo '<tag onclick="alert(\'begin'.escape_js_quotes($mid_part).'end\');">';
	 * Notice that this function happily works in both cases; i.e. you don't need:
	 *  echo '<tag onclick="alert(\'begin'.txt2html_old(escape_js_quotes($mid_part)).'end\');">';
	 * That would also work but is not necessary.
	 *
	 * @param string $str	text to be escaped
	 * @param bool $quotes	whether should wrap in quotes
	 * @return string
	 * @author zhujt
	 **/
	public static function escape_js_quotes($str, $quotes = false)
	{
		$str = strtr($str, array('\\'	=> '\\\\',
			"\n"	=> '\\n',
			"\r"	=> '\\r',
			'"'	=> '\\x22',
			'\''	=> '\\\'',
			'<'	=> '\\x3c',
			'>'	=> '\\x3e',
			'&'	=> '\\x26'));

		return $quotes ? '"'. $str . '"' : $str;
	}

	public static function escape_js_in_quotes($str, $quotes = false)
	{
		$str = strtr($str, array('\\"'	=> '\\&quot;',
			'"'	=> '\'',
			'\''	=> '\\\'',
		));

		return $quotes ? '"'. $str . '"' : $str;
	}

	/**
	 * Redirect to the specified page
	 *
	 * @param string $url	the specified page's url
	 * @param bool $top_redirect	Whether need to redirect the top page frame
	 * @author zhujt
	 **/
	public static function redirect($url, $top_redirect = true)
	{
		header('Location: ' . $url);
		exit();
	}

	/**
	 * Get current page's real url
	 * 
	 * @return string
	 * @author zhujt
	 **/
	public static function current_url()
	{
		$scheme = 'http';
		if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
			$scheme = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
		} elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$scheme = 'https';
		}

		return $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	/**
	 * Whether current request is https request
	 * 
	 * @return bool
	 * @author zhujt
	 */
	public static function is_https_request()
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
	 * Remove specified params from the query parameters of the url
	 * 
	 * @param string $url
	 * @param array|string $params
	 * @return string
	 */
	public static function remove_queries_from_url($url, $params)
	{
		if (is_string($params)) {
			$params = explode(',', $params);
		}

		$parts = parse_url($url);
		if ($parts === false || empty($parts['query'])) {
			return $url;
		}

		$get = array();
		parse_str($parts['query'], $get);
		foreach ($params as $key) {
			unset($get[$key]);
		}

		$url = $parts['scheme'] . '://' . $parts['host'];
		if (isset($parts['port'])) {
			$url .= ':' . $parts['host'];
		}

		$url .= $parts['path'];
		if (!empty($get)) {
			$url .= '?' . http_build_query($get);
		}

		if (!empty($parts['fragment'])) {
			$url .= '#' . $parts['fragment'];
		}

		return $url;
	}

	/**
	 * Converts charactors in the string to upper case
	 *
	 * @param string $str string to be convert
	 * @return string
	 * @author zhujt
	 **/
	public static function strtoupper($str)
	{
		$uppers =
			array('A','B','C','D','E','F','G','H','I','J','K','L','M','N',
				'O', 'P','Q','R','S','T','U','V','W','X','Y','Z');
		$lowers =
			array('a','b','c','d','e','f','g','h','i','j','k','l','m','n',
				'o','p','q','r','s','t','u','v','w','x','y','z');
		return str_replace($lowers, $uppers, $str);
	}

	/**
	 * Converts charactors in the string to lower case
	 *
	 * @param string $str	string to be convert
	 * @return string
	 * @author zhujt
	 **/
	public static function strtolower($str)
	{
		$uppers =
			array('A','B','C','D','E','F','G','H','I','J','K','L','M','N',
				'O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$lowers =
			array('a','b','c','d','e','f','g','h','i','j','k','l','m','n',
				'o','p','q','r','s','t','u','v','w','x','y','z');
		return str_replace($uppers, $lowers, $str);
	}

	/**
	 * Urlencode a variable recursively, array keys and object property names
	 * will not be encoded, so you would better use ASCII to define the array
	 * key name or object property name.
	 *
	 * @param mixed $var
	 * @return  mixed, with the same variable type
	 * @author zhujt
	 **/
	public static function urlencode_recursive($var)
	{
		if (is_array($var)) {
			return array_map(array('Utils', 'urlencode_recursive'), $var);
		} elseif (is_object($var)) {
			$rvar = null;
			foreach ($var as $key => $val) {
				$rvar->{$key} = self::urlencode_recursive($val);
			}
			return $rvar;
		} elseif (is_string($var)) {
			return urlencode($var);
		} else {
			return $var;
		}
	}

	/**
	 * Urldecode a variable recursively, array keys and object property
	 * names will not be decoded, so you would better use ASCII to define
	 * the array key name or object property name.
	 *
	 * @param mixed $var
	 * @return  mixed, with the same variable type
	 * @author zhujt
	 **/
	public static function urldecode_recursive($var)
	{
		if (is_array($var)) {
			return array_map(array('Utils', 'urldecode_recursive'), $var);
		} elseif (is_object($var)) {
			$rvar = null;
			foreach ($var as $key => $val) {
				$rvar->{$key} = self::urldecode_recursive($val);
			}
			return $rvar;
		} elseif (is_string($var)) {
			return urldecode($var);
		} else {
			return $var;
		}
	}

	/**
	 * Encode a string according to the RFC3986
	 * @param string $s
	 * @return string
	 */
	public static function urlencode3986($var)
	{
		return str_replace('%7E', '~', rawurlencode($var));
	}

	/**
	 * Decode a string according to RFC3986.
	 * Also correctly decodes RFC1738 urls.
	 * @param string $s
	 */
	public static function urldecode3986($var)
	{
		return rawurldecode($var);
	}

	/**
	 * Urlencode a variable recursively according to the RFC3986, array keys
	 * and object property names will not be encoded, so you would better use
	 * ASCII to define the array key name or object property name.
	 *
	 * @param mixed $var
	 * @return  mixed, with the same variable type
	 * @author zhujt
	 **/
	public static function urlencode3986_recursive($var)
	{
		if (is_array($var)) {
			return array_map(array('Utils', 'urlencode3986_recursive'), $var);
		} elseif (is_object($var)) {
			$rvar = null;
			foreach ($var as $key => $val) {
				$rvar->{$key} = self::urlencode3986($val);
			}
			return $rvar;
		} elseif (is_string($var)) {
			return str_replace('%7E', '~', rawurlencode($var));
		} else {
			return $var;
		}
	}

	/**
	 * Urldecode a variable recursively according to the RFC3986, array keys
	 * and object property names will not be decoded, so you would better use
	 * ASCII to define the array key name or object property name.
	 *
	 * @param mixed $var
	 * @return  mixed, with the same variable type
	 * @author zhujt
	 **/
	public static function urldecode3986_recursive($var)
	{
		if (is_array($var)) {
			return array_map(array('Utils', 'urldecode3986_recursive'), $var);
		} elseif (is_object($var)) {
			$rvar = null;
			foreach ($var as $key => $val) {
				$rvar->{$key} = self::urldecode3986_recursive($val);
			}
			return $rvar;
		} elseif (is_string($var)) {
			return rawurldecode($var);
		} else {
			return $var;
		}
	}

	/**
	 * Base64_encode a variable recursively, array keys and object property
	 * names will not be encoded, so you would better use ASCII to define the
	 * array key name or object property name.
	 *
	 * @param mixed $var
	 * @return mixed, with the same variable type
	 * @author zhujt
	 **/
	public static function base64_encode_recursive($var)
	{
		if (is_array($var)) {
			return array_map(array('Utils', 'base64_encode_recursive'), $var);
		} elseif (is_object($var)) {
			$rvar = null;
			foreach ($var as $key => $val) {
				$rvar->{$key} = self::base64_encode_recursive($val);
			}
			return $rvar;
		} elseif (is_string($var)) {
			return base64_encode($var);
		} else {
			return $var;
		}
	}

	/**
	 * Base64_decode a variable recursively, array keys and object property
	 * names will not be decoded, so you would better use ASCII to define the
	 * array key name or object property name.
	 *
	 * @param mixed $var
	 * @return mixed, with the same variable type
	 * @author zhujt
	 **/
	public static function base64_decode_recursive($var)
	{
		if (is_array($var)) {
			return array_map(array('Utils', 'base64_decode_recursive'), $var);
		} elseif (is_object($var)) {
			$rvar = null;
			foreach ($var as $key => $val) {
				$rvar->{$key} = self::base64_decode_recursive($val);
			}
			return $rvar;
		} elseif (is_string($var)) {
			return base64_decode($var);
		} else {
			return $var;
		}
	}

	/**
	 * Remove BOM string (0xEFBBBF in hex) for input string which is added
	 * by windows when create a UTF-8 file.
	 * 
	 * @param string $str
	 * @return string
	 * @author zhujt
	 */
	public static function remove_bom($str)
	{
		if (substr($str, 0, 3) === pack('CCC', 0xEF, 0xBB, 0xBF)) {
			$str = substr($str, 3);
		}
		return $str;
	}

	/**
	 * Generate a unique random key using the methodology
	 * recommend in php.net/uniqid
	 *
	 * @return string a unique random hex key
	 **/
	public static function generate_rand_key()
	{
		return md5(uniqid(mt_rand(), true));
	}

	public static function generate_rand_str($len = 32, $seed = '')
	{
		if (empty($seed)) {
			$seed = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ';
		}
		$seed_len = strlen($seed);
		$word = '';
		//随机种子更唯一
		mt_srand((double)microtime() * 1000000 * getmypid());
		for ($i = 0; $i < $len; ++$i) {
			$word .= $seed{mt_rand() % $seed_len};
		}
		return $word;
	}

	/**
	 * Send email by sendmail command
	 *
	 * @param string $from	mail sender
	 * @param string $to	mail receivers
	 * @param string $subject	subject of the mail
	 * @param string $content	content of the mail
	 * @param string $cc
	 * @return int result of sendmail command
	 * @author zhujt
	 **/
	public static function sendmail($from, $to, $subject, $content, $cc = null)
	{
		if (empty($from) || empty($to) || empty($subject) || empty($content)) {
			return false;
		}

		$mailContent = "To:$to\nFrom:$from\n";
		if (!empty($cc)) {
			$mailContent .= "Cc:$cc";
		}
		$mailContent .= "Subject:$subject\nContent-Type:text/html;charset=utf-8\n\n$content";

		$output = array();
		exec("echo -e '" . $mailContent . "' | /usr/sbin/sendmail -t", $output, $ret);

		return $ret;
	}

	/**
	 * Trim the right '/'s of an uri path, e.g. '/xxx//' will be sanitized to '/xxx'
	 *
	 * @param string $uri URI to be trim
	 * @return string sanitized uri
	 * @author zhujt
	 **/
	public static function sanitize_uri_path($uri)
	{
		$arrUri = explode('?', $uri);
		$arrUri = parse_url($arrUri[0]);
		$path = $arrUri['path'];

		$path = rtrim(trim($path), '/');
		if (!$path) {
			return '/';
		}
		return preg_replace('#/+#', '/', $path);
	}

	/**
	 * Check whether input url has http:// or https:// as its scheme,
	 * if hasn't, it will add http:// as its prefix
	 * @param string $url
	 * @return string
	 */
	public static function http_scheme_auto_complete($url)
	{
		$url = trim($url);
		if (stripos($url, 'http://') !== 0 && stripos($url, 'https://') !== 0) {
			$url = 'http://' . $url;
		}
		return $url;
	}

	/**
	 * Check whether the url is under allowed domains
	 * 
	 * @param string $url Url to be check
	 * @param array|string $allowed_domains domain list in index array or ',' seperated string
	 * @return bool
	 */
	public static function is_domain_allowed($url, $allowed_domains)
	{
		if (is_string($allowed_domains)) {
			$allowed_domains = explode(',', $allowed_domains);
		}

		$host = parse_url($url, PHP_URL_HOST);
		if (empty($host)) {
			return false;
		}

		foreach ($allowed_domains as $domain) {
			if (self::ends_with($host, $domain)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if the two bytes are a chinese charactor
	 *
	 * @param char $lower_chr	lower bytes of the charactor
	 * @param char $higher_chr	higher bytes of the charactor
	 * @return bool Returns true if it's a chinese charactor, or false otherwise
	 * @author liaohuiqin
	 **/
	public static function is_cjk($lower_chr, $higher_chr)
	{
		if (($lower_chr >= 0xb0 && $lower_chr <= 0xf7 && $higher_chr >= 0xa1 && $higher_chr <= 0xfe) ||
			($lower_chr >= 0x81 && $lower_chr <= 0xa0 && $higher_chr >= 0x40 && $higher_chr<=0xfe) ||
			($lower_chr >= 0xaa && $lower_chr <= 0xfe && $higher_chr >= 0x40 && $higher_chr <=0xa0)) {
				return true;
			}
		return false;
	}

	/**
	 * 检查一个字符是否是gbk图形字符
	 *
	 * @param char $lower_chr	lower bytes of the charactor
	 * @param char $higher_chr	higher bytes of the charactor
	 * @return bool Returns true if it's a chinese graph charactor, or false otherwise
	 * @author liaohq
	 **/
	public static function is_gbk_graph($lower_chr, $higher_chr)
	{
		if (($lower_chr >= 0xa1 && $lower_chr <= 0xa9 && $higher_chr >= 0xa1 && $higher_chr <= 0xfe) ||
			($lower_chr >= 0xa8 && $lower_chr <= 0xa9 && $higher_chr >= 0x40 && $higher_chr <= 0xa0)) {
				return true;
			}
		return false;
	}

	/**
	 * 检查字符串中每个字符是否是gbk范围内可见字符，包括图形字符和汉字, 半个汉字将导致检查失败,
	 * ascii范围内不可见字符允许，默认$str是gbk字符串,如果是其他编码可能会失败
	 * 
	 * @param string $str string to be checked
	 * @return  bool 都是gbk可见字符则返回true，否则返回false
	 * @author liaohq
	 **/
	public static function  check_gbk_seen($str)
	{
		$len = strlen($str);
		$chr_value = 0;

		for ($i = 0; $i < $len; $i++) {
			$chr_value = ord($str[$i]);
			if ($chr_value < 0x80) {
				continue;
			} elseif ($chr_value === 0x80) {
				//欧元字符;
				return false;
			} else {
				if ($i + 1 >= $len) {
					//半个汉字;
					return false;
				}
				if (!self::is_cjk(ord($str[$i]), ord($str[$i + 1])) &&
					!self::is_gbk_graph(ord($str[$i]), ord($str[$i + 1]))) {
						return false;
					}
			}
			$i++;
		}
		return true;
	}

	/**
	 * 检查$str是否由汉字/字母/数字/下划线/.组成，默认$str是gbk编码
	 *
	 * @param string $str string to be checked
	 * @return  bool
	 * @author liaohq
	 **/
	public static function check_cjkalnum($str)
	{
		$len = strlen($str);
		$chr_value = 0;

		for ($i = 0; $i < $len; $i++) {
			$chr_value = ord($str[$i]);
			if ($chr_value < 0x80) {
				if (!ctype_alnum($str[$i]) && $str[$i] != '_' && $str[$i] != '.') {
					return false;
				}
			} elseif ($chr_value === 0x80) {
				//欧元字符;
				return false;
			} else {
				if ($i + 1 >= $len) {
					//半个汉字;
					return false;
				}
				if (!self::is_cjk(ord($str[$i]), ord($str[$i + 1]))) {
					return false;
				}
				$i++;
			}
		}
		return true;
	}

	/**
	 * 检查字符串是否是gbk汉字，默认字符串的编码格式是gbk
	 *
	 * @param string $str string to be checked
	 * @return  bool
	 * @author liaohq
	 **/
	public static function check_cjk($str)
	{
		$len = strlen($str);
		$chr_value = 0;

		for ($i = 0; $i < $len; $i++) {
			$chr_value = ord($str[$i]);
			if ($chr_value <= 0x80) {
				return false;
			} else {
				if ($i + 1 >= $len) {
					//半个汉字;
					return false;
				}
				if (!self::is_cjk(ord($str[$i]), ord($str[$i + 1]))) {
					return false;
				}
				$i++;
			}
		}
		return true;
	}

	/**
	 * check whether the url is safe
	 * 
	 * @param string $url	URL to be checked
	 * @return bool
	 * @author zhujt
	 **/
	public static function is_valid_url($url)
	{
		if (strlen($url) > 0) {
			if (!preg_match('/^https?:\/\/[^\s&<>#;,"\'\?]+(|#[^\s<>"\']*|\?[^\s<>"\']*)$/i',
				$url, $match)) {
					return false;
				}
		}
		return true;
	}

	/**
	 * check whether the email address is valid
	 * 
	 * @param string $email Email to be checked
	 * @return bool
	 * @author zhujt
	 **/
	public static function is_valid_email($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
		/*
		if (strlen($email) > 0) {
			if (!preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/i',
							$email, $match)) {
				return false;
			}
		}
		return true;
		 */
	}

	/**
	 * Check whether the email is in the specified whitelist domains
	 * @param string $email Email to be checked
	 * @param array|string $whitelist Domain list seperated by ',' or an index array
	 * @return bool
	 * @author zhujt
	 */
	public static function is_email_in_whitelist($email, $whitelist)
	{
		if (!self::is_valid_email($email)) {
			return false;
		}

		if (is_string($whitelist)) {
			$whitelist = explode(',', $whitelist);
		}

		list($user, $domain) = explode('@', $email);
		if (empty($domain)) {
			return false;
		}

		return in_array($domain, $whitelist);
	}

	/**
	 * Check whether it is a valid phone number
	 * 
	 * @param string $phone	Phone number to be checked
	 * @return bool
	 * @author zhujt
	 **/
	public static function is_valid_phone($phone)
	{
		if (strlen($phone) > 0) {
			if (!preg_match('/^([0-9]{11}|[0-9]{3,4}-[0-9]{7,8}(-[0-9]{2,5})?)$/i',
				$phone, $match)) {
					return false;
				}
		}
		return true;
	}

	/**
	 * Check whether it is a valid ip list, each ip is delemited by ','
	 * 
	 * @param string $iplist Ip list string to be checked
	 * @return bool
	 * @author zhujt
	 **/
	public static function is_valid_iplist($iplist)
	{
		$iplist = trim($iplist);
		if (strlen($iplist) > 0) {
			if (!preg_match('/^(([0-9]{1,3}\.){3}[0-9]{1,3})(,(\s)*([0-9]{1,3}\.){3}[0-9]{1,3})*$/i',
				$iplist, $match)) {
					return false;
				}
		}
		return true;
	}

	/**
	 * Generate a signature.  Should be copied into the client
	 * library and also used on the server to validate signatures.
	 *
	 * @param array	$params	params to be signatured
	 * @param string $secret	secret key used in signature
	 * @param string $namespace	prefix of the param name, all params whose name are equal
	 * with $namespace will not be put in the signature.
	 * @return string md5 signature
	 **/
	public static function generate_sig($params, $secret, $namespace = 'sig')
	{
		$str = '';
		ksort($params);
		foreach ($params as $k => $v) {
			if ($k != $namespace && !is_null($v)) {
				$str .= "$k=$v";
			}
		}
		$str .= $secret;
		return md5($str);
	}

	/**
	 * Generate a 64 unsigned number signature.
	 *
	 * @param array	$params	params to be signatured
	 * @return int 64 unsigned number signature
	 **/
	public static function sign64($value) {
		$str = md5 ( $value, true );
		$high1 = unpack ( "@0/L", $str );
		$high2 = unpack ( "@4/L", $str );
		$high3 = unpack ( "@8/L", $str );
		$high4 = unpack ( "@12/L", $str );
		if(!isset($high1[1]) || !isset($high2[1]) || !isset($high3[1]) || !isset($high4[1]) ) {
			return false;
		}
		$sign1 = $high1 [1] + $high3 [1];
		$sign2 = $high2 [1] + $high4 [1];
		$sign = ($sign1 & 0xFFFFFFFF) | ($sign2 << 32);
		return sprintf ( "%u", $sign );
	}

	/**
	 * Generate a number mod result.
	 *
	 * @param int	$number	params to be mod
	 * @param int	$mod	params to mod
	 * @return int mod result of the number
	 **/
	public static function mod($number, $mod) {
		if(0 < intval($number)) {
			return $number%$mod;
		}
		$length = strlen($number);
		$left = 0;
		for($i = 0; $i < $length; $i++) {
			$digit = substr($number, $i, 1);
			$left = intval($left.$digit);
			if($left < $mod) {
				continue;
			}else if($left == $mod) {
				$left = 0;
				continue;
			}else{
				$left = $left%$mod;
			}
		}
		return $left;
	}

	public static function getHash($hashKey, $subTable) {
		if (! is_numeric ( $hashKey ) && ! is_string ( $hashKey )) {
			return false;

		}
		if (is_numeric ( $hashKey )) {
			$hash = $hashKey;
		} else {
			$str = strval ( $hashKey );
			$hash = self::getHashFromString ( $str );
		}
		if (intval ( $hash ) > 0) {
			$ret = $hash % $subTable;
		} else {
			$ret = Utils::mod ( $hash, $subTable );
		}
		return $ret;
	}

	public static function getHashFromString($str) {
		if (empty ( $str )) {
			return 0;
		}
		$h = 0;
		for($i = 0, $j = strlen ( $str ); $i < $j; $i = $i + 3) {
			$h = 5 * $h + ord ( $str [$i] );
		}
		return $h;
	}

	/**
	 * Check the array contains key or not.
	 *
	 * @param array	$arr_need	keys must exist
	 * @param array $arr_arg	array to check
	 * @return boolean true | false
	 **/
	static function check_exist_array($arr_need, $arr_arg) {
		$arr_diff = array_diff ( $arr_need, array_keys ( $arr_arg ) );
		if (! empty ( $arr_diff )) {
			return false;
		}
		return true;
	}

	/**
	 * Check the int input is valid or not.
	 *
	 * @param int $value	number value
	 * @param int $max max value to check
	 * @param int $min min value to check
	 * @param boolean $compare true to check max,false not to check max
	 * @return boolean true | false
	 **/
	static function check_int($value, $min = 0, $max = -1, $compare = true) {
		if(is_null($value)) {
			return false;
		}
		if(!is_numeric($value)) {
			return false;
		}
		if(intval($value) != $value) {
			return false;
		}
		if(true === $compare && $value < $min) {
			return false;
		}
		if(true === $compare && 0 <= $max && $max < $value) {
			return false;
		}
		
		return true;
	}

	/**
	 * Check the string input length is valid or not.
	 *
	 * @param int $value	string value
	 * @param int $max_length max value length to check
	 * @param int $min_length min value length to check
	 * @return boolean true | false
	 **/
	static function check_string($value, $max_length = NULL, $min_length = 1) {
		if(is_null($value)) {
			return false;
		}
		if(strlen($value) < $min_length) {
			return false;
		}
		if(!is_null($max_length) && strlen($value) > $max_length) {
			return false;
		}
		
		return true;
	}

	//按字符串生成hash数值
	public static function hash_string($str)
	{   
		if (empty($str)) return 0;
		$h = 0;
		for ($i=0,$j=strlen($str); $i<$j; $i=$i+2)
		{   
			$h = 5*$h + ord($str[$i]);
		}   
		return $h; 
	}  

	public static function getErrorCode($ex) {
		$errcode = $ex->getMessage();
		if (0 < ($pos = strpos($errcode,' '))) {
			$errcode = substr($errcode, 0, $pos); 
		}   
		return $errcode;
	}
	/**
      * 字符串加密以及解密函数
      *
      * @param string $string 原文或者密文
      * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
      * @param string $key 密钥
      * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
      * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
      *
      * @example
      *
      *     $a = authcode('abc', 'ENCODE', 'key');
      *     $b = authcode($a, 'DECODE', 'key'); // $b(abc)
      *
      *     $a = authcode('abc', 'ENCODE', 'key', 3600);
      *     $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
      */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

         $ckey_length = 4;    // 随机密钥长度 取值 0-32;
                     // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
                     // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
                     // 当此值为 0 时，则不产生随机密钥

         $key = md5($key ? $key : 'BJCB');
         $keya = md5(substr($key, 0, 16));
         $keyb = md5(substr($key, 16, 16));
         $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

         $cryptkey = $keya.md5($keya.$keyc);
         $key_length = strlen($cryptkey);

         $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
         $string_length = strlen($string);

         $result = '';
         $box = range(0, 255);

         $rndkey = array();
         for($i = 0; $i <= 255; $i++) {
             $rndkey[$i] = ord($cryptkey[$i % $key_length]);
         }

         for($j = $i = 0; $i < 256; $i++) {
             $j = ($j + $box[$i] + $rndkey[$i]) % 256;
             $tmp = $box[$i];
             $box[$i] = $box[$j];
             $box[$j] = $tmp;
         }

         for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
             $box[$a] = $box[$j];
            $box[$j] = $tmp;
             $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
         }

         if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
             } else {
                 return '';
             }
         } else {
             return $keyc.str_replace('=', '', base64_encode($result));
         }

     }
	 
	/**
	 * 字符截取 支持UTF8/GBK
	 * @param  $string 要截取的字符串
	 * @param  $length 截取长度
	 * @param  $dot	   后缀符
	 *
	 * @return $string
	 *
	 * @param Copy From phpcms/func by ChengBo
	 * @param addTime 2014-03-10
	 */
    public static function str_cut($string, $length, $dot = '...') {
		$strlen = strlen($string);
		if ($strlen <= $length)
			return $string;
		$string = str_replace(array(' ', '&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵', ' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
		$strcut = '';
		$length = intval($length - strlen($dot) - $length / 3);
		$n = $tn = $noc = 0;
		while ($n < strlen($string)) {
			$t = ord($string[$n]);
			if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1;
				$n++;
				$noc++;
			} elseif (194 <= $t && $t <= 223) {
				$tn = 2;
				$n += 2;
				$noc += 2;
			} elseif (224 <= $t && $t <= 239) {
				$tn = 3;
				$n += 3;
				$noc += 2;
			} elseif (240 <= $t && $t <= 247) {
				$tn = 4;
				$n += 4;
				$noc += 2;
			} elseif (248 <= $t && $t <= 251) {
				$tn = 5;
				$n += 5;
				$noc += 2;
			} elseif ($t == 252 || $t == 253) {
				$tn = 6;
				$n += 6;
				$noc += 2;
			} else {
				$n++;
			}
			if ($noc >= $length) {
				break;
			}
		}
		if ($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
		$strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);

		return $strcut . $dot;
	}


	//生成随机数
	public static function randomNumber($num='6'){
		for ($i=0; $i < $num; $i++) { 
		   $str = rand(0,9);
		   $newNum .= $str;
		}
		return  $newNum;
	}

	/**
	 * 生成随机字符串
	 *
	 * @param int $len 要生成的字符串长度
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public static function getRandomString($len = 0){
		//定义字符源
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; 

		//定义返回随机字符串
		$returnStr	= "";
		$strLen		= strlen($chars)-1;
		for($i = 0; $i<$len; $i++){
			$returnStr .= $chars[mt_rand(0, $strLen)];
		}

		return $returnStr;
	}

	/**
	 * 验证用户名合法性
	 * @param  $username 要验证的用户名
	 *
	 * @return bool
	 */
	public static function  check_username($username){
		if(trim($username)){
			return false;
		}
        $rule = '/^[a-zA-Z][a-zA-Z0-9_]{3,13}$/';
        preg_match($rule,$username,$result);  
        if(empty($result)){
        	return false;
        } 
        return true;  
	}


	 /**
	 * 分页函数
	 *
	 * @param $num 信息总数
	 * @param $curr_page 当前分页
	 * @param $perpage 每页显示数
	 * @param $urlrule URL规则
	 * @param $array 需要传递的数组，用于增加额外的方法
	 * @return 分页
	 */
	public static function pages($num, $curr_page, $perpage = 20, $urlrule = '', $setpages = 10) {
		if($urlrule == ''){
    	    $urlrule = self::url_par('page={$page}');
    	}
	    $multipage = '';
	    if ($num > $perpage) {
	        $page = $setpages + 1;
	        $offset = ceil($setpages / 2 - 1);
	        $pages = ceil($num / $perpage);
	        if (defined('IN_ADMIN') && !defined('PAGES'))
	            define('PAGES', $pages);
	        $from = $curr_page - $offset;
	        $to = $curr_page + $offset;
	        $more = 0;
	        if ($page >= $pages) {
	            $from = 2;
	            $to = $pages - 1;
	        } else {
	            if ($from <= 1) {
	                $to = $page - 1;
	                $from = 2;
	            } elseif ($to >= $pages) {
	                $from = $pages - ($page - 2);
	                $to = $pages - 1;
	            }
	            $more = 1;
	        }
	        $multipage .= '';//'共<font>' . $pages . '</font>页<font>' . $num . '</font>条&nbsp;';
	        if ($curr_page > 0) {
				$multipage .= '<p>';
	            $multipage .= $curr_page == 1 ? '': '<a href="' . self::pageurl($urlrule, 1) . '">首页</a>';
	            $multipage .= $curr_page == 1 ? '': '<a href="' . self::pageurl($urlrule, $curr_page - 1) . '">上一页</a>';
	            if ($curr_page == 1) {
	                $multipage .= '<span class="current">1</span>';
	            } elseif ($curr_page > 2*$offset && $more) {
	                $multipage .= '<a href="' . self::pageurl($urlrule, 1) . '">1</a> ...&nbsp;&nbsp;';
	            } else {
	                $multipage .= '<a href="' . self::pageurl($urlrule, 1) . '">1</a>';
	            }
	        }
	        for ($i = $from; $i <= $to; $i++) {
	            if ($i != $curr_page) {
	                $multipage .= '<a href="' . self::pageurl($urlrule, $i) . '">' . $i . '</a>';
	            } else {
	                $multipage .= '<span class="current">'.$i.'</span>';
	            }
	        }
	        if ($curr_page < $pages) {
	            if ($curr_page < $pages - 1 - $offset && $more) {
	                $multipage .= ' ...&nbsp;&nbsp;<a href="' . self::pageurl($urlrule, $pages) . '">' . $pages . '</a>';
	            } else {
	                $multipage .= '<a href="' . self::pageurl($urlrule, $pages) . '">' . $pages . '</a>';
	            }
	        } elseif ($curr_page == $pages) {
	            $multipage .= '<span class="current">' . $pages . '</span>';
	        } else {
	            $multipage .= '<a href="' . self::pageurl($urlrule, $pages, $array) . '">' . $pages . '</a>';
	        }
	        
	        $multipage .= $curr_page == $pages ? '' : '<a href="' . self::pageurl($urlrule, $curr_page + 1) . '">下一页</a>';
	        $multipage .= $curr_page == $pages ? '' : '<a href="' . self::pageurl($urlrule, $pages) . '">末页</a>';
			$multipage .= '</p>';
	    }
	    return $multipage;
	}

	/**
	 * 返回分页路径
	 *
	 * @param $urlrule 分页规则
	 * @param $page 当前页
	 * @param $array 需要传递的数组，用于增加额外的方法
	 * @return 完整的URL路径
	 */
	private static function pageurl($urlrule, $page) {
	    if (strpos($urlrule, '~')) {
	        $urlrules = explode('~', $urlrule);
	        $urlrule = $page < 2 ? $urlrules[0] : $urlrules[1];
	    }
	    $findme = array('{$page}');
	    $replaceme = array($page);
	    
	    $url = str_replace($findme, $replaceme, $urlrule);
	    $url = str_replace(array('http://', '//', '~'), array('~', '/', 'http://'), $url);
	    return $url;
	}

	/**
	 * URL路径解析，pages 函数的辅助函数
	 *
	 * @param $par 传入需要解析的变量 默认为，page={$page}
	 * @param $url URL地址
	 * @return URL
	 */
	private static function url_par($par, $url = '') {
	    if ($url == '')
	        $url = self::get_url();
	    $pos = strpos($url, '?');
	    if ($pos === false) {
	        $url .= '?' . $par;
	    } else {
	        $querystring = substr(strstr($url, '?'), 1);
	        parse_str($querystring, $pars);
	        $query_array = array();
	        foreach ($pars as $k => $v) {
	            if ($k != 'page')
	                $query_array[$k] = $v;
	        }
	        $querystring = http_build_query($query_array) . '&' . $par;
	        $url = substr($url, 0, $pos) . '?' . $querystring;
	    }
	    return $url;
	}

	 /**
	 * 获取当前页面完整URL地址
	 */
	private static function get_url() {
	    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	    $php_self = $_SERVER['PHP_SELF'] ? ($_SERVER['PHP_SELF']) : ($_SERVER['SCRIPT_NAME']);
	    $path_info = isset($_SERVER['PATH_INFO']) ? ($_SERVER['PATH_INFO']) : '';
	    $relate_url = isset($_SERVER['REQUEST_URI']) ? ($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . ($_SERVER['QUERY_STRING']) : $path_info);
	    return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
	}

	/**
	 * 检查邮箱地址
	 *
	 * @param string $email 邮箱地址
	 *
	 * @param addTime 2018-03-05
	 * @param author  ChengBo
	 */
	public static function check_email($email){
		//设置匹配正则
		$pregStr = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";

		//检查邮箱是否正确
		return preg_match($pregStr, $email);
	}
}
