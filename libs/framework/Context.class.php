<?php
/**
 * @brief  action基础类
 * @brief Runtime Context for actions.
 *  
 **/
class Context
{
	/**
	 * @var Action
	 */
	protected $rootAction;

	/**
	 * Context property dictionary
	 * @var array
	 */
	protected $dict;
	
	/**
	 * Caches for the initialized actions
	 * @var array
	 */
	protected $actions;
	
	/**
	 * Determin whether the application is under debug mode or not.
	 * @var bool
	 */
	protected $isDebug;
	
	private $actionStack;
	private $actionExecedStack;
	private $actionExecedStackPos;
	
	/**
	 * The instance of last execute failed action.
	 * @var Action
	 */
	private $lastFailedAction;
	
	/**
	 * Initialize the action context.
	 * 
	 * @return bool
	 */
	public function initial(array $rootActionConfig, $isDebug = false)
	{
		if (count($rootActionConfig) < 2) {
			$errmsg = 'Root Action Config invalid: ' . var_export($rootActionConfig, true);
			trigger_error($errmsg, E_USER_ERROR);
			return false;
		}
		$this->dict = array();
		$this->actions = array();
		$this->isDebug = $isDebug;
		$this->actionStack = array();
		$this->actionExecedStack = array();
		$this->actionExecedStackPos = 1;
		
		$action = $this->getAction($rootActionConfig[0], $rootActionConfig[1]);
		if (!$action) {
			$errmsg = 'Create root action failed: actionClassName['.var_export($rootActionConfig[0], true).']';
			trigger_error($errmsg, E_USER_ERROR);
			return false;
		}

		$this->rootAction = $action;
		return true;
	}
	
	/**
	 * Set last failed action
	 * @param Action $action
	 * @return Context
	 */
	public function setLastFailedAction(Action $action)
	{
		$this->lastFailedAction = $action;
		return $this;
	}

	/**
	 * Get current executing action.
	 * 
	 * @return Action
	 */
	public function getCurrentAction()
	{
		if (0 >= count($this->actionStack)) {
			return null;
		}
		return $this->actionStack[count($this->actionStack) - 1];
	}

	/**
	 * Get current action stack.
	 * @return array
	 */
	public function getActionStack()
	{
		return $this->actionStack;
	}

	/**
	 * Get the parent action for current executing action.
	 * @return Action
	 */
	public function getCurrentActionParent()
	{
		if (1 >= count($this->actionStack)) {
			return null;
		}
		return $this->actionStack[count($this->actionStack) - 2];
	}

	/**
	 * Get debug information.
	 * @return string
	 */
	public function getDebugInfo()
	{
		return var_export($this, true);
	}

	/**
	 * Get the executed actions' stack.
	 * @return array
	 */
	public function getActionExecedStack()
	{
		if ($this->isDebug) {
			return $this->actionExecedStack;
		}
		return null;
	}

	public function getAllStack()
	{
		return debug_backtrace();
	}

	public function printAllStack()
	{
		debug_print_backtrace();
	}

	/**
	 * Modify the specified property.
	 * 
	 * @param string $key Property key
	 * @param mixed $value Property value
	 * @return bool
	 */
	public function modifyProperty($key, $value)
	{
		if (!is_string($key) || 0 >= strlen($key)) {
			$errmsg = 'property key is invalid: key[' . var_export($key, true) . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return false;
		}

		$this->dict[$key] = $value;
		return true;
	}

	/**
	 * Get the specified property's value.
	 * @param string $key Property key
	 * @return mixed
	 */
	public function getProperty($key)
	{
		if (!is_string($key) || 0 >= strlen($key)) {
			$errmsg = 'property key is invalid: key[' . var_export($key, true) . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return null;
		}
		
		if (!isset($this->dict[$key])) {
			return null;
		}
		
		return $this->dict[$key];
	}
	
	/**
	 * Set value to the specified property.
	 * 
	 * @param string $key Property key
	 * @param mixed $value Property value
	 * @return bool
	 */
	public function setProperty($key, $value)
	{
		if (!is_string($key) || 0 >= strlen($key)) {
			$errmsg = 'property key is invalid: key[' . var_export($key, true) . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return false;
		}
		$this->dict[$key] = $value;
		return true;
	}

	/**
	 * Get an Action instance according to the action class path, and initialize it
	 * 
	 * @param string $actionClassName Action class name
	 * @param mixed $initObject Init params for the action instance
	 * @return Action
	 */
	public function getAction($actionClassName, $initObject = null)
	{
		if (array_key_exists($actionClassName, $this->actions)) {
			return $this->actions[$actionClassName];
		}
		
		$action = Action::getAction($actionClassName, $initObject);
		if (!$action) {
			return null;
		}
		
		$this->actions[$actionClassName] = $action;
		return $action;
	}

	/**
	 * Execute the specified action.
	 * 
	 * @param string $actionClassName action class name
	 * @param array $actionParams Params for the action
	 * @return bool
	 */
	public function callAction($actionClassName, array $actionParams = array())
	{
		$action = $this->getAction($actionClassName);
		if (!$action) {
			return false;
		}

		$this->actionStack[count($this->actionStack)] = $action;
		if ($this->isDebug) {
			// compute in stack prefix
			$inPrefix = '';
			for ($i = 0; $i < $this->actionExecedStackPos; $i++) {
				$inPrefix .= '>>>';
			}
			$this->actionExecedStack[] = $inPrefix . $actionClassName;
			$this->actionExecedStackPos++;
		}
		
		// execute action
		$ret = $action->execute($this, $actionParams);

		unset($this->actionStack[count($this->actionStack) - 1]);
		if ($this->isDebug) {
			// compute out stack prefix
			$this->actionExecedStackPos--;
			$outPrefix = '';
			for ($i = 0; $i < $this->actionExecedStackPos; $i++) {
				$outPrefix .= '<<<';
			}
			$this->actionExecedStack[] = $outPrefix . $actionClassName;
		}
		
		return $ret;
	}
	
	/**
	 * Execute the root action.
	 * 
	 * @param array $actionParams params for root action
	 * @return bool
	 */
	public function callRootAction(array $actionParams = array())
	{
		return $this->callAction($this->rootAction->actionClassName, $actionParams);
	}
}
