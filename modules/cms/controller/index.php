<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | Framework Main Controller			                                          |
// | File name :Index.php                                                 |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: ARUN SADASIVAN<arun.s@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class ControllerIndex extends BaseController
{
	/*
		construction function. we can initialize the models here
	*/
     public function init()
     {     	
        parent::init();
		$this->_common	 = new ModelCommon();
		PageContext::addStyle("cms.css");
		$this->view->setLayout("home");
		PageContext::$body_class 	 = 'cms';
		PageContext::$response->menu =  Cms::loadMenu();
      }

    /*
    function to load the index template
    */
    public function index(){
    	if(PageContext::$request['section']){
			PageContext::$response->section = Cms::loadSection(PageContext::$request);
    	}	
		Logger::info(PageContext::$response->section);
    }
    
    
    
}
?>