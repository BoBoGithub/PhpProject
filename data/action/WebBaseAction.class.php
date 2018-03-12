<?php
/**
 * @brief 要用模板的应继承此类
 *  
 **/
class WebBaseAction extends TemplateBasedAction
{
	//渲染模板
    	protected function buildPage() {
        	// Assign the common template var
		$this->assign('STATIC_HOST', CommonConst::STATIC_HOST);
		$this->assign('DATA_HOST', CommonConst::DATA_HOST); 

		$this->display($this->getTplName());
    	}

}
