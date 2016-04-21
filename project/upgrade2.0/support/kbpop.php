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
include("./languages/" . $_SP_language . "/main.php");
include("./languages/".$_SP_language."/viewticket.php");

include("languages/$_SP_language/showticket.php");


 $conn = getConnection();

 $txtKbSearchid       =$_REQUEST['txtKbSearchid'];
 

 
$sql = "SELECT * FROM sptbl_kb WHERE nKBID=".$txtKbSearchid;

$showflag = false;  // This is to check whether the ticket belong to the department assigned
$rs = executeSelect($sql,$conn);
if(mysql_num_rows($rs) > 0) {
	$row = mysql_fetch_array($rs);
	$tkflag = true;
        $var_name = $row['vKBTitle'];
	$var_desc = $row["tKBDesc"];



?>

<table width="100%" class="column1"  border="0" cellspacing="1" cellpadding="5">

							    <tr class="listing"><td width="100%" class="heading" height="10">KnowledgeBase Deatails</td></tr>
                                                            <tr class="listing"><td>
									
                                           <tr align="left"  class="fieldnames">
												<td colspan="5" style="word-break:break-all; ">Title
                                                                                                    
													<br><b><?php echo htmlentities($var_name); ?></b><br>&nbsp;
												</td>
									  </tr>
                                            <tr>
                                                  <tr align="left"  class="fieldnames">
												<td colspan="5" style="word-break:break-all; ">Description
													<br><b><?php echo $var_desc; ?></b><br>&nbsp;
												</td>
									  </tr>
                                            <tr>
                                            <td colspan="5" class="bodycolor" >

                                              </td>
                                        </tr>
										
								</table>

<?php

}
mysql_free_result($rs);
?>
<!-- End Of Ticket Display -->

<!-- Reply Detail -->

