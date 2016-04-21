<?php
$ticketfound = false;
if($_POST["postback"] == "Search Ticket"){
	if(isNotNull($_POST["txtEmail"])){
		$email = trim($_POST["txtEmail"]);
		if(!isValidEmail($email)){
			$ticketerror = true;
			$ticketerrormessage .= MESSAGE_INVALID_EMAIL . "<br>";
		}
	}else{//user Email null
		$ticketerror = true;
		$ticketerrormessage .= MESSAGE_EMAIL_REQUIRED . "<br>";
	}
    if(isNotNull($_POST["txtTicketRef"])){
            $ticketref = $_POST["txtTicketRef"];
    }else{//
            $ticketerror = true;
            $ticketerrormessage .= MESSAGE_TICKET_REF_REQUIRED . "<br>";
    }
    if($ticketerror){
    	$ticketerrormessage = MESSAGE_ERRORS_FOUND . "<br>" .$ticketerrormessage;
   	}else{//no error so validate
	    
		$sql  = "SELECT u.nUserId ,u.vEmail ,t.nTicketId, t.vRefNo, t.vTitle
		 FROM sptbl_users u INNER JOIN sptbl_tickets t on u.nUserId = t.nUserId   ";
	    $sql .= " WHERE u.vEmail = '".mysql_real_escape_string($email)."' and  t.vRefNo = '".mysql_real_escape_string($ticketref)."' and t.vDelStatus = '0' ";
	    $result = executeSelect($sql,$conn);
	    if (mysql_num_rows($result) > 0) {
	            $row = mysql_fetch_array($result);
	            $userid   = $row["nUserId"];
	            $useremail = $row["vEmail"];
	            $title = $row["vTitle"];
				$var_tid = $row["nTicketId"];
				$var_userid =$userid;
				$ticketfound = true;
        }else{
                $ticketerror = true;
                $ticketerrormessage = MESSAGE_NO_MATCH_FOUND;
        }
	}
}
elseif($_GET["mt"] == "y"){
	if(isNotNull($_GET["email"])){
		$email = trim($_GET["email"]);
		if(!isValidEmail($email)){
			$ticketerror = true;
			$ticketerrormessage .= MESSAGE_INVALID_EMAIL . "<br>";
		}
	}else{//user Email null
		$ticketerror = true;
		$ticketerrormessage .= MESSAGE_EMAIL_REQUIRED . "<br>";
	}
    if(isNotNull($_GET["ref"])){
            $ticketref = $_GET["ref"];
    }else{//
            $ticketerror = true;
            $ticketerrormessage .= MESSAGE_TICKET_REF_REQUIRED . "<br>";
    }
    if($ticketerror){
    	$ticketerrormessage = MESSAGE_ERRORS_FOUND . "<br>" .$ticketerrormessage;
   	}else{//no error so validate
	    
		$sql  = "SELECT u.nUserId ,u.vEmail ,t.nTicketId, t.vRefNo, t.vTitle
		 FROM sptbl_users u INNER JOIN sptbl_tickets t on u.nUserId = t.nUserId   ";
	    $sql .= " WHERE u.vEmail = '".mysql_real_escape_string($email)."' and  t.vRefNo ='".mysql_real_escape_string($ticketref)."' and t.vDelStatus = '0' ";
	    $result = executeSelect($sql,$conn);
	    if (mysql_num_rows($result) > 0) {
	            $row = mysql_fetch_array($result);
	            $userid   = $row["nUserId"];
	            $useremail = $row["vEmail"];
	            $title = $row["vTitle"];
				$var_tid = $row["nTicketId"];
				$var_userid =$userid;
				$ticketfound = true;
        }else{
                $ticketerror = true;
                $ticketerrormessage = MESSAGE_NO_MATCH_FOUND;
        }
	}
}

?>


<script>
function searchTicket(){
       	var frm = window.document.frmShowTicket;
        var errors="";
		
        if(frm.txtEmail.value.length == 0){
                errors += "<?php echo MESSAGE_EMAIL_REQUIRED; ?>"+ "\n";
        }else if(!isValidEmail(frm.txtEmail.value)){
			errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
		}
        if(frm.txtTicketRef.value.length == 0){
                errors += "<?php echo MESSAGE_TICKET_REF_REQUIRED; ?>" + "\n";
        }
        if(errors !=""){
                errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
                alert(errors);
                return false;
        }else{
                frm.postback.value = "Search Ticket";
                frm.submit();
        }
}

function isValidEmail(email){
	var str1=email;
	var arr=str1.split('@');
	var eFlag=true;
	if(arr.length != 2)
	{
		eFlag = false;
	}
	else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
	{
			eFlag = false;
	}
	else
	{
		var dot=arr[1].split('.');
		if(dot.length < 2)
		{
			eFlag = false;
		}
		else
		{
			if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
			{
				eFlag = false;
			}

			for(i=1;i < dot.length;i++)
			{
				if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1)
				{
					eFlag = false;
				}
			}
			if(dot[i-1].length > 4)
				eFlag = false;
		}
	}
		return eFlag;	
}
</script>
				
<form name="frmShowTicket" method="post">
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<?php
                  if($ticketerror){?>

                  <table width="90%"  border="0" cellspacing="10" cellpadding="0" align="center">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td align="left" class="errormessage"><p><?php echo $ticketerrormessage; ?></p></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
                  <?php }
                  if($ticketmessage){ ?>
                  <table width="90%"  border="0" cellspacing="10" cellpadding="0" align="center">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td class="listing"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td align="left" class="message"><p><?php echo $infomessage;?></p></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
                 <?php }?>


<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
            <table width="90%"  border="0" cellspacing="10" cellpadding="0" align="center">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                                  <tr>
                                    <td align="left" class=indexpagelisting>
                                    <p align="justify">
                                    <?php echo TEXT_SEARCH_DATA;?>
                                    </p>
                                    </td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>


                        <table width="90%"  border="0" cellspacing="10" cellpadding="0" align="center">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td><img src="images/spacerr.gif" width="1" height="1"></td>
                  </tr>
                </table>


                  <table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td align=center>


                              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="35%" align="left" class="listing"><?php echo  TEXT_EMAIL?> </td>
                                  <td width="65%" align="left">
                                      <input name="txtEmail" type="text" class="textbox" id="txtEmail" value="<?php echo htmlentities($email); ?>"></td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="left"  width="35%"  class="listing"><?php echo  TEXT_GET_REF_NO?></td>
                                  <td align="left"  width="65%" ><input name="txtTicketRef" type="text" class="textbox" id="txtTicketRef" value="<?php echo htmlentities($ticketref); ?>"></td>
                                </tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left">&nbsp;</td>
                                </tr>
                                  <tr>
                                  <td align="right" ></td><td align=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								  <input name="btnSearch" id="btnSearch" type="button" class="button" value="<?php echo BUTTON_TEXT_SEARCH ?>" onClick="javascript:searchTicket();"></td>
                                </tr>

                              </table>


                                                          </td>
                            </tr>
                        </table></td>
                      <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
			
			<!-- ============================================================================= -->
			<?php
			if($ticketfound){?>
			<table width="90%"  border="0" cellspacing="10" cellpadding="0" align="center">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
				  
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="98%"  border="0" cellspacing="0" cellpadding="5" >
						    <tr><td>&nbsp;</td></tr>
                            <tr>
                              <td>
							  <fieldset style="width:700px;">
                                <legend class="listing"><b><?php echo TEXT_TICKET_DETAILS?></b></legend>  
							  <table width="100%" class="column1"  border="0" cellspacing="1" cellpadding="5">
							  
							    <tr class="listing"><td width="100%" class="heading" height="10"><?php echo TEXT_TICKET_DETAILS; ?></td></tr>
                            <tr class="listing">
                              <td>
							   <?php require("./includes/ticketdisplay.php"); ?>
							 
							   </td>
                            </tr>                                
                              </table>
							  </fieldset>
							   </td>
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
			
			<?php }?>
			
			<!-- ============================================================================= -->
			
			<input type="hidden" name="postback" value=""> 
</form>