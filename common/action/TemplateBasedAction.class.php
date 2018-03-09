<?php
/**
 * 带Smarty模板action基类
	 All Action classes extends from TemplateBasedAction should:
	 - call assign() to assign template variable
	 - call display() to render the template
	 - call fetch() to fetch rendered templated output
	 - print all the template variables' value by adding test=1 in Query String
	 - override doCsrfAttackPrevention() to do customized prevention, or just
	 implement the tplForCurrentPage() interface to specify the template
	 file name and let FE to do the csrf attack prevention 
 *  
 **/
class TemplateBasedAction extends BaseAction
{
	/**
	 * Template file for current request, actions extends from TemplateBasedAction
	 * should init it before execute() be called.
	 * 
	 * @var string
	 */
	protected $tpl = '';
	protected $error_tpl = CommonConst::ERROR_TPL;

	/**
	 * Smarty instance.
	 * @var Smarty
	 */
	protected $smarty;

	public function initial($initObject)
	{
		parent::initial($initObject);

		set_error_handler(array($this, 'errorHandler'));

		$this->smarty = ResourceFactory::getSmartyInstance();
		return true;
	}

	protected function doExecute()
	{
		$this->setTplName($this->tpl);

		return parent::doExecute();
	}

	/**
	 * Set smarty template file name.
	 * 
	 * @param string $tplName Template file name
	 * @return void
	 */
	protected function setTplName($tplName)
	{
		$this->context_params['tplname'] = $tplName;
	}

	/**
	 * Get smarty template file name.
	 * 
	 * @return string
	 */
	protected function getTplName()
	{
		return $this->context_params['tplname'];
	}

	/**
	 * Assign value to a smarty template var.
	 * 
	 * @param string $var	Template variable name
	 * @param mix $value	Template variable value
	 * @return void
	 */
	protected function assign($var, $value)
	{
		if ($this->is_debug) {
			echo "<br><h1>var:$var:<br></h1>";
			var_dump($value);
		}
		$this->smarty->assign($var, $value);
	}

	/**
	 * Display a smarty template page.
	 * 
	 * @param string $tplName Template file name
	 * @return void
	 */
	protected function display($tplName)
	{
		$this->smarty->display(APP_NAME . '/' . $tplName);
	}

	/**
	 * Fetch rendered smarty template output.
	 * 
	 * @param string $tplName Template file name
	 * @return string
	 */
	protected function fetch($tplName)
	{
		return $this->smarty->fetch(APP_NAME . '/' . $tplName);
	}

	/**
	 * Csrf attack prevention strategy for template based actions.
	 * 
	 * @return void
	 */
	protected function doCsrfAttackPrevention()
	{
		if (empty($this->tpl)) {
			parent::doCsrfAttackPrevention();
		} else {
			// All action chains for template based pages contain the
			// PageBuilderAction, so we just need to set the template
			// file name.
			$this->setTplName($this->tpl);
		}
	}

	/**
	 * Custom error handler, it will exit the process
	 * 
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile may not exit
	 * @param int $errline may not exit
	 */
	public function errorHandler($errno, $errstr, $errfile, $errline)
	{
		if (!($errno & error_reporting())) {
			return false;
		}

		//记录错误日志
		CLog::warning('errno[%d] errmsg[%s] file[%s] line[%d] msg[caught warning]', $errno, $errstr, $errfile, $errline);
		
		if($errno == E_USER_NOTICE || $errno == E_STRICT) {
		}

		if ( !empty($this->error_tpl) ) {
			$this->setTplName($this->error_tpl);
			$this->buildPage();
		}
		exit(0);
	}
}
