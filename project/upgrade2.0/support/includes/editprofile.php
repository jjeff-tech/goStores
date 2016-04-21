<?php

if($_POST["postback"] == "Save Changes"){
	$error = false;
	$errormessage = "" ;
	$company = 0;
	if(isNotNull($_POST["txtName"])){
		$name = $_POST["txtName"];
	}else{//user name null
		$error = true;
		$errormessage .= MESSAGE_NAME_REQUIRED . "<br>";
	}
	if(isNotNull($_POST["txtEmail"])){
		$sql = "Select nCompId from sptbl_users where nUserId='" . $_SESSION["sess_userid"] . "'";
		$rs = executeSelect($sql,$conn);
		if(mysql_num_rows($rs) > 0) {
			$row = mysql_fetch_array($rs);
			$company = $row["nCompId"];
		}
		$email = $_POST["txtEmail"];
		if(!isValidEmail($email)){
			$error = true;
			$errormessage .= MESSAGE_INVALID_EMAIL . "<br>";
		}
		elseif(!isUniqueEmail($email,$_SESSION["sess_userid"],$company)) {
			$error = true;
			$errormessage .= MESSAGE_NONUNIQUE_EMAIL . "<br>";
		}
	}else{//user Email null
		$error = true;
		$errormessage .= MESSAGE_EMAIL_REQUIRED . "<br>";
	}
	
	if($error){
		$errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage; 
	}else{//no error so validate
		$sql1  = " UPDATE sptbl_users  ";
		$sql1 .= " SET vUserName = '".addslashes($name)."', vEmail = '".addslashes($email)."' WHERE nUserId = '".$_SESSION["sess_userid"]."' ";
		$result1 = executeQuery($sql1,$conn); 
		$message = true;
		$messagetext = MESSAGE_PROFILE_UPDATED_SUCCESSFULLY;
		
	}
}

$sql = "Select u.vUserName, u.vEmail , c.vCompName from sptbl_users as u,sptbl_companies as c ";
$sql .=" where u.nCompId=c.nCompId and u.nUserId='".addslashes($_SESSION["sess_userid"])."'";
$result = executeSelect($sql,$conn); 
if (mysql_num_rows($result) > 0) {
	$row = mysql_fetch_array($result);
	$companyname= $row["vCompName"];
	$username = $row["vUserName"];
	$email = $row["vEmail"];
}


?>
<script>
<!--

function validateProfileForm(){
	var frm = window.document.frmProfile;
	var errors="";
	if(frm.txtName.value.length == 0){
		errors += "<?php echo MESSAGE_NAME_REQUIRED; ?>"+ "\n";
	}
	if(frm.txtEmail.value.length == 0){
		errors += "<?php echo MESSAGE_EMAIL_REQUIRED; ?>"+ "\n";
	}else if(!isValidEmail(frm.txtEmail.value)){
		errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
	}
	if(errors !=""){
		errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors; 
		alert(errors);
		return false;
	}else{
		frm.postback.value = "Save Changes";
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

function cancel(){
	;
}

-->
</script>
<div class="content_section">
<form action="" method="post" name="frmProfile">

<div class="content_section_title"><h3><?php echo TEXT_EDIT_PROFILE?></h3></div>

<div class="content_section_data">
         
                    
                <?php  if($error){?>
														  
														  <div class="msg_error">
														  <?php echo $errormessage;?>
														  </div>
														  							  
														 
														  
														  <?php }
														  if($message){ ?>
														  
														   <div class="msg_success">
														 <?php echo $messagetext;?>
														  </div>
														  
														  
														 <?php }?>
												  
			<table width="100%"  border="0" cellpadding="0" cellspacing="0" border="0" class="comm_tbl btm_brdr">
											<tr>
												<td width="20%" align="right"><?php echo TEXT_NAME?>&nbsp;<span class="required">*</span></td>													  
												<td width="80%" align="left"><input name="txtName" type="text"  maxlength="100" class="comm_input input_width1" value="<?php echo htmlentities($username);?>"></td>
											 </tr>
											 
											<tr>
											<td align="right"><?php echo TEXT_EMAIL?>&nbsp;<span class="required">*</span></td>
										    <td align="left"><input name="txtEmail" type="text" size="30" maxlength="100" class="comm_input input_width1"  value="<?php echo htmlentities($email);?>"></td>
											</tr>
													  
											<tr>
											<td align="right"><?php echo TEXT_COMPANY?>&nbsp;</td>													   
											<td align="left"><?php echo htmlentities($companyname);?></td>
											</tr>													 
													</table>

								
												
				  
				  
            
			
                  
				<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="comm_tbl">
                                  
								 
								  <tr>
                                   
                                    <td align="center">
									<input name="btnSubmit" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SUBMIT?>" onClick="javascript:validateProfileForm();">
																		<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									
									</td>
                                   </tr>
								  
                              </table>
						
                    
					
				  
			
			</form>
			</div>
			</div>