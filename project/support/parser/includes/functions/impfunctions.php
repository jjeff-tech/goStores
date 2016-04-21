<?php
	
		function stripslashes_deep($value){
			$value = is_array($value) ?
	        array_map('stripslashes_deep', $value) :
	        stripslashes($value);
	        return $value;
        }
	
function adminLoggedIn(){
                if((isset($_SESSION["sess_staffname"]) and $_SESSION["sess_staffname"]!= "") and (isset($_SESSION["sess_isadmin"]) and $_SESSION["sess_isadmin"]!= 0) ){
                       return true;
                }else{
                       return false;
                }
        }


function staffLoggedIn(){
		if((isset($_SESSION["sess_staffname"]) and $_SESSION["sess_staffname"]!= "") and (isset($_SESSION["sess_isstaff"]) and ($_SESSION["sess_isstaff"]== 1))){
			return true;
		}else{
			return false;
		}
	}

function clearStaffSession(){
	global $_SESSION;
	$_SESSION['sess_staffid'] = "";
	$_SESSION['sess_staffname']= "";
	$_SESSION['sess_staffemail']= "";
	$_SESSION['sess_stafffullname']= "";
	$_SESSION['sess_staffdept']="";
	$_SESSION['sess_totaltickets']="";
	$_SESSION['sess_fieldlist']="";
	$_SESSION['sess_isstaff']="";
	$_SESSION["sess_cssurl"] = ""; 
	$_SESSION["sess_refresh"] = "";
	$_SESSION["sess_langchoice"] = "";
	$_SESSION["sess_defaultlang"] = "";
	$_SESSION["sess_helpdesktitle"] = "";
	$_SESSION["sess_logourl"] = "";
	$_SESSION["sess_language"] = "";
	$_SESSION["sess_backreplyurl"]="";
	/*session_unregister('sess_backreplyurl');
	session_unregister('sess_staffid');
	session_unregister('sess_staffname');
	session_unregister('sess_staffemail');
	session_unregister('sess_stafffullname');
	session_unregister('sess_staffdept');
	session_unregister('sess_totaltickets');
	session_unregister('sess_fieldlist');
	session_unregister('sess_cssurl');
	session_unregister('sess_refresh');
	session_unregister('sess_langchoice');
	session_unregister('sess_defaultlang');
	session_unregister('sess_helpdesktitle');
	session_unregister('sess_helpdesktitle');
	session_unregister('sess_logourl');
	session_unregister('sess_language');*/
}
function logActivity() {
	if($_SESSION["sess_logactivity"] == "1") {
		return true;
	}
	else {
		return false;
	}
}
?>