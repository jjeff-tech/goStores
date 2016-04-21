<?php
include("config/settings.php");
include("includes/session.php");
if (!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
}
include("includes/functions/dbfunctions.php");
include("includes/functions/miscfunctions.php");
include("includes/functions/impfunctions.php");
include("includes/main_smtp.php");
include("languages/$_SP_language/showticket.php");

 $conn = getConnection();

$txtKbSearchid = $_REQUEST['txtKbSearchid'];

//$txtTicketRef = $_REQUEST['txtTicketRef'];

 $ticketfound = false;
if($txtKbSearchid!=''){

          $sql = "SELECT * FROM sptbl_kb WHERE nKBID=".$txtKbSearchid;


          $result = executeSelect($sql,$conn);
	    if (mysql_num_rows($result) > 0) {
	            $row = mysql_fetch_array($result);
	            $userid   = $row["nUserId"];
	            $useremail = $row["vEmail"];
	            $title = $row["vTitle"];
				$var_tid = $row["nTicketId"];
				$var_userid =$userid;
				$ticketfound = true;

                               // echo "<a href='ticketpop.php' rel='#overlay' style='text-decoration:none'></a>";
                                echo json_encode(array("response"=>"success","var_tid"=>$var_tid,"var_userid"=>$var_userid));

        }else{
                echo json_encode(array("response"=>"failure"));
           }

           exit;

}
?>
