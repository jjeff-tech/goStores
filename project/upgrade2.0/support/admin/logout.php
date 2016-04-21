<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: programmer1<programmer1@armia.com>                          |
// |          programmer1<programmer2@armia.com>                          |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        $conn = getConnection();

       $sql1  = "UPDATE sptbl_staffs  ";
        $sql1 .= " SET vOnline = '0' WHERE nStaffId = '".$_SESSION['sess_staffid']."' ";
        $result1 = executeSelect($sql1,$conn);
   
  
        /*Added by Amaldev for Livechat*/
	    $sqlu  = "UPDATE sptbl_chat SET vStatus = 'finished', dTimeEnd = now() WHERE nStaffId = '".$_SESSION['sess_staffid']."' and vStatus != 'finished' ";
        $resultu = executeSelect($sqlu,$conn);
	    $sqls  = "UPDATE sptbl_operatorchat SET vStatus = 'finished', dTimeEnd=now() WHERE (nFirstStaffId = '".$_SESSION['sess_staffid']."' || nSecondStaffId = '".$_SESSION['sess_staffid']."' ) and vStatus != 'finished' ";
	    $results = executeSelect($sqls,$conn);

        $_SESSION['sess_staffid'] = "";
        $_SESSION['sess_staffname']= "";
        $_SESSION['sess_staffemail']= "";
        $_SESSION['sess_stafffullname']= "";
        $_SESSION["sess_isadmin"] = "";
        $_SESSION["sess_cssurl"]="";
		$_SESSION["sess_abackreplyurl"]="";
		//session_unregister('sess_abackreplyurl');
                unset($_SESSION['sess_abackreplyurl']);
        //session_unregister('sess_cssurl');
        unset($_SESSION['sess_cssurl']);
        //session_unregister('sess_staffid');
        unset($_SESSION['sess_staffid']);
        //session_unregister('sess_staffname');
        unset($_SESSION['sess_staffname']);
        //session_unregister('sess_staffemail');
        unset($_SESSION['sess_staffemail']);
        //session_unregister('sess_stafffullname');
        unset($_SESSION['sess_stafffullname']);
        //session_unregister('sess_isadmin');
        unset($_SESSION['sess_isadmin']);
		//session_unregister('sess_language');
//                unset($_SESSION['sess_language']);
		//session_unregister('sess_adminlangchange');
//                unset($_SESSION['sess_adminlangchange']);

        header("Location: index.php");
        exit;
?>