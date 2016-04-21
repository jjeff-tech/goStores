<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                          |
// |                                                                                                            |
// +----------------------------------------------------------------------+

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
		if ($_GET["tk"] != "") {
	      $var_tid = $_GET["tk"];
		  $var_sd = $_GET["sd"];
	    }
	    elseif ($_POST["tk"] != "") {
	      
		  $var_tid = $_POST["tk"];
		  $var_sd = $_POST["sd"];
	   }
         $var_userid = $_SESSION["sess_userid"];
         if ($_POST["postback"] == "A") {
                        $var_mark= trim($_POST["cmbMark"]);
                        $var_comments = trim($_POST["txtComments"]);
                        $dup_flag=0;
                        //check duplicate name template title to block page refrsh
                       /* $sql="select *  from sptbl_templates  WHERE   vTemplateTitle ='".addslashes($var_title) . "'";
                        $rs = executeSelect($sql,$conn);
                        if(mysql_num_rows($rs)>0){
                          $dup_flag=1;
                        }*/

                if (validateAddition() == true and $dup_flag==0) {
                        $sql = "insert into sptbl_staffratings(nSRId,nUserId,nStaffId,nTicketId,tComments,nMarks) ";
						$sql  .=" values('','".addslashes($var_userid)."','".addslashes($var_sd)."','".addslashes($var_tid)."',";
                        $sql  .="'".addslashes($var_comments)."','".addslashes($var_mark)."')";
						//echo "sql==$sql";
						executeQuery($sql,$conn);
                        $var_insert_id = mysql_insert_id($conn);
                        //Insert the actionlog
						if(logActivity()) {
                        $sql = "Insert into sptbl_actionlog(nALId,nUserId,vAction,vArea,nRespId,dDate) Values('','$var_userid','" . TEXT_ADDITION . "','Staff Ratings','" . addslashes($var_insert_id) . "',now())";
                        executeQuery($sql,$conn);
						}

                        $var_message = MESSAGE_RECORD_ADDED;
                        $var_mark= "";
                        $var_comments = "";

                }
                else {
                         $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
                }
        }
        function validateAddition(){
		     global $conn,$var_tid,$var_userid;
             if (trim($_POST["txtComments"]) == "" || trim($_POST["cmbMark"]) <=0) {
                    return false;
              }else {
			  
			        $sql="select nUserId from sptbl_tickets where nTicketId='".addslashes($var_tid)."' and nUserId='".addslashes($var_userid)."'";
					
                    $rs = executeSelect($sql,$conn);
                    if(mysql_num_rows($rs)==0){
                          return false;
                    }
                    return true;
              }
        }
?>
<form name="frmRateStaff" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
    <tr>
    <td>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
     <tr>
     <td><img src="./images/spacerr.gif" width="1" height="1"></td>
     </tr>

     </table>

     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">
     <table width="100%"  border="0" cellspacing="0" cellpadding="5">
     <tr>
     <td width="93%" class="heading" align="left"><?php echo TEXT_EDIT_RATINGS ?></td>
     </tr>
     </table>
     <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="column1">
         <tr>
     <td>

         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr class="whitebasic">
         <td width="100%" align="center" colspan=3 >&nbsp;</td>

         </tr>
            <tr class="whitebasic">
         <td width="100%" align="center" colspan=3 class="errormessage">
         <?php echo "<font color=red>".$var_message."</font>"; ?></td>

         </tr>
                <tr class="whitebasic">
         <td width="100%" align="center" colspan=3 class="fieldnames">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>
		 <?php
		   $sql="select vLogin from sptbl_staffs where nStaffId='".addslashes($var_sd)."'";
		   
		   $rs = executeSelect($sql,$conn);
           if(mysql_num_rows($rs)>0){
		     $row=mysql_fetch_array($rs);
			 
             $var_staffname=$row['vLogin'];
           }
		 ?>
		 <tr class="whitebasic"><td colspan="3">&nbsp;</td></tr>
        <tr class="whitebasic">
         <td width="100%" align="center" colspan=3 class="fieldnames">
         <b><?php echo TEXT_RATE_STAFF ; echo $var_staffname;?></b></td>

         </tr>
		 <tr class="whitebasic"><td width="100%" colspan=3 align=center>[<?php echo TEXT_RATE_MIN;?> 0&nbsp;&nbsp;<?php echo TEXT_RATE_MAX;?> 10]</td></tr>
                     <tr class="whitebasic"><td colspan="3">&nbsp;</td></tr>
                      <tr class="whitebasic">
                     <td width="13%" align="left">&nbsp;</td>
                     <td width="26%" align="left" class="fieldnames"><?php echo TEXT_MARK?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                                          <td width="61%" align="left">
                                         <select name="cmbMark" size="1" class="textbox" id="cmbMark" style="width:40px;">
								           <option value="0"><?php echo TEXT_SELECT_MARK?></option>
								           <?php  for($i=1;$i<=10;$i++) { ?>
									         <option value="<?php echo $i;?>" <?php if($i==$var_mark) echo "selected";?>><?php echo $i;?></option>
									       <?php } ?>	
										   
										  </select>	 
                                          </td>
                      </tr>
                      <tr class="whitebasic"><td colspan="3">&nbsp;</td></tr>
                                          <tr class="whitebasic">
                     <td width="13%" align="left">&nbsp;</td>
                     <td width="26%" align="left" class="fieldnames" valign="top"><?php echo TEXT_COMMENTS ?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                     <td width="61%" align="left">
                        <textarea name="txtComments" cols="50" rows="12" id="txtComments" class="textarea" style="width:450px;" ><?php echo htmlentities($var_comments); ?></textarea>
                                         </td>
                      </tr>

                                            <tr class="whitebasic"><td colspan="3">&nbsp;</td></tr>
                                                                </table>
                        </td>
                            </tr>
                        </table></td>
                      <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
				</td>
              </tr>
            </table>
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="./images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%"><input name="btAdd" type="button" class="button" value="<?php echo BUTTON_TEXT_RATE; ?>" onClick="javascript:add();"></td>
                                    <td width="20%">
                                                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
																		<input type="hidden" name="id" value="<?php echo($var_id); ?>">
																		<input type="hidden" name="sd" value="<?php echo($var_sd); ?>">
                                                                        <input type="hidden" name="tk" value="<?php echo($var_tid); ?>">
                                                                        <input type="hidden" name="postback" value="">
                                                                        </td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="./images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
            <p class="ashbody">&nbsp;</p></td>

  </tr>
</table>
<script>
        var setValue = "<?php echo trim($var_country); ?>";

        <?php
                if ($var_id == "") {
                        echo("document.frmRateStaff.btAdd.disabled=false;");
                        
                }
                else {
                        echo("document.frmRateStaff.btAdd.disabled=true;");
                       
                }
        ?>
</script>
</form>