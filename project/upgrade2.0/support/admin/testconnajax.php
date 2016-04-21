<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: Jipson thomas<jipson.thomas@armiasystems.com>               |
// |                                                                      |
// +----------------------------------------------------------------------+
require_once("./includes/applicationheader.php");
//include("./includes/functions/miscfunctions.php");
include("./languages/".$_SP_language."/editconfig.php");
$conn = getConnection();
?>
<?php
$username			=	$_REQUEST["user"];
$password			=	$_REQUEST["password"];
$domain				=	$_REQUEST["domain"];
$ldap_server		=	$_REQUEST["host"];
$domain_username  	= 	$username . "@" . $domain;
$ldap_conn 			= 	ldap_connect($ldap_server) or die("<font color='#FF0000'>".MSG_LDP_NOCON."</font>");

if($ldap_conn==true){
	//echo $ldap_conn."<br>".$domain_username."<br>".$password."<br>";
	if($ldapbind = @ldap_bind($ldap_conn, $domain_username, $password) == true){
		echo "<font color='#00FF33'>".MSG_LDP_SUCC."</font>";
	}else{
		echo "<font color='#FF0000'>".MSG_LDP_NOBIND."</font>";
	}
	
}else{
	echo "<font color='#FF0000'>".MSG_LDP_NOCON."</font>";
}
exit;
?>
