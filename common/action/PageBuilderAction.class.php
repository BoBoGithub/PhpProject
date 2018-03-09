<?php
/**
 * 页面渲染Action，一般作为Action链中的最后一个Action执行
 *  
 **/
class PageBuilderAction extends TemplateBasedAction
{
	public function doExecute()
	{
		$uid = $this->context_params['op_uid'];
		$uname = $this->context_params['op_uname'];

		// Avoid to cache the error page
		if ($this->errno() != CommonConst::SUCCESS) {
			$this->setNoBrowserCache();
		}
		
		// Assign the common template var
		$this->assign('islogin', $this->context_params['is_login']);
		$this->assign('username', $uname);
		$this->assign('userid', $uid);
		$this->assign('mobile', $this->context_params['op_mobile']);
		$this->assign('email', $this->context_params['op_email']);
		$this->assign('session', $this->context_params['session']);
		$this->assign('ispost', $this->is_post);
		$this->assign('errno', $this->errno());
		$this->assign('errmsg', $this->errmsg());
		
		$this->display($this->getTplName());

		$is_invalid_pv = isset($this->action_params['invalid_pv'])
			? (bool)$this->action_params['invalid_pv'] : false;
			
		$is_asyn = isset($this->action_params['is_asyn'])
			? (bool)$this->action_params['is_asyn'] : false;
			
		return true;
	}

}
