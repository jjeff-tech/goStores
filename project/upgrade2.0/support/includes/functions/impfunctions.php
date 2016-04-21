<?php

	function stripslashes_deep($value){
        $value = is_array($value) ?
        array_map('stripslashes_deep', $value) :
        stripslashes($value);
	return $value;
    }

	function userLoggedIn(){
		if(isset($_SESSION["sess_username"]) and $_SESSION["sess_username"]!= ""  ){
			return true;
		}else{
			return false;
		}
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
