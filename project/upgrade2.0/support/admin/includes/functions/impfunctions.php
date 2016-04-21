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
	//session_unregister('sess_backreplyurl');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_staffid');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_staffname');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_staffemail');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_stafffullname');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_staffdept');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_totaltickets');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_fieldlist');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_cssurl');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_refresh');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_langchoice');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_defaultlang');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_helpdesktitle');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_helpdesktitle');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_logourl');
        unset($_SESSION['sess_backreplyurl']);
	//session_unregister('sess_language');
        unset($_SESSION['sess_backreplyurl']);
}
function logActivity() {
	if($_SESSION["sess_logactivity"] == "1") {
		return true;
	}
	else {
		return false;
	}
}
function getLicense(){
	global $conn;
	$sql  = "SELECT * FROM sptbl_lookup WHERE  vLookUpName = 'vLicenceKey'";
	$result =executeSelect($sql,$conn);
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		$var_licencekey = stripslashes($row["vLookUpValue"]);
	}
	return $var_licencekey;
}
?>