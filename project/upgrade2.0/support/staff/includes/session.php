<?php
ini_set('session.gc_maxlifetime',14400);
session_set_cookie_params(0);
session_start();
if(!isset($_SESSION['sess_staffid'])){
	$_SESSION['sess_staffid']="";
	$_SESSION['sess_isstaff']= 0 ;
	$_SESSION['sess_staffname']="";
	$_SESSION['sess_staffemail']="";
	$_SESSION['sess_staffdept']="";
	$_SESSION['sess_totaltickets']="";
	$_SESSION['sess_backurl']="";
	//$_SESSION['sess_logourl']= "./../images/logoo.gif";
	//$_SESSION['sess_cssurl']="styles/coolgreen.css";
	$_SESSION["sess_language"] = "en";
	$_SESSION["sess_langchoice"]="";
	$_SESSION["sess_logactivity"]="1";
	$_SESSION["sess_maxpostperpage"]="30";
	$_SESSION["sess_messageorder"]="1";
}
?>