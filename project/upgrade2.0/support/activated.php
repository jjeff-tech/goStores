<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 			*/
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>             		              |
// |          										                      |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");

		if($_POST["id"] !="")
			$id = $_POST["id"];
		else
			$id=0;

		$conn = getConnection();
		
		$sql = " Select * from sptbl_users where nUserId=".$id." and vDelStatus='2'";
		$result = executeSelect($sql,$conn);

		if(mysql_num_rows($result) > 0) {
			$sql1  = " UPDATE sptbl_users  ";
			$sql1 .= " SET vDelStatus = '0' WHERE nUserId = '".$id."' ";
	
			executeQuery($sql1,$conn); 
	        echo("<script>alert('".MESSAGE_JS_ACTIVATED."');</script>");
		}else
	        echo("<script>alert('".MESSAGE_JS_ACTIVATION_EXPIRED."');</script>");

		echo ("<script>location.href='index.php'</script>");
?>