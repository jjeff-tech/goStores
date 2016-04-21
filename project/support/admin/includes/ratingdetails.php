<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
	
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];		
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];		
	} 
	if ($_GET["stylename"] != "") {
		$var_styleminus = $_GET["styleminus"];
		$var_stylename = $_GET["stylename"];
		$var_styleplus = $_GET["styleplus"];
	}
	else {
		$var_styleminus = $_POST["styleminus"];
		$var_stylename = $_POST["stylename"];
		$var_styleplus = $_POST["styleplus"];
	}	
	
	
        $sql    =   "SELECT s.vStaffname, t.vRefNo, r.tComments, r.nMarks FROM sptbl_staffratings r INNER JOIN sptbl_staffs s ON r.nStaffId = s.nStaffId
                    LEFT JOIN  sptbl_tickets t ON r.nTicketId = t.nTicketId
                    WHERE r.nStaffId = '" . mysql_real_escape_string($var_id) . "' ";
        $result = executeSelect($sql,$conn);


        $sql_staff    =   "SELECT s.vStaffname FROM sptbl_staffratings r INNER JOIN sptbl_staffs s ON r.nStaffId = s.nStaffId
                            WHERE r.nStaffId = '" . mysql_real_escape_string($var_id) . "' ";
        $result_staff = executeSelect($sql_staff,$conn);
        $row_staff    = mysql_fetch_array($result_staff);
	
	

?>

<style>

    /*-------------------Rating Star ------------------*/

            .rating_5{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px 0px;
                      float:left;

            }
            .rating_4{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -14px;
                      float:left;


            }
            .rating_3{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -28px;
                      float:left;

            }
            .rating_2{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -43px;
                      float:left;

            }
            .rating_1{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -57px;
                      float:left;

            }
            .rating_0{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -71px;
                      float:left;

            }

</style>
<form name="frmStaff" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo HEADING_RATING_DETAILS ?></h3>
			</div>
			<table width="100%"  border="0">
			  <tr>
				<td width="76%" valign="top">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td>
				<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
				 <tr>
				 <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
				 </tr>
			
				 </table>
			
				 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
				 <tr>
				 <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
				 <td class="pagecolor">
				 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
				 <tr>
				 <td width="93%" class="heading"></td>
				 </tr>
				 </table>
				<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
			
							 <tr>
					 <td align="center" colspan=3 >&nbsp;</td>
			
					 </tr>
					 <tr>
					 <td align="center" colspan=3 class="errormessage">
					 <?php

if ($var_message != ""){
?>
	<div class="msg_error">
<b><?php echo($var_message); ?></b>
</div>
<?php
}
?>			
					</td>
			
					 </tr>
			
								 <tr><td colspan="3">&nbsp;</td></tr>
                                                                 <tr style="background-color: #CCC">
								 <td width="7%" align="left">&nbsp;</td>
								 <td width="20%" align="left" class="toplinks"><b><?php echo TEXT_STAFF_NAME?> </b></td>
								 <td width="73%" align="left"><b>
								 <?php echo htmlentities($row_staff['vStaffname']); ?></b>
								 </td>
								  </tr>
			
								  <?php
                                                                  $i=0;
								  while($row = mysql_fetch_array($result)) {                     
                                                                    if($i%2==0)
                                                                        $class='unreadTK';
                                                                    else
                                                                        $class='readTK';
								  ?>
                                                                  <tr class="<?php echo $class; ?>"><td colspan="3">&nbsp;</td></tr>
								  <tr class="<?php echo $class; ?>">
								  <td align="left">&nbsp;</td>
								  <td align="left" class="toplinks"><?php echo TEXT_TICKET_NO ?> </td>
								  <td width="54%" align="left"><b><?php echo htmlentities($row['vRefNo']); ?></b></td>
								  </tr>
			
								  <tr class="<?php echo $class; ?>"><td colspan="3">&nbsp;</td></tr>
								  <tr class="<?php echo $class; ?>">
								  <td align="left">&nbsp;</td>
								  <td align="left" class="toplinks"><?php echo TEXT_RATING ?> </td>
								  <td width="54%" align="left"><span class="rating_<?php echo $row['nMarks']; ?>"></span></td>
								  </tr>
			
								  <tr class="<?php echo $class; ?>"><td colspan="3">&nbsp;</td></tr>
								  <tr class="<?php echo $class; ?>">
								  <td align="left">&nbsp;</td>
								  <td align="left" class="toplinks"><?php echo TEXT_COMMENTS ?> </td>
								  <td width="54%" align="left" class="toplinks">
									  <?php echo strip_tags(nl2br($row['tComments'])); ?>
								  </td>
								  </tr>
								  <?php
									$i++;	}//end while
										mysql_free_result($result);
									?>
								  <tr><td colspan="3">&nbsp;</td></tr>	
																																										
										  </table>
				</td>
								  <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
								</tr>
							  </table>
							  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
								<tr>
								  <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
								</tr>
							  </table></td>
						  </tr>
						</table>
						<table width="100%"  border="0" cellspacing="10" cellpadding="0">
						  <tr>
							<td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
								<tr>
								  <td class="btm_brdr"><img src="./../images/spacerr.gif" width="1" height="1"></td>
								</tr>
							  </table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr >
									<td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
									<td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
										<tr>
										  <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
											  <tr align="center"  class="listingbtnbar">
												<td width="22%">&nbsp;</td>                                    
												<td width="12%"><input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_BACK; ?>" onclick="javascript:window.location.href='rating.php?mt=y&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&'"></td>
												<td width="20%">
													<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
													<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
													<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
													<input type="hidden" name="id" value="<?php echo($var_id); ?>">
													<input type="hidden" name="postback" value="">
												</td>
											  </tr>
										  </table></td>
										</tr>
									</table></td>
									<td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
								  </tr>
								</table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
								  </tr>
							  </table></td>
						  </tr>
						</table>
						</td>
			
			  </tr>
			</table>
			</div>
</form>