<?php
/**
 * @brief 日志记录类
 *  
 **/

class CLog
{
	// Log level definition
    const LOG_LEVEL_NONE    = 0x00;
    const LOG_LEVEL_FATAL   = 0x01;
    const LOG_LEVEL_WARNING = 0x02;
    const LOG_LEVEL_NOTICE  = 0x04;
    const LOG_LEVEL_TRACE   = 0x08;
    const LOG_LEVEL_DEBUG   = 0x10;
    const LOG_LEVEL_ALL     = 0xFF;
    
    // Log type definition
    const LOG_TYPE_LOCALLOG	= 'LOCAL_LOG';
    
    /**
     * @var array
     */
    public static $logLevelMap = array(
        self::LOG_LEVEL_NONE    => 'NONE',
        self::LOG_LEVEL_FATAL   => 'FATAL',
        self::LOG_LEVEL_WARNING => 'WARNING',
        self::LOG_LEVEL_NOTICE  => 'NOTICE',
        self::LOG_LEVEL_TRACE	=> 'TRACE',
        self::LOG_LEVEL_DEBUG   => 'DEBUG',
        self::LOG_LEVEL_ALL     => 'ALL',
    );
    
    /**
     * @var array
     */
    public static $logTypes = array(
    	self::LOG_TYPE_LOCALLOG,
    );

    /**
     * Log output device type, can be "LOCAL_LOG", "STDOUT"
     * @var string
     */
    protected $type;
    /**
     * Log level
     * @var int
     */
    protected $level;
    /**
     * Log file path for local log file, or module name for comlog
     * @var string
     */
    protected $path;
    /**
     * Log file name
     * @var string
     */
    protected $filename;
    
    /**
     * Client IP
     * @var string
     */
    protected $clientIP;
    /**
     * Log Id for current request
     * @var uint
     */
    protected $logid;
    /**
     * PHP start time of current request
     * @var uint
     */
    protected $startTime;

    /**
     * @var CLog
     */
    private static $instance = null;

    /**
     * Constructor
     * 
     * @param array $conf
     * @param uint $startTime
     */
    private function __construct(Array $conf, $startTime)
    {
    	$this->type		= $conf['type'];
        $this->level	= $conf['level'];
        $this->path		= $conf['path'];
        $this->filename	= $conf['filename'];
        
        $this->startTime	= $startTime;
        $this->logId		= $this->__logId();
        $this->clientIP		= $this->__clientIP();
        
    }

	/**
	 * @return CLog
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			$startTime = defined('PROCESS_START_TIME') ? PROCESS_START_TIME : microtime(true) * 1000;
			self::$instance = new CLog($GLOBALS['LOG'], $startTime);
		}
		
		return self::$instance;
	}

    /**
     * Write debug log
     * <code>
     * int CLog::debug([int $depth,] string $fmt[, mixed $args[, mixed $...]]) .
     * </code>
     * @param int $depth Nesting depth relative to the log request point
     * @param string $fmt format string
     * @return int
     */
    public static function debug()
    {
    	$args = func_get_args();
    	return CLog::getInstance()->writeLog(self::LOG_LEVEL_DEBUG, $args);
    }
    
	/**
     * Write trace log
     * <code>
     * int CLog::trace([int $depth,] string $fmt[, mixed $args[, mixed $...]]) .
     * </code>
     * @param int $depth Nesting depth relative to the log request point
     * @param string $fmt format string
     * @return int
     */
    public static function trace()
    {
    	$args = func_get_args();
    	return CLog::getInstance()->writeLog(self::LOG_LEVEL_TRACE, $args);
    }

	/**
     * Write notice log
     * <code>
     * int CLog::notice([int $depth,] string $fmt[, mixed $args[, mixed $...]]) .
     * </code>
     * @param int $depth Nesting depth relative to the log request point
     * @param string $fmt format string
     * @return int
     */
    public static function notice()
    {
    	$args = func_get_args();
    	return CLog::getInstance()->writeLog(self::LOG_LEVEL_NOTICE, $args);
    }
    
	/**
     * Write warning log
     * <code>
     * int CLog::warning([int $depth,] string $fmt[, mixed $args[, mixed $...]]) .
     * </code>
     * @param int $depth Nesting depth relative to the log request point
     * @param string $fmt format string
     * @return int
     */
    public static function warning()
    {
    	$args = func_get_args();
    	return CLog::getInstance()->writeLog(self::LOG_LEVEL_WARNING, $args);
    }
    
	/**
     * Write fatal log
     * <code>
     * int CLog::fatal([int $depth,] string $fmt[, mixed $args[, mixed $...]]) .
     * </code>
     * @param int $depth Nesting depth relative to the log request point
     * @param string $fmt format string
     * @return int
     */
    public static function fatal()
    {
    	$args = func_get_args();
    	return CLog::getInstance()->writeLog(self::LOG_LEVEL_FATAL, $args);
    }

    /**
     * Get logId for current http request
     * @return int
     */
    public static function logId()
    {
        return CLog::getInstance()->logId;
    }

    /**
     * Get the real remote client's IP
     * @return string
     */
    public static function getClientIP()
	{
		return CLog::getInstance()->clientIP;
	}

    /**
     * Write log
     * 
     * @param int $level Log level
     * @param array $args format string and parameters
     * @return int
     */
	protected function writeLog($level, Array $args)
	{
		if ($level > $this->level || !isset(self::$logLevelMap[$level])) {
			return 0;
		}
		
		$depth = 1;
		if (is_int($args[0])) {
    		$depth = array_shift($args) + 1;
    	}
		
		$trace = debug_backtrace();
		if ($depth >= count($trace)) {
			$depth = count($trace) - 1;
		}
		$file = basename($trace[$depth]['file']);
		$line = $trace[$depth]['line'];

        $timeUsed = microtime(true)*1000 - $this->startTime;

        $fmt = array_shift($args);
		$str = vsprintf($fmt, $args);
        $str = sprintf( "%s: %s [%s:%d] ip[%s] logId[%u] uri[%s] time_used[%d] %s\n",
                        self::$logLevelMap[$level],
                        date('m-d H:i:s:', time()),
                        $file, $line,
                        $this->clientIP,
                        $this->logId,
                        isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
                        $timeUsed, $str);

		if ($this->type === self::LOG_TYPE_LOCALLOG) {
			$filename = $this->path . '/' . $this->filename;
			if ($level < self::LOG_LEVEL_NOTICE) {
				$filename .= '.wf';
			}
			return file_put_contents($filename, $str, FILE_APPEND | LOCK_EX);
		} else { // use stdout instead
			echo $str . '<br/>';
			return strlen($str);
		}
    }

	private function __clientIP()
	{
        if (isset($_SERVER['HTTP_CLIENTIP'])) {
			$ip = $_SERVER['HTTP_CLIENTIP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&
			$_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1') {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = '127.0.0.1';
		}
		
		$pos = strpos($ip, ',');
		if ($pos > 0) {
			$ip = substr($ip, 0, $pos);
		}
		
		return trim($ip);
    }

	private function __logId()
	{
		$arr = gettimeofday();
		return ((($arr['sec']*100000 + $arr['usec']/10) & 0x7FFFFFFF));
	}
}
