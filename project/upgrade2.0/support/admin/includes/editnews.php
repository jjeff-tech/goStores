<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>    		                      |
// |          									                          |
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
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
	} 
	$var_country = "UnitedStates";
	//$var_userid = $_SESSION["sess_staffid"];
	$var_staffid = $_SESSION["sess_staffid"];
	
	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "Select * from sptbl_news ";
        $sql .=" where nNewsId='".addslashes($var_id)."'";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);  
			$var_title = $var_row["vTitle"];
			$var_news= $var_row["tNews"];
			$var_validdate = datetimefrommysql($var_row["dVaildDate"],1);
			$vtype = $var_row["vType"];
			
    	}
		else {
			$var_id = "";
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "A") {
	      	$var_title= trim($_POST["txtNewsTitle"]);
			$var_news = trim($_POST["txtNews"]);
			$var_validdate = trim($_POST["txtDate"]);
			$var_stype = trim($_POST["chk_staff"]);
			$var_utype = trim($_POST["chk_user"]);
			if($var_stype!="" and $var_utype!=""){
			  $vtype="A";
			}elseif($var_stype!=""){
			  $vtype=$var_stype;
			  
			}else if($var_utype!=""){
			  $vtype=$var_utype;
			}
			$dup_flag=0;
			//check duplicate name news title to block page refrsh
			$sql="SELECT nNewsId   FROM sptbl_news WHERE   vTitle ='".addslashes($var_title) . "'";
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
				$var_message =	MESSAGE_DUPLICATE_TITLE;
                                $flag_msg    = 'class="msg_error"';
				$dup_flag=1;
			}
			//check whether the valid date greater than current_date
		    $sql="select '".datetimetomysql($var_validdate)."'<=now() as chdate";
			$rs = executeSelect($sql,$conn);
			$row_d = mysql_fetch_array($rs);
			if($row_d['chdate']=="1"){
				$var_message = MESSAGE_INVALID_DATE;
                                $flag_msg    = 'class="msg_error"';
			  	$dup_flag=1;			  
			}
		if (validateAddition() == true and $dup_flag==0) {
		  //Insert into the company table
		   $var_validdate =datetimetomysql($var_validdate);
		   
		 
			$sql = "Insert into sptbl_news(nNewsId,vTitle ,tNews ,dPostdate ,dVaildDate,vType";
			$sql .= ") Values('','" . addslashes($var_title). "','" . addslashes($var_news) . "',now(),'" . addslashes($var_validdate) . "',
					'" . addslashes($vtype) . "')";
				;
  	         executeQuery($sql,$conn);
			$var_insert_id = mysql_insert_id($conn);
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','News','" . addslashes($var_insert_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			
			$var_message = MESSAGE_RECORD_ADDED;
                        $flag_msg    = 'class="msg_success"';
			$var_title= "";
			$var_news = "";
			$var_validdate = "";
			$var_stype = "";
			$var_utype = "";
		}
		else {
		 	$var_message = $var_message ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "D") {
	       
			
		if (validateDeletion() == true) {
			$sql = "delete from  sptbl_news  where nNewsId ='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','News','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			$var_title= "";
			$var_news = "";
			$var_validdate = "";
			$var_stype = "";
			$var_utype = "";
			$var_id="";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
		}
		else {
		    $var_title= trim($_POST["txtNewsTitle"]);
			$var_news = trim($_POST["txtNews"]);
			$var_validdate = trim($_POST["txtDate"]);
			$var_stype = trim($_POST["chk_staff"]);
			$var_utype = trim($_POST["chk_user"]);
			if($var_stype!="" and $var_utype!=""){
			  $vtype="A";
			}elseif($var_stype!=""){
			  $vtype=$var_stype;
			  
			}else if($var_utype!=""){
			  $vtype=$var_utype;
			}
		}
	}
	elseif ($_POST["postback"] == "U") {

            $sql = "Select nNewsId from sptbl_news where nNewsId='" . addslashes($var_id) . "'";
		if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			$var_title= trim($_POST["txtNewsTitle"]);
			$var_news = trim($_POST["txtNews"]);
			$var_validdate = trim($_POST["txtDate"]);
			$var_stype = trim($_POST["chk_staff"]);
			$var_utype = trim($_POST["chk_user"]);
			if($var_stype!="" and $var_utype!=""){
			  $vtype="A";
			}elseif($var_stype!=""){
			  $vtype=$var_stype;
			  
			}else if($var_utype!=""){
			  $vtype=$var_utype;
			}
			$dup_flag=0;
			//check duplicate name department name
			$sql="SELECT nNewsId   FROM sptbl_news WHERE   vTitle ='".addslashes($var_title) . "'";
			$sql .=" and nNewsId  !=$var_id";
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
				$var_message =	MESSAGE_DUPLICATE_TITLE;
                                $flag_msg    = 'class="msg_error"';
				$dup_flag=1;
			}
			 $sql="select '".datetimetomysql($var_validdate)."'<=now() as chdate";
			$rs = executeSelect($sql,$conn);
			$row_d = mysql_fetch_array($rs);
			if($row_d['chdate']=="1"){
				$var_message = MESSAGE_INVALID_DATE;
                                $flag_msg    = 'class="msg_error"';
				$dup_flag=1;
			}
                        
                        if (validateUpdation() == true and $dup_flag==0) {
                            $var_validdate =datetimetomysql($var_validdate);
				 $sql = "Update sptbl_news  set vTitle ='" . addslashes($var_title) . "',
					    tNews ='" . addslashes($var_news) . "',
					    dPostdate =now(),
					    dVaildDate ='" . addslashes($var_validdate) . "', 
						vType ='" . addslashes($vtype) . "' 
					    where nNewsId='" . addslashes($var_id) . "'";
				executeQuery($sql,$conn);
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','News','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}			    
				$var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg    = 'class="msg_success"';
			}
			else {
                            
				$var_message = $var_message ;
                                $flag_msg    = 'class="msg_error"';
			}
                        
		}
		else {
			$var_id = "";
			$var_message = MESSAGE_INVALID_NEWS ."<br>";
                        $flag_msg    = 'class="msg_error"';
		}	
	}
	
	function validateAddition() 
	{
	
 	    if (trim($_POST["txtNewsTitle"]) == "" || trim($_POST["txtNews"]) == "") {
		    return false;
		}else if(trim($_POST["chk_staff"]) == "" && trim($_POST["chk_user"]) == ""){
		   return false;
		   
		}else if(valid_date(trim($_POST["txtDate"])) !="1"){
		    return false;
			
		}else {
		    return true;
		}
	}
	
	function validateDeletion() 
	{
	
		return true;
	}
	
	function validateUpdation() 
	{
           
            
		 if (trim($_POST["txtNewsTitle"]) == "" || trim($_POST["txtNews"]) == "") {
                    return false;
		}else if(trim($_POST["chk_staff"]) == "" && trim($_POST["chk_user"]) == ""){
                   return false;
		   
		}else {
		    return true;
		}
	}
        function isValidDateTime($dateTime) {
            
            if (trim($dateTime) == '') {
                return true;
            }
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})(\s+(([01]?[0-9])|(2[0-3]))(:[0-5][0-9]){0,2}(\s+(am|pm))?)?$/i', $dateTime, $matches)) {
                list($all,$mm,$dd,$year) = $matches;
                if ($year <= 99) {
                    $year += 2000;
                }
                return checkdate($mm, $dd, $year);
            }
            return false;
}

?>
<form name="frmNews" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php
if(!empty($_GET['id'])) {
    echo TEXT_EDIT_NEWS;
} else {
   echo TEXT_ADD_NEWS;  
}
?></h3>
			</div>
			
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1"> 
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td align="center" colspan=3 >&nbsp;</td>

         </tr>
    	<tr>
         <td align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $var_message ?></td>

         </tr>
		<tr>
		<td>&nbsp;</td>
         <td align="left" colspan=2 class="fieldnames">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>

			          <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                     <td width="2%" align="left">&nbsp;</td>
                     <td width="26%" align="left" class="fieldnames"><?php echo TEXT_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					  <td width="72%" align="left">
                        <input name="txtNewsTitle" type="text" class="comm_input input_width5" id="txtNewsTitle" size="64" maxlength="100" value="<?php echo htmlentities($var_title); ?>">
					  </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                     <td width="2%" align="left">&nbsp;</td>
                     <td width="26%" align="left" class="fieldnames" valign="top"><?php echo TEXT_NEWS?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                     <td width="72%" align="left">
                        <textarea name="txtNews" cols="50" rows="12" id="txtNews" class="textarea" style="width:405px;"><?php echo htmlentities($var_news); ?></textarea>
					 </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="fieldnames"><?php echo TEXT_NEWS_TYPE ?> <font style="color:#FF0000; font-size:9px">*</font></td>
					       
                           <td class="listing" valign=middle>
						     <?php
							   $stype_checked="";
							   $utype_checked="";
							   if($_POST["postback"] <> 'A'){
								   if($vtype=="A"){
									   $stype_checked="checked";
									   $utype_checked="checked";
								   }else  if($vtype=="S"){
									   $stype_checked="checked";
								   }else   if($vtype=="U"){							       
									   $utype_checked="checked";
								   }    
							   }  
							 ?>
						     <table border=0>
						      <tr><td class="listing">
						     		<input type="checkbox" name="chk_staff" id="chk_staff" value="S" class="checkbox" <?php echo $stype_checked ; ?>>
							 		</td>
								 <td class="fieldnames">
									 <?php echo TEXT_NEWS_TYPE_STAFF?>
							 	 </td>
							 	 <td class="listing">
							 			<input type="checkbox" name="chk_user" id="chk_user" value="U" class="checkbox" <?php echo $utype_checked ; ?>>
							 	</td>
							    <td class="fieldnames">
							 		<?php echo TEXT_NEWS_TYPE_USER?>
							 	</td>
							 </tr>
							</table> 
						   </td>	 
					      
					  </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  <?php
					    if($var_validdate ==""){
						  $var_validdate=date("m-d-Y H:i");
						}
					  ?>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="fieldnames"><?php echo TEXT_VALID_DATE ?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="72%" align="left">
                        <input name="txtDate" type="text" class="comm_input input_width4" id="txtDate" size="30" maxlength="100" value="<?php echo htmlentities($var_validdate); ?>" readonly style="width:125px">
						<!--<input type="button" value="V" id="button1" name="button1"  onMouseOver="this.className='applyBorder'" onMouseOut="this.className='removeBorder'" class="defaultBorder" style=" width:30px;">-->
						
						<input type="button" value="V" id="button1" name="button1"  class="comm_btn" style=" width:30px;">
					  </td>
                      </tr>
					  <tr>
					   <td>
					   		<script type="text/javascript">
						            Calendar.setup({
						            inputField    	: "txtDate",
						            button        : "button1",
									ifFormat      	: "%m-%d-%Y %H:%M",       // format of the input field
						        	showsTime      	: true,
						        	timeFormat     	: "24"
								    });
          					</script>
					   </td>
					  </tr>
					  <tr><td class="btm_brdr" colspan="3">&nbsp;</td></tr>
								</table>
                        </td>
                            </tr>
                        </table>
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
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
                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%"><input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();"></td>
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
<script>
	var setValue = "<?php echo trim($var_country); ?>";

	<?php
		if ($var_id == "") {
			echo("document.frmNews.btAdd.disabled=false;");
			echo("document.frmNews.btUpdate.disabled=true;");
			echo("document.frmNews.btDelete.disabled=true;");
		}
		else {
			echo("document.frmNews.btAdd.disabled=true;");
			echo("document.frmNews.btUpdate.disabled=false;");
			echo("document.frmNews.btDelete.disabled=false;");
		}
	?>
</script>
</form>