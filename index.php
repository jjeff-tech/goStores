<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | File name : index.php                                                |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: BINU CHANDRAN.E<binu.chandran@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Modified: ARUN SADASIVAN (01/07/2012)								  |
// |----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+
//ini_set('display_errors', 1);

error_reporting(0);
ob_start();
//session_start();  //

include_once('project/config/config.php');
if (!INSTALLED) {
    header("location: project/install/install.php");
    exit;
}

require_once('lib/pagecontext.php');
require_once('lib/debugger.php');
require_once('lib/language.php');
PageContext::$request = $_REQUEST;//load request object;


//fatal error handling
register_shutdown_function('shutdownFunction');
function shutDownFunction() { 
	PageContext::handleError();
}



/**
* Base path is the directory in which 
* your index.php file is located.
**/
define('BASE_PATH', getcwd() . '/');
/**
* Load the core application file 
**/

require_once('config/application.php');

/**
* Create object of bootstrap.
**/
$bootstrap = new ConfigBootstrap;


/**
* Framework will sart when call the function run
**/

$bootstrap->run();

/*
 * Render the debugger info if Debugger is Turned ON
 */
Debugger::renderDebugger();

?>