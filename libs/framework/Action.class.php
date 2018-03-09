<?php
/**
 * Base class for all actions
 **/
abstract class Action
{
	public $actionClassName;

	/**
	 * Get an Action instance according to the action class path, and initialize it
	 * 
	 * @param string $actionClassName Action class name
	 * @param mixed $initObject Init params for the action instance
	 * @return Action
	 */
	public static function getAction($actionClassName, $initObject = null)
	{
		if (!is_string($actionClassName) || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/i', $actionClassName)) {
			$errmsg = 'action class name invalid: actionClassName['.var_export($actionClassName, true) . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return null;
		}

		$actionObject = new $actionClassName;
		if (!($actionObject instanceof Action)) {
			$errmsg = 'The reflected object is not an Action class: actionClassName['. $actionClassName . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return null;
		}
		
		$actionObject->actionClassName = $actionClassName;
		if (true !== $actionObject->initial($initObject)) {
			$errmsg = 'Failed to initial the action: actionClassName[' . $actionClassName . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return null;
		}
		
		return $actionObject;
	}

	/**
	 * Initialize the action instance.
	 * 
	 * @param string $initObject Params for the action instance
	 * @return bool True if success, or false if otherwise
	 */
	public function initial($initObject)
	{
		return true;
	}

	/**
	 * Execute the action.
	 * 
	 * @param Context $context Context object for all the actions in action chain
	 * @param array $actionParams Params for the action
	 * @return bool Ture if the action has finish the request proccessing, or false if otherwise
	 */
	public abstract function execute(Context $context, array $actionParams = array());
}
