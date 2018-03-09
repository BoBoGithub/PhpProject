<?php
/**
 * @brief ActionChain supports actions to be executed in sequence order,
   someone could use this action in logic flow.
 *  
 **/
class ActionChain extends Action
{
	protected $actions = array();
	protected $pos = -1;

	/**
	 * Initialize the action chain
	 * 
	 * @param array $actions Array of actions
	 * @return true
	 */
	public function initial(array $actions)
	{
		$this->actions = $actions;
		$this->pos = -1;
		
		return true;
	}

	/**
	 * Append an action to the action chain.
	 * 
	 * @param string $actionClassName Action class name
	 * @param mixed $initObject Init params for the action instance
	 * @return bool
	 */
	public function addAction($actionClassName, $initObject = null)
	{
		return $this->addActionByIndex(count($this->actions), $actionClassName, $initObject);
	}

	/**
	 * Add an action after the action specified by the index.
	 * 
	 * @param int $index The action index after which we add an action to.
	 * @param string $actionClassName Action class name
	 * @param mixed $initObject Init params for the action instance
	 * @return Action
	 */
	public function addActionByIndex($index, $actionClassName, $initObject = null)
	{
		if (!is_string($actionClassName) || strlen($actionClassName) <= 0) {
			$errmsg = 'actionClassName is invalid: actionClassName['
					. var_export($actionClassName, true) . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return false;
		}
			
		$index = intval($index);
		if ($index < 0) {
			$index = 0;
		} elseif ($index >= count($this->actions)) {
			$index = count($this->actions);
		}
		
		if ($index <= $this->pos) {
			$errmsg = 'Can not add action before executed ActionChain position: '
				. 'index[' . var_export($index, true) . '] ActionChain.pos[' . $this->pos . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return false;
		}
		
		if ($index == count($this->actions)) {
			$this->actions[] = array($actionClassName, $initObject);
		} else {
			$beforeArr = array_slice($this->actions, 0, $index);
			$afterArr = array_slice($this->actions, $index);
			$newArr = array(array($actionClassName, $initObject));
			$this->actions = array_merge($beforeArr, $newArr, $afterArr);
		}
		return true;
	}

	/**
	 * Remove an action from action chain by the action class name.
	 * 
	 * @param string $actionClassName The action class name
	 * @return bool
	 */
	public function removeAction($actionClassName)
	{
		foreach ($this->actions as $index => &$actionInfo) {
			if (is_array($actionInfo) && count($actionInfo) > 0) {
				if ($actionInfo[0] == $actionClassName) {
					return $this->removeActionByIndex($index);
				}
			} else {
				$errmsg = 'ActionChain.actions config invalid: ActionChain.actions.index['
					. $index . '] ActionChain.actions.actionInfo['
					. var_export($actionInfo, true) . ']';
				trigger_error($errmsg, E_USER_ERROR);
			}
		}

		$errmsg = 'ActionChain.actions does not have this action: actionClassName['
			. var_export($actionClassName, true) . '] ActionChain.actions['
			. var_export($this->actions, true) . ']';
		trigger_error($errmsg, E_USER_ERROR);
		return false;
	}

	/**
	 * Remove action from action chain by index.
	 * 
	 * @param int $index
	 * @return bool
	 */
	public function removeActionByIndex($index)
	{
		$index = intval($index);
		if ($index >= count($this->actions)) {
			$index = count($this->actions) - 1;
		}

		if ($index <= $this->pos) {
			$errmsg = 'can not remove action before executed ActionChain position: index['
				. $index . '] ActionChain.pos[' . $this->pos
				. '] ActionChain.actions[' . var_export($this->actions, true) . ']';
			trigger_error($errmsg, E_USER_ERROR);
			return false;
		}
		$beforeArr = array_slice($this->actions, 0, $index);
		$afterArr = array_slice($this->actions, $index + 1);
		$this->actions = array_merge($beforeArr, $afterArr);
		return true;
	}

	/**
	 * @return array
	 */
	public function getActions()
	{
		return $this->actions;
	}
	
	/**
	 * @param array $actions
	 * @return ActionChain
	 */
	public function setActions(array $actions)
	{
		$this->actions = $actions;
		$this->pos = -1;
		return $this;
	}

	/**
	 * Start execution of the action chain.
	 * 
	 * @param Context $context Context object for all the actions in action chain
	 * @param array $actionParams Params for the action
	 * @return bool Ture if the action chain has finish the request proccessing,
	 * 				or false if otherwise
	 */
	public function execute(Context $context, array $actionParams = array())
	{		
		$this->pos++;
		$count = count($this->actions);
		for (; $this->pos < $count; $this->pos++) {
			$i = $this->pos;
			$actionInfo = $this->actions[$i];
			$cnt = count($actionInfo);
			if (!is_array($actionInfo) || $cnt <= 0) {
				$errmsg = 'The ' . $i . 'th action in ActionChain is invalid: '
					. 'ActionChain.actions[' . var_export($this->actions, true) . ']';
				trigger_error($errmsg, E_USER_ERROR);
				$this->pos = -1;
				return false;
			}
			
			$actionClassName = $actionInfo[0];
			if (1 == $cnt) {
				$initObject = null;
			} else {
				$initObject = $actionInfo[1];
			}
			
			$action = $context->getAction($actionClassName, $initObject);
			if (!($action instanceof Action)) {
				$errmsg = 'ActionChain->execute() getAction failed: actionClassName['
					. $actionClassName . '] ActionChain.index[' . $i
					. '] ActionChain.actions[' . var_export($this->actions, true) . ']';
				trigger_error($errmsg, E_USER_ERROR);
				$this->pos = -1;
				return false;
			}
			
			$ret = $context->callAction($actionClassName, $actionParams);
			if (false === $ret) {
				$errmsg = 'some action execute failed in the ActionChain: actionClassName['
					. $actionClassName . '] ActionChain.index[' . $i . ']';
				trigger_error($errmsg, E_USER_ERROR);
				$this->pos = -1;
				$context->setLastFailedAction($action);
				return false;
			}
		}
		
		$this->pos = -1;
		return true;
	}
}
