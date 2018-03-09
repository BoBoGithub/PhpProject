<?php
/**
 * @brief 数据库操作包装
 *  
 **/
class DbWrapper extends Db
{
	/**
	 * Get a DbWrapper instance for the specified database.
	 *
	 * @see Db::getInstance()
	 *
	 * @param string $database
	 * @return DbWrapper
	 */
	public static function getInstance($database)
	{
        return self::_getInstance(__CLASS__, $database);
	}

	/**
	 * Query all the result rows, each row as associated array
	 * Caller should pass all the argument for format string following the $format parameter
	 * example:
	 * <code>
	 * $ret = $db->queryAllRows('SELECT * FROM tb WHERE uid=%d', $uid);
	 * </code>
	 *
	 * @param string $format	SQL query string template
	 * @return bool|array	Return array on success or false on failure
	 */
	public function queryAllRows($format)
	{
		$argv = func_get_args();
		$sql = call_user_func_array(array($this, 'buildSqlStr'), $argv);
		return $this->__doSelectQuery($format, $sql, 1);
	}

	/**
	 * Query all the result rows, each row as associated array
	 * @param string $format	SQL query string template, can be empty,
	 * 							just for Log printing when needed
	 * @param string $sql		SQL query string
	 * @return bool|array		Return array on success or false on failure
	 */
	public function queryAllRowsEx($format, $sql)
	{
		return $this->__doSelectQuery($format, $sql, 1);
	}

	/**
	 * Query the first row of the result as associated array
	 * Caller should pass all the argument for format string following the $format parameter
	 * example:
	 * <code>
	 * $ret = $db->queryFirstRow('SELECT * FROM tb WHERE uid=%d', $uid);
	 * </code>
	 *
	 * @param string $format	SQL query string template
	 * @return bool|array		Return array on success or false on failure
	 */
	public function queryFirstRow($format)
	{
		$argv = func_get_args();
		$sql = call_user_func_array(array($this, 'buildSqlStr'), $argv);
		return $this->__doSelectQuery($format, $sql, 2);
	}

	/**
	 * Query the first row of the result as associated array
	 *
	 * @param string $format	SQL query string template, can be empty,
	 * 							just for Log printing when needed
	 * @param string $sql		SQL query string
	 * @return bool|array		Return array on success or false on failure
	 */
	public function queryFirstRowEx($format, $sql)
	{
		return $this->__doSelectQuery($format, $sql, 2);
	}

	/**
	 * Query the specified field value of the first result row
	 * Caller should pass all the argument for format string following the $format parameter
	 * example:
	 * <code>
	 * $ret = $db->querySpecifiedField('SELECT uname FROM tb WHERE uid=%d', $uid);
	 * </code>
	 *
	 * @param string $format	SQL query string template
	 * @return bool|string		The specified field value on success or false on failure
	 */
	public function querySpecifiedField($format)
	{
		$argv = func_get_args();
		$sql = call_user_func_array(array($this, 'buildSqlStr'), $argv);
		return $this->__doSelectQuery($format, $sql, 3);
	}

	/**
	 * Query the specified field value of the first result row
	 *
	 * @param string $format	SQL query string template, can be empty,
	 * 							just for Log printing when needed
	 * @param string $sql		SQL query string
	 * @return bool|string		The specified field value on success or false on failure
	 */
	public function querySpecifiedFieldEx($format, $sql)
	{
		return $this->__doSelectQuery($format, $sql, 3);
	}

	/**
	 * Do update query according to the SQL query string template and its arguments
	 * Caller should pass all the argument for format string following the $format parameter
	 * example:
	 * <code>
	 * $ret = $db->doUpdateQuery('UPDATE tb SET uname=%s WHERE uid=%d', $uname, $uid);
	 * </code>
	 *
	 * @param string $format	SQL query string template
	 * @return bool	Return true on success or false on failure
	 */
	public function doUpdateQuery($format)
	{
		$argv = func_get_args();
		$sql = call_user_func_array(array($this, 'buildSqlStr'), $argv);
		if (empty($sql)) {
			$this->__buildSqlStrError($format, 2);
			return false;
		}
		if (parent::doUpdateQuery($sql) === false) {
			$this->__sqlQueryError();
			CLog::warning("execute sql[%s] err[%s]",$sql,$this->getErrmsg());
			return false;
		}
		return true;
	}

	/**
	 *
	 * @param string $format	SQL query string template, can be empty,
	 * 							just for Log printing when needed
	 * @param string $sql		SQL query string
	 * @return bool	Return true on success or false on failure
	 */
	public function doUpdateQueryEx($format, $sql)
	{
		if (empty($sql)) {
			$this->__buildSqlStrError($format, 2);
			return false;
		}

		if (parent::doUpdateQuery($sql) === false) {
			$this->__sqlQueryError();
			return false;
		}
		return true;
	}

	private function __doSelectQuery($format, $sql, $mode, $log_trace_depth = 1)
	{
		if (empty($sql)) {
			$this->__buildSqlStrError($format, $log_trace_depth + 1);
			return false;
		}

		switch ($mode) {
			case 1:	//select all rows
				$ret = parent::queryAllRows($sql);
				break;

			case 2:	//select first row(or select single row in the other word)
				$ret = parent::queryFirstRow($sql);
				break;

			case 3:	//select the specified field
				$ret = parent::querySpecifiedField($sql);
				break;

			default:
				$ret = false;
				break;
		}

		if ($ret === false) {
			$this->__sqlQueryError($log_trace_depth + 1);
			return false;
		}

		return $ret;
	}

	private function __errorMessage()
	{
		return sprintf('module[dbproxy] errcode[%d] errmsg[%s] sql[%s]',
			$this->getErrno(), $this->getErrmsg(), $this->getSqlStr());
	}

	private function __buildSqlStrError($format, $log_trace_depth = 1)
	{
	    $errmsg = "module[dbproxy] buildSqlStr for [$format] failed";
	    //trigger_error($errmsg, E_USER_WARNING);
		CLog::warning($log_trace_depth + 1, $errmsg);
	}

	private function __sqlQueryError($log_trace_depth = 1)
	{
		$errmsg = $this->__errorMessage();
		//trigger_error($errmsg, E_USER_WARNING);
		//CLog::warning($log_trace_depth + 1, $errmsg);
	}
}

