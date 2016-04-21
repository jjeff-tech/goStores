<?php
if($_POST["postback"] == "Save Changes"){
	$error = false;
	$errormessage = "" ;
	if(isNotNull($_POST["txtOldPassword"])){
		$oldpassword = $_POST["txtOldPassword"];
		if(!isPasswordCorrect($oldpassword,$_SESSION["sess_userid"])){
			$error = true;
			$errormessage .= MESSAGE_OLD_PASSWORD_INCORRECT . "<br>";
		}
	}else{//user password null
		$error = true;
		$errormessage .= MESSAGE_OLD_PASSWORD_REQUIRED . "<br>";
	}
	if(isNotNull($_POST["txtNewPassword"])){
		$newpassword = $_POST["txtNewPassword"];
	}else{//user password null
		$error = true;
		$errormessage .= MESSAGE_NEW_PASSWORD_REQUIRED . "<br>";
	}
	if(isNotNull($_POST["txtConfirmNewPassword"])){
		$confirmnewpassword = $_POST["txtConfirmNewPassword"];
	}else{//user confirmpassword null
		$error = true;
		$errormessage .= MESSAGE_CONFIRM_NEW_PASSWORD_REQUIRED . "<br>";
	}
	if(isNotNull($_POST["txtConfirmNewPassword"]) and isNotNull($_POST["txtNewPassword"])){
		if($_POST["txtConfirmNewPassword"] != $_POST["txtNewPassword"] ){
			$error = true;
			$errormessage .= MESSAGE_PASSWORDS_SHOULD_MATCH . "<br>";
		}
	}
		
	if($error){
		$errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
		$passwordreset = false; 
	}else{//no error so update
		$sql1  = " UPDATE sptbl_users  SET vPassword = '".addslashes(md5($newpassword))."' ";
		$sql1 .= "  WHERE nUserId = '".$_SESSION["sess_userid"]."' ";
		executeQuery($sql1,$conn); 
		$passwordreset = true; 
		$message= true;
		$messagetext = MESSAGE_PASSWORD_CHANGE_SUCCESS;
	}
}

function isPasswordCorrect($password, $userid){
	global $conn;
	$sql = "SELECT vPassword FROM sptbl_users  WHERE nUserId = '" . addslashes($userid) . "'";
    $rs = executeSelect($sql,$conn);
    if(mysql_num_rows($rs)!=0){
		$row = mysql_fetch_array($rs);
		$pass = $row["vPassword"];
		if($pass == md5($password) ){
			return true;
		}else{
			return false;
		}
    }else{
		return false;
	}
}
?>
<script>
<!--

function validatePasswordForm(){
	var frm = window.document.frmPassword;
	var errors="";
	if(frm.txtOldPassword.value.length == 0){
		errors += "<?php echo MESSAGE_OLD_PASSWORD_REQUIRED; ?>"+ "\n";
	}
	if(frm.txtNewPassword.value.length == 0){
		errors += "<?php echo MESSAGE_NEW_PASSWORD_REQUIRED; ?>"+ "\n";
	}
	if(frm.txtConfirmNewPassword.value.length == 0){
		errors += "<?php echo MESSAGE_CONFIRM_NEW_PASSWORD_REQUIRED; ?>"+ "\n";
	}
	if((frm.txtNewPassword.value.length != 0) && (frm.txtConfirmNewPassword.value.length != 0)){
		if(frm.txtNewPassword.value != frm.txtConfirmNewPassword.value){
			errors += "<?php echo MESSAGE_PASSWORDS_SHOULD_MATCH; ?>"+ "\n";
		}
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
<form action="" method="post" name="frmPassword">
<div class="content_section_title">
<h3><?php echo TEXT_CHANGE_PASSWORD?></h3>
</div>
 
<div class="content_section_data">  
                   
                       <?php
														  if($error){?>
														  
														  <div class="msg_error">
														  <?php echo $errormessage;?>
														  </div>
														 
														  
														  <?php }
														  if($message){ ?>
														  
														   <div class="msg_success">
														  <?php echo $messagetext;?>
														  </div>
														  
														  
														 <?php }?>
													
												  
													
													 
													  <?php
													  if(!$passwordreset){?>
													  <table width="100%"  border="0" align="center" class="comm_tbl btm_brdr">
													   <tr>
													    <td align="right" width="20%"><?php echo TEXT_OLD_PASSWORD?>&nbsp;<span class="required">*</span></td>													   
													    <td align="left" width="80%"><input name="txtOldPassword" type="password" value=""  maxlength="100" class="comm_input input_width1"></td>
													  </tr>
													  
													  <tr>
													    <td align="right"><?php echo TEXT_NEW_PASSWORD?>&nbsp;<span class="required">*</span></td>													   
													    <td align="left"><input name="txtNewPassword" type="password" value=""  maxlength="100" class="comm_input input_width1"></td>
													  </tr>													
													  <tr>
													    <td align="right"><?php echo TEXT_CONFIRM_NEW_PASSWORD?>&nbsp;<span class="required">*</span></td>													    
													    <td align="left"><input name="txtConfirmNewPassword" type="password" value=""  maxlength="100" class="comm_input input_width1"></td>
													  </tr>
													  </table>
													<table width="100%"  border="0" align="center" class="comm_tbl">
													  <tr>
													  <td colspan="2" align="center">
													  <input name="btnSubmit" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SUBMIT?>" onClick="javascript:validatePasswordForm();">
													  <!--<input name="btnCancel" type="reset" class="button" value="<?php echo BUTTON_TEXT_CANCEL?>">-->
													  <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									
													  </td>
													  </tr>
													  
													  </table>															  													  
													 <?php }													  
													  ?>													  													 
													  
																		
												 
				  
			
			
			</form>
			</div>
			</div>