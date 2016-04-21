<?php
	
		function stripslashes_deep($value){
			$value = is_array($value) ?
	        array_map('stripslashes_deep', $value) :
	        stripslashes($value);
	        return $value;
        }
	
	function staffLoggedIn(){
		if((isset($_SESSION["sess_staffname"]) and $_SESSION["sess_staffname"]!= "") and (isset($_SESSION["sess_isstaff"]) and ($_SESSION["sess_isstaff"]== 1))){
			return true;
		}else{
			return false;
		}
	}


function adminLoggedIn(){
                if((isset($_SESSION["sess_staffname"]) and $_SESSION["sess_staffname"]!= "") and (isset($_SESSION["sess_isadmin"]) and $_SESSION["sess_isadmin"]!= 0) ){
                       return true;
                }else{
                       return false;
                }
        }

function clearAdminSession(){
	global $_SESSION;
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
}
function logActivity() {
	if($_SESSION["sess_logactivity"] == "1") {
		return true;
	}
	else {
		return false;
	}

}
function changeTicketStatus($statStr)
    {
        switch ($statStr)
        {
            case 'open':
                    return TEXT_TICKET_SELECT_OPEN;
                    break;
            case 'closed':
                    return TEXT_TICKET_SELECT_CLOSED;
                    break;
            case 'escalated':
                    return TEXT_TICKET_SELECT_ESCALATED;
                    break;
            case 'InProcess':
                    return TEXT_TICKET_SELECT_INPROCESS;
                    break;
            case 'New':
                    return TEXT_TICKET_SELECT_NEW;
                    break;

           case 'Select Status':
                    return TEXT_TICKET_SELECT_DEFAULT;
                    break;
        }
    }
    
    /*
 * Function to get ticket count
 */
function getTicketCount($ticketId)
{
    global $conn;
$getSortSql    = "SELECT COALESCE(count(*),0)
                    FROM dummy d
                    LEFT JOIN sptbl_tickets t ON ( d.num =0 AND t.nTicketId = '$ticketId')
                    LEFT JOIN sptbl_replies r ON ( d.num =1 AND r.nTicketId = '$ticketId'AND r.nHold =0 )
                    WHERE d.num <2 AND ( t.nTicketId IS NOT NULL OR r.nReplyId IS NOT NULL )
                    ORDER BY r.dDate";
         $getSortRs     = executeSelect($getSortSql,$conn);
         $getSortRw     = mysql_fetch_array($getSortRs);
         return $getSortRw[0];
    
}
?>