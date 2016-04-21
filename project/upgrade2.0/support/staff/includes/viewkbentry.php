<?php

$var_staffid = $_SESSION["sess_staffid"];

if($_GET["numBegin"] != "") {
    $numBegin = $_GET["numBegin"];
	$start = $_GET["start"];
	$begin = $_GET["begin"];
	$num = $_GET["num"];
}
if ($_GET["stylename"] != "") {
	$styleminus = $_GET["styleminus"];
	$stylename = $_GET["stylename"];
	$styleplus = $_GET["styleplus"];
	$ddlCategory = $_GET["ddlCategory"];
	$ddlDepartment = $_GET["ddlDepartment"];
	
}else {
	$styleminus = $_POST["styleminus"];
	$stylename = $_POST["stylename"];
	$styleplus = $_POST["styleplus"];
	$ddlCategory = $_POST["ddlCategory"];
	$ddlDepartment = $_POST["ddlDepartment"];
}

$error = false;
$errormessage = "" ;
  $flag_msg = "";
if(isNotNull($_GET["id"])){
	$kbid = $_GET["id"];
	
	settype($kbid,integer);
	$sql = " SELECT nKBID, vKBTitle, tKBDesc ";
	$sql .=" FROM  sptbl_kb  ";
	$sql .=" WHERE nKBID = '$kbid' ";

	$rs = executeSelect($sql,$conn);
	if(mysql_num_rows($rs) > 0){
		$row = mysql_fetch_array($rs);
		$title = $row["vKBTitle"];
		$description = $row["tKBDesc"]; 
	}else{
		$error = true;
		$errormessage = "" ;
                  $flag_msg = "";
	}
}else{
	$error = true;
}




	

if($error){
	$errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
          $flag_msg = "class='msg_error'";
}
		






?>
<script>
<!--

function validateProfileForm(){
	var frm = window.document.frmKB;
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
function cancel(){
	;
}
function changeDepartment(){
  document.frmKB.postback.value="CD";
  document.frmKB.method="post";
  document.frmKB.submit();
}	
function changeCategory(){
  document.frmKB.postback.value="CC";
  document.frmKB.method="post";
  document.frmKB.submit();
  
}
function clickDelete() {
                var i=1;
                var flag = false;
                	  for(i=1;i<=10;i++) {
							try{
                                if(eval("document.getElementById('c" + i + "').checked") == true) {
										flag = true;
                                        break;
                                }
			                }catch(e) {}
                        }
                        if(flag == true) {
							if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
								document.frmKB.postback.value="DA";
                                document.frmKB.method="post";
                                document.frmKB.submit();
							}	
                        }
						else {
							alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
						}
        }


        function deleted(id) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
				document.frmKB.id.value=id;
				document.frmKB.postback.value="D";
				document.frmKB.method="post";
				document.frmKB.submit();
			}	
        }
-->
</script>
<form action="" method="post" name="frmKB">
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="dotedhoriznline">
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" class="vline"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="93%"  class="heading" align="left"><?php echo TEXT_KNOWLEDGEBASE?></td>
                            </tr>
                          </table>
                            <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="maintext">
                              <tr>
                                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td class="whitebasic"><table width="100%"  border="0" cellpadding="0" cellspacing="3" class="pagecolor">
                                                <tr align="center" class="pagecolor">
                                                  <td class="maintext">
												  <!-- ##########################################- -->
												  
													<table width="100%"  border="0" align="center">
													  <tr>
													    <td colspan="3">
                                                                                                                <div <?php echo $flag_msg;?>>
														<!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->
														<?php
														if($error){
															echo $errormessage;
														}														
														?>
                                                                                                                </div>
														<!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->
														</td>
													  </tr>
													  
													  
													  <tr>
													  <td colspan="3">
													   <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
													  
													  
														   <table align="left">
														   		
																 <tr align="left"  class="listingmainboldtext">
											  
																   <td colspan=2>
																         <b><?php echo htmlentities($title); ?></b>
					                                                </td>
					                                                
					                                              </tr>
																   <tr align="left"  class="listingmaintext">
																  
																     <td colspan=2>
																	                                                   
																         <?php echo $description; ?>
					                                                </td>
					                                                
					                                              </tr>
					                                              <tr><td>&nbsp;</td></tr>
																 <tr>
																 <td><a class="topmainlink" href="<?php echo "knowledgebase.php?id=".$kbid."&ddlCategory=".$ddlCategory."&ddlDepartment=".$ddlDepartment."&stylename=STYLEKNOWLEDGEBASE&styleminus=minus6&styleplus=plus6&numBegin=".$numBegin."&start=".$start."&begin=".$begin."&num=".$num."&"?>"><?php echo BUTTON_TEXT_BACK; ?></a>
																	<input type="hidden" name="numBegin" value="<?php echo   $numBegin?>">
																	<input type="hidden" name="start" value="<?php echo   $start?>">
																	<input type="hidden" name="begin" value="<?php echo   $begin?>">
																	<input type="hidden" name="num" value="<?php echo   $num?>">    
																	<input type="hidden" name="mt" value="y">
																	<input type="hidden" name="stylename" value="<?php echo($stylename); ?>" >
																	<input type="hidden" name="styleminus" value="<?php echo($styleminus); ?>">
																	<input type="hidden" name="styleplus" value="<?php echo($styleplus); ?>">																
																	<input type="hidden" name="id" value="<?php echo($id); ?>">
																	
																	<input type="hidden" name="postback" value="">
																 </td>
																 </tr>
														   </table>
													   
													   	
													   
													   <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
													   </td>
													  </tr>
													</table>
												  <!-- ##########################################- -->
												  </td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                </table></td>
                              </tr>
                          </table></td>
                        <td width="1" background="./../images/vline.gif"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td background="./../images/horiznline.gif"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
            <!-- Buttons were placed here ---- -->
			
			
			 <!-- Buttons were placed here ---- -->
			</form>
