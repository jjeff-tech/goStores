<?php
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
}else {
	$styleminus = $_POST["styleminus"];
	$stylename = $_POST["stylename"];
	$styleplus = $_POST["styleplus"];
}

if($_POST["ddlCategory"] != ""){
	$ddlCategory = $_POST["ddlCategory"];
}else{
	$ddlCategory = $_GET["ddlCategory"];
}
if($_POST["ddlDepartment"] != ""){
	$ddlDepartment = $_POST["ddlDepartment"];
}else{
	$ddlDepartment = $_GET["ddlDepartment"];
}


$error = false;
$errormessage = "" ;

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
	}
}else{
	$error = true;
}






if($error){
	$errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
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
<div class="content_section">

<form action="" method="post" name="frmKB">

<div class="content_section_title">
<h3><?php echo TEXT_KNOWLEDGEBASE?></h3>
</div>

         
												  <!-- ##########################################- -->

													<table width="100%"  border="0" align="center">
													  <tr>
													    <td colspan="3">
														<!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->
														<?php
														if($error){
															echo "<div class='msg_error'>".$errormessage."</div>";
														}
														?>
														<!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->
														</td>
													  </tr>
													  <tr>
													  <td colspan="3" align="center">
													   <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
                                                                                                           <table align="left" width="100%">

																 <tr align="left"  class="listingmainboldtext">

																   <td colspan=2>
                                                                                                                                       <b><?php echo  stripslashes($title); ?></b>
					                                                </td>

					                                              </tr>
																   <tr align="left"  class="listingmaintext">

																     <td colspan=2>

																         <?php
                                                                                                                                         //echo strip_tags(htmlentities($description));
                                                                                                                                         echo $description; ?>
					                                                </td>

					                                              </tr>
					                                              <tr><td>&nbsp;</td></tr>

                                                                                      <tr><td align="left">
                                                                                              <?php
                                                                                              include("./includes/releatedresults.php");
                                                                                              getReleatedResults($title, $kbid);
                                                                                              ?>

                                                                                          </td></tr>
																 <tr>
                                                                                                                                     <td align="center">
																 <!-- <input type="button" value=" Back " onClick = "location.href='<?php echo "knowledgebase.php?ddlCategory=".$ddlCategory."&ddlDepartment=".$ddlDepartment."&stylename=KNOWLEDGEBASE&styleminus=threeminus&styleplus=threeplus&numBegin=".$numBegin."&start=".$start."&begin=".$begin."&num=".$num."&"?>';" class="button"> -->
																 	<input type="button" value=" <?php echo BUTTON_TEXT_BACK; ?> " onClick = "window.location.href = '<?php echo SITE_URL; ?>'" class="button">
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
												
            <!-- Buttons were placed here ---- -->


			 <!-- Buttons were placed here ---- -->
			</form>
</div>