<?php
/**
 * $brief Web application entrance.
 *  
 **/
class Application
{
	/**
	 * @var Context
	 */
	protected $context;
	
	/**
	 * @var array
	 */
	protected $rootActionConfig;
	
    /**
     * Constructor: initialize the Application instance.
     * 
     * @return void
     */
    protected function __construct()
    {
        $this->rootActionConfig = array(
    		'ActionController',
    		ActionControllerConfig::$config
    	);
    	$this->context = new Context(); 
    }
    
    /**
     * @return array
     */
    public function getRootActionConfig()
    {
    	return $this->rootActionConfig;
    }
    
	/**
     * Set root action config
     * @param array $rootActionConfig
     * @return Application
     */
    public function setRootActionConfig(array $rootActionConfig)
    {
    	$this->rootActionConfig = $rootActionConfig;
    	return $this;
    }
    
    /**
     * @return Context
     */
    public function getContext()
    {
    	return $this->context;
    }
    
    /**
     * Set the action context for the application.
     * 
     * @param Context $context
     * @return Application
     */
    public function setContext(Context $context)
    {
    	$this->context = $context;
    	return $this;
    }
    
    /**
     * Start the application.
     * 
     * @param bool $isDebug Whether use debug mode or not
     * @return bool
     */
    public function execute($isDebug = false)
    {
    	if ($this->context->initial($this->rootActionConfig, $isDebug)) {
    		return $this->context->callRootAction();    		
    	} else {
    		$errmsg = 'initial the action context failed: rootActionConfig['.var_export($this->rootActionConfig, true).']';
    		trigger_error($errmsg, E_USER_ERROR);
    		return false;
    	}
    }
    
    /**
     * Start an application use the default setting.
     * 
     * @param bool $isDebug Whether use debug mode or not
     * @return bool
     */
    public static function start($isDebug = false)
    {
    	$app = new Application();
    	return $app->execute($isDebug);
    }
}
