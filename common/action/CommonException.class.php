<?php
/**
 * 异常基类
 *  
 **/
class CommonException extends Exception
{
    public function __construct($errno, $errmsg = '')
    {
    	if (empty($errmsg)) {
    		$errmsg = CommonConst::getErrorDesc($errno);
    	}
    	
    	parent::__construct($errmsg, $errno);
    }
}
