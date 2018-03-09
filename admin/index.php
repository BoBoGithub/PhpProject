<?php
/**
 * 程序单一入口文件
 **/
require_once(dirname(__FILE__) .'/common/env_init.php');
Application::start(defined('IS_DEBUG') ? IS_DEBUG : false);
