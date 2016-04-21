<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshtih<roshith@armia.com> 		                          |
// |          									                          |
// +----------------------------------------------------------------------+
    require_once("./includes/applicationheader.php");
        $conn = getConnection();
        $sql1  = "UPDATE sptbl_users  ";
        $sql1 .= " SET vOnline = '0' WHERE nUserId = '".$_SESSION['sess_userid']."' ";
        $result1 = executeSelect($sql1,$conn);
		/*Added by Amaldev for Livechat*/
	    $sqlu  = "UPDATE sptbl_chat SET vStatus = 'finished', dTimeEnd = now() WHERE nUserId = '".$_SESSION['sess_userid']."' and vStatus != 'finished' ";
        $resultu = executeSelect($sqlu,$conn);
        $_SESSION['sess_userid'] = "";
        $_SESSION['sess_username']= "";
        $_SESSION['sess_useremail']= "";
        $_SESSION['sess_userfullname']= "";
        $_SESSION['sess_usercompid']= "";
		$_SESSION['sess_cssurl']= "";
//		$_SESSION['sess_language']= "";
		$_SESSION['sess_backurl']= "";
		$_SESSION["sess_ubackreplyurl"]="";
		/*Added by Amaldev for Livechat*/
		$_SESSION["sess_clientchatid"] = "";
	    $_SESSION["sess_clientchatdepid"] = "";
		//session_unregister('sess_ubackreplyurl');
                unset($_SESSION['sess_ubackreplyurl']);
        //session_unregister('sess_userid');
        unset($_SESSION['sess_userid']);
        //session_unregister('sess_username');
        unset($_SESSION['sess_username']);
        //session_unregister('sess_useremail');
        unset($_SESSION['sess_useremail']);
        //session_unregister('sess_userfullname');
        unset($_SESSION['sess_userfullname']);
    	//session_unregister('sess_usercompid');
        unset($_SESSION['sess_usercompid']);
		//session_unregister('sess_cssurl');
                unset($_SESSION['sess_cssurl']);
		//session_unregister('sess_language');
//                unset($_SESSION['sess_language']);
		//session_unregister('sess_userlangchange');
//                unset($_SESSION['sess_userlangchange']);
		//session_unregister('sess_backurl');
                unset($_SESSION['sess_backurl']);
		/*Added by Amaldev for Livechat*/
		//session_unregister('sess_clientchatid');
                unset($_SESSION['sess_clientchatid']);
	    //session_unregister('sess_clientchatdepid');
            unset($_SESSION['sess_clientchatdepid']);
			
        header("Location: index.php");
        exit;


?>